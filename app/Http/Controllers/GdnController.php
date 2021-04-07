<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Gdn;
use App\Models\Product;
use App\Models\Sale;
use App\Models\Warehouse;
use App\Notifications\GdnApproved;
use App\Notifications\GdnPrepared;
use App\Services\StoreSaleableProducts;
use App\Traits\Approvable;
use App\Traits\NotifiableUsers;
use App\Traits\PrependCompanyId;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Notification;

class GdnController extends Controller
{
    use PrependCompanyId, Approvable, NotifiableUsers;

    private $gdn;

    public function __construct(Gdn $gdn)
    {
        $this->authorizeResource(Gdn::class, 'gdn');

        $this->gdn = $gdn;
    }

    public function index(Gdn $gdn)
    {
        $gdns = $gdn->getAll()->load(['gdnDetails', 'createdBy', 'updatedBy', 'approvedBy', 'sale', 'customer', 'company']);

        $totalGdns = $gdn->countGdnsOfCompany();

        $totalNotApproved = $gdns->whereNull('approved_by')->count();

        $totalNotSubtracted = $gdns->where('status', 'Not Subtracted From Inventory')->whereNotNull('approved_by')->count();

        return view('gdns.index', compact('gdns', 'totalGdns', 'totalNotApproved', 'totalNotSubtracted'));
    }

    public function create(Product $product, Customer $customer, Sale $sale, Warehouse $warehouse)
    {
        $products = $product->getProductNames();

        $customers = $customer->getCustomerNames();

        $sales = $sale->getManualSales();

        $warehouses = $warehouse->getAllWithoutRelations();

        $currentGdnCode = (Gdn::select('code')->companyGdn()->latest()->first()->code) ?? 0;

        return view('gdns.create', compact('products', 'customers', 'sales', 'warehouses', 'currentGdnCode'));
    }

    public function store(Request $request)
    {
        $request['code'] = $this->prependCompanyId($request->code);

        $gdnData = $request->validate([
            'code' => 'required|string|unique:gdns',
            'gdn' => 'required|array',
            'gdn.*.product_id' => 'required|integer',
            'gdn.*.warehouse_id' => 'required|integer',
            'gdn.*.unit_price' => 'nullable|numeric',
            'gdn.*.quantity' => 'required|numeric|min:1',
            'gdn.*.description' => 'nullable|string',
            'customer_id' => 'nullable|integer',
            'sale_id' => 'nullable|integer',
            'issued_on' => 'required|date',
            'payment_type' => 'required|string',
            'description' => 'nullable|string',
        ]);

        $gdnData['status'] = 'Not Subtracted From Inventory';
        $gdnData['company_id'] = auth()->user()->employee->company_id;
        $gdnData['created_by'] = auth()->user()->id;
        $gdnData['updated_by'] = auth()->user()->id;
        $gdnData['approved_by'] = $this->approvedBy();

        $basicGdnData = Arr::except($gdnData, 'gdn');
        $gdnDetailsData = $gdnData['gdn'];

        $gdn = DB::transaction(function () use ($basicGdnData, $gdnDetailsData) {
            $gdn = $this->gdn->create($basicGdnData);
            $gdn->gdnDetails()->createMany($gdnDetailsData);
            $isGdnValid = StoreSaleableProducts::storeSoldProducts($gdn);

            if (!$isGdnValid) {
                DB::rollback();
            }

            return $isGdnValid ? $gdn : false;
        });

        if ($gdn) {
            Notification::send($this->notifiableUsers('Approve GDN'), new GdnPrepared($gdn));
            return redirect()->route('gdns.show', $gdn->id);
        }

        return redirect()->back()->withInput($request->all());
    }

    public function show(Gdn $gdn)
    {
        $gdn->load(['gdnDetails.product', 'gdnDetails.warehouse', 'customer', 'sale', 'company']);

        return view('gdns.show', compact('gdn'));
    }

    public function edit(Gdn $gdn, Product $product, Customer $customer, Sale $sale, Warehouse $warehouse)
    {
        $products = $product->getProductNames();

        $customers = $customer->getCustomerNames();

        $sales = $sale->getManualSales();

        $warehouses = $warehouse->getAllWithoutRelations();

        $gdn->load(['gdnDetails.product', 'gdnDetails.warehouse']);

        return view('gdns.edit', compact('gdn', 'products', 'customers', 'sales', 'warehouses'));
    }

    public function update(Request $request, Gdn $gdn)
    {
        if ($gdn->isGdnApproved()) {
            $gdnSaleId = $request->validate([
                'sale_id' => 'nullable|integer',
            ]);

            $gdnSaleId['updated_by'] = auth()->user()->id;

            $gdn->update($gdnSaleId);

            return redirect()->route('gdns.show', $gdn->id);
        }

        $request['code'] = $this->prependCompanyId($request->code);

        $gdnData = $request->validate([
            'code' => 'required|string|unique:gdns,code,' . $gdn->id,
            'gdn' => 'required|array',
            'gdn.*.product_id' => 'required|integer',
            'gdn.*.warehouse_id' => 'required|integer',
            'gdn.*.unit_price' => 'nullable|numeric',
            'gdn.*.quantity' => 'required|numeric|min:1',
            'gdn.*.description' => 'nullable|string',
            'customer_id' => 'nullable|integer',
            'sale_id' => 'nullable|integer',
            'issued_on' => 'required|date',
            'payment_type' => 'required|string',
            'description' => 'nullable|string',
        ]);

        $gdnData['updated_by'] = auth()->user()->id;

        $basicGdnData = Arr::except($gdnData, 'gdn');
        $gdnDetailsData = $gdnData['gdn'];

        DB::transaction(function () use ($basicGdnData, $gdnDetailsData, $gdn) {
            $gdn->update($basicGdnData);

            for ($i = 0; $i < count($gdnDetailsData); $i++) {
                $gdn->gdnDetails[$i]->update($gdnDetailsData[$i]);
            }
        });

        return redirect()->route('gdns.show', $gdn->id);
    }

    public function destroy(Gdn $gdn)
    {
        if ($gdn->isGdnApproved()) {
            return view('errors.permission_denied');
        }

        $gdn->forceDelete();

        return redirect()->back()->with('deleted', 'Deleted Successfully');
    }

    public function approve(Gdn $gdn)
    {
        $this->authorize('approve', $gdn);

        $message = 'This DO/GDN is already approved';

        if (!$gdn->isGdnApproved()) {
            $gdn->approveGdn();
            $message = 'You have approved this DO/GDN successfully';
            Notification::send($this->notifiableUsers('Subtract GDN'), new GdnApproved($gdn));
            Notification::send($this->notifyCreator($gdn, $this->notifiableUsers('Subtract GDN')), new GdnApproved($gdn));
        }

        return redirect()->back()->with('successMessage', $message);
    }

    public function printed(Gdn $gdn)
    {
        $this->authorize('view', $gdn);

        $gdn->load(['gdnDetails.product', 'customer', 'company', 'createdBy', 'approvedBy']);

        return view('gdns.print', compact('gdn'));
    }
}

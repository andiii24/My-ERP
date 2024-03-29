<?php

namespace App\Http\Controllers\Resource;

use App\DataTables\BillOfMaterialDatatable;
use App\DataTables\BillOfMaterialDetailDatatable;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreBillOfMaterialRequest;
use App\Http\Requests\UpdateBillOfMaterialRequest;
use App\Models\BillOfMaterial;
use App\Notifications\BillOfMaterialCreated;
use App\Notifications\BillOfMaterialUpdated;
use App\Utilities\Notifiables;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Notification;

class BillOfMaterialController extends Controller
{
    public function __construct()
    {
        $this->middleware('isFeatureAccessible:Bill Of Material Management');

        $this->authorizeResource(BillOfMaterial::class);
    }

    public function index(BillOfMaterialDatatable $datatable)
    {
        $datatable->builder()->setTableId('bill-of-materials-datatable')->orderBy(1, 'desc')->orderBy(2, 'desc');

        $totalBillOfMaterials = BillOfMaterial::count();

        $totalActiveBillOfMaterials = BillOfMaterial::active()->count();

        $totalInActiveBillOfMaterials = BillOfMaterial::inActive()->count();

        $totalApproved = BillOfMaterial::approved()->count();

        $totalNotApproved = BillOfMaterial::notApproved()->count();

        return $datatable->render('bill-of-materials.index', compact('totalBillOfMaterials', 'totalActiveBillOfMaterials', 'totalInActiveBillOfMaterials', 'totalApproved', 'totalNotApproved'));
    }

    public function create()
    {
        return view('bill-of-materials.create');
    }

    public function store(StoreBillOfMaterialRequest $request)
    {
        $billOfMaterial = DB::transaction(function () use ($request) {
            $billOfMaterial = BillOfMaterial::create($request->safe()->except('billOfMaterial'));

            foreach ($request->validated('billOfMaterial') as $billOfMaterialDetail) {
                if (isset($billOfMaterialDetail['product_id']) && isset($billOfMaterialDetail['quantity'])) {
                    $billOfMaterial->billOfMaterialDetails()->create($billOfMaterialDetail);
                }
            }

            Notification::send(Notifiables::byNextActionPermission('Read BOM'), new BillOfMaterialCreated($billOfMaterial));

            return $billOfMaterial;
        });

        return redirect()->route('bill-of-materials.show', $billOfMaterial->id);
    }

    public function show(BillOfMaterial $billOfMaterial, BillOfMaterialDetailDatatable $datatable)
    {
        $datatable->builder()->setTableId('bill-of-material-details-datatable');

        $billOfMaterial->load(['billOfMaterialDetails.product']);

        return $datatable->render('bill-of-materials.show', compact('billOfMaterial'));
    }

    public function edit(BillOfMaterial $billOfMaterial)
    {
        if ($billOfMaterial->isUsedForProduction()) {
            return back()->with('failedMessage', 'This bill of material was used for production, therefore it is not allowed to be modified.');
        }

        if ($billOfMaterial->isApproved()) {
            return back()->with('failedMessage', 'You can not modified an bill of material that is approved.');
        }

        $billOfMaterial->load(['billOfMaterialDetails.product']);

        return view('bill-of-materials.edit', compact('billOfMaterial'));
    }

    public function update(UpdateBillOfMaterialRequest $request, BillOfMaterial $billOfMaterial)
    {
        if ($billOfMaterial->isUsedForProduction()) {
            return redirect()->route('bill-of-materials.show')
                ->with('failedMessage', 'This bill of material was used for production, therefore it is not allowed to be modified.');
        }

        if ($billOfMaterial->isApproved()) {
            return back()->with('failedMessage', 'You can not modified an bill of material that is approved.');
        }

        DB::transaction(function () use ($request, $billOfMaterial) {
            $billOfMaterial->update($request->safe()->except('billOfMaterial'));

            $billOfMaterial->billOfMaterialDetails()->forceDelete();

            foreach ($request->validated('billOfMaterial') as $billOfMaterialDetail) {
                if (isset($billOfMaterialDetail['product_id']) && isset($billOfMaterialDetail['quantity'])) {
                    $billOfMaterial->billOfMaterialDetails()->create($billOfMaterialDetail);
                }
            }

            Notification::send(Notifiables::byNextActionPermission('Read BOM'), new BillOfMaterialUpdated($billOfMaterial));
        });

        return redirect()->route('bill-of-materials.show', $billOfMaterial->id);
    }

    public function destroy(BillOfMaterial $billOfMaterial)
    {
        if ($billOfMaterial->isUsedForProduction()) {
            return back()->with('failedMessage', 'This bill of material was used for production, therefore it is not allowed to be deleted.');
        }

        if ($billOfMaterial->isApproved()) {
            return back()->with('failedMessage', 'You can not delete an bill of material that is approved.');
        }

        $billOfMaterial->forceDelete();

        return back()->with('deleted', 'Deleted successfully.');
    }
}

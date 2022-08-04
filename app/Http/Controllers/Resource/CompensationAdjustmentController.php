<?php

namespace App\Http\Controllers\Resource;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreCompensationAdjustmentRequest;
use App\Http\Requests\UpdateCompensationAdjustmentRequest;
use App\Models\Compensation;
use App\Models\CompensationAdjustment;
use App\Models\User;
use App\Notifications\CompensationAdjustmentCreated;
use App\Utilities\Notifiables;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Notification;

class CompensationAdjustmentController extends Controller
{
    public function __construct()
    {
        $this->middleware('isFeatureAccessible:Compensation Adjustment');

        $this->authorizeResource(CompensationAdjustment::class);
    }

    public function index()
    {
        //
    }

    public function create()
    {
        $compensationAdjustmentCode = nextReferenceNumber('compensation_adjustments');

        $compensations = Compensation::orderBy('name')->get(['id', 'name']);

        $users = User::whereRelation('employee', 'company_id', '=', userCompany()->id)->with('employee')->orderBy('name')->get();

        return view('compensation-adjustments.create', compact('adjustmentCode', 'compensations', 'users'));
    }

    public function store(StoreCompensationAdjustmentRequest $request, CompensationAdjustment $compensationAdjustment)
    {
        $compensationAdjustmentDetails = $request->validated('compensationAdjustment');

        foreach ($compensationAdjustmentDetails as &$compensationAdjustmentDetail) {
            data_set($compensationAdjustmentDetail, 'employeeAdjustments.*.employee_id', $compensationAdjustmentDetail['employee_id']);
        }

        $compensationAdjustmentDetails = data_get($compensationAdjustmentDetails, '*.employeeadjustments');

        $compensationAdjustment = DB::transaction(function () use ($request, $compensationAdjustmentDetails) {
            $compensationAdjustment = CompensationAdjustment::create($request->safe()->except('compensationAdjustment'));

            foreach ($compensationAdjustmentDetails as $compensationAdjustmentDetail) {
                $compensationAdjustment->compensationAdjustmentDetails()->createMany($compensationAdjustmentDetail);
            }

            Notification::send(Notifiables::byNextActionPermission('Approve Compensation Adjustment'), new CompensationAdjustmentCreated($compensationAdjustment));

            return $compensationAdjustment;
        });

        return redirect()->route('compensation-adjustments.show', $compensationAdjustment->id);
    }

    public function show(CompensationAdjustment $compensationAdjustment)
    {
        //
    }

    public function edit(CompensationAdjustment $compensationAdjustment)
    {
        if ($compensationAdjustment->isApproved()) {
            return back()->with('failedMessage', 'You can not modify an adjustment that is approved.');
        }

        $compensations = Compensation::orderBy('name')->get(['id', 'name']);

        $users = User::whereRelation('employee', 'company_id', '=', userCompany()->id)->with('employee')->orderBy('name')->get();

        $compensationAdjustmentDetails = array_values($compensationAdjustment->load(['compensationAdjustmentDetails'])->compensationAdjustmentDetails->groupBy('employee_id')->toArray());

        return view('compensation-adjustments.edit', compact('compensationAdjustment', 'compensations', 'users', 'compensationAdjustmentDetails'));
    }

    public function update(UpdateCompensationAdjustmentRequest $request, CompensationAdjustment $compensationAdjustment)
    {
        if ($compensationAdjustment->isApproved()) {
            return back()->with('failedMessage', 'You can not modify an adjustment that is approved.');
        }

        $compensationAdjustmentDetails = $request->validated('compensationAdjustment');

        foreach ($compensationAdjustmentDetails as &$compensationAdjustmentDetail) {
            data_set($compensationAdjustmentDetail, 'employeeAdjustments.*.employee_id', $compensationAdjustmentDetail['employee_id']);
        }

        $compensationAdjustmentDetails = data_get($compensationAdjustmentDetails, '*.employeeAdjustments');

        DB::transaction(function () use ($request, $compensationAdjustment, $compensationAdjustmentDetails) {
            $compensationAdjustment->update($request->safe()->except('compensationAdjustment'));

            $compensationAdjustment->compensationAdjustmentDetails()->forceDelete();

            foreach ($compensationAdjustmentDetails as $compensationAdjustmentDetail) {
                $compensationAdjustment->compensationAdjustmentDetails()->createMany($compensationAdjustmentDetail);
            }
        });

        return redirect()->route('compensation-adjustments.show', $compensationAdjustment->id);
    }

    public function destroy(CompensationAdjustment $compensationAdjustment)
    {
        if ($compensationAdjustment->isApproved()) {
            return back()->with('failedMessage', 'You can not delete an adjustment that is approved.');
        }

        $compensationAdjustment->forceDelete();

        return back()->with('deleted', 'Deleted successfully.');
    }
}

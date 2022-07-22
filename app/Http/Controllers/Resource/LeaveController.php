<?php

namespace App\Http\Controllers\Resource;

use App\DataTables\LeaveDatatable;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreLeaveRequest;
use App\Http\Requests\UpdateLeaveRequest;
use App\Models\Leave;
use App\Models\LeaveCategory;
use App\Models\User;
use App\Notifications\LeaveCreated;
use App\Utilities\Notifiables;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Notification;

class LeaveController extends Controller
{
    public function __construct()
    {
        $this->middleware('isFeatureAccessible:Leave Management');

        $this->authorizeResource(Leave::class, 'leaf');
    }

    public function index(LeaveDatatable $datatable)
    {
        $datatable->builder()->setTableId('leaves-datatable')->orderBy(1, 'desc')->orderBy(2, 'desc');

        $totalLeaves = Leave::count();

        $totalApproved = Leave::approved()->notCancelled()->count();

        $totalNotApproved = Leave::notApproved()->notCancelled()->count();

        $totalCancelled = Leave::cancelled()->count();

        return $datatable->render('leaves.index', compact('totalLeaves', 'totalApproved', 'totalNotApproved', 'totalCancelled'));
    }

    public function create()
    {
        $leaveCategories = LeaveCategory::all();

        $users = User::whereRelation('employee', 'company_id', '=', userCompany()->id)->with('employee')->orderBy('name')->get();

        return view('leaves.create', compact('users', 'leaveCategories'));
    }

    public function store(StoreLeaveRequest $request)
    {
        $leaves = collect($request->validated('leave'));

        DB::transaction(function () use ($leaves) {
            foreach ($leaves as $leave) {
                Leave::firstOrCreate($leave);
            }

            Notification::send(Notifiables::byNextActionPermission('Approve Leave'), new LeaveCreated($leave));
        });

        return redirect()->route('leaves.index')->with('successMessage', 'New leave are added.');
    }

    public function show(Leave $leaf)
    {
        $leaf->load('employee.user');

        return view('leaves.show', compact('leaf'));
    }

    public function edit(Leave $leaf)
    {
        if ($leaf->isApproved()) {
            return back()->with('failedMessage', 'You can not modify an leave that is approved.');
        }

        if ($leaf->isCancelled()) {
            return back()->with('failedMessage', 'You can not modify an leave that is cancelled.');
        }

        $leaveCategories = LeaveCategory::all();

        $users = User::whereRelation('employee', 'company_id', '=', userCompany()->id)->with('employee')->orderBy('name')->get();

        return view('leaves.edit', compact('leaf', 'users', 'leaveCategories'));
    }

    public function update(UpdateLeaveRequest $request, Leave $leaf)
    {
        $leaf->update($request->validated());

        return redirect()->route('leaves.index');
    }

    public function destroy(Leave $leaf)
    {
        if ($leaf->isApproved()) {
            return back()->with('failedMessage', 'You can not delete an leave that is approved.');
        }

        if ($leaf->isCancelled()) {
            return back()->with('failedMessage', 'You can not delete an leave that is cancelled.');
        }

        $leaf->forceDelete();

        return back()->with('deleted', 'Deleted successfully.');
    }
}
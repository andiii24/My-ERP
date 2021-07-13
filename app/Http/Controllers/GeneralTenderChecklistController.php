<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreGeneralTenderChecklistRequest;
use App\Http\Requests\UpdateGeneralTenderChecklistRequest;
use App\Models\GeneralTenderChecklist;
use App\Models\TenderChecklistType;

class GeneralTenderChecklistController extends Controller
{
    private $generalTenderChecklist;

    public function __construct(GeneralTenderChecklist $generalTenderChecklist)
    {
        $this->middleware('\App\Http\Middleware\AllowOnlyEnabledFeatures:Tender Management');

        $this->authorizeResource(GeneralTenderChecklist::class);

        $this->generalTenderChecklist = $generalTenderChecklist;
    }

    public function index()
    {
        $generalTenderChecklists = $this->generalTenderChecklist->getAll()->load(['createdBy', 'updatedBy']);

        return view('general_tender_checklists.index', compact('generalTenderChecklists'));
    }

    public function create()
    {
        $tenderChecklistTypes = TenderChecklistType::companyTenderChecklistType()->get();

        return view('general_tender_checklists.create', compact('tenderChecklistTypes'));
    }

    public function store(StoreGeneralTenderChecklistRequest $request)
    {
        $this->generalTenderChecklist->firstOrCreate(
            $request->only(['item', 'company_id']),
            $request->except(['item', 'company_id'])
        );

        return redirect()->route('general-tender-checklists.index');
    }

    public function edit(GeneralTenderChecklist $generalTenderChecklist)
    {
        $tenderChecklistTypes = TenderChecklistType::companyTenderChecklistType()->get();

        return view('general_tender_checklists.edit', compact('generalTenderChecklist', 'tenderChecklistTypes'));
    }

    public function update(UpdateGeneralTenderChecklistRequest $request, GeneralTenderChecklist $generalTenderChecklist)
    {
        $generalTenderChecklist->update($request->all());

        return redirect()->route('general-tender-checklists.index');
    }

    public function destroy(GeneralTenderChecklist $generalTenderChecklist)
    {
        $generalTenderChecklist->forceDelete();

        return redirect()->back()->with('deleted', 'Deleted Successfully');
    }
}

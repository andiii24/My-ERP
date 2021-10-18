@extends('layouts.app')

@section('title')
    Adjustment Management
@endsection

@section('content')
    <div class="columns is-marginless is-multiline">
        <div class="column is-6 p-lr-0">
            <div class="box text-green">
                <div class="columns is-marginless is-vcentered is-mobile">
                    <div class="column has-text-centered is-paddingless">
                        <span class="icon is-large is-size-1">
                            <i class="fas fa-eraser"></i>
                        </span>
                    </div>
                    <div class="column is-paddingless">
                        <div class="is-size-3 has-text-weight-bold">
                            {{ $totalAdjustments }}
                        </div>
                        <div class="is-uppercase is-size-7">
                            Total Adjustments
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="column is-6 p-lr-0">
            <div class="box text-purple">
                <div class="columns is-marginless is-vcentered is-mobile">
                    <div class="column is-paddingless has-text-centered">
                        <div class="is-uppercase is-size-7">
                            Create new Adjustments to correct inventory level
                        </div>
                        <div class="is-size-3">
                            <a href="{{ route('adjustments.create') }}" class="button bg-purple has-text-white has-text-weight-medium is-size-7 px-5 py-4 mt-3">
                                <span class="icon">
                                    <i class="fas fa-plus-circle"></i>
                                </span>
                                <span>
                                    Create New Adjustments
                                </span>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="column is-4 p-lr-0">
            <div class="box text-green has-text-centered" style="border-left: 2px solid #3d8660;">
                <div class="is-size-3 has-text-weight-bold">
                    {{ $totalAdjusted }}
                </div>
                <div class="is-uppercase is-size-7">
                    Adjusted
                </div>
            </div>
        </div>
        <div class="column is-4 p-lr-0">
            <div class="box text-gold has-text-centered" style="border-left: 2px solid #86843d;">
                <div class="is-size-3 has-text-weight-bold">
                    {{ $totalNotAdjusted }}
                </div>
                <div class="is-uppercase is-size-7">
                    Approved (Not Adjusted)
                </div>
            </div>
        </div>
        <div class="column is-4 p-lr-0">
            <div class="box text-purple has-text-centered" style="border-left: 2px solid #863d63;">
                <div class="is-size-3 has-text-weight-bold">
                    {{ $totalNotApproved }}
                </div>
                <div class="is-uppercase is-size-7">
                    Waiting Approval
                </div>
            </div>
        </div>
    </div>

    <x-common.content-wrapper>

        <x-content.header title="Adjustments" />

        <x-content.footer>
            <x-common.success-message :message="session('deleted')" />

            <table class="regular-datatable is-hoverable is-size-7 display nowrap" data-date="[4]" data-numeric="[]">
                <thead>
                    <tr>
                        <th><abbr> # </abbr></th>
                        <th class="has-text-centered"><abbr> Adjustment No </abbr></th>
                        <th><abbr> Status </abbr></th>
                        <th><abbr> Description </abbr></th>
                        <th class="has-text-right"><abbr> Issued On </abbr></th>
                        <th><abbr> Prepared By </abbr></th>
                        <th><abbr> Approved By </abbr></th>
                        <th><abbr> Adjusted By </abbr></th>
                        <th><abbr> Edited By </abbr></th>
                        <th><abbr> Actions </abbr></th>
                    </tr>
                </thead>
                <tbody class="list">
                    @foreach ($adjustments as $adjustment)
                        <tr class="showRowDetails is-clickable" data-id="{{ route('adjustments.show', $adjustment->id) }}">
                            <td> {{ $loop->index + 1 }} </td>
                            <td class="is-capitalized has-text-centered">
                                {{ $adjustment->code }}
                            </td>
                            <td class="is-capitalized">
                                @if (!$adjustment->isApproved())
                                    <span class="tag is-small bg-purple has-text-white">
                                        <span class="icon">
                                            <i class="fas fa-clock"></i>
                                        </span>
                                        <span>
                                            Waiting Approval
                                        </span>
                                    </span>
                                @elseif ($adjustment->isAdjusted())
                                    <span class="tag is-small bg-green has-text-white">
                                        <span class="icon">
                                            <i class="fas fa-check-circle"></i>
                                        </span>
                                        <span>
                                            Adjusted
                                        </span>
                                    </span>
                                @else
                                    <span class="tag is-small bg-gold has-text-white">
                                        <span class="icon">
                                            <i class="fas fa-exclamation-circle"></i>
                                        </span>
                                        <span>
                                            Approved (Not Adjusted)
                                        </span>
                                    </span>
                                @endif
                            </td>
                            <td>
                                {!! nl2br(e(substr($adjustment->description, 0, 40))) ?? 'N/A' !!}
                                <span class="is-hidden">
                                    {!! $adjustment->description ?? '' !!}
                                </span>
                            </td>
                            <td class="has-text-right">
                                {{ $adjustment->issued_on->toFormattedDateString() }}
                            </td>
                            <td> {{ $adjustment->createdBy->name ?? 'N/A' }} </td>
                            <td> {{ $adjustment->approvedBy->name ?? 'N/A' }} </td>
                            <td> {{ $adjustment->adjustedBy->name ?? 'N/A' }} </td>
                            <td> {{ $adjustment->updatedBy->name ?? 'N/A' }} </td>
                            <td class="actions">
                                <x-common.action-buttons buttons="all" model="adjustments" :id="$adjustment->id" />
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </x-content.footer>

    </x-common.content-wrapper>
@endsection

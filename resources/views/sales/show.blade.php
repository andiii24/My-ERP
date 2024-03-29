@extends('layouts.app')

@section('title', 'Invoice Details')

@section('content')
    <x-common.content-wrapper>
        <x-content.header title="General Information" />
        <x-content.footer>
            <div class="columns is-marginless is-multiline">
                <div class="column is-6">
                    <x-common.show-data-section
                        icon="fas fa-hashtag"
                        :data="$sale->code ?? 'N/A'"
                        label="Invoice No"
                    />
                </div>
                <div class="column is-6">
                    <x-common.show-data-section
                        icon="fas fa-hashtag"
                        :data="!is_null($sale->fs_number) ? str()->padLeft($sale->fs_number, 8, 0) : 'N/A'"
                        label="FS No"
                    />
                </div>
                @if ($sale->bank_name)
                    <div class="column is-6">
                        <x-common.show-data-section
                            icon="fas fa-university"
                            :data="$sale->bank_name"
                            label="Bank"
                        />
                    </div>
                @endif
                @if ($sale->reference_number)
                    <div class="column is-6">
                        <x-common.show-data-section
                            icon="fas fa-hashtag"
                            :data="$sale->reference_number"
                            label="Bank Reference No"
                        />
                    </div>
                @endif
                <div class="column is-6">
                    <x-common.show-data-section
                        icon="fas fa-credit-card"
                        :data="$sale->payment_type ?? 'N/A'"
                        label="Payment Type"
                    />
                </div>
                @if ($sale->isPaymentInCredit())
                    <div class="column is-6">
                        <x-common.show-data-section
                            icon="fas fa-calendar-day"
                            :data="$sale->due_date?->toFormattedDateString()"
                            label="Credit Due Date"
                        />
                    </div>
                @endif
                <div class="column is-6">
                    <x-common.show-data-section
                        icon="fas fa-user"
                        :data="$sale->customer->company_name ?? 'N/A'"
                        label="Customer"
                    />
                </div>
                <div class="column is-6">
                    <x-common.show-data-section
                        icon="fas fa-calendar-day"
                        :data="$sale->issued_on->toFormattedDateString() ?? 'N/A'"
                        label="Issued On"
                    />
                </div>
                <div class="column is-6">
                    <x-common.show-data-section
                        icon="fas fa-address-card"
                        :data="$sale->contact->name ?? 'N/A'"
                        label="Contact"
                    />
                </div>
                <div class="column is-6">
                    <x-common.show-data-section
                        icon="fas fa-hand-holding-usd"
                        data="{{ number_format($sale->paymentInCash, 2) }} ({{ number_format($sale->cashReceivedInPercentage, 2) }}%)"
                        label="In Cash ({{ userCompany()->currency }})"
                    />
                </div>
                <div class="column is-6">
                    <x-common.show-data-section
                        icon="fas fa-money-check"
                        data="{{ number_format($sale->paymentInCredit, 2) }} ({{ number_format($sale->credit_payable_in_percentage, 2) }}%)"
                        label="On Credit ({{ userCompany()->currency }})"
                    />
                </div>
                <div class="column is-6">
                    <x-common.show-data-section
                        icon="fas fa-dollar-sign"
                        :data="number_format($sale->subtotalPrice, 2)"
                        label="SubTotal Price ({{ userCompany()->currency }})"
                    />
                </div>
                <div class="column is-6">
                    <x-common.show-data-section
                        icon="fas fa-dollar-sign"
                        :data="number_format($sale->grandTotalPrice, 2)"
                        label=" Grand Total Price ({{ userCompany()->currency }})"
                    />
                </div>
                <div class="column is-12">
                    <x-common.show-data-section
                        type="long"
                        :data="is_null($sale->description) ? 'N/A' : nl2br(e($sale->description))"
                        label="Details"
                    />
                </div>
            </div>
        </x-content.footer>
    </x-common.content-wrapper>

    <x-common.content-wrapper class="mt-5">
        <x-content.header
            title="Details"
            is-mobile
        >
            <x-common.dropdown name="Actions">
                @if (!$sale->isApproved() && !$sale->isCancelled())
                    @can('Approve Sale')
                        <x-common.dropdown-item>
                            <x-common.transaction-button
                                :route="route('sales.approve', $sale->id)"
                                action="approve"
                                intention="approve this sale"
                                icon="fas fa-signature"
                                label="Approve"
                                class="has-text-weight-medium is-small text-green is-borderless is-transparent-color is-block is-fullwidth has-text-left"
                            />
                        </x-common.dropdown-item>
                    @endcan
                @endif
                @if (!$sale->isCancelled())
                    @can('Cancel Sale')
                        <x-common.dropdown-item>
                            <x-common.transaction-button
                                :route="route('sales.cancel', $sale->id)"
                                action="cancel"
                                intention="cancel this sale"
                                icon="fas fa-times"
                                label="Cancel"
                                class="has-text-weight-medium is-small text-green is-borderless is-transparent-color is-block is-fullwidth has-text-left"
                            />
                        </x-common.dropdown-item>
                    @endcan
                @endif
                <x-common.dropdown-item>
                    <x-common.button
                        tag="a"
                        href="{{ route('sales.print', $sale->id) }}"
                        target="_blank"
                        mode="button"
                        icon="fas fa-print"
                        label="Print"
                        class="has-text-weight-medium is-small text-green is-borderless is-transparent-color is-block is-fullwidth has-text-left"
                    />
                </x-common.dropdown-item>
                <x-common.dropdown-item>
                    <x-common.button
                        tag="a"
                        href="{{ route('sales.edit', $sale->id) }}"
                        mode="button"
                        icon="fas fa-pen"
                        label="Edit"
                        class="has-text-weight-medium is-small text-green is-borderless is-transparent-color is-block is-fullwidth has-text-left"
                    />
                </x-common.dropdown-item>
            </x-common.dropdown>
        </x-content.header>
        <x-content.footer>
            <x-common.success-message :message="session('deleted')" />
            <x-common.fail-message :message="session('failedMessage')" />

            @if ($sale->isCancelled())
                <x-common.fail-message message="This invoice is cancelled." />
            @elseif ($sale->isApproved())
                <x-common.success-message message="This invoice is approved." />
            @else
                <x-common.fail-message message="This invoice is not approved yet." />
            @endif

            {{ $dataTable->table() }}
        </x-content.footer>
    </x-common.content-wrapper>

    @if (isFeatureEnabled('Gdn Management') && $sale->gdns()->count())
        <x-common.content-wrapper class="mt-5">
            <x-content.header title="Delivery Orders" />
            <x-content.footer>
                <x-common.bulma-table>
                    <x-slot name="headings">
                        <th><abbr> # </abbr></th>
                        <th><abbr> DO No </abbr></th>
                        <th><abbr> Status </abbr></th>
                        <th><abbr> Issued on </abbr></th>
                    </x-slot>
                    <x-slot name="body">
                        @foreach ($sale->gdns as $gdn)
                            <tr>
                                <td> {{ $loop->index + 1 }} </td>
                                <td class="is-capitalized">
                                    <a
                                        class="is-underlined"
                                        href="{{ route('gdns.show', $gdn->id) }}"
                                    >
                                        {{ $gdn->code }}
                                    </a>
                                </td>
                                <td>
                                    @if ($gdn->isSubtracted())
                                        <span class="tag is-small bg-purple has-text-white">
                                            Subtracted from inventory
                                        </span>
                                    @else
                                        <span class="tag is-small bg-blue has-text-white">
                                            Not subtracted from inventory
                                        </span>
                                    @endif
                                </td>
                                <td class="is-capitalized">
                                    {{ $gdn->issued_on->toFormattedDateString() }}
                                </td>
                            </tr>
                        @endforeach
                    </x-slot>
                </x-common.bulma-table>
            </x-content.footer>
        </x-common.content-wrapper>
    @endif
@endsection

@push('scripts')
    {{ $dataTable->scripts() }}
@endpush

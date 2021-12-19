@extends('layouts.app')

@section('title')
    Delivery Order Details
@endsection

@section('content')
    <div class="box mt-3 mx-3 m-lr-0">
        <div class="columns is-marginless is-multiline">
            <div class="column is-6">
                <div>
                    <div class="columns is-marginless is-vcentered is-mobile text-green">
                        <div class="column is-1">
                            <span class="icon is-size-3">
                                <i class="fas fa-file-invoice"></i>
                            </span>
                        </div>
                        <div class="column m-lr-20">
                            <div class="is-size- has-text-weight-bold">
                                {{ $gdn->code ?? 'N/A' }}
                            </div>
                            <div class="is-uppercase is-size-7">
                                DO No
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @if (isFeatureEnabled('Sale Management'))
                <div class="column is-6">
                    <div>
                        <div class="columns is-marginless is-vcentered is-mobile text-green">
                            <div class="column is-1">
                                <span class="icon is-size-3">
                                    <i class="fas fa-hashtag"></i>
                                </span>
                            </div>
                            <div class="column m-lr-20">
                                <div class="is-size- has-text-weight-bold">
                                    {{ $gdn->sale->code ?? 'N/A' }}
                                </div>
                                <div class="is-uppercase is-size-7">
                                    Receipt No
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
            <div class="column is-6">
                <div>
                    <div class="columns is-marginless is-vcentered is-mobile text-green">
                        <div class="column is-1">
                            <span class="icon is-size-3">
                                <i class="fas fa-credit-card"></i>
                            </span>
                        </div>
                        <div class="column m-lr-20">
                            <div class="is-size- has-text-weight-bold">
                                {{ $gdn->payment_type ?? 'N/A' }}
                            </div>
                            <div class="is-uppercase is-size-7">
                                Payment Type
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="column is-6">
                <div>
                    <div class="columns is-marginless is-vcentered is-mobile text-green">
                        <div class="column is-1">
                            <span class="icon is-size-3">
                                <i class="fas fa-user"></i>
                            </span>
                        </div>
                        <div class="column m-lr-20">
                            <div class="is-size- has-text-weight-bold">
                                {{ $gdn->customer->company_name ?? 'N/A' }}
                            </div>
                            <div class="is-uppercase is-size-7">
                                Customer
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="column is-6">
                <div>
                    <div class="columns is-marginless is-vcentered is-mobile text-green">
                        <div class="column is-1">
                            <span class="icon is-size-3">
                                <i class="fas fa-calendar-day"></i>
                            </span>
                        </div>
                        <div class="column m-lr-20">
                            <div class="is-size- has-text-weight-bold">
                                {{ $gdn->issued_on->toFormattedDateString() ?? 'N/A' }}
                            </div>
                            <div class="is-uppercase is-size-7">
                                Issued On
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @if ($gdn->payment_in_credit > 0)
                <div class="column is-6">
                    <div>
                        <div class="columns is-marginless is-vcentered is-mobile text-green">
                            <div class="column is-1">
                                <span class="icon is-size-3">
                                    <i class="fas fa-calendar-day"></i>
                                </span>
                            </div>
                            <div class="column m-lr-20">
                                <div class="is-size- has-text-weight-bold">
                                    {{ $gdn->due_date->toFormattedDateString() ?? 'N/A' }}
                                </div>
                                <div class="is-uppercase is-size-7">
                                    Credit Due Date
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
            <div class="column is-6">
                <div>
                    <div class="columns is-marginless is-vcentered is-mobile text-green">
                        <div class="column is-1">
                            <span class="icon is-size-3">
                                <i class="fas fa-hand-holding-usd"></i>
                            </span>
                        </div>
                        <div class="column m-lr-20">
                            <div class="is-size- has-text-weight-bold">
                                {{ number_format($gdn->paymentInCash, 2) }}
                                ({{ (float) $gdn->cash_received_in_percentage }}%)
                            </div>
                            <div class="is-uppercase is-size-7">
                                In Cash ({{ userCompany()->currency }})
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="column is-6">
                <div>
                    <div class="columns is-marginless is-vcentered is-mobile text-green">
                        <div class="column is-1">
                            <span class="icon is-size-3">
                                <i class="fas fa-money-check"></i>
                            </span>
                        </div>
                        <div class="column m-lr-20">
                            <div class="is-size- has-text-weight-bold">
                                {{ number_format($gdn->paymentInCredit, 2) }}
                                ({{ $gdn->credit_payable_in_percentage }}%)
                            </div>
                            <div class="is-uppercase is-size-7">
                                On Credit ({{ userCompany()->currency }})
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="column is-6">
                <div>
                    <div class="columns is-marginless is-vcentered is-mobile text-green">
                        <div class="column is-1">
                            <span class="icon is-size-3">
                                <i class="fas fa-dollar-sign"></i>
                            </span>
                        </div>
                        <div class="column m-lr-20">
                            <div class="is-size- has-text-weight-bold">
                                {{ number_format($gdn->subtotalPrice, 2) }}
                            </div>
                            <div class="is-uppercase is-size-7">
                                SubTotal Price ({{ userCompany()->currency }})
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="column is-6">
                <div>
                    <div class="columns is-marginless is-vcentered is-mobile text-purple">
                        <div class="column is-1">
                            <span class="icon is-size-3">
                                <i class="fas fa-dollar-sign"></i>
                            </span>
                        </div>
                        <div class="column m-lr-20">
                            <div class="is-size- has-text-weight-bold">
                                {{ number_format($gdn->grandTotalPrice, 2) }}
                            </div>
                            <div class="is-uppercase is-size-7">
                                Grand Total Price ({{ userCompany()->currency }})
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @if (!userCompany()->isDiscountBeforeVAT())
                <div class="column is-6">
                    <div>
                        <div class="columns is-marginless is-vcentered is-mobile text-green">
                            <div class="column is-1">
                                <span class="icon is-size-3">
                                    <i class="fas fa-percentage"></i>
                                </span>
                            </div>
                            <div class="column m-lr-20">
                                <div class="is-size- has-text-weight-bold">
                                    {{ number_format($gdn->discount * 100, 2) }}%
                                </div>
                                <div class="is-uppercase is-size-7">
                                    Discount
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="column is-6">
                    <div>
                        <div class="columns is-marginless is-vcentered is-mobile text-green">
                            <div class="column is-1">
                                <span class="icon is-size-3">
                                    <i class="fas fa-dollar-sign"></i>
                                </span>
                            </div>
                            <div class="column m-lr-20">
                                <div class="is-size- has-text-weight-bold">
                                    {{ number_format($gdn->grandTotalPriceAfterDiscount, 2) }}
                                </div>
                                <div class="is-uppercase is-size-7">
                                    Grand Total Price (After Discount)
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
            <div class="column is-12">
                <div>
                    <div class="columns is-marginless is-vcentered text-green">
                        <div class="column">
                            <div class="has-text-weight-bold">
                                Details
                            </div>
                            <div class="is-size-7 mt-3">
                                {!! $gdn->description !!}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <section class="mt-3 mx-3 m-lr-0">
        <div class="box radius-bottom-0 mb-0 has-background-white-bis">
            <div class="level">
                <div class="level-left">
                    <div class="level-item is-justify-content-left">
                        <div>
                            <h1 class="title text-green has-text-weight-medium is-size-5">
                                Delivery Order Details
                            </h1>
                        </div>
                    </div>
                </div>
                <div class="level-right">
                    <div class="level-item is-justify-content-left">
                        <div>
                            @if (isFeatureEnabled('Credit Management') && $gdn->isApproved() && !$gdn->credit()->exists() && $gdn->cash_received_in_percentage < 100 && $gdn->payment_in_credit > 0 && $gdn->customer()->exists())
                                @can('Create Credit')
                                    <x-common.transaction-button
                                        :route="route('gdns.convert_to_credit', $gdn->id)"
                                        action="convert"
                                        intention="convert this delivery order to credit"
                                        icon="fas fa-money-check"
                                        label="Convert to Credit"
                                    />
                                @endcan
                            @endif
                            @if ($gdn->isSubtracted() && !$gdn->isClosed())
                                <x-common.transaction-button
                                    :route="route('gdns.close', $gdn->id)"
                                    action="close"
                                    intention="close this delivery order"
                                    icon="fas fa-ban"
                                    label="Close"
                                />
                            @endif
                            @if ($gdn->isApproved())
                                <a
                                    class="button btn-purple is-outlined is-small is-hidden-mobile"
                                    href="{{ route('gdns.print', $gdn->id) }}"
                                    target="_blank"
                                >
                                    <span class="icon">
                                        <i class="fas fa-print"></i>
                                    </span>
                                    <span>
                                        Print
                                    </span>
                                </a>
                            @endif
                            @if ($gdn->isSubtracted() && !$gdn->isClosed())
                                <x-common.transaction-button
                                    :route="route('gdns.convert_to_siv', $gdn->id)"
                                    action="attach"
                                    intention="attach SIV to this delivery order"
                                    icon="fas fa-file-export"
                                    label="Attach SIV"
                                />
                            @endif
                            <a
                                href="{{ route('gdns.edit', $gdn->id) }}"
                                class="button is-small bg-green has-text-white"
                            >
                                <span class="icon">
                                    <i class="fas fa-pen"></i>
                                </span>
                                <span>
                                    Edit
                                </span>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="box radius-bottom-0 mb-0 radius-top-0">
            <x-common.fail-message :message="session('failedMessage')" />
            <x-common.success-message :message="session('successMessage')" />
            @if ($gdn->isSubtracted())
                <x-common.success-message message="Products have been subtracted from inventory." />
            @elseif (!$gdn->isApproved())
                @can('Approve GDN')
                    <div class="box has-background-white-ter has-text-left mb-6">
                        <p class="has-text-grey text-purple is-size-7">
                            This Delivery Order has not been approved.
                            <br>
                            Click on the button below to approve this Delivery Order.
                        </p>
                        <form
                            x-data="swal('approve', 'approve this delivery order')"
                            action="{{ route('gdns.approve', $gdn->id) }}"
                            method="post"
                            novalidate
                            @submit.prevent="open"
                        >
                            @csrf
                            <button
                                class="button bg-purple has-text-white mt-5 is-size-7-mobile"
                                x-ref="submitButton"
                            >
                                <span class="icon">
                                    <i class="fas fa-signature"></i>
                                </span>
                                <span>
                                    Approve
                                </span>
                            </button>
                        </form>
                    </div>
                @else
                    <x-common.fail-message message="This Delivery Order has not been approved." />
                @endcan
            @elseif (!$gdn->isSubtracted())
                @can('Subtract GDN')
                    <div class="box has-background-white-ter has-text-left mb-6">
                        <p class="has-text-grey text-purple is-size-7">
                            Product(s) listed below are still not subtracted from your inventory.
                            <br>
                            Click on the button below to subtract product(s) from the inventory.
                        </p>
                        <form
                            x-data="swal('subtract', 'subtract products of this delivery order')"
                            action="{{ route('gdns.subtract', $gdn->id) }}"
                            method="post"
                            novalidate
                            @submit.prevent="open"
                        >
                            @csrf
                            <button
                                class="button bg-purple has-text-white mt-5 is-size-7-mobile"
                                x-ref="submitButton"
                            >
                                <span class="icon">
                                    <i class="fas fa-minus-circle"></i>
                                </span>
                                <span>
                                    Subtract from inventory
                                </span>
                            </button>
                        </form>
                    </div>
                @else
                    <x-common.fail-message message="Product(s) listed below are still not subtracted from your inventory." />
                @endcan
            @endif
            <x-common.success-message :message="session('deleted')" />
            <div class="table-container">
                <table class="table is-hoverable is-fullwidth is-size-7">
                    <thead>
                        <tr>
                            <th><abbr> # </abbr></th>
                            <th><abbr> From </abbr></th>
                            <th><abbr> Product </abbr></th>
                            <th><abbr> Quantity </abbr></th>
                            <th><abbr> Unit Price </abbr></th>
                            @if (userCompany()->isDiscountBeforeVAT())
                                <th><abbr> Discount </abbr></th>
                            @endif
                            <th><abbr> Total </abbr></th>
                            <th><abbr> Description </abbr></th>
                            <th><abbr> Actions </abbr></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($gdn->gdnDetails as $gdnDetail)
                            <tr>
                                <td> {{ $loop->index + 1 }} </td>
                                <td class="is-capitalized">
                                    {{ $gdnDetail->warehouse->name }}
                                </td>
                                <td class="is-capitalized">
                                    <span>
                                        {{ $gdnDetail->product->name }}
                                    </span>
                                    <span class="has-text-grey {{ $gdnDetail->product->code ? '' : 'is-hidden' }}">
                                        ({{ $gdnDetail->product->code }})
                                    </span>
                                </td>
                                <td>
                                    {{ number_format($gdnDetail->quantity, 2) }}
                                    {{ $gdnDetail->product->unit_of_measurement }}
                                </td>
                                <td>
                                    {{ userCompany()->currency }}.
                                    {{ number_format($gdnDetail->unit_price, 2) }}
                                </td>
                                @if (userCompany()->isDiscountBeforeVAT())
                                    <td>
                                        {{ number_format($gdnDetail->discount * 100, 2) }}%
                                    </td>
                                @endif
                                <td>
                                    {{ number_format($gdnDetail->totalPrice, 2) }}
                                </td>
                                <td>
                                    {!! nl2br(e($gdnDetail->description)) !!}
                                </td>
                                <td>
                                    <x-common.action-buttons
                                        :buttons="['delete']"
                                        model="gdn-details"
                                        :id="$gdnDetail->id"
                                    />
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </section>
@endsection

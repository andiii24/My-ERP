@extends('layouts.app')

@section('title')
    Transfer Details
@endsection

@section('content')
    <div class="columns is-marginless is-multiline">
        <div class="column">
            <div class="box text-green">
                <div class="columns is-marginless is-vcentered is-mobile">
                    <div class="column has-text-centered is-paddingless">
                        <span class="icon is-large is-size-1">
                            <i class="fas fa-exchange-alt"></i>
                        </span>
                    </div>
                    <div class="column is-paddingless">
                        <div class="is-size-3 has-text-weight-bold">
                            {{ $transfer->code ?? 'N/A' }}
                        </div>
                        <div class="is-uppercase is-size-7">
                            Transfer No
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="column">
            <div class="box text-green">
                <div class="columns is-marginless is-vcentered is-mobile">
                    <div class="column has-text-centered is-paddingless">
                        <span class="icon is-large is-size-1">
                            <i class="fas fa-calendar-day"></i>
                        </span>
                    </div>
                    <div class="column is-paddingless">
                        <div class="is-size-3 is-size-5-mobile has-text-weight-bold">
                            {{ $transfer->issued_on->toFormattedDateString() }}
                        </div>
                        <div class="is-uppercase is-size-7">
                            Issued On
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
                                Transfer Details
                            </h1>
                        </div>
                    </div>
                </div>
                <div class="level-right">
                    <div class="level-item is-justify-content-left">
                        <div>
                            <a href="{{ route('transfers.edit', $transfer->id) }}" class="button is-small bg-green has-text-white">
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
            <div class="notification bg-lightpurple text-purple {{ session('failedMessage') ? '' : 'is-hidden' }}">
                @foreach ((array) session('failedMessage') as $message)
                    <span class="icon">
                        <i class="fas fa-times-circle"></i>
                    </span>
                    <span>
                        {{ $message }}
                    </span>
                    <br>
                @endforeach
            </div>
            <div class="notification bg-green has-text-white has-text-weight-medium {{ session('successMessage') ? '' : 'is-hidden' }}">
                <span class="icon">
                    <i class="fas fa-check-circle"></i>
                </span>
                <span>
                    {{ session('successMessage') }}
                </span>
            </div>
            @if ($transfer->isApproved() && $transfer->isTransferDone())
                <div class="box is-shadowless bg-lightgreen has-text-left mb-6">
                    <p class="has-text-grey text-green is-size-6">
                        <span class="icon">
                            <i class="fas fa-check-circle"></i>
                        </span>
                        <span>
                            Products have been transferred successfully.
                        </span>
                    </p>
                </div>
            @endif
            @if ($transfer->isApproved() && !$transfer->isTransferDone())
                @can('Make Transfer')
                    <div class="box has-background-white-ter has-text-left mb-6">
                        <p class="has-text-grey text-purple is-size-7">
                            Product(s) listed below are still not transferred
                            <br>
                            Click on the button below to transfer.
                        </p>
                        <form id="formOne" action="{{ route('transfers.transfer', $transfer->id) }}" method="post" novalidate>
                            @csrf
                            <button id="transferButton" class="button bg-purple has-text-white mt-5 is-size-7-mobile">
                                <span class="icon">
                                    <i class="fas fa-minus-circle"></i>
                                </span>
                                <span>
                                    Transfer products
                                </span>
                            </button>
                        </form>
                    </div>
                @else
                    <div class="box is-shadowless bg-lightpurple has-text-left mb-6">
                        <p class="has-text-grey text-purple is-size-6">
                            <span class="icon">
                                <i class="fas fa-exclamation-circle"></i>
                            </span>
                            <span>
                                Product(s) listed below are still not transferred.
                            </span>
                        </p>
                    </div>
                @endcan
            @endif
            @if (!$transfer->isApproved())
                @can('Approve Transfer')
                    <div class="box has-background-white-ter has-text-left mb-6">
                        <p class="has-text-grey text-purple is-size-7">
                            This Transfer has not been approved.
                            <br>
                            Click on the button below to approve this Transfer.
                        </p>
                        <form id="formOne" action="{{ route('transfers.approve', $transfer->id) }}" method="post" novalidate>
                            @csrf
                            <button id="openApproveTransferModal" class="button bg-purple has-text-white mt-5 is-size-7-mobile">
                                <span class="icon">
                                    <i class="fas fa-signature"></i>
                                </span>
                                <span>
                                    Approve Transfer
                                </span>
                            </button>
                        </form>
                    </div>
                @else
                    <div class="box is-shadowless bg-lightpurple has-text-left mb-6">
                        <p class="has-text-grey text-purple is-size-6">
                            <span class="icon">
                                <i class="fas fa-exclamation-circle"></i>
                            </span>
                            <span>
                                This Transfer has not been approved.
                            </span>
                        </p>
                    </div>
                @endcan
            @endif
            <div class="table-container">
                <table class="table is-hoverable is-fullwidth is-size-7">
                    <thead>
                        <tr>
                            <th><abbr> # </abbr></th>
                            <th><abbr> Product </abbr></th>
                            <th><abbr> Quantity </abbr></th>
                            <th><abbr> From </abbr></th>
                            <th><abbr> To</abbr></th>
                            <th><abbr> Description </abbr></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($transfer->transferDetails as $transferDetail)
                            <tr>
                                <td> {{ $loop->index + 1 }} </td>
                                <td class="is-capitalized">
                                    {{ $transferDetail->product->name }}
                                </td>
                                <td>
                                    {{ number_format($transferDetail->quantity, 2) }}
                                    {{ $transferDetail->product->unit_of_measurement }}
                                </td>
                                <td class="is-capitalized">
                                    {{ $transferDetail->warehouse->name }}
                                </td>
                                <td class="is-capitalized">
                                    {{ $transferDetail->toWarehouse->name }}
                                </td>
                                <td>
                                    {!! nl2br(e($transferDetail->description)) !!}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </section>
@endsection

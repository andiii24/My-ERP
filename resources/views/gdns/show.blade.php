@extends('layouts.app')

@section('title')
    SIV/GDN Details
@endsection

@section('content')
    <div class="columns is-marginless is-multiline">
        <div class="column">
            <div class="box text-green">
                <div class="columns is-marginless is-vcentered is-mobile">
                    <div class="column has-text-centered is-paddingless">
                        <span class="icon is-large is-size-1">
                            <i class="fas fa-file-invoice"></i>
                        </span>
                    </div>
                    <div class="column is-paddingless">
                        <div class="is-size-3 has-text-weight-bold">
                            {{ $gdn->code ?? 'N/A' }}
                        </div>
                        <div class="is-uppercase is-size-7">
                            SIV/GDN No
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
                            <i class="fas fa-hashtag"></i>
                        </span>
                    </div>
                    <div class="column is-paddingless">
                        <div class="is-size-3 has-text-weight-bold">
                            {{ $gdn->sale->receipt_no ?? 'N/A' }}
                        </div>
                        <div class="is-uppercase is-size-7">
                            Receipt No
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="column is-12">
            <div class="box text-green">
                <div class="columns is-marginless is-vcentered is-mobile">
                    <div class="column has-text-centered is-paddingless">
                        <span class="icon is-large is-size-1">
                            <i class="fas fa-user"></i>
                        </span>
                    </div>
                    <div class="column is-paddingless">
                        <div class="is-size-4 is-size-7-mobile has-text-weight-bold">
                            {{ $gdn->customer->company_name ?? 'N/A' }}
                        </div>
                        <div class="is-uppercase is-size-7">
                            Customer
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
                                SIV/GDN Details
                            </h1>
                        </div>
                    </div>
                </div>
                <div class="level-right">
                    <div class="level-item is-justify-content-left">
                        <div>
                            <a href="{{ route('gdns.edit', $gdn->id) }}" class="button is-small bg-green has-text-white">
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
            <div class="notification bg-gold has-text-white has-text-weight-medium {{ session('message') ? '' : 'is-hidden' }}">
                <span class="icon">
                    <i class="fas fa-times-circle"></i>
                </span>
                <span>
                    {{ session('message') }}
                </span>
            </div>
            @if (!$gdn->isGdnSubtracted())
                <div class="box has-background-white-ter has-text-left mb-6">
                    <p class="has-text-grey text-purple is-size-7">
                        Product(s) listed below are still not subtracted from your inventory.
                        <br>
                        Click on the button below to subtract product(s) from the inventory.
                    </p>
                    <form id="formOne" action="{{ route('merchandises.subtractFromInventory', $gdn->id) }}" method="post" novalidate>
                        @csrf
                        <button id="openCloseSaleModal" class="button bg-purple has-text-white mt-5 is-size-7-mobile">
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
                <div class="message is-success">
                    <p class="message-body">
                        <span class="icon">
                            <i class="fas fa-check-circle"></i>
                        </span>
                        <span>
                            Products have been subtracted from inventory.
                        </span>
                    </p>
                </div>
            @endif
            <div class="table-container">
                <table class="table is-hoverable is-fullwidth is-size-7">
                    <thead>
                        <tr>
                            <th><abbr> # </abbr></th>
                            <th><abbr> Warehouse </abbr></th>
                            <th><abbr> Product </abbr></th>
                            <th><abbr> Quantity </abbr></th>
                            <th><abbr> Description </abbr></th>
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
                                    {{ $gdnDetail->product->name }}
                                </td>
                                <td>
                                    {{ number_format($gdnDetail->quantity, 2) }}
                                    {{ $gdnDetail->product->unit_of_measurement }}
                                </td>
                                <td>
                                    {!! nl2br(e($gdnDetail->description)) !!}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </section>
@endsection

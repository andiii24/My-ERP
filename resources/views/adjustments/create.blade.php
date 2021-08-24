@extends('layouts.app')

@section('title')
    Create New Adjustment
@endsection

@section('content')
    <section class="mt-3 mx-3 m-lr-0">
        <div class="box radius-bottom-0 mb-0 has-background-white-bis">
            <h1 class="title text-green has-text-weight-medium is-size-5">
                New Adjustment
            </h1>
        </div>
        <form id="formOne" action="{{ route('adjustments.store') }}" method="POST" enctype="multipart/form-data" novalidate>
            @csrf
            <div class="box radius-bottom-0 mb-0 radius-top-0">
                <div class="columns is-marginless is-multiline">
                    <div class="column is-6">
                        <div class="field">
                            <label for="code" class="label text-green has-text-weight-normal">Adjustment Number <sup class="has-text-danger">*</sup> </label>
                            <div class="control has-icons-left">
                                <input class="input" type="number" name="code" id="code" value="{{ $currentAdjustmentCode + 1 }}">
                                <span class="icon is-large is-left">
                                    <i class="fas fa-hashtag"></i>
                                </span>
                                @error('code')
                                    <span class="help has-text-danger" role="alert">
                                        {{ $message }}
                                    </span>
                                @enderror
                            </div>
                        </div>
                    </div>
                    <div class="column is-6">
                        <div class="field">
                            <label for="issued_on" class="label text-green has-text-weight-normal"> Issued On <sup class="has-text-danger">*</sup> </label>
                            <div class="control has-icons-left">
                                <input class="input" type="date" name="issued_on" id="issued_on" placeholder="mm/dd/yyyy" value="{{ old('issued_on') ?? now()->toDateString() }}">
                                <div class="icon is-small is-left">
                                    <i class="fas fa-calendar-alt"></i>
                                </div>
                                @error('issued_on')
                                    <span class="help has-text-danger" role="alert">
                                        {{ $message }}
                                    </span>
                                @enderror
                            </div>
                        </div>
                    </div>
                    <div class="column is-6">
                        <div class="field">
                            <label for="reason" class="label text-green has-text-weight-normal">Description <sup class="has-text-danger"></sup></label>
                            <div class="control has-icons-left">
                                <textarea name="reason" id="reason" cols="30" rows="3" class="textarea pl-6" placeholder="Description or note to be taken">{{ old('reason') ?? '' }}</textarea>
                                <span class="icon is-large is-left">
                                    <i class="fas fa-edit"></i>
                                </span>
                                @error('reason')
                                    <span class="help has-text-danger" role="alert">
                                        {{ $message }}
                                    </span>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>
                <div id="adjustment-details">
                    @foreach (old('adjustment', [0]) as $adjustment)
                        <div class="adjustment-detail mx-3">
                            <div class="has-text-weight-medium has-text-left mt-5">
                                <span name="item-number" class="tag bg-green has-text-white is-medium radius-bottom-0">
                                    Item {{ $loop->iteration }}
                                </span>
                            </div>
                            <div class="box has-background-white-bis radius-top-0">
                                <div class="columns is-marginless is-multiline">
                                    <div class="column is-6">
                                        <div class="field">
                                            <label for="adjustment[{{ $loop->index }}][product_id]" class="label text-green has-text-weight-normal"> Product <sup class="has-text-danger">*</sup> </label>
                                            <div class="control has-icons-left">
                                                <x-product-list name="adjustment[{{ $loop->index }}]" selected-product-id="{{ $adjustment['product_id'] ?? '' }}" />
                                                <div class="icon is-small is-left">
                                                    <i class="fas fa-th"></i>
                                                </div>
                                                @error('adjustment.' . $loop->index . '.product_id')
                                                    <span class="help has-text-danger" role="alert">
                                                        {{ $message }}
                                                    </span>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                    <div class="column is-6">
                                        <label for="adjustment[{{ $loop->index }}][quantity]" class="label text-green has-text-weight-normal">Quantity <sup class="has-text-danger">*</sup> </label>
                                        <div class="field has-addons">
                                            <div class="control has-icons-left is-expanded">
                                                <input id="adjustment[{{ $loop->index }}][quantity]" name="adjustment[{{ $loop->index }}][quantity]" type="number" class="input" placeholder="Quantity" value="{{ $adjustment['quantity'] ?? '' }}">
                                                <span class="icon is-small is-left">
                                                    <i class="fas fa-balance-scale"></i>
                                                </span>
                                                @error('adjustment.' . $loop->index . '.quantity')
                                                    <span class="help has-text-danger" role="alert">
                                                        {{ $message }}
                                                    </span>
                                                @enderror
                                            </div>
                                            <div class="control">
                                                <button id="adjustment[{{ $loop->index }}][product_id]Quantity" class="button bg-green has-text-white" type="button"></button>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="column is-6">
                                        <div class="field">
                                            <label for="adjustment[{{ $loop->index }}][is_subtract]" class="label text-green has-text-weight-normal"> Operation <sup class="has-text-danger">*</sup> </label>
                                            <div class="control has-icons-left">
                                                <div class="select is-fullwidth">
                                                    <select id="adjustment[{{ $loop->index }}][is_subtract]" name="adjustment[{{ $loop->index }}][is_subtract]">
                                                        <option value="0" {{ ($adjustment['is_subtract'] ?? '') == 0 ? 'selected' : '' }}> Add </option>
                                                        <option value="1" {{ ($adjustment['is_subtract'] ?? '') == 1 ? 'selected' : '' }}> Subtract </option>
                                                    </select>
                                                </div>
                                                <div class="icon is-small is-left">
                                                    <i class="fas fa-sort"></i>
                                                </div>
                                                @error('adjustment.' . $loop->index . '.is_subtract')
                                                    <span class="help has-text-danger" role="alert">
                                                        {{ $message }}
                                                    </span>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                    <div class="column is-6">
                                        <div class="field">
                                            <label for="adjustment[{{ $loop->index }}][warehouse_id]" class="label text-green has-text-weight-normal"> Warehouse <sup class="has-text-danger">*</sup> </label>
                                            <div class="control has-icons-left">
                                                <div class="select is-fullwidth">
                                                    <select id="adjustment[{{ $loop->index }}][warehouse_id]" name="adjustment[{{ $loop->index }}][warehouse_id]">
                                                        @foreach ($warehouses as $warehouse)
                                                            <option value="{{ $warehouse->id }}" {{ ($adjustment['warehouse_id'] ?? '') == $warehouse->id ? 'selected' : '' }}>{{ $warehouse->name }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <div class="icon is-small is-left">
                                                    <i class="fas fa-warehouse"></i>
                                                </div>
                                                @error('adjustment.' . $loop->index . '.warehouse_id')
                                                    <span class="help has-text-danger" role="alert">
                                                        {{ $message }}
                                                    </span>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                    <div class="column is-6">
                                        <div class="field">
                                            <label for="adjustment[{{ $loop->index }}][reason]" class="label text-green has-text-weight-normal">Reason <sup class="has-text-danger">*</sup></label>
                                            <div class="control has-icons-left">
                                                <textarea name="adjustment[{{ $loop->index }}][reason]" id="adjustment[{{ $loop->index }}][reason]" cols="30" rows="3" class="textarea pl-6"
                                                    placeholder="Describe reason for adjusting this product level">{{ $adjustment['reason'] ?? '' }}</textarea>
                                                <span class="icon is-large is-left">
                                                    <i class="fas fa-edit"></i>
                                                </span>
                                                @error('adjustment.' . $loop->index . '.reason')
                                                    <span class="help has-text-danger" role="alert">
                                                        {{ $message }}
                                                    </span>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
                <button id="addNewAdjustmentForm" type="button" class="button bg-purple has-text-white is-small ml-3 mt-6">
                    Add More Item
                </button>
            </div>
            <div class="box radius-top-0">
                <div class="columns is-marginless">
                    <div class="column is-paddingless">
                        <div class="buttons is-right">
                            <button class="button is-white text-green" type="reset">
                                <span class="icon">
                                    <i class="fas fa-times"></i>
                                </span>
                                <span>
                                    Cancel
                                </span>
                            </button>
                            <button id="saveButton" class="button bg-green has-text-white">
                                <span class="icon">
                                    <i class="fas fa-save"></i>
                                </span>
                                <span>
                                    Save
                                </span>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </section>
@endsection

@extends('layouts.app')

@section('title')
    Create New Product
@endsection

@section('content')
    <section class="mt-3 mx-3 m-lr-0">
        <div class="box radius-bottom-0 mb-0 has-background-white-bis">
            <h1 class="title text-green has-text-weight-medium is-size-5">
                New Product
            </h1>
        </div>
        <form id="formOne" action="{{ route('products.store') }}" method="POST" enctype="multipart/form-data" novalidate>
            @csrf
            <div class="box radius-bottom-0 mb-0 radius-top-0">
                <div class="columns is-marginless is-multiline">
                    <div class="column is-12">
                        <div class="field">
                            <label for="type" class="label text-green has-text-weight-normal"> Product Type <sup class="has-text-danger">*</sup> </label>
                            <div class="control has-icons-left">
                                <div class="select is-fullwidth">
                                    <select id="type" name="type">
                                        <x-inventory-type-options :type="old('type')" />
                                    </select>
                                </div>
                                <div class="icon is-small is-left">
                                    <i class="fas fa-sitemap"></i>
                                </div>
                                @error('type')
                                    <span class="help has-text-danger" role="alert">
                                        {{ $message }}
                                    </span>
                                @enderror
                            </div>
                        </div>
                    </div>
                    <div class="column is-6">
                        <div class="field">
                            <label for="name" class="label text-green has-text-weight-normal">Name <sup class="has-text-danger">*</sup> </label>
                            <div class="control has-icons-left">
                                <input id="name" name="name" type="text" class="input" placeholder="Product Name" value="{{ old('name') ?? '' }}">
                                <span class="icon is-small is-left">
                                    <i class="fas fa-boxes"></i>
                                </span>
                                @error('name')
                                    <span class="help has-text-danger" role="alert">
                                        {{ $message }}
                                    </span>
                                @enderror
                            </div>
                        </div>
                    </div>
                    <div class="column is-6">
                        <div class="field">
                            <label for="code" class="label text-green has-text-weight-normal">Product Code <sup class="has-text-danger"></sup> </label>
                            <div class="control has-icons-left">
                                <input id="code" name="code" type="text" class="input" placeholder="Product Code" value="{{ old('code') ?? '' }}">
                                <span class="icon is-small is-left">
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
                            <label for="product_category_id" class="label text-green has-text-weight-normal"> Product Category <sup class="has-text-danger">*</sup> </label>
                            <div class="control has-icons-left">
                                <div class="select is-fullwidth">
                                    <select id="product_category_id" name="product_category_id">
                                        <option selected disabled>Category</option>
                                        @foreach ($categories as $category)
                                            <option value="{{ $category->id }}" {{ old('product_category_id') == $category->id ? 'selected' : '' }}>
                                                {{ $category->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="icon is-small is-left">
                                    <i class="fas fa-layer-group"></i>
                                </div>
                                @error('product_category_id')
                                    <span class="help has-text-danger" role="alert">
                                        {{ $message }}
                                    </span>
                                @enderror
                            </div>
                        </div>
                    </div>
                    <div class="column is-6">
                        <div class="field">
                            <label for="unit_of_measurement" class="label text-green has-text-weight-normal"> Unit of Measurement <sup class="has-text-danger">*</sup> </label>
                            <div class="control has-icons-left">
                                <div class="select is-fullwidth">
                                    <select id="unit_of_measurement" name="unit_of_measurement">
                                        <x-measurement-unit-options :selectedUnitType="old('unit_of_measurement')" />
                                    </select>
                                </div>
                                <div class="icon is-small is-left">
                                    <i class="fas fa-balance-scale"></i>
                                </div>
                                @error('unit_of_measurement')
                                    <span class="help has-text-danger" role="alert">
                                        {{ $message }}
                                    </span>
                                @enderror
                            </div>
                        </div>
                    </div>
                    <div class="column is-6">
                        <div class="field">
                            <label for="min_on_hand" class="label text-green has-text-weight-normal"> Minimum Level <sup class="has-text-danger">*</sup> </label>
                            <div class="control has-icons-left">
                                <input id="min_on_hand" name="min_on_hand" type="number" class="input" placeholder="What is considered low stock for this product?" value="{{ old('min_on_hand') ?? '0.00' }}">
                                <span class="icon is-small is-left">
                                    <i class="fas fa-battery-quarter"></i>
                                </span>
                                @error('min_on_hand')
                                    <span class="help has-text-danger" role="alert">
                                        {{ $message }}
                                    </span>
                                @enderror
                            </div>
                        </div>
                    </div>
                    <div class="column is-6">
                        <div class="field">
                            <label for="supplier_id" class="label text-green has-text-weight-normal"> Product Supplier <sup class="has-text-danger"></sup> </label>
                            <div class="control has-icons-left">
                                <div class="select is-fullwidth">
                                    <select id="supplier_id" name="supplier_id">
                                        <option selected disabled>Suppliers</option>
                                        @foreach ($suppliers as $supplier)
                                            <option value="{{ $supplier->id }}" {{ old('supplier_id') == $supplier->id ? 'selected' : '' }}>
                                                {{ $supplier->company_name }}
                                            </option>
                                        @endforeach
                                        <option value="">None</option>
                                    </select>
                                </div>
                                <div class="icon is-small is-left">
                                    <i class="fas fa-address-card"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="column is-12">
                        <div class="field">
                            <label for="description" class="label text-green has-text-weight-normal">Description</label>
                            <div class="control has-icons-left">
                                <textarea name="description" id="description" cols="30" rows="10" class="textarea pl-6" placeholder="Description or note about the new category">{{ old('description') ?? '' }}</textarea>
                                <span class="icon is-large is-left">
                                    <i class="fas fa-edit"></i>
                                </span>
                                @error('description')
                                    <span class="help has-text-danger" role="alert">
                                        {{ $message }}
                                    </span>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>
                <div id="newForm" class="columns is-marginless is-multiline is-hidden"></div>
                <div class="columns is-marginless is-multiline">
                    <div class="column is-12">
                        <div class="field mt-5">
                            <button id="addNewForm" class="button has-text-white bg-purple is-small" type="button">
                                <span class="icon">
                                    <i class="fas fa-plus-circle"></i>
                                </span>
                                <span>
                                    Add More Forms
                                </span>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="box radius-top-0">
                <x-save-button />
            </div>
        </form>
    </section>
@endsection

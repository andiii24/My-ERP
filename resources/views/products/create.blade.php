@extends('layouts.app')

@section('title', 'Create New Product')

@section('content')
    <x-common.content-wrapper>
        <x-content.header title="New Product" />
        <form
            id="formOne"
            action="{{ route('products.store') }}"
            method="POST"
            enctype="multipart/form-data"
            novalidate
        >
            @csrf
            <x-content.main x-data="productType('{{ old('type') }}')">
                <div class="columns is-marginless is-multiline">
                    <div class="column is-12">
                        <x-forms.field>
                            <x-forms.label for="type">
                                Type <sup class="has-text-danger">*</sup>
                            </x-forms.label>
                            <x-forms.control class="has-icons-left">
                                <x-forms.select
                                    class="is-fullwidth"
                                    id="type"
                                    name="type"
                                    x-model="type"
                                    x-on:change="changeProductType"
                                >
                                    <x-common.inventory-type-options :type="old('type')" />
                                </x-forms.select>
                                <x-common.icon
                                    name="fas fa-sitemap"
                                    class="is-small is-left"
                                />
                                <x-common.validation-error property="type" />
                            </x-forms.control>
                        </x-forms.field>
                    </div>
                    <div class="column is-6">
                        <x-forms.field>
                            <x-forms.label for="name">
                                Name <sup class="has-text-danger">*</sup>
                            </x-forms.label>
                            <x-forms.control class="has-icons-left">
                                <x-forms.input
                                    id="name"
                                    name="name"
                                    type="text"
                                    placeholder="Product/Service Name"
                                    value="{{ old('name') ?? '' }}"
                                />
                                <x-common.icon
                                    name="fas fa-boxes"
                                    class="is-small is-left"
                                />
                                <x-common.validation-error property="name" />
                            </x-forms.control>
                        </x-forms.field>
                    </div>
                    <div class="column is-6">
                        <x-forms.field>
                            <x-forms.label for="code">
                                Code <sup class="has-text-danger"></sup>
                            </x-forms.label>
                            <x-forms.control class="has-icons-left">
                                <x-forms.input
                                    id="code"
                                    name="code"
                                    type="text"
                                    placeholder="Product/Service Code"
                                    value="{{ old('code') ?? '' }}"
                                />
                                <x-common.icon
                                    name="fas fa-hashtag"
                                    class="is-small is-left"
                                />
                                <x-common.validation-error property="code" />
                            </x-forms.control>
                        </x-forms.field>
                    </div>
                    <div class="column is-6">
                        <x-forms.field>
                            <x-forms.label for="product_category_id">
                                Category <sup class="has-text-danger">*</sup>
                            </x-forms.label>
                            <x-forms.control class="has-icons-left">
                                <x-forms.select
                                    class="is-fullwidth"
                                    id="product_category_id"
                                    name="product_category_id"
                                >
                                    <option
                                        selected
                                        disabled
                                    >Category</option>
                                    @foreach ($categories as $category)
                                        <option
                                            value="{{ $category->id }}"
                                            {{ old('product_category_id') == $category->id ? 'selected' : '' }}
                                        >
                                            {{ $category->name }}
                                        </option>
                                    @endforeach
                                </x-forms.select>
                                <x-common.icon
                                    name="fas fa-layer-group"
                                    class="is-small is-left"
                                />
                                <x-common.validation-error property="product_category_id" />
                            </x-forms.control>
                        </x-forms.field>
                    </div>
                    <div class="column is-6">
                        <x-forms.field>
                            <x-forms.label for="unit_of_measurement">
                                Unit of Measurement <sup class="has-text-danger">*</sup>
                            </x-forms.label>
                            <x-forms.control class="has-icons-left">
                                <x-forms.select
                                    class="is-fullwidth"
                                    id="unit_of_measurement"
                                    name="unit_of_measurement"
                                >
                                    <x-common.measurement-unit-options :selectedUnitType="old('unit_of_measurement')" />
                                </x-forms.select>
                                <x-common.icon
                                    name="fas fa-balance-scale"
                                    class="is-small is-left"
                                />
                                <x-common.validation-error property="unit_of_measurement" />
                            </x-forms.control>
                        </x-forms.field>
                    </div>
                    <div
                        class="column is-6"
                        x-cloak
                        x-bind:class="{ 'is-hidden': isTypeService }"
                    >
                        <x-forms.field>
                            <x-forms.label for="min_on_hand">
                                Minimum Level <sup class="has-text-danger">*</sup>
                            </x-forms.label>
                            <x-forms.control class="has-icons-left">
                                <x-forms.input
                                    id="min_on_hand"
                                    name="min_on_hand"
                                    type="number"
                                    placeholder="What is considered low stock for this product?"
                                    value="{{ old('min_on_hand') ?? '0.00' }}"
                                />
                                <x-common.icon
                                    name="fas fa-battery-quarter"
                                    class="is-small is-left"
                                />
                                <x-common.validation-error property="min_on_hand" />
                            </x-forms.control>
                        </x-forms.field>
                    </div>
                    <div
                        class="column is-6"
                        x-cloak
                        x-bind:class="{ 'is-hidden': isTypeService }"
                    >
                        <x-forms.field>
                            <x-forms.label for="supplier_id">
                                Product Supplier <sup class="has-text-danger"></sup>
                            </x-forms.label>
                            <x-forms.control class="has-icons-left">
                                <x-forms.select
                                    class="is-fullwidth"
                                    id="supplier_id"
                                    name="supplier_id"
                                >
                                    <option
                                        selected
                                        disabled
                                    >Suppliers</option>
                                    @foreach ($suppliers as $supplier)
                                        <option
                                            value="{{ $supplier->id }}"
                                            {{ old('supplier_id') == $supplier->id ? 'selected' : '' }}
                                        >
                                            {{ $supplier->company_name }}
                                        </option>
                                    @endforeach
                                    <option value="">None</option>
                                </x-forms.select>
                                <x-common.icon
                                    name="fas fa-address-card"
                                    class="is-small is-left"
                                />
                            </x-forms.control>
                        </x-forms.field>
                    </div>
                    <div
                        class="column is-6"
                        x-cloak
                        x-bind:class="{ 'is-hidden': isTypeService }"
                    >
                        <x-forms.field>
                            <x-forms.label for="is_batchable">
                                Is Batchable <sup class="has-text-danger"></sup>
                            </x-forms.label>
                            <x-forms.control class="has-icons-left ">
                                <x-forms.select
                                    class="is-fullwidth"
                                    id="is_batchable"
                                    name="is_batchable"
                                >
                                    <option
                                        value="1"
                                        @selected(old('is_batchable') == '1')
                                    > Yes </option>
                                    <option
                                        <option
                                        value="0"
                                        @selected(old('is_batchable') == '0')
                                    > No </option>
                                </x-forms.select>
                                <x-common.icon
                                    name="fas fa-sort"
                                    class="is-small is-left"
                                />
                                <x-common.validation-error property="is_batchable" />
                            </x-forms.control>
                        </x-forms.field>
                    </div>
                    <div
                        class="column is-6"
                        x-cloak
                        x-bind:class="{ 'is-hidden': isTypeService }"
                    >
                        <x-forms.field>
                            <x-forms.label for="batch_priority">
                                Batch Priority <sup class="has-text-danger"></sup>
                            </x-forms.label>
                            <x-forms.control class="has-icons-left ">
                                <x-forms.select
                                    class="is-fullwidth"
                                    id="batch_priority"
                                    name="batch_priority"
                                >
                                    <option
                                        disabled
                                        selected
                                    >
                                        Select Batch Priority
                                    </option>
                                    <option
                                        value="fifo"
                                        @selected(old('batch_priority') == 'fifo')
                                    > First In First Out </option>
                                    <option
                                        <option
                                        value="lifo"
                                        @selected(old('batch_priority') == 'lifo')
                                    > Last In First Out </option>
                                    <option
                                        <option
                                        value=""
                                    > None</option>
                                </x-forms.select>
                                <x-common.icon
                                    name="fas fa-sort"
                                    class="is-small is-left"
                                />
                                <x-common.validation-error property="batch_priority" />
                            </x-forms.control>
                        </x-forms.field>
                    </div>
                    <div class="column is-12">
                        <x-forms.field>
                            <x-forms.label for="description">
                                Description
                            </x-forms.label>
                            <x-forms.control class="has-icons-left">
                                <x-forms.textarea
                                    name="description"
                                    id="description"
                                    class="textarea summernote"
                                    placeholder="Description or note about the new category"
                                >{{ old('description') ?? '' }}</x-forms.textarea>
                                <x-common.validation-error property="description" />
                            </x-forms.control>
                        </x-forms.field>
                    </div>
                </div>
                <div
                    id="newForm"
                    class="columns is-marginless is-multiline is-hidden"
                ></div>
                <div class="columns is-marginless is-multiline">
                    <div class="column is-12">
                        <x-forms.field class="mt-5">
                            <x-common.button
                                id="addNewForm"
                                tag="button"
                                type="button"
                                mode="button"
                                label=" Add More Forms"
                                class="bg-purple has-text-white is-small ml-3 mt-6"
                            />
                        </x-forms.field>
                    </div>
                </div>
            </x-content.main>
            <x-content.footer>
                <x-common.save-button />
            </x-content.footer>
        </form>
    </x-common.content-wrapper>
@endsection

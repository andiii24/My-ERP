@extends('layouts.app')

@section('title', 'Create New Brand')

@section('content')
    <x-common.content-wrapper>
        <x-content.header title="New Brand" />
        <form
            id="formOne"
            action="{{ route('brands.store') }}"
            method="post"
            enctype="multipart/form-data"
            novalidate
        >
            @csrf
            <x-content.main
                x-data="brandMasterDetailForm({{ Js::from(session()->getOldInput()) }})"
                x-init="$store.errors.setErrors({{ Js::from($errors->get('brand.*')) }})"
            >
                <template
                    x-for="(brand, index) in brands"
                    x-bind:key="index"
                >
                    <div class="mx-3">
                        <x-forms.field class="has-addons mb-0 mt-5">
                            <x-forms.control>
                                <span
                                    class="tag bg-green has-text-white is-medium is-radiusless"
                                    x-text="`Item ${index + 1}`"
                                ></span>
                            </x-forms.control>
                            <x-forms.control>
                                <x-common.button
                                    tag="button"
                                    mode="tag"
                                    type="button"
                                    class="bg-lightgreen has-text-white is-medium is-radiusless"
                                    x-on:click="remove(index)"
                                >
                                    <x-common.icon
                                        name="fas fa-times-circle"
                                        class="text-green"
                                    />
                                </x-common.button>
                            </x-forms.control>
                        </x-forms.field>
                        <div class="box has-background-white-bis radius-top-0">
                            <div class="columns is-marginless is-multiline">
                                <div class="column is-6">
                                    <x-forms.label x-bind:for="`brand[${index}][name]`">
                                        Name <sup class="has-text-danger">*</sup>
                                    </x-forms.label>
                                    <x-forms.field class="has-addons">
                                        <x-forms.control class="has-icons-left is-expanded">
                                            <x-forms.input
                                                type="text"
                                                x-bind:id="`brand[${index}][name]`"
                                                x-bind:name="`brand[${index}][name]`"
                                                x-model="brand.name"
                                                placeholder="Brand Name"
                                            />
                                            <x-common.icon
                                                name="fas fa-trademark"
                                                class="is-small is-left"
                                            />
                                            <span
                                                class="help has-text-danger"
                                                x-text="$store.errors.getErrors(`brand.${index}.name`)"
                                            ></span>
                                        </x-forms.control>
                                    </x-forms.field>
                                </div>
                                <div class="column is-6">
                                    <x-forms.field>
                                        <x-forms.label x-bind:for="`brand[${index}][description]`">
                                            Description <sup class="has-text-danger"></sup>
                                        </x-forms.label>
                                        <x-forms.control class="has-icons-left">
                                            <x-forms.textarea
                                                x-bind:id="`brand[${index}][description]`"
                                                x-bind:name="`brand[${index}][description]`"
                                                x-model="brand.description"
                                                class="textarea pl-6"
                                                placeholder="Description or note about the new brand"
                                            >
                                            </x-forms.textarea>
                                            <x-common.icon
                                                name="fas fa-edit"
                                                class="is-large is-left"
                                            />
                                            <span
                                                class="help has-text-danger"
                                                x-text="$store.errors.getErrors(`brand.${index}.description`)"
                                            ></span>
                                        </x-forms.control>
                                    </x-forms.field>
                                </div>
                            </div>
                        </div>
                    </div>
                </template>
                <x-common.button
                    tag="button"
                    type="button"
                    mode="button"
                    label="Add More Item"
                    class="bg-purple has-text-white is-small ml-3 mt-6"
                    x-on:click="add"
                />
            </x-content.main>
            <x-content.footer>
                <x-common.save-button />
            </x-content.footer>
        </form>
    </x-common.content-wrapper>
@endsection

@push('scripts')
    <script>
        document.addEventListener("alpine:init", () => {
            Alpine.data("brandMasterDetailForm", ({
                brand
            }) => ({
                brands: [],

                async init() {
                    if (brand) {
                        this.brands = brand;

                        return;
                    }

                    this.add();
                },
                add() {
                    this.brands.push({});
                },
                remove(index) {
                    if (this.brands.length <= 0) {
                        return;
                    }

                    this.brands.splice(index, 1);

                    Pace.restart();
                },
            }));
        });
    </script>
@endpush

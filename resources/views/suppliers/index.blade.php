@extends('layouts.app')

@section('title', 'Suppliers')

@section('content')
    <x-common.content-wrapper>
        <x-content.header>
            <x-slot name="header">
                <h1 class="title text-green has-text-weight-medium is-size-5">
                    Suppliers
                    <span class="tag bg-green has-text-white has-text-weight-normal ml-1 m-lr-0">
                        <x-common.icon name="fas fa-user" />
                        <span>
                            {{ number_format($totalSuppliers) }} {{ str()->plural('supplier', $totalSuppliers) }}
                        </span>
                        <span class="is-hidden-mobile">
                            &nbsp; registered
                        </span>
                    </span>
                </h1>
            </x-slot>
            @can('Import Supplier')
                <x-common.button
                    tag="button"
                    mode="button"
                    @click="$dispatch('open-import-modal') "
                    icon="fas fa-upload"
                    label="Import Suppliers"
                    class="btn-green is-outlined is-small"
                />
            @endcan
            @can('Create Supplier')
                <x-common.button
                    tag="a"
                    href="{{ route('suppliers.create') }}"
                    mode="button"
                    icon="fas fa-plus-circle"
                    label="Create Supplier"
                    class="btn-green is-outlined is-small"
                />
            @endcan
        </x-content.header>
        <x-content.footer>
            <x-common.success-message :message="session('deleted') ?? session('imported')" />
            <x-common.fail-message :message="count($errors->all()) ? $errors->all() : null" />
            {{ $dataTable->table() }}
        </x-content.footer>
    </x-common.content-wrapper>
    @can('Import Supplier')
        <x-common.import
            title="Import Suppliers"
            action="{{ route('suppliers.import') }}"
        />
    @endcan
@endsection

@push('scripts')
    {{ $dataTable->scripts() }}
@endpush

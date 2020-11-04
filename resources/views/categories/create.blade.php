@extends('layouts.app')

@section('title')
Create New Product Category - Onrica Technologies PLC
@endsection

@section('content')
<section class="mt-3 mx-3">
    <div class="box radius-bottom-0 mb-0 has-background-white-bis">
        <h1 class="title text-green has-text-weight-medium is-size-5">
            New Product Category
        </h1>
    </div>
    <div class="box radius-bottom-0 mb-0 radius-top-0">
        <div class="columns is-marginless">
            <div class="column is-6">
                <div class="field">
                    <label for="name" class="label text-green has-text-weight-normal">Name <sup class="has-text-danger">*</sup> </label>
                    <div class="control has-icons-left">
                        <input id="name" name="name" type="text" class="input" placeholder="Category Name">
                        <span class="icon is-small is-left">
                            <i class="fas fa-layer-group"></i>
                        </span>
                    </div>
                </div>
                <div class="field mt-5">
                    <label for="name" class="label text-green has-text-weight-normal">Description</label>
                    <div class="control has-icons-left">
                        <textarea name="description" id="description" cols="30" rows="10" class="textarea pl-6" placeholder="Description or note about the new category"></textarea>
                        <span class="icon is-large is-left">
                            <i class="fas fa-edit"></i>
                        </span>
                    </div>
                </div>
                <div class="field mt-5">
                    <button class="button has-text-white bg-purple is-small">
                        <span class="icon">
                            <i class="fas fa-plus-circle"></i>
                        </span>
                        <span>
                            Add More Form
                        </span>
                    </button>
                </div>
            </div>
        </div>
    </div>
    <div class="box radius-top-0">
        <div class="columns is-marginless">
            <div class="column is-paddingless">
                <div class="buttons is-right">
                    <button class="button is-white text-green">
                        <span class="icon">
                            <i class="fas fa-times"></i>
                        </span>
                        <span>
                            Cancel
                        </span>
                    </button>
                    <button class="button bg-green has-text-white">
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
</section>
@endsection

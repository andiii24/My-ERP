@extends('layouts.app')

@section('title', 'Add New Employee')

@section('content')
    <x-common.content-wrapper>
        <x-content.header title="New Employee" />
        <form
            id="formOne"
            action="{{ route('employees.store') }}"
            method="POST"
            enctype="multipart/form-data"
            novalidate
        >
            @csrf
            <x-content.main>
                <x-common.fail-message :message="session('limitReachedMessage')" />
                <section>
                    <div class="box radius-bottom-0 mb-0 has-background-white-bis p-3">
                        <h1 class="text-green is-size-5">
                            Basic Information
                        </h1>
                    </div>
                    <div class="box is-radiusless">
                        <div class="columns is-marginless is-multiline">
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
                                            placeholder="Employee Name"
                                            value="{{ old('name') }}"
                                            autocomplete="name"
                                            autofocus
                                        />
                                        <x-common.icon
                                            name="fas fa-user"
                                            class="is-small is-left"
                                        />
                                        <x-common.validation-error property="name" />
                                    </x-forms.control>
                                </x-forms.field>
                            </div>
                            <div class="column is-6">
                                <x-forms.field>
                                    <x-forms.label for="email">
                                        Email <sup class="has-text-danger">*</sup>
                                    </x-forms.label>
                                    <x-forms.control class="has-icons-left">
                                        <x-forms.input
                                            id="email"
                                            name="email"
                                            type="text"
                                            placeholder="Email Address"
                                            value="{{ old('email') }}"
                                            autocomplete="email"
                                        />
                                        <x-common.icon
                                            name="fas fa-at"
                                            class="is-small is-left"
                                        />
                                        <x-common.validation-error property="email" />
                                    </x-forms.control>
                                </x-forms.field>
                            </div>
                            <div class="column is-6">
                                <x-forms.field>
                                    <x-forms.label for="gender">
                                        Gender <sup class="has-text-danger">*</sup>
                                    </x-forms.label>
                                    <x-forms.control class="has-icons-left ">
                                        <x-forms.select
                                            class="is-fullwidth"
                                            id="gender"
                                            name="gender"
                                        >
                                            <option
                                                value="male"
                                                @selected(old('male'))
                                            > Male</option>
                                            <option
                                                value="female"
                                                @selected(old('female'))
                                            > Female </option>
                                        </x-forms.select>
                                        <x-common.icon
                                            name="fas fa-sort"
                                            class="is-small is-left"
                                        />
                                        <x-common.validation-error property="gender" />
                                    </x-forms.control>
                                </x-forms.field>
                            </div>
                            <div class="column is-6">
                                <x-forms.field>
                                    <x-forms.label for="address">
                                        Address <sup class="has-text-danger">*</sup>
                                    </x-forms.label>
                                    <x-forms.control class="has-icons-left">
                                        <x-forms.input
                                            id="address"
                                            name="address"
                                            type="text"
                                            placeholder="Address"
                                            value="{{ old('address') }}"
                                            autocomplete="address"
                                        />
                                        <x-common.icon
                                            name="fas fa-map-marker-alt"
                                            class="is-small is-left"
                                        />
                                        <x-common.validation-error property="address" />
                                    </x-forms.control>
                                </x-forms.field>
                            </div>
                            <div class="column is-6">
                                <x-forms.field>
                                    <x-forms.label for="bank_name">
                                        Bank Name <sup class="has-text-danger"></sup>
                                    </x-forms.label>
                                    <x-forms.control class="has-icons-left">
                                        <x-forms.input
                                            id="bank_name"
                                            name="bank_name"
                                            type="text"
                                            placeholder="Bank Name"
                                            value="{{ old('bank_name') }}"
                                            autocomplete="bank_name"
                                        />
                                        <x-common.icon
                                            name="fas fa-university"
                                            class="is-small is-left"
                                        />
                                        <x-common.validation-error property="bank_name" />
                                    </x-forms.control>
                                </x-forms.field>
                            </div>
                            <div class="column is-6">
                                <x-forms.field>
                                    <x-forms.label for="bank_account">
                                        Bank Account <sup class="has-text-danger"></sup>
                                    </x-forms.label>
                                    <x-forms.control class="has-icons-left">
                                        <x-forms.input
                                            id="bank_account"
                                            name="bank_account"
                                            type="text"
                                            placeholder="Bank Account"
                                            value="{{ old('bank_account') }}"
                                            autocomplete="bank_account"
                                        />
                                        <x-common.icon
                                            name="fas fa-hashtag"
                                            class="is-small is-left"
                                        />
                                        <x-common.validation-error property="bank_account" />
                                    </x-forms.control>
                                </x-forms.field>
                            </div>
                            <div class="column is-6">
                                <x-forms.field>
                                    <x-forms.label for="tin_number">
                                        Tin Number <sup class="has-text-danger"></sup>
                                    </x-forms.label>
                                    <x-forms.control class="has-icons-left">
                                        <x-forms.input
                                            id="tin_number"
                                            name="tin_number"
                                            type="text"
                                            placeholder="Tin Number"
                                            value="{{ old('tin_number') }}"
                                            autocomplete="tin_number"
                                        />
                                        <x-common.icon
                                            name="fas fa-hashtag"
                                            class="is-small is-left"
                                        />
                                        <x-common.validation-error property="tin_number" />
                                    </x-forms.control>
                                </x-forms.field>
                            </div>
                            <div class="column is-6">
                                <x-forms.field>
                                    <x-forms.label for="job_type">
                                        Job Type <sup class="has-text-danger">*</sup>
                                    </x-forms.label>
                                    <x-forms.control class="has-icons-left ">
                                        <x-forms.select
                                            class="is-fullwidth"
                                            id="job_type"
                                            name="job_type"
                                        >
                                            <option
                                                value="full time"
                                                @selected(old('Full time'))
                                            > Full Time </option>
                                            <option
                                                value="part time"
                                                @selected(old('Part time'))
                                            > Part Time </option>
                                            <option
                                                value="contractual"
                                                @selected(old('Contractual'))
                                            > Contractual </option>
                                            <option
                                                value="remote"
                                                @selected(old('Remote'))
                                            > Remote </option>
                                            <option
                                                value="internship"
                                                @selected(old('Internship'))
                                            > Internship </option>
                                        </x-forms.select>
                                        <x-common.icon
                                            name="fas fa-sort"
                                            class="is-small is-left"
                                        />
                                        <x-common.validation-error property="job_type" />
                                    </x-forms.control>
                                </x-forms.field>
                            </div>
                            <div class="column is-6">
                                <x-forms.field>
                                    <x-forms.label for="phone">
                                        Phone <sup class="has-text-danger">*</sup>
                                    </x-forms.label>
                                    <x-forms.control class="has-icons-left">
                                        <x-forms.input
                                            id="phone"
                                            name="phone"
                                            type="phone"
                                            placeholder="Phone"
                                            value="{{ old('phone') }}"
                                            autocomplete="phone"
                                        />
                                        <x-common.icon
                                            name="fas fa-phone"
                                            class="is-small is-left"
                                        />
                                        <x-common.validation-error property="phone" />
                                    </x-forms.control>
                                </x-forms.field>
                            </div>
                            <div class="column is-6">
                                <x-forms.field>
                                    <x-forms.label for="id_type">
                                        ID Type <sup class="has-text-danger">*</sup>
                                    </x-forms.label>
                                    <x-forms.control class="has-icons-left ">
                                        <x-forms.select
                                            class="is-fullwidth"
                                            id="id_type"
                                            name="id_type"
                                        >
                                            <option
                                                value="passport"
                                                @selected(old('passport'))
                                            > Passport </option>
                                            <option
                                                value="drivers license"
                                                @selected(old('drivers license'))
                                            > Drivers license </option>
                                            <option
                                                value="employee id"
                                                @selected(old('employee id'))
                                            > Employee Id </option>
                                            <option
                                                value="kebele id"
                                                @selected(old('Kebele Id'))
                                            > Kebele Id </option>
                                            <option
                                                value="student id"
                                                @selected(old('student id'))
                                            > Student Id </option>
                                        </x-forms.select>
                                        <x-common.icon
                                            name="fas fa-id-card"
                                            class="is-small is-left"
                                        />
                                        <x-common.validation-error property="id_type" />
                                    </x-forms.control>
                                </x-forms.field>
                            </div>
                            <div class="column is-6">
                                <x-forms.field>
                                    <x-forms.label for="id_number">
                                        ID Number <sup class="has-text-danger"></sup>
                                    </x-forms.label>
                                    <x-forms.control class="has-icons-left">
                                        <x-forms.input
                                            id="id_number"
                                            name="id_number"
                                            type="text"
                                            placeholder="ID Number"
                                            value="{{ old('id_number') }}"
                                            autocomplete="id_number"
                                        />
                                        <x-common.icon
                                            name="fas fa-hashtag"
                                            class="is-small is-left"
                                        />
                                        <x-common.validation-error property="id_number" />
                                    </x-forms.control>
                                </x-forms.field>
                            </div>
                            <div class="column is-6">
                                <x-forms.field>
                                    <x-forms.label for="date_of_hiring">
                                        Date Of Hiring <sup class="has-text-danger">*</sup>
                                    </x-forms.label>
                                    <x-forms.control class="has-icons-left">
                                        <x-forms.input
                                            id="date_of_hiring"
                                            name="date_of_hiring"
                                            type="datetime-local"
                                            placeholder="mm/dd/yyyy"
                                            value="{{ old('date_of_hiring') ?? now()->toDateTimeLocalString() }}"
                                            autocomplete="date_of_hiring"
                                        />
                                        <x-common.icon
                                            name="fas fa-calendar-alt"
                                            class="is-small is-left"
                                        />
                                        <x-common.validation-error property="date_of_hiring" />
                                    </x-forms.control>
                                </x-forms.field>
                            </div>
                            <div class="column is-6">
                                <x-forms.field>
                                    <x-forms.label for="gross_salary">
                                        Gross salery <sup class="has-text-danger"></sup>
                                    </x-forms.label>
                                    <x-forms.control class="has-icons-left">
                                        <x-forms.input
                                            id="gross_salary"
                                            name="gross_salary"
                                            type="number"
                                            placeholder="Gross Salary"
                                            value="{{ old('gross_salary') }}"
                                            autocomplete="gross_salary"
                                        />
                                        <x-common.icon
                                            name="fas fa-phone"
                                            class="is-small is-left"
                                        />
                                        <x-common.validation-error property="gross_salary" />
                                    </x-forms.control>
                                </x-forms.field>
                            </div>
                            <div class="column is-6">
                                <x-forms.field>
                                    <x-forms.label for="date_of_birth">
                                        Date Of Birth <sup class="has-text-danger"></sup>
                                    </x-forms.label>
                                    <x-forms.control class="has-icons-left">
                                        <x-forms.input
                                            id="date_of_birth"
                                            name="date_of_birth"
                                            type="datetime-local"
                                            placeholder="mm/dd/yyyy"
                                            value="{{ old('date_of_birth') }}"
                                            autocomplete="date_of_birth"
                                        />
                                        <x-common.icon
                                            name="fas fa-calendar-alt"
                                            class="is-small is-left"
                                        />
                                        <x-common.validation-error property="date_of_birth" />
                                    </x-forms.control>
                                </x-forms.field>
                            </div>
                            <div class="column is-6">
                                <x-forms.field>
                                    <x-forms.label for="emergency_name">
                                        Emergency Name <sup class="has-text-danger"></sup>
                                    </x-forms.label>
                                    <x-forms.control class="has-icons-left">
                                        <x-forms.input
                                            id="emergency_name"
                                            name="emergency_name"
                                            type="text"
                                            placeholder="Emergency Name"
                                            value="{{ old('emergency_name') }}"
                                            autocomplete="emergency_name"
                                        />
                                        <x-common.icon
                                            name="fas fa-user"
                                            class="is-small is-left"
                                        />
                                        <x-common.validation-error property="emergency_name" />
                                    </x-forms.control>
                                </x-forms.field>
                            </div>
                            <div class="column is-6">
                                <x-forms.field>
                                    <x-forms.label for="emergency_phone">
                                        Emergency Phone <sup class="has-text-danger"></sup>
                                    </x-forms.label>
                                    <x-forms.control class="has-icons-left">
                                        <x-forms.input
                                            id="emergency_phone"
                                            name="emergency_phone"
                                            type="phone"
                                            placeholder="Emergency Phone"
                                            value="{{ old('emergency_phone') }}"
                                            autocomplete="emergency_phone"
                                        />
                                        <x-common.icon
                                            name="fas fa-phone"
                                            class="is-small is-left"
                                        />
                                        <x-common.validation-error property="emergency_phone" />
                                    </x-forms.control>
                                </x-forms.field>
                            </div>
                            <div class="column is-6">
                                <x-forms.field>
                                    <x-forms.label for="department_id">
                                        Department <sup class="has-text-danger">*</sup>
                                    </x-forms.label>
                                    <x-forms.control class="has-icons-left">
                                        <x-forms.select
                                            class="is-fullwidth"
                                            id="department_id"
                                            name="department_id"
                                        >
                                            @foreach ($departments as $department)
                                                <option
                                                    value="{{ $department->id }}"
                                                    {{ old('department_id') == $department->id ? 'selected' : '' }}
                                                >{{ $department->name }}</option>
                                            @endforeach
                                        </x-forms.select>
                                        <x-common.icon
                                            name="fas fa-sort"
                                            class="is-small is-left"
                                        />
                                        <x-common.validation-error property="department_id" />
                                    </x-forms.control>
                                </x-forms.field>
                            </div>
                            <div class="column is-6">
                                <x-forms.field>
                                    <x-forms.label for="password">
                                        Password <sup class="has-text-danger">*</sup>
                                    </x-forms.label>
                                    <x-forms.control class="has-icons-left">
                                        <x-forms.input
                                            id="password"
                                            name="password"
                                            type="password"
                                            placeholder="Employee Password"
                                            autocomplete="new-password"
                                        />
                                        <x-common.icon
                                            name="fas fa-unlock"
                                            class="is-small is-left"
                                        />
                                        <x-common.validation-error property="password" />
                                    </x-forms.control>
                                </x-forms.field>
                            </div>
                            <div class="column is-6">
                                <x-forms.field>
                                    <x-forms.label for="password-confirm">
                                        Confrim Password <sup class="has-text-danger">*</sup>
                                    </x-forms.label>
                                    <x-forms.control class="has-icons-left">
                                        <x-forms.input
                                            id="password-confirm"
                                            type="password"
                                            name="password_confirmation"
                                            placeholder="Confirm Password"
                                            autocomplete="new-password"
                                        />
                                        <x-common.icon
                                            name="fas fa-unlock"
                                            class="is-small is-left"
                                        />
                                        <x-common.validation-error property="password" />
                                    </x-forms.control>
                                </x-forms.field>
                            </div>
                            <div class="column is-6">
                                <x-forms.field>
                                    <x-forms.label for="position">
                                        Job Title/Position <sup class="has-text-danger">*</sup>
                                    </x-forms.label>
                                    <x-forms.control class="has-icons-left">
                                        <x-forms.input
                                            id="position"
                                            name="position"
                                            type="text"
                                            placeholder="Job Title"
                                            value="{{ old('position') ?? '' }}"
                                        />
                                        <x-common.icon
                                            name="fas fa-user-tie"
                                            class="is-small is-left"
                                        />
                                        <x-common.validation-error property="position" />
                                    </x-forms.control>
                                </x-forms.field>
                            </div>
                            <div class="column is-6">
                                <x-forms.field>
                                    <x-forms.label for="warehouse_id">
                                        Assign To <sup class="has-text-danger">*</sup>
                                    </x-forms.label>
                                    <x-forms.control class="has-icons-left">
                                        <x-forms.select
                                            class="is-fullwidth"
                                            id="warehouse_id"
                                            name="warehouse_id"
                                        >
                                            @foreach ($warehouses as $warehouse)
                                                <option
                                                    value="{{ $warehouse->id }}"
                                                    {{ old('warehouse_id') == $warehouse->id ? 'selected' : '' }}
                                                >{{ $warehouse->name }}</option>
                                            @endforeach
                                        </x-forms.select>
                                        <x-common.icon
                                            name="fas fa-warehouse"
                                            class="is-small is-left"
                                        />
                                        <x-common.validation-error property="warehouse_id" />
                                    </x-forms.control>
                                </x-forms.field>
                            </div>
                            <div class="column is-6">
                                <x-forms.field>
                                    <x-forms.label for="enabled">
                                        Can this employee access the system? <sup class="has-text-danger">*</sup>
                                    </x-forms.label>
                                    <x-forms.control>
                                        <label class="radio has-text-grey has-text-weight-light">
                                            <input
                                                type="radio"
                                                name="enabled"
                                                value="1"
                                                class="mt-3"
                                                {{ old('enabled') == 1 ? 'checked' : '' }}
                                            >
                                            Yes, this employee can access the system
                                        </label>
                                        <br>
                                        <label class="radio has-text-grey has-text-weight-light mt-2">
                                            <input
                                                type="radio"
                                                name="enabled"
                                                value="0"
                                                {{ old('enabled') == 0 ? 'checked' : '' }}
                                            >
                                            No, this employee can't access the system
                                        </label>
                                        <x-common.validation-error property="enabled" />
                                    </x-forms.control>
                                </x-forms.field>
                            </div>
                            <div class="column is-6">
                                <x-forms.field>
                                    <x-forms.label for="role">
                                        Choose Role <sup class="has-text-danger">*</sup>
                                    </x-forms.label>
                                    <x-forms.control>
                                        @foreach ($roles as $role)
                                            <label class="radio has-text-grey has-text-weight-light">
                                                <input
                                                    type="radio"
                                                    name="role"
                                                    value="{{ $role->name }}"
                                                    class="mt-3"
                                                    {{ old('role') == $role->name ? 'checked' : '' }}
                                                >
                                                {{ $role->name }}
                                            </label>
                                            <br>
                                        @endforeach
                                        <x-common.validation-error property="role" />
                                    </x-forms.control>
                                </x-forms.field>
                            </div>
                        </div>
                    </div>
                </section>
                <section class="mt-5">
                    <div class="box radius-bottom-0 mb-0 has-background-white-bis p-3">
                        <h1 class="text-green is-size-5">
                            Branch Permissions
                        </h1>
                    </div>
                    <div class="box is-radiusless">
                        <div class="columns is-marginless is-multiline">
                            <div class="column is-3">
                                <label
                                    for="transactions[]"
                                    class="label text-green"
                                > Transactions <sup class="has-text-danger"></sup> </label>
                                <x-forms.field>
                                    @foreach ($warehouses as $warehouse)
                                        <label class="checkbox mr-3 has-text-grey has-text-weight-light">
                                            <input
                                                name="transactions[]"
                                                value="{{ $warehouse->id }}"
                                                type="checkbox"
                                                {{ in_array($warehouse->id, old('transactions', [])) ? 'checked' : '' }}
                                            >
                                            {{ $warehouse->name }}
                                        </label>
                                        <br>
                                    @endforeach
                                </x-forms.field>
                                <x-common.validation-error property="transactions.*" />
                            </div>
                            <div class="column is-3">
                                <label
                                    for="read[]"
                                    class="label text-green"
                                > Inventory Level <sup class="has-text-danger"></sup> </label>
                                <x-forms.field>
                                    @foreach ($warehouses as $warehouse)
                                        <label class="checkbox mr-3 has-text-grey has-text-weight-light">
                                            <input
                                                name="read[]"
                                                value="{{ $warehouse->id }}"
                                                type="checkbox"
                                                {{ in_array($warehouse->id, old('read', [])) ? 'checked' : '' }}
                                            >
                                            {{ $warehouse->name }}
                                        </label>
                                        <br>
                                    @endforeach
                                </x-forms.field>
                                <x-common.validation-error property="read.*" />
                            </div>
                            <div class="column is-3">
                                <label
                                    for="sales[]"
                                    class="label text-green"
                                > Sales <sup class="has-text-weight-light is-size-7">(Delivery Order, Reservation)</sup> </label>
                                <x-forms.field>
                                    @foreach ($warehouses as $warehouse)
                                        <label class="checkbox mr-3 has-text-grey has-text-weight-light">
                                            <input
                                                name="sales[]"
                                                value="{{ $warehouse->id }}"
                                                type="checkbox"
                                                {{ in_array($warehouse->id, old('sales', [])) == $warehouse->id ? 'checked' : '' }}
                                            >
                                            {{ $warehouse->name }}
                                        </label>
                                        <br>
                                    @endforeach
                                    <x-common.validation-error property="sales.*" />
                                </x-forms.field>
                            </div>
                            <div class="column is-3">
                                <label
                                    for="subtract[]"
                                    class="label text-green"
                                > Subtract <sup class="has-text-weight-light is-size-7">(Transfer, Damage)</sup> </label>
                                <x-forms.field>
                                    @foreach ($warehouses as $warehouse)
                                        <label class="checkbox mr-3 has-text-grey has-text-weight-light">
                                            <input
                                                name="subtract[]"
                                                value="{{ $warehouse->id }}"
                                                type="checkbox"
                                                {{ in_array($warehouse->id, old('subtract', [])) ? 'checked' : '' }}
                                            >
                                            {{ $warehouse->name }}
                                        </label>
                                        <br>
                                    @endforeach
                                    <x-common.validation-error property="subtract.*" />
                                </x-forms.field>
                            </div>
                            <div class="column is-3">
                                <label
                                    for="add[]"
                                    class="label text-green"
                                > Add <sup class="has-text-weight-light is-size-7">(Transfer, Return, GRN)</sup> </label>
                                <x-forms.field>
                                    @foreach ($warehouses as $warehouse)
                                        <label class="checkbox mr-3 has-text-grey has-text-weight-light">
                                            <input
                                                name="add[]"
                                                value="{{ $warehouse->id }}"
                                                type="checkbox"
                                                {{ in_array($warehouse->id, old('add', [])) == $warehouse->id ? 'checked' : '' }}
                                            >
                                            {{ $warehouse->name }}
                                        </label>
                                        <br>
                                    @endforeach
                                    <x-common.validation-error property="add.*" />
                                </x-forms.field>
                            </div>
                            <div class="column is-3">
                                <label
                                    for="adjustment[]"
                                    class="label text-green"
                                > Adjustment <sup class="has-text-danger"></sup> </label>
                                <x-forms.field>
                                    @foreach ($warehouses as $warehouse)
                                        <label class="checkbox mr-3 has-text-grey has-text-weight-light">
                                            <input
                                                name="adjustment[]"
                                                value="{{ $warehouse->id }}"
                                                type="checkbox"
                                                {{ in_array($warehouse->id, old('adjustment', [])) == $warehouse->id ? 'checked' : '' }}
                                            >
                                            {{ $warehouse->name }}
                                        </label>
                                        <br>
                                    @endforeach
                                    <x-common.validation-error property="adjustment.*" />
                                </x-forms.field>
                            </div>
                            <div class="column is-3">
                                <label
                                    for="siv[]"
                                    class="label text-green"
                                > SIV <sup class="has-text-danger"></sup> </label>
                                <x-forms.field>
                                    @foreach ($warehouses as $warehouse)
                                        <label class="checkbox mr-3 has-text-grey has-text-weight-light">
                                            <input
                                                name="siv[]"
                                                value="{{ $warehouse->id }}"
                                                type="checkbox"
                                                {{ in_array($warehouse->id, old('siv', [])) == $warehouse->id ? 'checked' : '' }}
                                            >
                                            {{ $warehouse->name }}
                                        </label>
                                        <br>
                                    @endforeach
                                    <x-common.validation-error property="siv.*" />
                                </x-forms.field>
                            </div>
                        </div>
                    </div>
                </section>
            </x-content.main>
            <x-content.footer>
                <x-common.save-button />
            </x-content.footer>
        </form>
    </x-common.content-wrapper>
@endsection

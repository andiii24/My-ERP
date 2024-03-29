@extends('layouts.app')

@section('title')
    {{ authUser()->is($employee->user) ? 'My' : str($employee->user->name)->append('\'s') }} Profile
@endsection

@section('content')
    <div class="columns is-marginless is-multiline">
        <div class="column is-12">
            <x-content.header bg-color="has-background-white">
                <x-slot:header>
                    <h1 class="title text-green has-text-weight-medium is-size-6">
                        <span class="icon mr-1">
                            <i class="fas fa-circle-user"></i>
                        </span>
                        <span>
                            {{ authUser()->is($employee->user) ? 'My' : str($employee->user->name)->append('\'s') }} Profile
                        </span>
                    </h1>
                </x-slot:header>
            </x-content.header>
            <x-content.footer>
                <div class="columns is-marginless is-multiline">
                    <div class="column is-2">
                        <figure class="image is-128x128 m-auto">
                            <img
                                class="is-rounded"
                                src="{{ asset('img/user.jpg') }}"
                            >
                        </figure>
                    </div>
                    <div class="column is-10 pl-5 p-lr-0">
                        <div class="columns is-marginless is-multiline">
                            <div class="column is-4">
                                <p class="has-text-grey is-size-7 is-uppercase">
                                    <span class="icon">
                                        <i class="fas fa-user"></i>
                                    </span>
                                    <span>
                                        Name
                                    </span>
                                </p>
                                <p class="has-text-weight-bold text-green ml-1">
                                    {{ $employee->user->name }}
                                </p>
                            </div>
                            <div class="column is-4">
                                <p class="has-text-grey is-size-7 is-uppercase">
                                    <span class="icon">
                                        <i class="fas fa-at"></i>
                                    </span>
                                    <span>
                                        Email
                                    </span>
                                </p>
                                <p class="has-text-weight-bold text-green ml-1">
                                    {{ $employee->user->email }}
                                </p>
                            </div>
                            <div class="column is-4">
                                <p class="has-text-grey is-size-7 is-uppercase">
                                    <span class="icon">
                                        <i class="fas fa-th"></i>
                                    </span>
                                    <span>
                                        Gender
                                    </span>
                                </p>
                                <p class="has-text-weight-bold text-green ml-1 is-capitalized">
                                    {{ $employee->gender ?? 'N/A' }}
                                </p>
                            </div>
                            <div class="column is-4">
                                <p class="has-text-grey is-size-7 is-uppercase">
                                    <span class="icon">
                                        <i class="fas fa-lock"></i>
                                    </span>
                                    <span>
                                        System Access
                                    </span>
                                </p>
                                <p class="has-text-weight-bold {{ $employee->enabled ? 'text-green' : 'text-purple' }} ml-1">
                                    {{ $employee->enabled ? 'Enabled' : 'Disabled' }}
                                </p>
                            </div>
                            <div class="column is-4">
                                <p class="has-text-grey is-size-7 is-uppercase">
                                    <span class="icon">
                                        <i class="fas fa-cogs"></i>
                                    </span>
                                    <span>
                                        Role
                                    </span>
                                </p>
                                <p class="has-text-weight-bold text-green ml-1">
                                    {{ $employee->user->roles[0]->name }}
                                </p>
                            </div>
                            <div class="column is-4">
                                <p class="has-text-grey is-size-7 is-uppercase">
                                    <span class="icon">
                                        <i class="fas fa-phone"></i>
                                    </span>
                                    <span>
                                        Phone
                                    </span>
                                </p>
                                <p class="has-text-weight-bold text-green ml-1">
                                    {{ $employee->phone ?? 'N/A' }}
                                </p>
                            </div>
                            <div class="column is-4">
                                <p class="has-text-grey is-size-7 is-uppercase">
                                    <span class="icon">
                                        <i class="fas fa-map-marker-alt"></i>
                                    </span>
                                    <span>
                                        Address
                                    </span>
                                </p>
                                <p class="has-text-weight-bold text-green ml-1">
                                    {{ $employee->address ?? 'N/A' }}
                                </p>
                            </div>
                            <div class="column is-4">
                                <p class="has-text-grey is-size-7 is-uppercase">
                                    <span class="icon">
                                        <i class="fas fa-users-rectangle"></i>
                                    </span>
                                    <span>
                                        Department
                                    </span>
                                </p>
                                <p class="has-text-weight-bold text-green ml-1">
                                    {{ $employee->department->name ?? 'N/A' }}
                                </p>
                            </div>
                            <div class="column is-4">
                                <p class="has-text-grey is-size-7 is-uppercase">
                                    <span class="icon">
                                        <i class="fas fa-user-tie"></i>
                                    </span>
                                    <span>
                                        Job Position
                                    </span>
                                </p>
                                <p class="has-text-weight-bold text-green ml-1">
                                    {{ $employee->position ?? 'N/A' }}
                                </p>
                            </div>
                            <div class="column is-4">
                                <p class="has-text-grey is-size-7 is-uppercase">
                                    <span class="icon">
                                        <i class="fas fa-sort"></i>
                                    </span>
                                    <span>
                                        Job Type
                                    </span>
                                </p>
                                <p class="has-text-weight-bold text-green ml-1">
                                    {{ $employee->job_type ?? 'N/A' }}
                                </p>
                            </div>
                            <div class="column is-4">
                                <p class="has-text-grey is-size-7 is-uppercase">
                                    <span class="icon">
                                        <i class="fas fa-warehouse"></i>
                                    </span>
                                    <span>
                                        Work Place
                                    </span>
                                </p>
                                <p class="has-text-weight-bold text-green ml-1">
                                    {{ $employee->user->warehouse->name ?? 'N/A' }}
                                </p>
                            </div>
                            <div class="column is-4">
                                <p class="has-text-grey is-size-7 is-uppercase">
                                    <span class="icon">
                                        <i class="fas fa-id-card"></i>
                                    </span>
                                    <span>
                                        ID Info
                                    </span>
                                </p>
                                <p class="has-text-weight-bold text-green ml-1">
                                    {{ $employee->id_number ? str($employee->id_type)->append(' (', $employee->id_number, ')') : 'N/A' }}
                                </p>
                            </div>
                            <div class="column is-4">
                                <p class="has-text-grey is-size-7 is-uppercase">
                                    <span class="icon">
                                        <i class="fas fa-university"></i>
                                    </span>
                                    <span>
                                        Bank Account
                                    </span>
                                </p>
                                <p class="has-text-weight-bold text-green ml-1">
                                    {{ $employee->bank_account ? str($employee->bank_name)->append(' (', $employee->bank_account, ')') : 'N/A' }}
                                </p>
                            </div>
                            <div class="column is-4">
                                <p class="has-text-grey is-size-7 is-uppercase">
                                    <span class="icon">
                                        <i class="fas fa-hashtag"></i>
                                    </span>
                                    <span>
                                        TIN Number
                                    </span>
                                </p>
                                <p class="has-text-weight-bold text-green ml-1">
                                    {{ $employee->tin_number ?? 'N/A' }}
                                </p>
                            </div>
                            <div class="column is-4">
                                <p class="has-text-grey is-size-7 is-uppercase">
                                    <span class="icon">
                                        <i class="fas fa-th"></i>
                                    </span>
                                    <span>
                                        Paid Time off Amount
                                    </span>
                                </p>
                                <p class="has-text-weight-bold text-green ml-1">
                                    {{ $employee->paid_time_off_amount ?? 'N/A' }}
                                </p>
                            </div>
                            <div class="column is-4">
                                <p class="has-text-grey is-size-7 is-uppercase">
                                    <span class="icon">
                                        <i class="fas fa-calendar-alt"></i>
                                    </span>
                                    <span>
                                        Hiring Date
                                    </span>
                                </p>
                                <p class="has-text-weight-bold text-green ml-1">
                                    {{ $employee->date_of_hiring?->toFormattedDateString() ?? 'N/A' }}
                                </p>
                            </div>
                            <div class="column is-4">
                                <p class="has-text-grey is-size-7 is-uppercase">
                                    <span class="icon">
                                        <i class="fas fa-calendar-alt"></i>
                                    </span>
                                    <span>
                                        Birth Date
                                    </span>
                                </p>
                                <p class="has-text-weight-bold text-green ml-1 is-capitalized">
                                    {{ $employee->date_of_birth?->toFormattedDateString() ?? 'N/A' }}
                                </p>
                            </div>
                            @if (isFeatureEnabled('Leave Management'))
                                <div class="column is-4">
                                    <p class="has-text-grey is-size-7 is-uppercase">
                                        <span class="icon">
                                            <i class="fas fa-umbrella-beach"></i>
                                        </span>
                                        <span>
                                            Leave Status
                                        </span>
                                    </p>
                                    <p class="has-text-weight-bold {{ $employee->isOnLeave ? 'text-purple' : 'text-green' }} ml-1">
                                        {{ $employee->isOnLeave ? 'On Leave' : 'On Duty' }}
                                    </p>
                                </div>
                                <div class="column is-4">
                                    <p class="has-text-grey is-size-7 is-uppercase">
                                        <span class="icon">
                                            <i class="fas fa-umbrella-beach"></i>
                                        </span>
                                        <span>
                                            Leave {{ userCompany()->paid_time_off_type }} Left
                                        </span>
                                    </p>
                                    <p class="has-text-weight-bold text-green ml-1">
                                        {{ $employee->paid_time_off_amount }} {{ userCompany()->paid_time_off_type }}
                                    </p>
                                </div>
                            @endif
                            @if (isFeatureEnabled('Attendance Management'))
                                <div class="column is-4">
                                    <p class="has-text-grey is-size-7 is-uppercase">
                                        <span class="icon">
                                            <i class="fas fa-clipboard-user"></i>
                                        </span>
                                        <span>
                                            Absent Days <sup>(LAST ATTENDANCE LIST)</sup>
                                        </span>
                                    </p>
                                    <p class="has-text-weight-bold text-green ml-1">
                                        {{ !is_null($employee->absentDays) ? str($employee->absentDays)->append(' Days') : 'N/A' }}
                                    </p>
                                </div>
                            @endif
                            <div class="column is-4">
                                <p class="has-text-grey is-size-7 is-uppercase">
                                    <span class="icon">
                                        <i class="fas fa-phone"></i>
                                    </span>
                                    <span>
                                        Emergency Phone
                                    </span>
                                </p>
                                <p class="has-text-weight-bold text-green ml-1">
                                    {{ $employee->emergency_phone ?? 'N/A' }}
                                </p>
                            </div>
                            <div class="column is-4">
                                <p class="has-text-grey is-size-7 is-uppercase">
                                    <span class="icon">
                                        <i class="fas fa-user"></i>
                                    </span>
                                    <span>
                                        Emergency Name
                                    </span>
                                </p>
                                <p class="has-text-weight-bold text-green ml-1">
                                    {{ $employee->emergency_name ?? 'N/A' }}
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </x-content.footer>
        </div>
    </div>
    <section class="mt-3">
        <div class="columns is-marginless is-multiline">
            @if (isFeatureEnabled('Warning Management'))
                <div class="column is-6 p-lr-0">
                    <x-content.header bg-color="has-background-white">
                        <x-slot:header>
                            <h1 class="title text-green has-text-weight-medium is-size-6">
                                <span class="icon mr-1">
                                    <i class="fas fa-circle-exclamation"></i>
                                </span>
                                <span>Warnings</span>
                            </h1>
                        </x-slot:header>
                    </x-content.header>
                    <x-content.footer>
                        <x-common.client-datatable
                            has-filter="false"
                            has-length-change="false"
                            paging-type="simple"
                            length-menu=[5]
                        >
                            <x-slot name="headings">
                                <th><abbr> # </abbr></th>
                                <th><abbr> Warning No </abbr></th>
                                <th><abbr> Type </abbr></th>
                            </x-slot>
                            <x-slot name="body">
                                @foreach ($employee->warnings as $warning)
                                    <tr>
                                        <td> {{ $loop->iteration }} </td>
                                        <td>
                                            <a
                                                href="{{ route('warnings.show', $warning->id) }}"
                                                class="is-underlined has-text-weight-medium"
                                            >
                                                {{ $warning->code }}
                                            </a>
                                        </td>
                                        <td> {{ $warning->type }} </td>
                                    </tr>
                                @endforeach
                            </x-slot>
                        </x-common.client-datatable>
                    </x-content.footer>
                </div>
            @endif
            @if (isFeatureEnabled('Expense Claim'))
                <div class="column is-6 p-lr-0">
                    <x-content.header bg-color="has-background-white">
                        <x-slot:header>
                            <h1 class="title text-green has-text-weight-medium is-size-6">
                                <span class="icon mr-1">
                                    <i class="fas fa-file-invoice-dollar"></i>
                                </span>
                                <span>Expense Claims</span>
                            </h1>
                        </x-slot:header>
                    </x-content.header>
                    <x-content.footer>
                        <x-common.client-datatable
                            has-filter="false"
                            has-length-change="false"
                            paging-type="simple"
                            length-menu=[5]
                        >
                            <x-slot name="headings">
                                <th><abbr> # </abbr></th>
                                <th><abbr> Claim No </abbr></th>
                                <th><abbr> Status </abbr></th>
                                <th class="has-text-right"><abbr> Price </abbr></th>
                            </x-slot>
                            <x-slot name="body">
                                @foreach ($employee->expenseClaims as $expenseClaim)
                                    <tr>
                                        <td> {{ $loop->iteration }} </td>
                                        <td>
                                            <a
                                                href="{{ route('expense-claims.show', $expenseClaim->id) }}"
                                                class="is-underlined has-text-weight-medium"
                                            >
                                                {{ $expenseClaim->code }}
                                            </a>
                                        </td>
                                        <td> {{ view('components.datatables.expense-claim-status', compact('expenseClaim')) }} </td>
                                        <td class="has-text-right"> {{ money($expenseClaim->totalPrice) }} </td>
                                    </tr>
                                @endforeach
                            </x-slot>
                        </x-common.client-datatable>
                    </x-content.footer>
                </div>
            @endif
            @if (isFeatureEnabled('Compensation Management'))
                <div class="column is-6 p-lr-0">
                    <x-content.header bg-color="has-background-white">
                        <x-slot:header>
                            <h1 class="title text-green has-text-weight-medium is-size-6">
                                <span class="icon mr-1">
                                    <i class="fas fa-circle-dollar-to-slot"></i>
                                </span>
                                <span>Compensations</span>
                            </h1>
                        </x-slot:header>
                    </x-content.header>
                    <x-content.footer>
                        <x-common.client-datatable
                            has-filter="false"
                            has-length-change="false"
                            paging-type="simple"
                            length-menu=[5]
                        >
                            <x-slot name="headings">
                                <th><abbr> # </abbr></th>
                                <th><abbr> Compensation Name </abbr></th>
                                <th class="has-text-right"><abbr> Amount </abbr></th>
                            </x-slot>
                            <x-slot name="body">
                                @foreach ($employee->employeeCompensations as $employeeCompensation)
                                    <tr>
                                        <td> {{ $loop->iteration }} </td>
                                        <td>{{ $employeeCompensation->compensation->name }}</td>
                                        <td class="has-text-right"> {{ money($employeeCompensation->amount) }} </td>
                                    </tr>
                                @endforeach
                            </x-slot>
                        </x-common.client-datatable>
                    </x-content.footer>
                </div>
            @endif
        </div>
    </section>
@endsection

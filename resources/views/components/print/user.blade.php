@props(['createdBy', 'approvedBy'])

@if (!is_null($createdBy) && $createdBy->is($approvedBy))
    <footer>
        <h1 class="is-size-7 is-uppercase has-text-black-lighter has-text-weight-bold">
            Prepared & Approved By
        </h1>
        <h1 class="has-text-weight-bold has-text-grey-dark is-capitalized">
            {{ $createdBy->name }},
            {{ $createdBy->employee->position }}
        </h1>
    </footer>
@else
    <footer class="is-clearfix">
        @if (!is_null($createdBy))
            <aside
                class="is-pulled-left"
                style="width: 50%"
            >
                <h1 class="is-size-7 is-uppercase has-text-black-lighter has-text-weight-bold">
                    Prepared By
                </h1>
                <h1 class="has-text-weight-bold has-text-grey-dark is-capitalized">
                    {{ $createdBy->name }},
                    {{ $createdBy->employee->position }}

                </h1>
            </aside>
        @endif
        @if (!is_null($approvedBy))
            <aside
                class="is-pulled-right"
                style="width: 50%"
            >
                <h1 class="is-size-7 is-uppercase has-text-black-lighter has-text-weight-bold">
                    Approved By
                </h1>
                <h1 class="has-text-weight-bold has-text-grey-dark is-capitalized">
                    {{ $approvedBy->name }},
                    {{ $approvedBy->employee->position }}

                </h1>
            </aside>
        @endif
    </footer>
@endif

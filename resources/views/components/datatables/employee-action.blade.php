<x-common.button
    tag="a"
    href="{{ route('employees.show', $employee->id) }}"
    mode="button"
    data-title="Profile"
    icon="fas fa-circle-user"
    class="text-green has-text-weight-medium is-not-underlined is-small px-2 py-0 is-transparent-color"
/>

<x-common.button
    tag="a"
    href="{{ route('permissions.edit', $employee->id) }}"
    mode="button"
    data-title="Permissions"
    icon="fas fa-lock"
    class="text-green has-text-weight-medium is-not-underlined is-small px-2 py-0 is-transparent-color"
/>

<x-common.action-buttons
    :buttons="['edit', 'delete']"
    model="employees"
    :id="$employee->id"
/>

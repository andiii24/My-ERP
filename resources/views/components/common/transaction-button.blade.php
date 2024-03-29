@props(['route', 'intention', 'action', 'icon', 'label' => null])

<form
    x-data="swal('{{ $action }}', '{{ $intention }}')"
    class="is-inline"
    action="{{ $route }}"
    method="post"
    novalidate
    @submit.prevent="open"
>
    @csrf
    <x-common.button
        tag="button"
        mode="button"
        :label="$label"
        icon="{{ $icon }}"
        x-ref="submitButton"
        {{ $attributes->merge(['class' => '']) }}
    />
</form>

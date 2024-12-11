@php
    $uniqueId = uniqid();
    $dynamicIdInput = $idInput ?? 'input-' . $uniqueId;
    $dynamicIdOptions = $idOptions ?? 'options-' . $uniqueId;
    $dynamicIdSpan = $uniqueId;
    $value = $defaultValue ?? '';
    $isDisabled = $disabled ?? false ;
    $DISABLED = $isDisabled ? 'disabled' : '';
    $spanOwnClassName = $spanClassName ?? ''; 
    $selectFunction = $onSelectFunction ?? 'selectOption'; 
    $onClick = $onClickFunction ?? 'toggleOptionsSelectNoCleanable'; 
    $name = $inputName ?? '';
@endphp

<div class="input-select">
    <div class="onlySelectInput-container">
        <input 
            class="{{ $inputClassName }}"
            type="text" 
            id="{{ $dynamicIdInput }}" 
            oninput="filterOptions('{{ $dynamicIdInput }}', '{{ $dynamicIdOptions }}')" 
            onclick="{{ $onClick }}('{{ $dynamicIdOptions }}', '{{ $dynamicIdSpan }}')" 
            value="{{ $value }}"
            autocomplete="off"
            readonly
            name="{{ $name }}"
            {{ $DISABLED }}
        >
        <span class="material-symbols-outlined noCleanable {{ $spanOwnClassName }}" id="{{ $dynamicIdSpan }}">keyboard_arrow_down</span>
    </div>  
    <ul class="select-items" id="{{ $dynamicIdOptions }}">
        @foreach ($options as $option) {{--$options es enviada desde la vista--}}
            <li onclick="{{ $selectFunction }}('{{ $option }}', '{{ $dynamicIdInput }}', '{{ $dynamicIdOptions }}')">
                {{ $option }} 
            </li>
        @endforeach
    </ul>
</div>

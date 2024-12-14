@php
    $uniqueId = uniqid();
    $dynamicIdSelect = $idSelect ?? 'select-' . $uniqueId;
    $dynamicIdInput = $idInput ?? 'input-' . $uniqueId;
    $dynamicIdOptions = $idOptions ?? 'options-' . $uniqueId;
    $dynamicIdSpan = $uniqueId;
    $value = $defaultValue ?? '';
    $isDisabled = $disabled ?? false ;
    $DISABLED = $isDisabled ? 'disabled' : '';
    $spanOwnClassName = $spanClassName ?? ''; 
    $containerOwnClass = $containerClassName ?? '';
    $selectFunction = $onSelectFunction ?? 'selectOption'; 
    $onClick = $onClickFunction ?? 'toggleOptionsSelectNoCleanable';
    $extraArgJson = $isExtraArgJson ?? false;
    
    if ($extraArgJson) {
        $argumClickFunction = $extraArgOnClickFunction ? ", " . json_encode($extraArgOnClickFunction) : ''; 
    } else {
        $argumClickFunction = $extraArgOnClickFunction ? ", " . $extraArgOnClickFunction : ''; 
    }

    $name = $inputName ?? '';
@endphp

<div class="input-select" id='{{ $dynamicIdSelect }}'>
    <div class="onlySelectInput-container {{ $containerOwnClass }}">
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
        <span class="material-symbols-outlined noCleanable {{ $spanOwnClassName }}" id="{{ $dynamicIdSpan }}"
                onclick="{{ $isDisabled ? '' : "toggleOptionsSelectNoCleanable('{$dynamicIdOptions}', '{$dynamicIdSpan}')" }}">
                keyboard_arrow_down
        </span>
    </div>  
    <ul class="select-items noCleanable" id="{{ $dynamicIdOptions }}">
        @foreach ($options as $option) {{--$options es enviada desde la vista--}}
            <li onclick="{{ $selectFunction }}('{{ $option }}', '{{ $dynamicIdInput }}', '{{ $dynamicIdOptions }}' {{ $argumClickFunction }})">
                {{ $option }} 
            </li>
        @endforeach
    </ul>
</div>

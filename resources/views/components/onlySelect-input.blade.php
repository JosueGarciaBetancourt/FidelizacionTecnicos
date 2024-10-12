@php
    $uniqueId = uniqid();
    $dynamicIdInput = $idInput ?? 'input-' . $uniqueId;
    $dynamicIdOptions = $idOptions ?? 'options-' . $uniqueId;
    $isDisabled = $disabled ?? false;
    $spanOwnClassName = $spanClassName ?? ''; // Asignar un valor por defecto si $spanClassName no está definido
    $focusBorder = $focusBorder ?? '';
    $selectFunction = $onSelectFunction ?? 'selectOption'; // Asignar la función predeterminada
    $onClick = $onClickFunction ?? 'toggleOptions'; // Asignar la función predeterminada
    $spanClickFunction = isset($onSpanClickFunction) ? ", " . $onSpanClickFunction . "()" : '';
@endphp

<div class="input-select">
    <div class="onlySelectInput-container {{ $focusBorder }}">
        <input 
            class="{{ $inputClassName }}"
            type="text" 
            id="{{ $dynamicIdInput }}" 
            placeholder="{{ $placeholder ?? 'Seleccionar opción' }}" 
            oninput="filterOptions('{{ $dynamicIdInput }}', '{{ $dynamicIdOptions }}')" 
            onclick="{{ $onClick }}('{{ $dynamicIdInput }}', '{{ $dynamicIdOptions }}')" 
            autocomplete="off"
            readonly
            name="{{ $name ?? '' }}"
            {{ $isDisabled ? 'disabled' : '' }}
        >
        <span class="material-symbols-outlined {{ $spanOwnClassName }}" 
              onclick="{{ $isDisabled ? '' : "clearInput('{$dynamicIdInput}')" }} {{$spanClickFunction}}"> cancel </span>
    </div>  
    <ul class="select-items" id="{{ $dynamicIdOptions }}">
        @foreach ($options as $option)
            <li 
                onclick="{{ $selectFunction }}('{{ $option }}', '{{ $dynamicIdInput }}', '{{ $dynamicIdOptions }}')"
            >
                {{ $option }}
            </li>
        @endforeach
    </ul>
</div>

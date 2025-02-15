@props([
    'idMultiSelectDropdownContainer' => '',
    'idInput' => '',
    'idSelectedOptionsDiv' => '',
    'options' => [], 
    'clickOptionFunction' => '',
    'empyDataMessage' => '',
    'disabled' => false,
])

<div class="multiSelectDropdownContainer" id="{{ $idMultiSelectDropdownContainer }}">
    <div class="custom-select">
        <div class="multiSelectDropdown select-box @if($disabled) disabled @endif">
            <input type="hidden" class="tags_input" id="{{ $idInput }}" name="tags" @if($disabled) disabled @endif />
            <div class="selected-options" id="{{ $idSelectedOptionsDiv }}">
                <span class="selectedOptions__placeholder @if($disabled) disabled @endif">Seleccionar oficio</span>
            </div>
            <div class="arrow"><i class="fa fa-angle-down"></i></div>
        </div>
        <div class="optionsMultiSelectDropdown @if($disabled) disabled @endif">
            @if (!$disabled)
                <div class="option-search-tags">
                    <input type="text" class="search-tags" placeholder="Buscar"/>
                    <button type="button" class="clear"><i class="fa fa-close"></i></button>	
                </div>
            @endif

            @if (count($options) > 0)
                <div class="option all-tags @if($disabled) disabled @endif" data-value="All">
                    Seleccionar todo
                </div>
                @foreach ($options as $option) 
                    <div class="option @if($disabled) disabled @endif" data-value="{{ $option }}">
                        {{ $option }}
                    </div>
                @endforeach
                <div class="no-result-message">No se encontraron resultados</div>
            @else
                <div class="empty-data-message">{{ $empyDataMessage }}</div>
            @endif
        </div>
    </div>
</div>

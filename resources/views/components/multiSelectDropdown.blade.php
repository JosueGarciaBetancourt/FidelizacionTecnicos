@props(['options' => [], 
		'clickOptionFunction' => '',
		'empyDataMessage' => '',
	])

<div class="multiSelectDropdownContainer" id="idMultiSelectDropdownContainer">
	<div class="custom-select">
		<div class="multiSelectDropdown select-box">
			<input type="hidden" class="tags_input" name="tags"/>
			<div class="selected-options"><span class="selectedOptions__placeholder">Seleccionar oficio</span></div>
			<div class="arrow"><i class="fa fa-angle-down"></i></div>
		</div>
		<div class="optionsMultiSelectDropdown">
			<div class="option-search-tags">
				<input type="text" class="search-tags" placeholder="Buscar"/>
				<button type="button" class="clear"><i class="fa fa-close"></i></button>	
			</div>
			@if (count($options) > 0)
				<div class="option all-tags" data-value="All">Seleccionar todo</div>
				@foreach ($options as $option) 
					<div class="option" data-value="{{ $option }}" onclick="{{ $clickOptionFunction }}('{{ $option }}')">
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
<!-- Definición del componente en Blade -->
@props(['id' => '', 'onclick' => '', 'slot' => ''])

<div class="btnDeleteItem-container" id="{{ $id }}">
	<button class="btnDeleteItem" onclick="{{ $onclick }}">
		{{ $slot }}
		<span class="material-symbols-outlined">delete</span>
	</button>
</div>
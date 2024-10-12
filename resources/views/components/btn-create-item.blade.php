<!-- Definición del componente en Blade -->
@props(['id' => '', 'onclick' => '', 'slot' => ''])

<div class="btnCreateItem-container" id="{{ $id }}">
    <button class="btnCreateItem" onclick="{{ $onclick }}">
        {{ $slot }}
        <span class="material-symbols-outlined">add_circle</span>
    </button>
</div>

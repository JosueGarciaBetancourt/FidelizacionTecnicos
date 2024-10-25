<!-- Definición del componente en Blade -->
@props(['id' => '', 'onclick' => '', 'type' => '', 'slot' => ''])

<div class="btnCreateItem-container addRowTable" id="{{ $id }}">
    <button type="{{ $type }}" class="btnCreateItem addRowTable" onclick="{{ $onclick }}">
        {{ $slot }}
        <span class="material-symbols-outlined">playlist_add</span>
    </button>
</div>

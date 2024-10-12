<!-- Definición del componente en Blade -->
@props(['id' => '', 'onclick' => '', 'slot' => ''])

<div class="btnCreateItem-container" id="{{ $id }}">
    <button class="btnCreateItem" onclick="{{ $onclick }}">
        {{ $slot }}
        <span class="material-symbols-outlined">playlist_add</span>
    </button>
</div>

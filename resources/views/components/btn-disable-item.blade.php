<!-- Definición del componente en Blade -->
@props(['id' => '', 'onclick' => '', 'type' => 'submit', 'slot' => ''])

<div class="btnDisableItem-container" id="{{ $id }}">
    <button type="{{ $type }}" class="btnDisableItem" onclick="{{ $onclick }}">
        {{ $slot }}
        <span class="material-symbols-outlined noUserSelect">hide_source</span>
    </button>
</div>

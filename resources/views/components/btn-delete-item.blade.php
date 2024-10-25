<!-- Definición del componente en Blade -->
@props(['id' => '', 'onclick' => '', 'type' => 'submit', 'slot' => ''])

<div class="btnDeleteItem-container" id="{{ $id }}">
    <button type="{{ $type }}" class="btnDeleteItem" onclick="{{ $onclick }}">
        {{ $slot }}
        <span class="material-symbols-outlined">delete</span>
    </button>
</div>

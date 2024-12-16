    @extends('layouts.layoutDashboard')

    @section('title', 'Oficios')

    @push('styles')
        <link rel="stylesheet" href="{{ asset('css/oficiosStyle.css') }}">
        <link rel="stylesheet" href="{{asset('css/modalRegistrarNuevoOficio.css')}}">
        <link rel="stylesheet" href="{{asset('css/modalEditarOficio.css')}}">
        <link rel="stylesheet" href="{{asset('css/modalEliminarOficio.css')}}">
        <link rel="stylesheet" href="{{asset('css/modalRestaurarOficio.css')}}">
    @endpush

    @section('main-content')
        <div class="oficiosContainer">
            <div class="firstRow">
                <x-btn-create-item onclick="openModal('modalRegistrarNuevoOficio')"> Registrar nuevo oficio </x-btn-create-item>
                @include('modals.oficios.modalRegistrarNuevoOficio')

                <x-btn-edit-item onclick="openModal('modalEditarOficio')"> Editar </x-btn-edit-item>
                @include('modals.oficios.modalEditarOficio')

                <x-btn-delete-item onclick="openModal('modalEliminarOficio')"> Eliminar </x-btn-delete-item>
                @include('modals.oficios.modalEliminarOficio')

                <x-btn-recover-item onclick="openModal('modalRestaurarOficio')"> Restaurar </x-btn-delete-item>
                @include('modals.oficios.modalRestaurarOficio')
            </div>
            
            <x-modalSuccessAction 
                :idSuccesModal="'successModalOficioGuardado'"
                :message="'Oficio guardado correctamente'"
            />

            <x-modalSuccessAction 
                :idSuccesModal="'successModalOficioActualizado'"
                :message="'Oficio actualizado correctamente'"
            />

            <x-modalSuccessAction 
                :idSuccesModal="'successModalOficioEliminado'"
                :message="'Oficio eliminado correctamente'"
            />

            <x-modalSuccessAction 
                :idSuccesModal="'successModalOficioRestaurado'"
                :message="'Oficio restaurado correctamente'"
            />

            <!--Tabla de ventas intermediadas-->
            <div class="secondRow">
                <table id="tblOficios">
                    <thead>
                        <tr>
                            <th class="celda-centered">#</th>
                            <th>Código</th>
                            <th>Nombre de oficio</th>
                            <th>Descripción</th>
                            <th>Fecha y Hora de creación</th>
                            <th>Fecha y Hora de actualización</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            $contador = 1;
                        @endphp
                        @foreach ($oficios as $oficio)
                        <tr>
                            <td class="celda-centered">{{ $contador++ }}</td> 
                            <td>{{ $oficio->codigoOficio }}</td>
                            <td>{{ $oficio->nombre_Oficio }}</td>
                            <td>{{ $oficio->descripcion_Oficio }}</td>
                            <td>{{ $oficio->created_at }}</td>
                            <td>{{ $oficio->updated_at }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    @endsection

    @push('scripts')
        <script src="{{ asset('js/modalRegistrarNuevoOficio.js') }}"></script>
        <script src="{{ asset('js/modalEditarOficio.js') }}"></script>
        <script src="{{ asset('js/modalEliminarOficio.js') }}"></script>
        <script src="{{ asset('js/modalRestaurarOficio.js') }}"></script>
        <script>
            document.addEventListener("DOMContentLoaded", function() {
                @if(session('successOficioStore'))
                    openModal('successModalOficioGuardado');
                @endif
                @if(session('successOficioUpdate'))
                    openModal('successModalOficioActualizado');
                @endif
                @if(session('successOficioDelete'))
                    openModal('successModalOficioEliminado');
                @endif
                @if(session('successOficioRestaurado'))
                    openModal('successModalOficioRestaurado');
                @endif
            });
        </script>
    @endpush
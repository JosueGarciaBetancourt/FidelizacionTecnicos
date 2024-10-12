@extends('layouts.layoutDashboard')

@section('title', 'NADA')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/ventasIntermediadasStyles.css') }}">
@endpush

@section('main-content')
    <div class="ventasIntermediadasContainer">
        <div class="firstRow">
            <div class="searcherContainer">
                <span class="material-symbols-outlined">search</span>
                <div class="searcher">
                    <input type="text" placeholder="Buscar DNI/Nombre de técnico o número de comprobante">
                </div>
            </div>

            <div class="agregarNuevaVentaContainer">
                <button class="btnAgregarNuevaVenta">
                    Agregar nueva venta
                    <span class="material-symbols-outlined">note_add</span>
                </button>
            </div>
        </div>
       

        <div class="date">
            <input type="date">
        </div>

        <div class="insights">

            <!-- start seling -->
            <div class="sales">
                <span class="material-symbols-outlined">trending_up</span>
                <div class="middle">

                    <div class="left">
                        <h3>Total Sales</h3>
                        <h1>$25,024</h1>
                    </div>
                    <div class="progress">
                        <svg>
                            <circle r="30" cy="40" cx="40"></circle>
                        </svg>
                        <div class="number">
                            <p>80%</p>
                        </div>
                    </div>

                </div>
                <small>Last 24 Hours</small>
            </div>
            <!-- end seling -->
            <!-- start expenses -->
            <div class="expenses">
                <span class="material-symbols-outlined">local_mall</span>
                <div class="middle">

                    <div class="left">
                        <h3>Total Sales</h3>
                        <h1>$25,024</h1>
                    </div>
                    <div class="progress">
                        <svg>
                            <circle r="30" cy="40" cx="40"></circle>
                        </svg>
                        <div class="number">
                            <p>80%</p>
                        </div>
                    </div>

                </div>
                <small>Last 24 Hours</small>
            </div>
            <!-- end seling -->
            <!-- start seling -->
            <div class="income">
                <span class="material-symbols-outlined">stacked_line_chart</span>
                <div class="middle">

                    <div class="left">
                        <h3>Total Sales</h3>
                        <h1>$25,024</h1>
                    </div>
                    <div class="progress">
                        <svg>
                            <circle r="30" cy="40" cx="40"></circle>
                        </svg>
                        <div class="number">
                            <p>80%</p>
                        </div>
                    </div>

                </div>
                <small>Last 24 Hours</small>
            </div>
            <!-- end seling -->

        </div>
        <!-- end insights -->
        <div class="recent_order">
            <h2>Lista de ventas intermediadas</h2>
            <table>
                <thead>
                    <tr>
                        <th>Product Name</th>
                        <th>Product Number</th>
                        <th>Payments</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>Mini USB</td>
                        <td>4563</td>
                        <td>Due</td>
                        <td class="warning">Pending</td>
                        <td class="primary">Details</td>
                    </tr>
                    <tr>
                        <td>Mini USB</td>
                        <td>4563</td>
                        <td>Due</td>
                        <td class="warning">Pending</td>
                        <td class="primary">Details</td>
                    </tr>
                    <tr>
                        <td>Mini USB</td>
                        <td>4563</td>
                        <td>Due</td>
                        <td class="warning">Pending</td>
                        <td class="primary">Details</td>
                    </tr>
                    <tr>
                        <td>Mini USB</td>
                        <td>4563</td>
                        <td>Due</td>
                        <td class="warning">Pending</td>
                        <td class="primary">Details</td>
                    </tr>
                </tbody>
            </table>
            <a href="#">Show All</a>
        </div>
    </div>
@endsection

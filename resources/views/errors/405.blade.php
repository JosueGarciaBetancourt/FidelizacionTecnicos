<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Error 405 - Método No Permitido</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f0f2f5; /* Color de fondo similar al de la aplicación */
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
            text-align: center;
        }

        .error-container {
            background-color: white;
            border-radius: 12px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
            padding: 40px;
            max-width: 500px;
            width: 90%;
        }

        .error-icon {
            font-size: 80px;
            color: #6c2bd9; /* Color morado de los botones de registro y edición */
            margin-bottom: 20px;
        }

        .error-title {
            color: #333;
            font-size: 2.5em;
            margin-bottom: 15px;
        }

        .error-message {
            color: #666;
            font-size: 1.1em;
            margin-bottom: 25px;
        }

        .error-actions {
            display: flex;
            justify-content: center;
            gap: 15px;
        }

        .btn {
            text-decoration: none;
            padding: 10px 20px;
            border-radius: 5px;
            transition: all 0.3s ease;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            font-weight: bold;
        }

        .btn-primary {
            background-color: #2196f3; /* Color azul del menú lateral */
            color: white;
        }

        .btn-primary:hover {
            background-color: #1976d2;
        }

       /*  .btn-secondary {
            background-color: #2ecc71;
            color: white;
        }

        .btn-secondary:hover {
            background-color: #27ae60;
        } */

        @media (max-width: 480px) {
            .error-actions {
                flex-direction: column;
            }

            .btn {
                width: 100%;
                justify-content: center;
            }
        }
    </style>
</head>
<body>
    <div class="error-container">
        <div class="error-icon">⚠️</div>
        <h1 class="error-title">Error 405</h1>
        <p class="error-message">La acción que intentas realizar no está autorizada.</p>
        <div class="error-actions">
            <a href="{{route('ventasIntermediadas.create')}}" class="btn btn-primary">
                🏠 Ir al Inicio
            </a>
            {{-- <a href="javascript:location.reload()" class="btn btn-secondary">
                🔄 Recargar Página
            </a> --}}
        </div>
    </div>
</body>
</html>
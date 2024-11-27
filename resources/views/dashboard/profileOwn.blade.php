<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Perfil de Usuario</title>
    <style>
        /* Reset básico */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: system-ui, -apple-system, sans-serif;
            background-color: #f3f4f6;
            color: #111827;
            line-height: 1.5;
        }

        /* Layout principal */
        .container {
            max-width: 1280px;
            margin: 0 auto;
            padding: 1.5rem;
        }

        .header {
            background-color: white;
            padding: 1rem 0;
            border-bottom: 1px solid #e5e7eb;
            margin-bottom: 2rem;
        }

        .header h1 {
            font-size: 1.5rem;
            font-weight: 600;
            color: #1f2937;
            max-width: 1280px;
            margin: 0 auto;
            padding: 0 1.5rem;
        }

        /* Secciones de contenido */
        .section-container {
            background-color: white;
            border-radius: 0.5rem;
            box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.1);
            padding: 2rem;
            margin-bottom: 1.5rem;
            max-width: 36rem;
        }

        .section-title {
            font-size: 1.125rem;
            font-weight: 500;
            color: #111827;
            margin-bottom: 0.5rem;
        }

        .section-description {
            font-size: 0.875rem;
            color: #6b7280;
            margin-bottom: 1.5rem;
        }

        /* Formularios */
        .form-group {
            margin-bottom: 1.5rem;
        }

        .form-label {
            display: block;
            font-size: 0.875rem;
            font-weight: 500;
            color: #374151;
            margin-bottom: 0.5rem;
        }

        .form-input {
            width: 100%;
            padding: 0.5rem 0.75rem;
            border: 1px solid #d1d5db;
            border-radius: 0.375rem;
            font-size: 0.875rem;
            margin-top: 0.25rem;
        }

        .form-input:focus {
            outline: none;
            ring: 2px solid #6366f1;
            border-color: #6366f1;
        }

        /* Botones */
        .btn {
            padding: 0.5rem 1rem;
            border-radius: 0.375rem;
            font-size: 0.875rem;
            font-weight: 500;
            cursor: pointer;
            transition: background-color 0.2s;
        }

        .btn-primary {
            background-color: #4f46e5;
            color: white;
            border: none;
        }

        .btn-primary:hover {
            background-color: #4338ca;
        }

        .btn-danger {
            background-color: #dc2626;
            color: white;
            border: none;
        }

        .btn-danger:hover {
            background-color: #b91c1c;
        }

        .btn-secondary {
            background-color: white;
            color: #374151;
            border: 1px solid #d1d5db;
        }

        .btn-secondary:hover {
            background-color: #f3f4f6;
        }

        /* Utilidades */
        .flex {
            display: flex;
        }

        .items-center {
            align-items: center;
        }

        .gap-4 {
            gap: 1rem;
        }

        .mt-2 {
            margin-top: 0.5rem;
        }

        /* Modal */
        .modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
            align-items: center;
            justify-content: center;
        }

        .modal-content {
            background-color: white;
            padding: 1.5rem;
            border-radius: 0.5rem;
            max-width: 28rem;
            width: 90%;
        }

        /* Mensajes de estado */
        .status-message {
            font-size: 0.875rem;
            color: #059669;
        }

        /* Dark mode */
        @media (prefers-color-scheme: dark) {
            body {
                background-color: #111827;
                color: #f9fafb;
            }

            .header {
                background-color: #1f2937;
                border-color: #374151;
            }

            .section-container {
                background-color: #1f2937;
            }

            .section-title {
                color: #f9fafb;
            }

            .section-description {
                color: #9ca3af;
            }

            .form-label {
                color: #e5e7eb;
            }

            .form-input {
                background-color: #374151;
                border-color: #4b5563;
                color: #f9fafb;
            }

            .btn-secondary {
                background-color: #374151;
                color: #e5e7eb;
                border-color: #4b5563;
            }

            .btn-secondary:hover {
                background-color: #4b5563;
            }

            .modal-content {
                background-color: #1f2937;
            }
        }
    </style>
</head>
<body>
    <header class="header">
        <h1>Perfil</h1>
    </header>

    <div class="container">
        <!-- Sección de Información de Perfil -->
        <div class="section-container">
            <h2 class="section-title">Información de perfil</h2>
            <p class="section-description">
                Actualiza la información de perfil y dirección de correo electrónico de tu cuenta
            </p>

            <form action="/profile/update" method="POST">
                <div class="form-group">
                    <label class="form-label" for="name">Nombre</label>
                    <input class="form-input" type="text" id="name" name="name" required>
                </div>

                <div class="form-group">
                    <label class="form-label" for="email">Correo electrónico</label>
                    <input class="form-input" type="email" id="email" name="email" required>
                </div>

                <div class="flex items-center gap-4">
                    <button type="submit" class="btn btn-primary">Guardar</button>
                </div>
            </form>
        </div>

        <!-- Sección de Actualización de Contraseña -->
        <div class="section-container">
            <h2 class="section-title">Actualizar contraseña</h2>
            <p class="section-description">
                Asegúrate de que tu cuenta utilice una contraseña larga y aleatoria para mantenerse segura
            </p>

            <form action="/password/update" method="POST">
                <div class="form-group">
                    <label class="form-label" for="current_password">Contraseña actual</label>
                    <input class="form-input" type="password" id="current_password" name="current_password" required>
                </div>

                <div class="form-group">
                    <label class="form-label" for="new_password">Contraseña nueva</label>
                    <input class="form-input" type="password" id="new_password" name="new_password" required>
                </div>

                <div class="form-group">
                    <label class="form-label" for="password_confirmation">Confirmar contraseña</label>
                    <input class="form-input" type="password" id="password_confirmation" name="password_confirmation" required>
                </div>

                <div class="flex items-center gap-4">
                    <button type="submit" class="btn btn-primary">Guardar</button>
                </div>
            </form>
        </div>

        <!-- Sección de Eliminación de Cuenta -->
        <div class="section-container">
            <h2 class="section-title">Eliminar cuenta</h2>
            <p class="section-description">
                Una vez que se elimine su cuenta, todos sus recursos y datos se eliminarán permanentemente. 
                Antes de eliminar su cuenta, descargue cualquier dato o información que desee conservar.
            </p>

            <button class="btn btn-danger" onclick="showDeleteModal()">Eliminar cuenta</button>
        </div>
    </div>

    <!-- Modal de Confirmación de Eliminación -->
    <div id="deleteModal" class="modal">
        <div class="modal-content">
            <h2 class="section-title">¿Estás seguro de que quieres eliminar tu cuenta?</h2>
            <p class="section-description">
                Una vez que se elimine su cuenta, todos sus recursos y datos se eliminarán permanentemente. 
                Ingrese su contraseña para confirmar que desea eliminar permanentemente su cuenta.
            </p>

            <form action="/profile/delete" method="POST">
                <div class="form-group">
                    <label class="form-label" for="delete_password">Contraseña</label>
                    <input class="form-input" type="password" id="delete_password" name="password" required>
                </div>

                <div class="flex items-center gap-4">
                    <button type="button" class="btn btn-secondary" onclick="hideDeleteModal()">Cancelar</button>
                    <button type="submit" class="btn btn-danger">Eliminar cuenta</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function showDeleteModal() {
            document.getElementById('deleteModal').style.display = 'flex';
        }

        function hideDeleteModal() {
            document.getElementById('deleteModal').style.display = 'none';
        }
    </script>
</body>
</html>
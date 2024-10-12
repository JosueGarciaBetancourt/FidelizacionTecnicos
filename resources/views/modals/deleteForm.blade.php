<section class="space-y-6">
    <header>
        <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
            Eliminar cuenta
        </h2>
        <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
            Una vez que se elimine su cuenta, todos sus recursos y datos se eliminarán permanentemente. Antes de eliminar su cuenta, descargue cualquier dato o información que desee conservar.
        </p>
    </header>

    <button onclick="openModal('confirm-user-deletion')" class="bg-red-500 hover:bg-red-600 text-white font-bold py-2 px-4 rounded">
        Eliminar cuenta
    </button>

    <div id="confirm-user-deletion-modal" class="modal">
        <div class="modal-content">
            <form method="post" action="{{ route('profile.destroy') }}" class="p-6">
                <!-- Aquí va el CSRF y el método DELETE -->
                <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                    ¿Estás seguro de que quieres eliminar tu cuenta?
                </h2>
                <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                    Una vez que se elimine su cuenta, todos sus recursos y datos se eliminarán permanentemente. Ingrese su contraseña para confirmar que desea eliminar permanentemente su cuenta.
                </p>

                <div class="mt-6">
                    <label for="password" class="sr-only">Password</label>
                    <input
                        id="password"
                        name="password"
                        type="password"
                        class="mt-1 block w-3-4 border-gray-300 rounded-md shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                        placeholder="Contraseña"
                    />
                    <!-- Aquí va el error del input password -->
                </div>

                <div class="mt-6 flex justify-end">
                    <button type="button" onclick="closeModal('confirm-user-deletion')" class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-4 rounded">
                        Cancelar
                    </button>
                    <button type="submit" class="bg-red-500 hover:bg-red-600 text-white font-bold py-2 px-4 rounded ms-3">
                        Eliminar cuenta
                    </button>
                </div>
            </form>
        </div>
    </div>
</section>

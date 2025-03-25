<div class="celda-btnAcciones" onclick="">
	@if ($idEstadoSolicitudCanje === 1 && Auth::check() && Auth::user()->idPerfilUsuario != 3)
		<button class="btnAprobar" onclick="aprobarSolicitudCanje('{{ $idSolicitudCanje }}')">Aprobar</button>
		<button class="btnRechazar" onclick="rechazarSolicitudCanje('{{ $idSolicitudCanje }}')">Rechazar</button>
	@endif
</div>

<style> 
	/* Botón Aprobar */
	.celda-btnAcciones .btnAprobar {
		cursor: pointer;
		max-width: 6rem;
		background-color: #4caf50; /* Verde de aprobación */
		border: 2px solid #4caf50; /* Borde verde */
		color: #fff; /* Texto blanco */
		padding: 5px 10px;
		border-radius: 5px;
		font-size: 0.85rem;
		font-weight: 600;
		display: inline-flex;
		align-items: center;
		justify-content: center;
		margin: 0.5rem 0;
		gap: 8px; /* Espacio entre ícono y texto */
		box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
		transition: background-color 0.3s ease, transform 0.2s ease;
	}

	/* Efecto hover para el botón Aprobar */
	.celda-btnAcciones .btnAprobar:hover {
		background-color: #66bb6a; /* Verde más claro */
		border-color: #66bb6a;
		transform: translateY(-2px); /* Eleva ligeramente el botón */
	}

	/* Botón Rechazar */
	.celda-btnAcciones .btnRechazar {
		cursor: pointer;
		max-width: 6rem;
		background-color: #f44336; /* Rojo de rechazo */
		border: 2px solid #f44336; /* Borde rojo */
		color: #fff; /* Texto blanco */
		padding: 5px 10px;
		border-radius: 5px;
		font-size: 0.85rem;
		font-weight: 600;
		display: inline-flex;
		align-items: center;
		justify-content: center;
		gap: 8px; /* Espacio entre ícono y texto */
		box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
		transition: background-color 0.3s ease, transform 0.2s ease;
	}

	/* Efecto hover para el botón Rechazar */
	.celda-btnAcciones .btnRechazar:hover {
		background-color: #ef5350; /* Rojo más claro */
		border-color: #ef5350;
		transform: translateY(-2px); /* Eleva ligeramente el botón */
	}

	/* Efecto focus para ambos botones */
	.celda-btnAcciones .btnAprobar:focus,
	.celda-btnAcciones .btnRechazar:focus {
		outline: none;
		box-shadow: 0 0 10px rgba(0, 0, 0, 0.2); /* Resalta el botón */
	}
</style>

<script>
	function aprobarSolicitudCanje(idSolicitudCanje){
		// Mostrar el modal y esperar la respuesta del usuario
		openConfirmSolicitudCanjeModal('modalConfirmActionAprobarSolicitudCanje').then((response) => {
			if (response.answer) {
				if (response.comment) {
					aprobarSolicitud(idSolicitudCanje, response.comment); 
					return;
				}
			} 
		});
	}

	function rechazarSolicitudCanje(idSolicitudCanje){
		// Mostrar el modal y esperar la respuesta del usuario
		openConfirmSolicitudCanjeModal('modalConfirmActionRechazarSolicitudCanje').then((response) => {
			if (response.answer) {
				if (response.comment) {
					rechazarSolicitud(idSolicitudCanje, response.comment); 
				}
				return;
			}
		});
	}

	async function aprobarSolicitud(idSolicitudCanje, comentario) {
		const url = `${baseUrlMAIN}/dashboard-solicitudesCanjes/solicitudCanje/aprobar/${idSolicitudCanje}`;

		try {
			const response = await fetch(url, {
				method: 'POST', // Cambiado a POST para enviar datos
				headers: {
					'Content-Type': 'application/json', // Importante para enviar JSON
					'X-CSRF-TOKEN': csrfTokenMAIN, // Agregar el token CSRF aquí
				},
				body: JSON.stringify({ comentario: comentario }), // Enviando el comentario
			});

			if (!response.ok) {
				throw new Error(await response.text());
			}

			const mensaje = await response.json();
			console.log(mensaje);

			sessionStorage.setItem('solicitudAprobada', 'true');

			// Recargar la página después de que la solicitud se haya procesado correctamente
			location.reload();
		} catch (error) {
			console.error('Error al realizar la consulta al backend para aprobar la solicitud:', error.message);
		}
	}

	async function rechazarSolicitud(idSolicitudCanje, comentario) {
		const url = `${baseUrlMAIN}/dashboard-solicitudesCanjes/solicitudCanje/rechazar/${idSolicitudCanje}`;

		try {
			const response = await fetch(url, {
				method: 'POST', // Cambiado a POST para enviar datos
				headers: {
					'Content-Type': 'application/json', // Importante para enviar JSON
					'X-CSRF-TOKEN': csrfTokenMAIN, // Agregar el token CSRF aquí
				},
				body: JSON.stringify({ comentario: comentario }), // Enviando el comentario
			});

			if (!response.ok) {
				throw new Error(await response.text());
			}

			const mensaje = await response.json();

			// Recargar la página después de que la solicitud se haya procesado correctamente
			location.reload();
		} catch (error) {
			console.error('Error al realizar la consulta al backend para rechazar la solicitud:', error.message);
		}
	}
</script>
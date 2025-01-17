<a href="#" class="btnRestorePassword" onclick="openModalRestorePassword(this)">Restaurar contraseña
	<span class="material-symbols-outlined noUserSelect">lock_open</span>
</a>

<style> 
	.btnRestorePassword {
		cursor: pointer;
		max-width: 8rem;
		background-color: #4c6faf;
		border: 2px solid #4c6faf;
		color: #fff;
		padding: 5px 10px;
		border-radius: 5px;
		font-size: 0.8rem;
		font-weight: 600;
		display: inline-flex;
		align-items: center;
		text-align: center;
		gap: 8px;
		box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
		transition: background-color 0.3s ease, transform 0.2s ease;
		text-decoration: none;
	}

	.btnRestorePassword:hover {
		background-color: #8daade; 
		border-color: #8daade; 
		transform: translateY(-2px); 
	}

	.btnRestorePassword:focus {
		outline: none; 
		box-shadow: 0 0 10px rgba(72, 145, 184, 0.5); 
	}

	.btnRestorePassword .material-symbols-outlined {
		font-size: 18px; 
		transition: transform 0.2s ease-in-out;
	}

	.btnRestorePassword:hover .material-symbols-outlined {
		transform: translateX(4px); 
	}
</style>

<script>
	function openModalRestorePassword(btn) {
		const fila = btn.closest('tr');
		const celdaIdTecnico = fila.getElementsByClassName('idTecnico')[0]; 
		const idTecnico = celdaIdTecnico.innerText;

		// Mostrar el modal y esperar la respuesta del usuario
		openConfirmModal('modalConfirmActionRestorePasswordTecnico').then((response) => {
			if (response) {
				restorePassword(idTecnico);
				return;
			}
    	});
	}

	async function restorePassword(idTecnico) {
		const url = `${baseUrlMAIN}/dashboard-tecnicos/restorePassword`;

		try {
			const response = await fetch(url, {
				method: 'POST',
				headers: {
					'Content-Type': 'application/json',
					'X-CSRF-TOKEN': csrfTokenMAIN,
				},
				body: JSON.stringify({ idTecnico: idTecnico }),
			});

			if (!response.ok) {
				const errorData = await response.json();
				throw new Error(errorData.error || 'Error desconocido');
			}

			const data = await response.json();

			console.log(data.wasRestored);
			
			if (data.wasRestored == true) {
				justOpenModal('errorModalPasswordRestored');
				return;
			} 
			
			sessionStorage.setItem('passwordRestoredTecnico', 'true');
			location.reload();
		} catch (error) {
			console.error('Error al restaurar la contraseña:', error.message);
		}
	}
</script>
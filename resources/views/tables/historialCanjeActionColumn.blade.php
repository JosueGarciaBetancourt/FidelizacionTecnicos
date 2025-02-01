<a href="#" class="btnDetalle" onclick="openModalDetalleHistorialCanje(this)">Ver detalle
	<span class="material-symbols-outlined noUserSelect">visibility</span>
</a>

<style> 
	.btnDetalle {
		cursor: pointer;
		max-width: 5.5rem;
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

	.btnDetalle:hover {
		background-color: #8daade; 
		border-color: #8daade; 
		transform: translateY(-2px); 
	}

	.btnDetalle:focus {
		outline: none; 
		box-shadow: 0 0 10px rgba(72, 145, 184, 0.5); 
	}

	.btnDetalle .material-symbols-outlined {
		font-size: 18px; 
		transition: transform 0.2s ease-in-out;
	}

	.btnDetalle:hover .material-symbols-outlined {
		transform: translateX(4px); 
	}
</style>

<script>
	function openModalDetalleHistorialCanje(btnDetalle) {
		const fila = btnDetalle.closest('tr');
		const celdaCodigoCanje = fila.getElementsByClassName('idCanje')[0]; 
		const codigoCanje = celdaCodigoCanje.innerText;
		
		objCanjeAndDetailsByIdCanjeFetch(codigoCanje);
	}
</script>
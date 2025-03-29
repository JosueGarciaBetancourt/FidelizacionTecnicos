$(document).ready(function() {
	$.ajaxSetup({
		headers: {
			'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
		}
	});

	const loadingModal = document.getElementById('loadingModal');

	window.tblVentasIntermediadas  = $('#tblVentasIntermediadas').DataTable({	
		// Configuración inicial
		lengthMenu: [[5, 10, 50, 100, 500, -1], [5, 10, 50, 100, 500, "Todos"]],
		pageLength: 10, 
		dom: "Blifrtp", //B:buttons f:filter r:processing t:table
						//i:info l:length ("Mostrar n registros") p:paging
		buttons: [
			{   extend: "excelHtml5",
				text: "<i class='fa-solid fa-file-excel'></i>",
				titleAttr: "Exportar todas las filas a excel", //tooltip
				filename: `Club de Técnicos DIMACOF-Listado de Ventas Intermediadas-${obtenerFechaHoraFormateadaExportaciones()}`, 
				title: 'Club de Técnicos DIMACOF-Listado de Ventas Intermediadas', 
				action: function (e, dt, node, config) {
					// Guardar el valor actual de `pageLength`
					const currentPageLength = tblVentasIntermediadas.page.len();
	
					// Cambiar a mostrar todas las filas
					tblVentasIntermediadas.page.len(-1).draw(); 
	
					loadingModal.classList.add('show');

					// Esperar a que la tabla se redibuje
					tblVentasIntermediadas.one('draw', function () {
						loadingModal.classList.remove('show');
						// Exportar después de que la tabla esté cargada
						$.fn.dataTable.ext.buttons.excelHtml5.action.call(this, e, dt, node, config);
						// Restaurar el valor original de `pageLength`
						tblVentasIntermediadas.page.len(currentPageLength).draw();
					});
				},
			},  
			{   extend: "pdfHtml5",
				text: "<i class='fa-solid fa-file-pdf'></i>",
				titleAttr: "Exportar a PDF", //tooltip,
				orientation: 'landscape',
				filename: `Club de Técnicos DIMACOF-Listado de Ventas Intermediadas-${obtenerFechaHoraFormateadaExportaciones()}`, 
				title: 'Club de Técnicos DIMACOF - Listado de Ventas Intermediadas',
				customize: function(doc) {
					// Centrar título
					doc.content[0].text = {
						text: 'Club de Técnicos DIMACOF - Listado de Ventas Intermediadas',
						bold: true,
						alignment: 'center'
					};
				},
				action: function (e, dt, node, config) {
					window.open(baseUrlMAIN + '/dashboard-ventasIntermediadas/export-pdf', '_blank');
				}
			},  
		],

		// Configuración del buscador
		search: {
			caseInsensitive: true, // Búsqueda sin distinción entre mayúsculas y minúsculas
			regex: true, // Habilitar búsqueda usando expresiones regulares (opcional)
			smart: true, // Habilitar búsqueda inteligente (por defecto)
		},

		// Configurando el idioma
		language: {
			"processing": "Procesando...",
			"lengthMenu": "Mostrar _MENU_ registros",
			"zeroRecords": "No se encontraron ventas intermediadas",
			"emptyTable": "No se encontraron ventas intermediadas",
			"infoEmpty": "Mostrando registros del 0 al 0 de un total de 0 registros",
			"infoFiltered": "(filtrado de un total de _MAX_ registros)",
			"search": "<span class='material-symbols-outlined'>search</span>",
			"searchPlaceholder": "Buscar DNI/Nombre de técnico o número de comprobante", // Placeholder para el campo de búsqueda
			"loadingRecords": "Cargando datos... (Si tarda demasiado, actualizar la página)",
			"paginate": {
				"first": "Primero",
				"last": "Último",
				"next": "Siguiente",
				"previous": "Anterior"
			},
			"aria": {
				"sortAscending": ": Activar para ordenar la columna de manera ascendente",
				"sortDescending": ": Activar para ordenar la columna de manera descendente"
			},
			"buttons": {
				"copy": "Copiar",
				"colvis": "Visibilidad",
				"collection": "Colección",
				"colvisRestore": "Restaurar visibilidad",
				"copyKeys": "Presione ctrl o u2318 + C para copiar los datos de la tabla al portapapeles del sistema. <br \/> <br \/> Para cancelar, haga clic en este mensaje o presione escape.",
				"copySuccess": {
					"1": "Copiada 1 fila al portapapeles",
					"_": "Copiadas %ds fila al portapapeles"
				},
				"copyTitle": "Copiar al portapapeles",
				"csv": "CSV",
				"excel": "Excel",
				"pageLength": {
					"-1": "Mostrar todas las filas",
					"_": "Mostrar %d filas"
				},
				"pdf": "PDF",
				"print": "Imprimir",
				"renameState": "Cambiar nombre",
				"updateState": "Actualizar",
				"createState": "Crear Estado",
				"removeAllStates": "Remover Estados",
				"removeState": "Remover",
				"savedStates": "Estados Guardados",
				"stateRestore": "Estado %d"
			},
			"autoFill": {
				"cancel": "Cancelar",
				"fill": "Rellene todas las celdas con <i>%d<\/i>",
				"fillHorizontal": "Rellenar celdas horizontalmente",
				"fillVertical": "Rellenar celdas verticalmente"
			},
			"decimal": ",",
			"searchBuilder": {
				"add": "Añadir condición",
				"button": {
					"0": "Constructor de búsqueda",
					"_": "Constructor de búsqueda (%d)"
				},
				"clearAll": "Borrar todo",
				"condition": "Condición",
				"conditions": {
					"date": {
						"before": "Antes",
						"between": "Entre",
						"empty": "Vacío",
						"equals": "Igual a",
						"notBetween": "No entre",
						"not": "Diferente de",
						"after": "Después",
						"notEmpty": "No Vacío"
					},
					"number": {
						"between": "Entre",
						"equals": "Igual a",
						"gt": "Mayor a",
						"gte": "Mayor o igual a",
						"lt": "Menor que",
						"lte": "Menor o igual que",
						"notBetween": "No entre",
						"notEmpty": "No vacío",
						"not": "Diferente de",
						"empty": "Vacío"
					},
					"string": {
						"contains": "Contiene",
						"empty": "Vacío",
						"endsWith": "Termina en",
						"equals": "Igual a",
						"startsWith": "Empieza con",
						"not": "Diferente de",
						"notContains": "No Contiene",
						"notStartsWith": "No empieza con",
						"notEndsWith": "No termina con",
						"notEmpty": "No Vacío"
					},
					"array": {
						"not": "Diferente de",
						"equals": "Igual",
						"empty": "Vacío",
						"contains": "Contiene",
						"notEmpty": "No Vacío",
						"without": "Sin"
					}
				},
				"data": "Data",
				"deleteTitle": "Eliminar regla de filtrado",
				"leftTitle": "Criterios anulados",
				"logicAnd": "Y",
				"logicOr": "O",
				"rightTitle": "Criterios de sangría",
				"title": {
					"0": "Constructor de búsqueda",
					"_": "Constructor de búsqueda (%d)"
				},
				"value": "Valor"
			},
			"searchPanes": {
				"clearMessage": "Borrar todo",
				"collapse": {
					"0": "Paneles de búsqueda",
					"_": "Paneles de búsqueda (%d)"
				},
				"count": "{total}",
				"countFiltered": "{shown} ({total})",
				"emptyPanes": "Sin paneles de búsqueda",
				"loadMessage": "Cargando paneles de búsqueda",
				"title": "Filtros Activos - %d",
				"showMessage": "Mostrar Todo",
				"collapseMessage": "Colapsar Todo"
			},
			"select": {
				"cells": {
					"1": "1 celda seleccionada",
					"_": "%d celdas seleccionadas"
				},
				"columns": {
					"1": "1 columna seleccionada",
					"_": "%d columnas seleccionadas"
				},
				"rows": {
					"1": "1 fila seleccionada",
					"_": "%d filas seleccionadas"
				}
			},
			"thousands": ".",
			"datetime": {
				"previous": "Anterior",
				"hours": "Horas",
				"minutes": "Minutos",
				"seconds": "Segundos",
				"unknown": "-",
				"amPm": [
					"AM",
					"PM"
				],
				"months": {
					"0": "Enero",
					"1": "Febrero",
					"10": "Noviembre",
					"11": "Diciembre",
					"2": "Marzo",
					"3": "Abril",
					"4": "Mayo",
					"5": "Junio",
					"6": "Julio",
					"7": "Agosto",
					"8": "Septiembre",
					"9": "Octubre"
				},
				"weekdays": {
					"0": "Dom",
					"1": "Lun",
					"2": "Mar",
					"4": "Jue",
					"5": "Vie",
					"3": "Mié",
					"6": "Sáb"
				},
				"next": "Próximo"
			},
			"editor": {
				"close": "Cerrar",
				"create": {
					"button": "Nuevo",
					"title": "Crear Nuevo Registro",
					"submit": "Crear"
				},
				"edit": {
					"button": "Editar",
					"title": "Editar Registro",
					"submit": "Actualizar"
				},
				"remove": {
					"button": "Eliminar",
					"title": "Eliminar Registro",
					"submit": "Eliminar",
					"confirm": {
						"_": "¿Está seguro de que desea eliminar %d filas?",
						"1": "¿Está seguro de que desea eliminar 1 fila?"
					}
				},
				"error": {
					"system": "Ha ocurrido un error en el sistema (<a target=\"\\\" rel=\"\\ nofollow\" href=\"\\\">Más información&lt;\\\/a&gt;).<\/a>"
				},
				"multi": {
					"title": "Múltiples Valores",
					"restore": "Deshacer Cambios",
					"noMulti": "Este registro puede ser editado individualmente, pero no como parte de un grupo.",
					"info": "Los elementos seleccionados contienen diferentes valores para este registro. Para editar y establecer todos los elementos de este registro con el mismo valor, haga clic o pulse aquí, de lo contrario conservarán sus valores individuales."
				}
			},
			"info": "Mostrando desde _START_ hasta _END_ de un total de _TOTAL_ registros",
			"stateRestore": {
				"creationModal": {
					"button": "Crear",
					"name": "Nombre:",
					"order": "Clasificación",
					"paging": "Paginación",
					"select": "Seleccionar",
					"columns": {
						"search": "Búsqueda de Columna",
						"visible": "Visibilidad de Columna"
					},
					"title": "Crear Nuevo Estado",
					"toggleLabel": "Incluir:",
					"scroller": "Posición de desplazamiento",
					"search": "Búsqueda",
					"searchBuilder": "Búsqueda avanzada"
				},
				"removeJoiner": "y",
				"removeSubmit": "Eliminar",
				"renameButton": "Cambiar Nombre",
				"duplicateError": "Ya existe un Estado con este nombre.",
				"emptyStates": "No hay Estados guardados",
				"removeTitle": "Remover Estado",
				"renameTitle": "Cambiar Nombre Estado",
				"emptyError": "El nombre no puede estar vacío.",
				"removeConfirm": "¿Seguro que quiere eliminar %s?",
				"removeError": "Error al eliminar el Estado",
				"renameLabel": "Nuevo nombre para %s:"
			},
			"infoThousands": "."
		},

		// Ajax
		processing: true,
		serverSide: true,
		responsive: true,

		"createdRow": function(row, data){
			let tdEstadoVenta = row.children[10];
			let span = document.createElement('span');
			span.classList.add('estado__span-' + (data['idEstadoVenta']));
			span.innerHTML = data['nombre_EstadoVenta'];
			tdEstadoVenta.innerHTML = '';
			tdEstadoVenta.appendChild(span);
		},
		
		ajax: {
			url: "tblVentasIntermediadasData",
			type: "POST",
			dataType: "json",
			error: function(xhr, status, error) {
				if (xhr.status !== 204) {
					console.warn("Error en AJAX de tblVentasIntermediadas:");
					console.error("status: " + status);
					console.error("error: " + error);
					console.error(xhr.status);
					location.reload();
				}
			}
		},

		/* 
			index
			idVentaIntermediada
			tipoComprobante
			fechaHoraEmision_VentaIntermediada
			fechaHoraCargada_VentaIntermediada
			nombreCliente_VentaIntermediada
			tipoCodigoCliente_VentaIntermediada
			codigoCliente_VentaIntermediada
			montoTotal_VentaIntermediada
			puntosGanados_VentaIntermediada
			nombreTecnico
			idTecnico
			fechaHora_Canje
			diasTranscurridos
			idEstadoVenta
			nombre_EstadoVenta
		*/

		columns: [
			{data: 'index', name: 'index', class: 'celda-centered tdContador'},
			{
				data: null, // Indica que no se tomará un solo campo
				render: function (data) {
					return `${data.idVentaIntermediada} <br>
							<small>${data.tipoComprobante}</small>`;
				},
				name: 'idVentaIntermediada_tipoComprobante', // Nombre para búsquedas
			},
			{data: 'fechaHoraEmision_VentaIntermediada', name: 'fechaHoraEmision_VentaIntermediada', class: 'celda-centered'},
			{data: 'fechaHoraCargada_VentaIntermediada', name: 'fechaHoraCargada_VentaIntermediada', class: 'celda-centered'},
			{
				data: null,
				render: function (data) {
					return `${data.nombreCliente_VentaIntermediada} <br>
							<small>${data.tipoCodigoCliente_VentaIntermediada}: ${data.codigoCliente_VentaIntermediada}</small>`;
				},
				name: 'nombreCliente_TipoCodigo_Codigo',
			},
			{
				data: 'montoTotal_VentaIntermediada',
				name: 'montoTotal_VentaIntermediada',
				class: 'celda-centered',
			},
			{
				data: null,
				render: function (data) {
					return `<small>P. Iniciales: </small> ${data.puntosGanados_VentaIntermediada} <br>
							<small>P. Actuales:</small> ${data.puntosActuales_VentaIntermediada}`;
				},
				name: 'puntosGanados_PuntosActuales_VentaIntermediada',
			},
			{
				data: null,
				render: function (data) {
					return `${data.nombreTecnico} <br>
							<small>DNI: ${data.idTecnico}</small>`;
				},
				name: 'nombreTecnico_idTecnico', 
			},
			{data: 'fechaHora_Canje', name: 'fechaHora_Canje', class: 'celda-centered'},
			{data: 'diasTranscurridos', name: 'diasTranscurridos', class: 'celda-centered'},
			{data: 'nombre_EstadoVenta', name: 'nombre_EstadoVenta', class: 'celda-centered estado__celda'},
		],
	});

	window.tblRecompensas = $('#tblRecompensas').DataTable({
		// Configuración inicial
		lengthMenu: [[5, 10, -1], [5, 10, "Todos"]],
		pageLength: 10, 
		dom: "Blifrtp", //B:buttons f:filter r:processing t:table
						//i:info l:length ("Mostrar n registros") p:paging
		buttons: [
			{   extend: "excelHtml5",
				text: "<i class='fa-solid fa-file-excel'></i>",
				titleAttr: "Exportar todas las filas a excel", //tooltip
				filename: `Club de Técnicos DIMACOF-Listado de Recompensas-${obtenerFechaHoraFormateadaExportaciones()}`, 
				title: 'Club de Técnicos DIMACOF-Listado de Recompensas', 
				action: function (e, dt, node, config) {
					// Guardar el valor actual de `pageLength`
					const currentPageLength = tblRecompensas.page.len();
	
					// Cambiar a mostrar todas las filas
					tblRecompensas.page.len(-1).draw(); 
					loadingModal.classList.add('show');

					setTimeout(() => {
						loadingModal.classList.remove('show');
						$.fn.dataTable.ext.buttons.excelHtml5.action.call(this, e, dt, node, config);
						tblRecompensas.page.len(currentPageLength).draw(); 
					}, 500); // Esperar medio segundo para asegurar que todas las filas estén cargadas
				},
			},  
			{   extend: "pdfHtml5",
				text: "<i class='fa-solid fa-file-pdf'></i>",
				titleAttr: "Exportar a PDF", //tooltip
				orientation: 'landscape',
				filename: `Club de Técnicos DIMACOF-Listado de Recompensas-${obtenerFechaHoraFormateadaExportaciones()}`, 
				title: 'Club de Técnicos DIMACOF - Listado de Recompensas',
				customize: function(doc) {
					// Centrar título
					doc.content[0].text = {
						text: 'Club de Técnicos DIMACOF - Listado de Recompensas',
						bold: true,
						alignment: 'center'
					};
				},
				action: function (e, dt, node, config) {
					window.open(baseUrlMAIN + '/dashboard-recompensas/export-pdf', '_blank');
				}
			},  
		],

		// Configuración del buscador
		search: {
			caseInsensitive: true, // Búsqueda sin distinción entre mayúsculas y minúsculas
			regex: true, // Habilitar búsqueda usando expresiones regulares (opcional)
			smart: true, // Habilitar búsqueda inteligente (por defecto)
		},
		
		// Configurando el idioma
		language: {
			"processing": "Procesando...",
			"lengthMenu": "Mostrar _MENU_ registros",
			"zeroRecords": "No se encontraron recompensas",
			"emptyTable": "No se encontraron recompensas",
			"infoEmpty": "Mostrando registros del 0 al 0 de un total de 0 registros",
			"infoFiltered": "(filtrado de un total de _MAX_ registros)",
			"search": "<span class='material-symbols-outlined'>search</span>",
			"searchPlaceholder": "Buscar Código/Tipo/Descripción de recompensa", // Placeholder para el campo de búsqueda
			"loadingRecords": "Cargando...",
			"paginate": {
				"first": "Primero",
				"last": "Último",
				"next": "Siguiente",
				"previous": "Anterior"
			},
			"aria": {
				"sortAscending": ": Activar para ordenar la columna de manera ascendente",
				"sortDescending": ": Activar para ordenar la columna de manera descendente"
			},
			"buttons": {
				"copy": "Copiar",
				"colvis": "Visibilidad",
				"collection": "Colección",
				"colvisRestore": "Restaurar visibilidad",
				"copyKeys": "Presione ctrl o u2318 + C para copiar los datos de la tabla al portapapeles del sistema. <br \/> <br \/> Para cancelar, haga clic en este mensaje o presione escape.",
				"copySuccess": {
					"1": "Copiada 1 fila al portapapeles",
					"_": "Copiadas %ds fila al portapapeles"
				},
				"copyTitle": "Copiar al portapapeles",
				"csv": "CSV",
				"excel": "Excel",
				"pageLength": {
					"-1": "Mostrar todas las filas",
					"_": "Mostrar %d filas"
				},
				"pdf": "PDF",
				"print": "Imprimir",
				"renameState": "Cambiar nombre",
				"updateState": "Actualizar",
				"createState": "Crear Estado",
				"removeAllStates": "Remover Estados",
				"removeState": "Remover",
				"savedStates": "Estados Guardados",
				"stateRestore": "Estado %d"
			},
			"autoFill": {
				"cancel": "Cancelar",
				"fill": "Rellene todas las celdas con <i>%d<\/i>",
				"fillHorizontal": "Rellenar celdas horizontalmente",
				"fillVertical": "Rellenar celdas verticalmente"
			},
			"decimal": ",",
			"searchBuilder": {
				"add": "Añadir condición",
				"button": {
					"0": "Constructor de búsqueda",
					"_": "Constructor de búsqueda (%d)"
				},
				"clearAll": "Borrar todo",
				"condition": "Condición",
				"conditions": {
					"date": {
						"before": "Antes",
						"between": "Entre",
						"empty": "Vacío",
						"equals": "Igual a",
						"notBetween": "No entre",
						"not": "Diferente de",
						"after": "Después",
						"notEmpty": "No Vacío"
					},
					"number": {
						"between": "Entre",
						"equals": "Igual a",
						"gt": "Mayor a",
						"gte": "Mayor o igual a",
						"lt": "Menor que",
						"lte": "Menor o igual que",
						"notBetween": "No entre",
						"notEmpty": "No vacío",
						"not": "Diferente de",
						"empty": "Vacío"
					},
					"string": {
						"contains": "Contiene",
						"empty": "Vacío",
						"endsWith": "Termina en",
						"equals": "Igual a",
						"startsWith": "Empieza con",
						"not": "Diferente de",
						"notContains": "No Contiene",
						"notStartsWith": "No empieza con",
						"notEndsWith": "No termina con",
						"notEmpty": "No Vacío"
					},
					"array": {
						"not": "Diferente de",
						"equals": "Igual",
						"empty": "Vacío",
						"contains": "Contiene",
						"notEmpty": "No Vacío",
						"without": "Sin"
					}
				},
				"data": "Data",
				"deleteTitle": "Eliminar regla de filtrado",
				"leftTitle": "Criterios anulados",
				"logicAnd": "Y",
				"logicOr": "O",
				"rightTitle": "Criterios de sangría",
				"title": {
					"0": "Constructor de búsqueda",
					"_": "Constructor de búsqueda (%d)"
				},
				"value": "Valor"
			},
			"searchPanes": {
				"clearMessage": "Borrar todo",
				"collapse": {
					"0": "Paneles de búsqueda",
					"_": "Paneles de búsqueda (%d)"
				},
				"count": "{total}",
				"countFiltered": "{shown} ({total})",
				"emptyPanes": "Sin paneles de búsqueda",
				"loadMessage": "Cargando paneles de búsqueda",
				"title": "Filtros Activos - %d",
				"showMessage": "Mostrar Todo",
				"collapseMessage": "Colapsar Todo"
			},
			"select": {
				"cells": {
					"1": "1 celda seleccionada",
					"_": "%d celdas seleccionadas"
				},
				"columns": {
					"1": "1 columna seleccionada",
					"_": "%d columnas seleccionadas"
				},
				"rows": {
					"1": "1 fila seleccionada",
					"_": "%d filas seleccionadas"
				}
			},
			"thousands": ".",
			"datetime": {
				"previous": "Anterior",
				"hours": "Horas",
				"minutes": "Minutos",
				"seconds": "Segundos",
				"unknown": "-",
				"amPm": [
					"AM",
					"PM"
				],
				"months": {
					"0": "Enero",
					"1": "Febrero",
					"10": "Noviembre",
					"11": "Diciembre",
					"2": "Marzo",
					"3": "Abril",
					"4": "Mayo",
					"5": "Junio",
					"6": "Julio",
					"7": "Agosto",
					"8": "Septiembre",
					"9": "Octubre"
				},
				"weekdays": {
					"0": "Dom",
					"1": "Lun",
					"2": "Mar",
					"4": "Jue",
					"5": "Vie",
					"3": "Mié",
					"6": "Sáb"
				},
				"next": "Próximo"
			},
			"editor": {
				"close": "Cerrar",
				"create": {
					"button": "Nuevo",
					"title": "Crear Nuevo Registro",
					"submit": "Crear"
				},
				"edit": {
					"button": "Editar",
					"title": "Editar Registro",
					"submit": "Actualizar"
				},
				"remove": {
					"button": "Eliminar",
					"title": "Eliminar Registro",
					"submit": "Eliminar",
					"confirm": {
						"_": "¿Está seguro de que desea eliminar %d filas?",
						"1": "¿Está seguro de que desea eliminar 1 fila?"
					}
				},
				"error": {
					"system": "Ha ocurrido un error en el sistema (<a target=\"\\\" rel=\"\\ nofollow\" href=\"\\\">Más información&lt;\\\/a&gt;).<\/a>"
				},
				"multi": {
					"title": "Múltiples Valores",
					"restore": "Deshacer Cambios",
					"noMulti": "Este registro puede ser editado individualmente, pero no como parte de un grupo.",
					"info": "Los elementos seleccionados contienen diferentes valores para este registro. Para editar y establecer todos los elementos de este registro con el mismo valor, haga clic o pulse aquí, de lo contrario conservarán sus valores individuales."
				}
			},
			"info": "Mostrando desde _START_ hasta _END_ de un total de _TOTAL_ registros",
			"stateRestore": {
				"creationModal": {
					"button": "Crear",
					"name": "Nombre:",
					"order": "Clasificación",
					"paging": "Paginación",
					"select": "Seleccionar",
					"columns": {
						"search": "Búsqueda de Columna",
						"visible": "Visibilidad de Columna"
					},
					"title": "Crear Nuevo Estado",
					"toggleLabel": "Incluir:",
					"scroller": "Posición de desplazamiento",
					"search": "Búsqueda",
					"searchBuilder": "Búsqueda avanzada"
				},
				"removeJoiner": "y",
				"removeSubmit": "Eliminar",
				"renameButton": "Cambiar Nombre",
				"duplicateError": "Ya existe un Estado con este nombre.",
				"emptyStates": "No hay Estados guardados",
				"removeTitle": "Remover Estado",
				"renameTitle": "Cambiar Nombre Estado",
				"emptyError": "El nombre no puede estar vacío.",
				"removeConfirm": "¿Seguro que quiere eliminar %s?",
				"removeError": "Error al eliminar el Estado",
				"renameLabel": "Nuevo nombre para %s:"
			},
			"infoThousands": "."
		},
	});

	window.tblTecnicos = $('#tblTecnicos').DataTable({
		// Configuración inicial
		lengthMenu: [[5, 10, 50, 100, 500, -1], [5, 10, 50, 100, 500, "Todos"]],
		pageLength: 10, 
		dom: "Blifrtp", //B:buttons f:filter r:processing t:table
						//i:info l:length ("Mostrar n registros") p:paging
		buttons: [
			{   extend: "excelHtml5",
				text: "<i class='fa-solid fa-file-excel'></i>",
				titleAttr: "Exportar todas las filas a excel", //tooltip
				exportOptions: {
					columns: [0, 1, 2, 3, 4, 5, 6, 7, 8], // Exportar solo columnas específicas
				},
				filename: `Club de Técnicos DIMACOF-Listado de Técnicos-${obtenerFechaHoraFormateadaExportaciones()}`, 
				title: 'Club de Técnicos DIMACOF-Listado de Tecnicos',
				action: function (e, dt, node, config) {
					// Guardar el valor actual de `pageLength`
					const currentPageLength = tblTecnicos.page.len();
	
					// Cambiar a mostrar todas las filas
					tblTecnicos.page.len(-1).draw(); 
	
					loadingModal.classList.add('show');

					// Esperar a que la tabla se redibuje
					tblTecnicos.one('draw', function () {
						loadingModal.classList.remove('show');
						// Exportar después de que la tabla esté cargada
						$.fn.dataTable.ext.buttons.excelHtml5.action.call(this, e, dt, node, config);
						// Restaurar el valor original de `pageLength`
						tblTecnicos.page.len(currentPageLength).draw();
					});
				},
			},  
			{   extend: "pdfHtml5",
				text: "<i class='fa-solid fa-file-pdf'></i>",
				titleAttr: "Exportar a PDF", //tooltip
				orientation: 'landscape',
				exportOptions: {
					columns: [0, 1, 2, 3, 4, 5, 6, 7, 8], 
				},
				filename: `Club de Técnicos DIMACOF-Listado de Técnicos-${obtenerFechaHoraFormateadaExportaciones()}`, 
				title: 'Club de Técnicos DIMACOF - Listado de Tecnicos',
				action: function (e, dt, node, config) {
					window.open(baseUrlMAIN + '/dashboard-tecnicos/export-pdf', '_blank' );
				}
			},  
		],

		// Configurando el idioma
		language: {
			"processing": "Procesando...",
			"lengthMenu": "Mostrar _MENU_ registros",
			"zeroRecords": "No se encontraron técnicos",
			"emptyTable": "No se encontraron técnicos",
			"infoEmpty": "Mostrando registros del 0 al 0 de un total de 0 registros",
			"infoFiltered": "(filtrado de un total de _MAX_ registros)",
			"search": "<span class='material-symbols-outlined'>search</span>",
			"searchPlaceholder": "Buscar DNI/Nombre de recompensa", // Placeholder para el campo de búsqueda
			"loadingRecords": "Cargando datos... (Si tarda demasiado, actualizar la página)",
			"paginate": {
				"first": "Primero",
				"last": "Último",
				"next": "Siguiente",
				"previous": "Anterior"
			},
			"aria": {
				"sortAscending": ": Activar para ordenar la columna de manera ascendente",
				"sortDescending": ": Activar para ordenar la columna de manera descendente"
			},
			"buttons": {
				"copy": "Copiar",
				"colvis": "Visibilidad",
				"collection": "Colección",
				"colvisRestore": "Restaurar visibilidad",
				"copyKeys": "Presione ctrl o u2318 + C para copiar los datos de la tabla al portapapeles del sistema. <br \/> <br \/> Para cancelar, haga clic en este mensaje o presione escape.",
				"copySuccess": {
					"1": "Copiada 1 fila al portapapeles",
					"_": "Copiadas %ds fila al portapapeles"
				},
				"copyTitle": "Copiar al portapapeles",
				"csv": "CSV",
				"excel": "Excel",
				"pageLength": {
					"-1": "Mostrar todas las filas",
					"_": "Mostrar %d filas"
				},
				"pdf": "PDF",
				"print": "Imprimir",
				"renameState": "Cambiar nombre",
				"updateState": "Actualizar",
				"createState": "Crear Estado",
				"removeAllStates": "Remover Estados",
				"removeState": "Remover",
				"savedStates": "Estados Guardados",
				"stateRestore": "Estado %d"
			},
			"autoFill": {
				"cancel": "Cancelar",
				"fill": "Rellene todas las celdas con <i>%d<\/i>",
				"fillHorizontal": "Rellenar celdas horizontalmente",
				"fillVertical": "Rellenar celdas verticalmente"
			},
			"decimal": ",",
			"searchBuilder": {
				"add": "Añadir condición",
				"button": {
					"0": "Constructor de búsqueda",
					"_": "Constructor de búsqueda (%d)"
				},
				"clearAll": "Borrar todo",
				"condition": "Condición",
				"conditions": {
					"date": {
						"before": "Antes",
						"between": "Entre",
						"empty": "Vacío",
						"equals": "Igual a",
						"notBetween": "No entre",
						"not": "Diferente de",
						"after": "Después",
						"notEmpty": "No Vacío"
					},
					"number": {
						"between": "Entre",
						"equals": "Igual a",
						"gt": "Mayor a",
						"gte": "Mayor o igual a",
						"lt": "Menor que",
						"lte": "Menor o igual que",
						"notBetween": "No entre",
						"notEmpty": "No vacío",
						"not": "Diferente de",
						"empty": "Vacío"
					},
					"string": {
						"contains": "Contiene",
						"empty": "Vacío",
						"endsWith": "Termina en",
						"equals": "Igual a",
						"startsWith": "Empieza con",
						"not": "Diferente de",
						"notContains": "No Contiene",
						"notStartsWith": "No empieza con",
						"notEndsWith": "No termina con",
						"notEmpty": "No Vacío"
					},
					"array": {
						"not": "Diferente de",
						"equals": "Igual",
						"empty": "Vacío",
						"contains": "Contiene",
						"notEmpty": "No Vacío",
						"without": "Sin"
					}
				},
				"data": "Data",
				"deleteTitle": "Eliminar regla de filtrado",
				"leftTitle": "Criterios anulados",
				"logicAnd": "Y",
				"logicOr": "O",
				"rightTitle": "Criterios de sangría",
				"title": {
					"0": "Constructor de búsqueda",
					"_": "Constructor de búsqueda (%d)"
				},
				"value": "Valor"
			},
			"searchPanes": {
				"clearMessage": "Borrar todo",
				"collapse": {
					"0": "Paneles de búsqueda",
					"_": "Paneles de búsqueda (%d)"
				},
				"count": "{total}",
				"countFiltered": "{shown} ({total})",
				"emptyPanes": "Sin paneles de búsqueda",
				"loadMessage": "Cargando paneles de búsqueda",
				"title": "Filtros Activos - %d",
				"showMessage": "Mostrar Todo",
				"collapseMessage": "Colapsar Todo"
			},
			"select": {
				"cells": {
					"1": "1 celda seleccionada",
					"_": "%d celdas seleccionadas"
				},
				"columns": {
					"1": "1 columna seleccionada",
					"_": "%d columnas seleccionadas"
				},
				"rows": {
					"1": "1 fila seleccionada",
					"_": "%d filas seleccionadas"
				}
			},
			"thousands": ".",
			"datetime": {
				"previous": "Anterior",
				"hours": "Horas",
				"minutes": "Minutos",
				"seconds": "Segundos",
				"unknown": "-",
				"amPm": [
					"AM",
					"PM"
				],
				"months": {
					"0": "Enero",
					"1": "Febrero",
					"10": "Noviembre",
					"11": "Diciembre",
					"2": "Marzo",
					"3": "Abril",
					"4": "Mayo",
					"5": "Junio",
					"6": "Julio",
					"7": "Agosto",
					"8": "Septiembre",
					"9": "Octubre"
				},
				"weekdays": {
					"0": "Dom",
					"1": "Lun",
					"2": "Mar",
					"3": "Mié",
					"4": "Jue",
					"5": "Vie",
					"6": "Sáb"
				},
				"next": "Próximo"
			},
			"editor": {
				"close": "Cerrar",
				"create": {
					"button": "Nuevo",
					"title": "Crear Nuevo Registro",
					"submit": "Crear"
				},
				"edit": {
					"button": "Editar",
					"title": "Editar Registro",
					"submit": "Actualizar"
				},
				"remove": {
					"button": "Eliminar",
					"title": "Eliminar Registro",
					"submit": "Eliminar",
					"confirm": {
						"_": "¿Está seguro de que desea eliminar %d filas?",
						"1": "¿Está seguro de que desea eliminar 1 fila?"
					}
				},
				"error": {
					"system": "Ha ocurrido un error en el sistema (<a target=\"\\\" rel=\"\\ nofollow\" href=\"\\\">Más información&lt;\\\/a&gt;).<\/a>"
				},
				"multi": {
					"title": "Múltiples Valores",
					"restore": "Deshacer Cambios",
					"noMulti": "Este registro puede ser editado individualmente, pero no como parte de un grupo.",
					"info": "Los elementos seleccionados contienen diferentes valores para este registro. Para editar y establecer todos los elementos de este registro con el mismo valor, haga clic o pulse aquí, de lo contrario conservarán sus valores individuales."
				}
			},
			"info": "Mostrando desde _START_ hasta _END_ de un total de _TOTAL_ registros",
			"stateRestore": {
				"creationModal": {
					"button": "Crear",
					"name": "Nombre:",
					"order": "Clasificación",
					"paging": "Paginación",
					"select": "Seleccionar",
					"columns": {
						"search": "Búsqueda de Columna",
						"visible": "Visibilidad de Columna"
					},
					"title": "Crear Nuevo Estado",
					"toggleLabel": "Incluir:",
					"scroller": "Posición de desplazamiento",
					"search": "Búsqueda",
					"searchBuilder": "Búsqueda avanzada"
				},
				"removeJoiner": "y",
				"removeSubmit": "Eliminar",
				"renameButton": "Cambiar Nombre",
				"duplicateError": "Ya existe un Estado con este nombre.",
				"emptyStates": "No hay Estados guardados",
				"removeTitle": "Remover Estado",
				"renameTitle": "Cambiar Nombre Estado",
				"emptyError": "El nombre no puede estar vacío.",
				"removeConfirm": "¿Seguro que quiere eliminar %s?",
				"removeError": "Error al eliminar el Estado",
				"renameLabel": "Nuevo nombre para %s:"
			},
			"infoThousands": "."
		},

		// Ajax
		processing: true,
		serverSide: true,
		responsive: true,

		"createdRow": function(row, data){
			let tdRango = row.children[8];
			let span = document.createElement('span');

			span.style.color = data['colorTexto_Rango'];
			span.style.backgroundColor = data['colorFondo_Rango'];

			span.innerHTML = data['rangoTecnico'];
			tdRango.innerHTML = '';
			tdRango.appendChild(span);
		},
		
		ajax: {
			url: "tblTecnicosData",
			type: "POST",
			dataType: "json",
			error: function(xhr, status, error) {
				if (xhr.status !== 204) {
					console.log("AJAX");
					console.error("status: " + status);
					console.error("error: " + error);
					console.error(xhr.status);
					location.reload();
				}
			}
		},

		columns: [
			{data: 'index', name: 'index', class: 'celda-centered tdContador'},
			{data: 'idTecnico', name: 'idTecnico', class: 'celda-centered idTecnico'},
			{data: 'nombreTecnico', name: 'nombreTecnico'/*, class: 'celda-centered'*/},
			{data: 'oficioTecnico', name: 'oficioTecnico', class: 'celda-centered'},
			{data: 'celularTecnico', name: 'celularTecnico', class: 'celda-centered'},
			{data: 'fechaNacimiento_Tecnico', name: 'fechaNacimiento_Tecnico', class: 'celda-centered'},
			{data: 'totalPuntosActuales_Tecnico', name: 'totalPuntosActuales_Tecnico', class: 'celda-centered'},
			{data: 'historicoPuntos_Tecnico', name: 'historicoPuntos_Tecnico', class: 'celda-centered'},
			{data: 'rangoTecnico', name: 'rangoTecnico', class: 'celda-centered celda__rangoTecnico'},
			{data: 'actions', name: 'actions', class: 'celda-centered', orderable: false},
		],
	});

	const tblOficios = $('#tblOficios').DataTable({
		lengthMenu: [[5, 10, -1], [5, 10, "Todos"]],
		pageLength: 10, // Cantidad de registros por página
		dom: "Blifrtp", //B:buttons f:filter r:processing t:table
						//i:info l:length ("Mostrar n registros") p:paging
		buttons: [
			{   extend: "excelHtml5",
				text: "<i class='fa-solid fa-file-excel'></i>",
				titleAttr: "Exportar todas las filas a excel", //tooltip
				filename: `Club de Técnicos DIMACOF-Listado de Oficios-${obtenerFechaHoraFormateadaExportaciones()}`, 
				title: 'Club de Técnicos DIMACOF-Listado de Oficios', 
				action: function (e, dt, node, config) {
					// Guardar el valor actual de `pageLength`
					const currentPageLength = tblOficios.page.len();
	
					// Cambiar a mostrar todas las filas
					tblOficios.page.len(-1).draw(); 
					loadingModal.classList.add('show');

					setTimeout(() => {
						loadingModal.classList.remove('show');
						$.fn.dataTable.ext.buttons.excelHtml5.action.call(this, e, dt, node, config);
						tblOficios.page.len(currentPageLength).draw(); 
					}, 500); // Esperar medio segundo para asegurar que todas las filas estén cargadas
				},
			},  
			{   extend: "pdfHtml5",
				text: "<i class='fa-solid fa-file-pdf'></i>",
				titleAttr: "Exportar a PDF", //tooltip
				orientation: 'landscape',
				filename: `Club de Técnicos DIMACOF-Listado de Oficios-${obtenerFechaHoraFormateadaExportaciones()}`, 
				title: 'Club de Técnicos DIMACOF - Listado de Oficios',
				/* customize: function(doc) {
					// Centrar título
					doc.content[0].text = {
						text: 'Club de Técnicos DIMACOF - Listado de Oficios',
						bold: true,
						alignment: 'center'
					};
				} */
				action: function (e, dt, node, config) {
					window.open(baseUrlMAIN + '/dashboard-oficios/export-pdf', '_blank' );
				}
			},
		],

		// Configuración del buscador
		search: {
			caseInsensitive: true, // Búsqueda sin distinción entre mayúsculas y minúsculas
			regex: true, // Habilitar búsqueda usando expresiones regulares (opcional)
			smart: true, // Habilitar búsqueda inteligente (por defecto)
		},
		
		// Configurando el idioma
		language: {
			"processing": "Procesando...",
			"lengthMenu": "Mostrar _MENU_ registros",
			"zeroRecords": "No se encontraron oficios",
			"emptyTable": "No se encontraron oficios",
			"infoEmpty": "Mostrando registros del 0 al 0 de un total de 0 registros",
			"infoFiltered": "(filtrado de un total de _MAX_ registros)",
			"search": "<span class='material-symbols-outlined'>search</span>",
			"searchPlaceholder": "Buscar Código/Nombre de oficio", // Placeholder para el campo de búsqueda
			"loadingRecords": "Cargando...",
			"paginate": {
				"first": "Primero",
				"last": "Último",
				"next": "Siguiente",
				"previous": "Anterior"
			},
			"aria": {
				"sortAscending": ": Activar para ordenar la columna de manera ascendente",
				"sortDescending": ": Activar para ordenar la columna de manera descendente"
			},
			"buttons": {
				"copy": "Copiar",
				"colvis": "Visibilidad",
				"collection": "Colección",
				"colvisRestore": "Restaurar visibilidad",
				"copyKeys": "Presione ctrl o u2318 + C para copiar los datos de la tabla al portapapeles del sistema. <br \/> <br \/> Para cancelar, haga clic en este mensaje o presione escape.",
				"copySuccess": {
					"1": "Copiada 1 fila al portapapeles",
					"_": "Copiadas %ds fila al portapapeles"
				},
				"copyTitle": "Copiar al portapapeles",
				"csv": "CSV",
				"excel": "Excel",
				"pageLength": {
					"-1": "Mostrar todas las filas",
					"_": "Mostrar %d filas"
				},
				"pdf": "PDF",
				"print": "Imprimir",
				"renameState": "Cambiar nombre",
				"updateState": "Actualizar",
				"createState": "Crear Estado",
				"removeAllStates": "Remover Estados",
				"removeState": "Remover",
				"savedStates": "Estados Guardados",
				"stateRestore": "Estado %d"
			},
			"autoFill": {
				"cancel": "Cancelar",
				"fill": "Rellene todas las celdas con <i>%d<\/i>",
				"fillHorizontal": "Rellenar celdas horizontalmente",
				"fillVertical": "Rellenar celdas verticalmente"
			},
			"decimal": ",",
			"searchBuilder": {
				"add": "Añadir condición",
				"button": {
					"0": "Constructor de búsqueda",
					"_": "Constructor de búsqueda (%d)"
				},
				"clearAll": "Borrar todo",
				"condition": "Condición",
				"conditions": {
					"date": {
						"before": "Antes",
						"between": "Entre",
						"empty": "Vacío",
						"equals": "Igual a",
						"notBetween": "No entre",
						"not": "Diferente de",
						"after": "Después",
						"notEmpty": "No Vacío"
					},
					"number": {
						"between": "Entre",
						"equals": "Igual a",
						"gt": "Mayor a",
						"gte": "Mayor o igual a",
						"lt": "Menor que",
						"lte": "Menor o igual que",
						"notBetween": "No entre",
						"notEmpty": "No vacío",
						"not": "Diferente de",
						"empty": "Vacío"
					},
					"string": {
						"contains": "Contiene",
						"empty": "Vacío",
						"endsWith": "Termina en",
						"equals": "Igual a",
						"startsWith": "Empieza con",
						"not": "Diferente de",
						"notContains": "No Contiene",
						"notStartsWith": "No empieza con",
						"notEndsWith": "No termina con",
						"notEmpty": "No Vacío"
					},
					"array": {
						"not": "Diferente de",
						"equals": "Igual",
						"empty": "Vacío",
						"contains": "Contiene",
						"notEmpty": "No Vacío",
						"without": "Sin"
					}
				},
				"data": "Data",
				"deleteTitle": "Eliminar regla de filtrado",
				"leftTitle": "Criterios anulados",
				"logicAnd": "Y",
				"logicOr": "O",
				"rightTitle": "Criterios de sangría",
				"title": {
					"0": "Constructor de búsqueda",
					"_": "Constructor de búsqueda (%d)"
				},
				"value": "Valor"
			},
			"searchPanes": {
				"clearMessage": "Borrar todo",
				"collapse": {
					"0": "Paneles de búsqueda",
					"_": "Paneles de búsqueda (%d)"
				},
				"count": "{total}",
				"countFiltered": "{shown} ({total})",
				"emptyPanes": "Sin paneles de búsqueda",
				"loadMessage": "Cargando paneles de búsqueda",
				"title": "Filtros Activos - %d",
				"showMessage": "Mostrar Todo",
				"collapseMessage": "Colapsar Todo"
			},
			"select": {
				"cells": {
					"1": "1 celda seleccionada",
					"_": "%d celdas seleccionadas"
				},
				"columns": {
					"1": "1 columna seleccionada",
					"_": "%d columnas seleccionadas"
				},
				"rows": {
					"1": "1 fila seleccionada",
					"_": "%d filas seleccionadas"
				}
			},
			"thousands": ".",
			"datetime": {
				"previous": "Anterior",
				"hours": "Horas",
				"minutes": "Minutos",
				"seconds": "Segundos",
				"unknown": "-",
				"amPm": [
					"AM",
					"PM"
				],
				"months": {
					"0": "Enero",
					"1": "Febrero",
					"10": "Noviembre",
					"11": "Diciembre",
					"2": "Marzo",
					"3": "Abril",
					"4": "Mayo",
					"5": "Junio",
					"6": "Julio",
					"7": "Agosto",
					"8": "Septiembre",
					"9": "Octubre"
				},
				"weekdays": {
					"0": "Dom",
					"1": "Lun",
					"2": "Mar",
					"4": "Jue",
					"5": "Vie",
					"3": "Mié",
					"6": "Sáb"
				},
				"next": "Próximo"
			},
			"editor": {
				"close": "Cerrar",
				"create": {
					"button": "Nuevo",
					"title": "Crear Nuevo Registro",
					"submit": "Crear"
				},
				"edit": {
					"button": "Editar",
					"title": "Editar Registro",
					"submit": "Actualizar"
				},
				"remove": {
					"button": "Eliminar",
					"title": "Eliminar Registro",
					"submit": "Eliminar",
					"confirm": {
						"_": "¿Está seguro de que desea eliminar %d filas?",
						"1": "¿Está seguro de que desea eliminar 1 fila?"
					}
				},
				"error": {
					"system": "Ha ocurrido un error en el sistema (<a target=\"\\\" rel=\"\\ nofollow\" href=\"\\\">Más información&lt;\\\/a&gt;).<\/a>"
				},
				"multi": {
					"title": "Múltiples Valores",
					"restore": "Deshacer Cambios",
					"noMulti": "Este registro puede ser editado individualmente, pero no como parte de un grupo.",
					"info": "Los elementos seleccionados contienen diferentes valores para este registro. Para editar y establecer todos los elementos de este registro con el mismo valor, haga clic o pulse aquí, de lo contrario conservarán sus valores individuales."
				}
			},
			"info": "Mostrando desde _START_ hasta _END_ de un total de _TOTAL_ registros",
			"stateRestore": {
				"creationModal": {
					"button": "Crear",
					"name": "Nombre:",
					"order": "Clasificación",
					"paging": "Paginación",
					"select": "Seleccionar",
					"columns": {
						"search": "Búsqueda de Columna",
						"visible": "Visibilidad de Columna"
					},
					"title": "Crear Nuevo Estado",
					"toggleLabel": "Incluir:",
					"scroller": "Posición de desplazamiento",
					"search": "Búsqueda",
					"searchBuilder": "Búsqueda avanzada"
				},
				"removeJoiner": "y",
				"removeSubmit": "Eliminar",
				"renameButton": "Cambiar Nombre",
				"duplicateError": "Ya existe un Estado con este nombre.",
				"emptyStates": "No hay Estados guardados",
				"removeTitle": "Remover Estado",
				"renameTitle": "Cambiar Nombre Estado",
				"emptyError": "El nombre no puede estar vacío.",
				"removeConfirm": "¿Seguro que quiere eliminar %s?",
				"removeError": "Error al eliminar el Estado",
				"renameLabel": "Nuevo nombre para %s:"
			},
			"infoThousands": "."
		},
	});

	const tblRangos = $('#tblRangos').DataTable({
		lengthMenu: [[5, 10, -1], [5, 10, "Todos"]],
		pageLength: 5,
		dom: "Blifrtp", //B:buttons f:filter r:processing t:table
						//i:info l:length ("Mostrar n registros") p:paging
		buttons: [
			{   extend: "excelHtml5",
				text: "<i class='fa-solid fa-file-excel'></i>",
				titleAttr: "Exportar todas las filas a excel", //tooltip
				filename: `Club de Técnicos DIMACOF-Listado de Rangos-${obtenerFechaHoraFormateadaExportaciones()}`, 
				title: 'Club de Técnicos DIMACOF-Listado de Rangos', 
				action: function (e, dt, node, config) {
					// Guardar el valor actual de `pageLength`
					const currentPageLength = tblRangos.page.len();
	
					// Cambiar a mostrar todas las filas
					tblRangos.page.len(-1).draw(); 
					loadingModal.classList.add('show');

					setTimeout(() => {
						loadingModal.classList.remove('show');
						$.fn.dataTable.ext.buttons.excelHtml5.action.call(this, e, dt, node, config);
						tblOficios.page.len(currentPageLength).draw(); 
					}, 500); // Esperar medio segundo para asegurar que todas las filas estén cargadas
				},
			},  
			{   extend: "pdfHtml5",
				text: "<i class='fa-solid fa-file-pdf'></i>",
				titleAttr: "Exportar a PDF", //tooltip
				orientation: 'landscape',
				filename: `Club de Técnicos DIMACOF-Listado de Oficios-${obtenerFechaHoraFormateadaExportaciones()}`, 
				title: 'Club de Técnicos DIMACOF - Listado de Oficios',
				/* customize: function(doc) {
					// Centrar título
					doc.content[0].text = {
						text: 'Club de Técnicos DIMACOF - Listado de Oficios',
						bold: true,
						alignment: 'center'
					};
				} */
				action: function (e, dt, node, config) {
					window.open(baseUrlMAIN + '/dashboard-rangos/export-pdf', '_blank' );
				}
			},
		],

		// Configuración del buscador
		search: {
			caseInsensitive: true, // Búsqueda sin distinción entre mayúsculas y minúsculas
			regex: true, // Habilitar búsqueda usando expresiones regulares (opcional)
			smart: true, // Habilitar búsqueda inteligente (por defecto)
		},
		
		// Configurando el idioma
		language: {
			"processing": "Procesando...",
			"lengthMenu": "Mostrar _MENU_ registros",
			"zeroRecords": "No se encontraron rangos",
			"emptyTable": "No se encontraron rangos",
			"infoEmpty": "Mostrando registros del 0 al 0 de un total de 0 registros",
			"infoFiltered": "(filtrado de un total de _MAX_ registros)",
			"search": "<span class='material-symbols-outlined'>search</span>",
			"searchPlaceholder": "Buscar Código/Nombre de rango", // Placeholder para el campo de búsqueda
			"loadingRecords": "Cargando...",
			"paginate": {
				"first": "Primero",
				"last": "Último",
				"next": "Siguiente",
				"previous": "Anterior"
			},
			"aria": {
				"sortAscending": ": Activar para ordenar la columna de manera ascendente",
				"sortDescending": ": Activar para ordenar la columna de manera descendente"
			},
			"buttons": {
				"copy": "Copiar",
				"colvis": "Visibilidad",
				"collection": "Colección",
				"colvisRestore": "Restaurar visibilidad",
				"copyKeys": "Presione ctrl o u2318 + C para copiar los datos de la tabla al portapapeles del sistema. <br \/> <br \/> Para cancelar, haga clic en este mensaje o presione escape.",
				"copySuccess": {
					"1": "Copiada 1 fila al portapapeles",
					"_": "Copiadas %ds fila al portapapeles"
				},
				"copyTitle": "Copiar al portapapeles",
				"csv": "CSV",
				"excel": "Excel",
				"pageLength": {
					"-1": "Mostrar todas las filas",
					"_": "Mostrar %d filas"
				},
				"pdf": "PDF",
				"print": "Imprimir",
				"renameState": "Cambiar nombre",
				"updateState": "Actualizar",
				"createState": "Crear Estado",
				"removeAllStates": "Remover Estados",
				"removeState": "Remover",
				"savedStates": "Estados Guardados",
				"stateRestore": "Estado %d"
			},
			"autoFill": {
				"cancel": "Cancelar",
				"fill": "Rellene todas las celdas con <i>%d<\/i>",
				"fillHorizontal": "Rellenar celdas horizontalmente",
				"fillVertical": "Rellenar celdas verticalmente"
			},
			"decimal": ",",
			"searchBuilder": {
				"add": "Añadir condición",
				"button": {
					"0": "Constructor de búsqueda",
					"_": "Constructor de búsqueda (%d)"
				},
				"clearAll": "Borrar todo",
				"condition": "Condición",
				"conditions": {
					"date": {
						"before": "Antes",
						"between": "Entre",
						"empty": "Vacío",
						"equals": "Igual a",
						"notBetween": "No entre",
						"not": "Diferente de",
						"after": "Después",
						"notEmpty": "No Vacío"
					},
					"number": {
						"between": "Entre",
						"equals": "Igual a",
						"gt": "Mayor a",
						"gte": "Mayor o igual a",
						"lt": "Menor que",
						"lte": "Menor o igual que",
						"notBetween": "No entre",
						"notEmpty": "No vacío",
						"not": "Diferente de",
						"empty": "Vacío"
					},
					"string": {
						"contains": "Contiene",
						"empty": "Vacío",
						"endsWith": "Termina en",
						"equals": "Igual a",
						"startsWith": "Empieza con",
						"not": "Diferente de",
						"notContains": "No Contiene",
						"notStartsWith": "No empieza con",
						"notEndsWith": "No termina con",
						"notEmpty": "No Vacío"
					},
					"array": {
						"not": "Diferente de",
						"equals": "Igual",
						"empty": "Vacío",
						"contains": "Contiene",
						"notEmpty": "No Vacío",
						"without": "Sin"
					}
				},
				"data": "Data",
				"deleteTitle": "Eliminar regla de filtrado",
				"leftTitle": "Criterios anulados",
				"logicAnd": "Y",
				"logicOr": "O",
				"rightTitle": "Criterios de sangría",
				"title": {
					"0": "Constructor de búsqueda",
					"_": "Constructor de búsqueda (%d)"
				},
				"value": "Valor"
			},
			"searchPanes": {
				"clearMessage": "Borrar todo",
				"collapse": {
					"0": "Paneles de búsqueda",
					"_": "Paneles de búsqueda (%d)"
				},
				"count": "{total}",
				"countFiltered": "{shown} ({total})",
				"emptyPanes": "Sin paneles de búsqueda",
				"loadMessage": "Cargando paneles de búsqueda",
				"title": "Filtros Activos - %d",
				"showMessage": "Mostrar Todo",
				"collapseMessage": "Colapsar Todo"
			},
			"select": {
				"cells": {
					"1": "1 celda seleccionada",
					"_": "%d celdas seleccionadas"
				},
				"columns": {
					"1": "1 columna seleccionada",
					"_": "%d columnas seleccionadas"
				},
				"rows": {
					"1": "1 fila seleccionada",
					"_": "%d filas seleccionadas"
				}
			},
			"thousands": ".",
			"datetime": {
				"previous": "Anterior",
				"hours": "Horas",
				"minutes": "Minutos",
				"seconds": "Segundos",
				"unknown": "-",
				"amPm": [
					"AM",
					"PM"
				],
				"months": {
					"0": "Enero",
					"1": "Febrero",
					"10": "Noviembre",
					"11": "Diciembre",
					"2": "Marzo",
					"3": "Abril",
					"4": "Mayo",
					"5": "Junio",
					"6": "Julio",
					"7": "Agosto",
					"8": "Septiembre",
					"9": "Octubre"
				},
				"weekdays": {
					"0": "Dom",
					"1": "Lun",
					"2": "Mar",
					"4": "Jue",
					"5": "Vie",
					"3": "Mié",
					"6": "Sáb"
				},
				"next": "Próximo"
			},
			"editor": {
				"close": "Cerrar",
				"create": {
					"button": "Nuevo",
					"title": "Crear Nuevo Registro",
					"submit": "Crear"
				},
				"edit": {
					"button": "Editar",
					"title": "Editar Registro",
					"submit": "Actualizar"
				},
				"remove": {
					"button": "Eliminar",
					"title": "Eliminar Registro",
					"submit": "Eliminar",
					"confirm": {
						"_": "¿Está seguro de que desea eliminar %d filas?",
						"1": "¿Está seguro de que desea eliminar 1 fila?"
					}
				},
				"error": {
					"system": "Ha ocurrido un error en el sistema (<a target=\"\\\" rel=\"\\ nofollow\" href=\"\\\">Más información&lt;\\\/a&gt;).<\/a>"
				},
				"multi": {
					"title": "Múltiples Valores",
					"restore": "Deshacer Cambios",
					"noMulti": "Este registro puede ser editado individualmente, pero no como parte de un grupo.",
					"info": "Los elementos seleccionados contienen diferentes valores para este registro. Para editar y establecer todos los elementos de este registro con el mismo valor, haga clic o pulse aquí, de lo contrario conservarán sus valores individuales."
				}
			},
			"info": "Mostrando desde _START_ hasta _END_ de un total de _TOTAL_ registros",
			"stateRestore": {
				"creationModal": {
					"button": "Crear",
					"name": "Nombre:",
					"order": "Clasificación",
					"paging": "Paginación",
					"select": "Seleccionar",
					"columns": {
						"search": "Búsqueda de Columna",
						"visible": "Visibilidad de Columna"
					},
					"title": "Crear Nuevo Estado",
					"toggleLabel": "Incluir:",
					"scroller": "Posición de desplazamiento",
					"search": "Búsqueda",
					"searchBuilder": "Búsqueda avanzada"
				},
				"removeJoiner": "y",
				"removeSubmit": "Eliminar",
				"renameButton": "Cambiar Nombre",
				"duplicateError": "Ya existe un Estado con este nombre.",
				"emptyStates": "No hay Estados guardados",
				"removeTitle": "Remover Estado",
				"renameTitle": "Cambiar Nombre Estado",
				"emptyError": "El nombre no puede estar vacío.",
				"removeConfirm": "¿Seguro que quiere eliminar %s?",
				"removeError": "Error al eliminar el Estado",
				"renameLabel": "Nuevo nombre para %s:"
			},
			"infoThousands": "."
		},
	});

	const tblHistorialCanjes = $('#tblHistorialCanjes').DataTable({
		// Configuración inicial
		lengthMenu: [[5, 10, 50, 100, 500, -1], [5, 10, 50, 100, 500, "Todos"]],
		pageLength: 10, 
		dom: "Blifrtp", //B:buttons f:filter r:processing t:table
						//i:info l:length ("Mostrar n registros") p:paging
		buttons: [
			{   extend: "excelHtml5",
				text: "<i class='fa-solid fa-file-excel'></i>",
				titleAttr: "Exportar todas las filas a excel", //tooltip
				exportOptions: {
					columns: [0, 1, 2, 3, 4, 5, 6, 7, 8], // Exportar solo columnas específicas
				},
				filename: `Club de Técnicos DIMACOF-Listado de Canjes-${obtenerFechaHoraFormateadaExportaciones()}`, 
				title: 'Club de Técnicos DIMACOF-Listado de Canjes',
				action: function (e, dt, node, config) {
					// Guardar el valor actual de `pageLength`
					const currentPageLength = tblHistorialCanjes.page.len();
	
					// Cambiar a mostrar todas las filas
					tblHistorialCanjes.page.len(-1).draw(); 
	
					loadingModal.classList.add('show');

					// Esperar a que la tabla se redibuje
					tblHistorialCanjes.one('draw', function () {
						loadingModal.classList.remove('show');
						// Exportar después de que la tabla esté cargada
						$.fn.dataTable.ext.buttons.excelHtml5.action.call(this, e, dt, node, config);
						// Restaurar el valor original de `pageLength`
						tblHistorialCanjes.page.len(currentPageLength).draw();
					});
				},
			},  
			{   extend: "pdfHtml5",
				text: "<i class='fa-solid fa-file-pdf'></i>",
				titleAttr: "Exportar a PDF", //tooltip
				orientation: 'landscape',
				exportOptions: {
					columns: [0, 1, 2, 3, 4, 5, 6, 7, 8], 
				},
				filename: `Club de Técnicos DIMACOF-Listado de Canjes-${obtenerFechaHoraFormateadaExportaciones()}`, 
				title: 'Club de Técnicos DIMACOF - Listado de Canjes',
				action: function (e, dt, node, config) {
					window.open(baseUrlMAIN + '/dashboard-canjes/export-pdf', '_blank');
				}
			},  
		],

		// Configurando el idioma
		language: {
			"processing": "Procesando...",
			"lengthMenu": "Mostrar _MENU_ registros",
			"zeroRecords": "No se encontraron canjes",
			"emptyTable": "No se encontraron canjes",
			"infoEmpty": "Mostrando registros del 0 al 0 de un total de 0 registros",
			"infoFiltered": "(filtrado de un total de _MAX_ registros)",
			"search": "<span class='material-symbols-outlined'>search</span>",
			"searchPlaceholder": "Buscar Código/Fecha de canje", // Placeholder para el campo de búsqueda
			"loadingRecords": "Cargando...",
			"paginate": {
				"first": "Primero",
				"last": "Último",
				"next": "Siguiente",
				"previous": "Anterior"
			},
			"aria": {
				"sortAscending": ": Activar para ordenar la columna de manera ascendente",
				"sortDescending": ": Activar para ordenar la columna de manera descendente"
			},
			"buttons": {
				"copy": "Copiar",
				"colvis": "Visibilidad",
				"collection": "Colección",
				"colvisRestore": "Restaurar visibilidad",
				"copyKeys": "Presione ctrl o u2318 + C para copiar los datos de la tabla al portapapeles del sistema. <br \/> <br \/> Para cancelar, haga clic en este mensaje o presione escape.",
				"copySuccess": {
					"1": "Copiada 1 fila al portapapeles",
					"_": "Copiadas %ds fila al portapapeles"
				},
				"copyTitle": "Copiar al portapapeles",
				"csv": "CSV",
				"excel": "Excel",
				"pageLength": {
					"-1": "Mostrar todas las filas",
					"_": "Mostrar %d filas"
				},
				"pdf": "PDF",
				"print": "Imprimir",
				"renameState": "Cambiar nombre",
				"updateState": "Actualizar",
				"createState": "Crear Estado",
				"removeAllStates": "Remover Estados",
				"removeState": "Remover",
				"savedStates": "Estados Guardados",
				"stateRestore": "Estado %d"
			},
			"autoFill": {
				"cancel": "Cancelar",
				"fill": "Rellene todas las celdas con <i>%d<\/i>",
				"fillHorizontal": "Rellenar celdas horizontalmente",
				"fillVertical": "Rellenar celdas verticalmente"
			},
			"decimal": ",",
			"searchBuilder": {
				"add": "Añadir condición",
				"button": {
					"0": "Constructor de búsqueda",
					"_": "Constructor de búsqueda (%d)"
				},
				"clearAll": "Borrar todo",
				"condition": "Condición",
				"conditions": {
					"date": {
						"before": "Antes",
						"between": "Entre",
						"empty": "Vacío",
						"equals": "Igual a",
						"notBetween": "No entre",
						"not": "Diferente de",
						"after": "Después",
						"notEmpty": "No Vacío"
					},
					"number": {
						"between": "Entre",
						"equals": "Igual a",
						"gt": "Mayor a",
						"gte": "Mayor o igual a",
						"lt": "Menor que",
						"lte": "Menor o igual que",
						"notBetween": "No entre",
						"notEmpty": "No vacío",
						"not": "Diferente de",
						"empty": "Vacío"
					},
					"string": {
						"contains": "Contiene",
						"empty": "Vacío",
						"endsWith": "Termina en",
						"equals": "Igual a",
						"startsWith": "Empieza con",
						"not": "Diferente de",
						"notContains": "No Contiene",
						"notStartsWith": "No empieza con",
						"notEndsWith": "No termina con",
						"notEmpty": "No Vacío"
					},
					"array": {
						"not": "Diferente de",
						"equals": "Igual",
						"empty": "Vacío",
						"contains": "Contiene",
						"notEmpty": "No Vacío",
						"without": "Sin"
					}
				},
				"data": "Data",
				"deleteTitle": "Eliminar regla de filtrado",
				"leftTitle": "Criterios anulados",
				"logicAnd": "Y",
				"logicOr": "O",
				"rightTitle": "Criterios de sangría",
				"title": {
					"0": "Constructor de búsqueda",
					"_": "Constructor de búsqueda (%d)"
				},
				"value": "Valor"
			},
			"searchPanes": {
				"clearMessage": "Borrar todo",
				"collapse": {
					"0": "Paneles de búsqueda",
					"_": "Paneles de búsqueda (%d)"
				},
				"count": "{total}",
				"countFiltered": "{shown} ({total})",
				"emptyPanes": "Sin paneles de búsqueda",
				"loadMessage": "Cargando paneles de búsqueda",
				"title": "Filtros Activos - %d",
				"showMessage": "Mostrar Todo",
				"collapseMessage": "Colapsar Todo"
			},
			"select": {
				"cells": {
					"1": "1 celda seleccionada",
					"_": "%d celdas seleccionadas"
				},
				"columns": {
					"1": "1 columna seleccionada",
					"_": "%d columnas seleccionadas"
				},
				"rows": {
					"1": "1 fila seleccionada",
					"_": "%d filas seleccionadas"
				}
			},
			"thousands": ".",
			"datetime": {
				"previous": "Anterior",
				"hours": "Horas",
				"minutes": "Minutos",
				"seconds": "Segundos",
				"unknown": "-",
				"amPm": [
					"AM",
					"PM"
				],
				"months": {
					"0": "Enero",
					"1": "Febrero",
					"10": "Noviembre",
					"11": "Diciembre",
					"2": "Marzo",
					"3": "Abril",
					"4": "Mayo",
					"5": "Junio",
					"6": "Julio",
					"7": "Agosto",
					"8": "Septiembre",
					"9": "Octubre"
				},
				"weekdays": {
					"0": "Dom",
					"1": "Lun",
					"2": "Mar",
					"4": "Jue",
					"5": "Vie",
					"3": "Mié",
					"6": "Sáb"
				},
				"next": "Próximo"
			},
			"editor": {
				"close": "Cerrar",
				"create": {
					"button": "Nuevo",
					"title": "Crear Nuevo Registro",
					"submit": "Crear"
				},
				"edit": {
					"button": "Editar",
					"title": "Editar Registro",
					"submit": "Actualizar"
				},
				"remove": {
					"button": "Eliminar",
					"title": "Eliminar Registro",
					"submit": "Eliminar",
					"confirm": {
						"_": "¿Está seguro de que desea eliminar %d filas?",
						"1": "¿Está seguro de que desea eliminar 1 fila?"
					}
				},
				"error": {
					"system": "Ha ocurrido un error en el sistema (<a target=\"\\\" rel=\"\\ nofollow\" href=\"\\\">Más información&lt;\\\/a&gt;).<\/a>"
				},
				"multi": {
					"title": "Múltiples Valores",
					"restore": "Deshacer Cambios",
					"noMulti": "Este registro puede ser editado individualmente, pero no como parte de un grupo.",
					"info": "Los elementos seleccionados contienen diferentes valores para este registro. Para editar y establecer todos los elementos de este registro con el mismo valor, haga clic o pulse aquí, de lo contrario conservarán sus valores individuales."
				}
			},
			"info": "Mostrando desde _START_ hasta _END_ de un total de _TOTAL_ registros",
			"stateRestore": {
				"creationModal": {
					"button": "Crear",
					"name": "Nombre:",
					"order": "Clasificación",
					"paging": "Paginación",
					"select": "Seleccionar",
					"columns": {
						"search": "Búsqueda de Columna",
						"visible": "Visibilidad de Columna"
					},
					"title": "Crear Nuevo Estado",
					"toggleLabel": "Incluir:",
					"scroller": "Posición de desplazamiento",
					"search": "Búsqueda",
					"searchBuilder": "Búsqueda avanzada"
				},
				"removeJoiner": "y",
				"removeSubmit": "Eliminar",
				"renameButton": "Cambiar Nombre",
				"duplicateError": "Ya existe un Estado con este nombre.",
				"emptyStates": "No hay Estados guardados",
				"removeTitle": "Remover Estado",
				"renameTitle": "Cambiar Nombre Estado",
				"emptyError": "El nombre no puede estar vacío.",
				"removeConfirm": "¿Seguro que quiere eliminar %s?",
				"removeError": "Error al eliminar el Estado",
				"renameLabel": "Nuevo nombre para %s:"
			},
			"infoThousands": "."
		},

		// Ajax
		processing: true,
		serverSide: true,
		responsive: true,

		ajax: {
			url: "tblHistorialCanjesData",
			type: "POST",
			dataType: "json",
			error: function(xhr, status, error) {
				if (xhr.status !== 204) {
					console.log("AJAX");
					console.error("status: " + status);
					console.error("error: " + error);
					console.error(xhr.status);
					location.reload();
				}
			}
		},

		/*
			index
			idCanje
			fechaHora_Canje
			idVentaIntermediada
			puntosComprobante_Canje
			fechaHoraEmision_VentaIntermediada
			diasTranscurridos_Canje
			idTecnico
			nombreTecnico
			puntosCanjeados_Canje
			puntosRestantes_Canje

			// Campos compuestos
			idVentaIntermediada_puntosGenerados
			nombreTecnico_idTecnico
		*/

		columns: [
			{data: 'index', name: 'index', class: 'celda-centered'},
			{data: 'idCanje', name: 'idCanje', class: 'celda-centered idCanje'},
			{data: 'fechaHora_Canje', name: 'fechaHora_Canje', class: 'celda-centered'},
			{
				data: null, // Indica que no se tomará un solo campo
				render: function (data) {
					return `${data.idVentaIntermediada} <br>
							<small>Puntos Generados: ${data.puntosComprobante_Canje}</small>`;
				},
				name: 'idVentaIntermediada_puntosGenerados', // Nombre para búsquedas
			},
			{data: 'fechaHoraEmision_VentaIntermediada', name: 'fechaHoraEmision_VentaIntermediada', class: 'celda-centered'},
			{data: 'diasTranscurridos_Canje', name: 'diasTranscurridos_Canje', class: 'celda-centered'},
			{
				data: null, // Indica que no se tomará un solo campo
				render: function (data) {
					return `${data.nombreTecnico} <br>
							<small>DNI: ${data.idTecnico}</small>`;
				},
				name: 'nombreTecnico_idTecnico', 
			},
			{
				data: null,
				render: function (data) {
					return `<small>P. Actuales:</small> ${data.puntosActuales_Canje} <br>
							<small>P. Canjeados:</small> ${data.puntosCanjeados_Canje} <br>
							<small>P. Restantes:</small> ${data.puntosRestantes_Canje}`;
				},
				name: 'puntosActuales_PuntosCanjeados_PuntosRestantes_Canje',
			},
			{data: 'actions', name: 'actions', class: 'celda-centered', orderable: false},
		],
	});

	window.tblSolicitudesAppCanje  = $('#tblSolicitudesAppCanje').DataTable({
		// Configuración inicial
		lengthMenu: [[10, 10, 50, 100, 500, -1], [10, 10, 50, 100, 500, "Todos"]],
		pageLength: 10, 
		dom: "Blifrtp", //B:buttons f:filter r:processing t:table
						//i:info l:length ("Mostrar n registros") p:paging
		buttons: [
			{   extend: "excelHtml5",
				text: "<i class='fa-solid fa-file-excel'></i>",
				titleAttr: "Exportar todas las filas a excel",
				exportOptions: {
					columns: [0, 1, 2, 3, 4, 5], // Exportar solo columnas específicas
				},
				title: `Club de Técnicos DIMACOF-Listado de Solicitudes de Canjes-${obtenerFechaHoraFormateadaExportaciones()}`, 
				title: 'Club de Técnicos DIMACOF-Listado de Solicitudes de Canjes',
				action: function (e, dt, node, config) {
					// Guardar el valor actual de `pageLength`
					const currentPageLength = tblSolicitudesAppCanje.page.len();
	
					// Cambiar a mostrar todas las filas
					tblSolicitudesAppCanje.page.len(-1).draw(); 
	
					loadingModal.classList.add('show');

					// Esperar a que la tabla se redibuje
					tblSolicitudesAppCanje.one('draw', function () {
						loadingModal.classList.remove('show');
						// Exportar después de que la tabla esté cargada
						$.fn.dataTable.ext.buttons.excelHtml5.action.call(this, e, dt, node, config);
						// Restaurar el valor original de `pageLength`
						tblSolicitudesAppCanje.page.len(currentPageLength).draw();
					});
				},
			},  
			{   extend: "pdfHtml5",
				text: "<i class='fa-solid fa-file-pdf'></i>",
				titleAttr: "Exportar a PDF",
				orientation: 'landscape',
				exportOptions: {
					columns: [0, 1, 2, 3, 4, 5], // Exportar solo columnas específicas
				},
				filename: `Club de Técnicos DIMACOF-Listado de Solicitudes de Canjes-${obtenerFechaHoraFormateadaExportaciones()}`, 
				title: 'Club de Técnicos DIMACOF - Listado de Solicitudes de Canjes',
				action: function (e, dt, node, config) {
					window.open(baseUrlMAIN + '/dashboard-solicitudesCanjes/export-pdf', '_blank');
				}
			},  
		],

		// Configurando el idioma
		language: {
			"processing": "Procesando...",
			"lengthMenu": "Mostrar _MENU_ registros",
			"zeroRecords": "No se encontraron solicitudes de canjes",
			"emptyTable": "No se encontraron solicitudes de canjes",
			"infoEmpty": "Mostrando registros del 0 al 0 de un total de 0 registros",
			"infoFiltered": "(filtrado de un total de _MAX_ registros)",
			"search": "<span class='material-symbols-outlined'>search</span>",
			"searchPlaceholder": "Buscar Código/Fecha de canje", // Placeholder para el campo de búsqueda
			"loadingRecords": "Cargando...",
			"paginate": {
				"first": "Primero",
				"last": "Último",
				"next": "Siguiente",
				"previous": "Anterior"
			},
			"aria": {
				"sortAscending": ": Activar para ordenar la columna de manera ascendente",
				"sortDescending": ": Activar para ordenar la columna de manera descendente"
			},
			"buttons": {
				"copy": "Copiar",
				"colvis": "Visibilidad",
				"collection": "Colección",
				"colvisRestore": "Restaurar visibilidad",
				"copyKeys": "Presione ctrl o u2318 + C para copiar los datos de la tabla al portapapeles del sistema. <br \/> <br \/> Para cancelar, haga clic en este mensaje o presione escape.",
				"copySuccess": {
					"1": "Copiada 1 fila al portapapeles",
					"_": "Copiadas %ds fila al portapapeles"
				},
				"copyTitle": "Copiar al portapapeles",
				"csv": "CSV",
				"excel": "Excel",
				"pageLength": {
					"-1": "Mostrar todas las filas",
					"_": "Mostrar %d filas"
				},
				"pdf": "PDF",
				"print": "Imprimir",
				"renameState": "Cambiar nombre",
				"updateState": "Actualizar",
				"createState": "Crear Estado",
				"removeAllStates": "Remover Estados",
				"removeState": "Remover",
				"savedStates": "Estados Guardados",
				"stateRestore": "Estado %d"
			},
			"autoFill": {
				"cancel": "Cancelar",
				"fill": "Rellene todas las celdas con <i>%d<\/i>",
				"fillHorizontal": "Rellenar celdas horizontalmente",
				"fillVertical": "Rellenar celdas verticalmente"
			},
			"decimal": ",",
			"searchBuilder": {
				"add": "Añadir condición",
				"button": {
					"0": "Constructor de búsqueda",
					"_": "Constructor de búsqueda (%d)"
				},
				"clearAll": "Borrar todo",
				"condition": "Condición",
				"conditions": {
					"date": {
						"before": "Antes",
						"between": "Entre",
						"empty": "Vacío",
						"equals": "Igual a",
						"notBetween": "No entre",
						"not": "Diferente de",
						"after": "Después",
						"notEmpty": "No Vacío"
					},
					"number": {
						"between": "Entre",
						"equals": "Igual a",
						"gt": "Mayor a",
						"gte": "Mayor o igual a",
						"lt": "Menor que",
						"lte": "Menor o igual que",
						"notBetween": "No entre",
						"notEmpty": "No vacío",
						"not": "Diferente de",
						"empty": "Vacío"
					},
					"string": {
						"contains": "Contiene",
						"empty": "Vacío",
						"endsWith": "Termina en",
						"equals": "Igual a",
						"startsWith": "Empieza con",
						"not": "Diferente de",
						"notContains": "No Contiene",
						"notStartsWith": "No empieza con",
						"notEndsWith": "No termina con",
						"notEmpty": "No Vacío"
					},
					"array": {
						"not": "Diferente de",
						"equals": "Igual",
						"empty": "Vacío",
						"contains": "Contiene",
						"notEmpty": "No Vacío",
						"without": "Sin"
					}
				},
				"data": "Data",
				"deleteTitle": "Eliminar regla de filtrado",
				"leftTitle": "Criterios anulados",
				"logicAnd": "Y",
				"logicOr": "O",
				"rightTitle": "Criterios de sangría",
				"title": {
					"0": "Constructor de búsqueda",
					"_": "Constructor de búsqueda (%d)"
				},
				"value": "Valor"
			},
			"searchPanes": {
				"clearMessage": "Borrar todo",
				"collapse": {
					"0": "Paneles de búsqueda",
					"_": "Paneles de búsqueda (%d)"
				},
				"count": "{total}",
				"countFiltered": "{shown} ({total})",
				"emptyPanes": "Sin paneles de búsqueda",
				"loadMessage": "Cargando paneles de búsqueda",
				"title": "Filtros Activos - %d",
				"showMessage": "Mostrar Todo",
				"collapseMessage": "Colapsar Todo"
			},
			"select": {
				"cells": {
					"1": "1 celda seleccionada",
					"_": "%d celdas seleccionadas"
				},
				"columns": {
					"1": "1 columna seleccionada",
					"_": "%d columnas seleccionadas"
				},
				"rows": {
					"1": "1 fila seleccionada",
					"_": "%d filas seleccionadas"
				}
			},
			"thousands": ".",
			"datetime": {
				"previous": "Anterior",
				"hours": "Horas",
				"minutes": "Minutos",
				"seconds": "Segundos",
				"unknown": "-",
				"amPm": [
					"AM",
					"PM"
				],
				"months": {
					"0": "Enero",
					"1": "Febrero",
					"10": "Noviembre",
					"11": "Diciembre",
					"2": "Marzo",
					"3": "Abril",
					"4": "Mayo",
					"5": "Junio",
					"6": "Julio",
					"7": "Agosto",
					"8": "Septiembre",
					"9": "Octubre"
				},
				"weekdays": {
					"0": "Dom",
					"1": "Lun",
					"2": "Mar",
					"4": "Jue",
					"5": "Vie",
					"3": "Mié",
					"6": "Sáb"
				},
				"next": "Próximo"
			},
			"editor": {
				"close": "Cerrar",
				"create": {
					"button": "Nuevo",
					"title": "Crear Nuevo Registro",
					"submit": "Crear"
				},
				"edit": {
					"button": "Editar",
					"title": "Editar Registro",
					"submit": "Actualizar"
				},
				"remove": {
					"button": "Eliminar",
					"title": "Eliminar Registro",
					"submit": "Eliminar",
					"confirm": {
						"_": "¿Está seguro de que desea eliminar %d filas?",
						"1": "¿Está seguro de que desea eliminar 1 fila?"
					}
				},
				"error": {
					"system": "Ha ocurrido un error en el sistema (<a target=\"\\\" rel=\"\\ nofollow\" href=\"\\\">Más información&lt;\\\/a&gt;).<\/a>"
				},
				"multi": {
					"title": "Múltiples Valores",
					"restore": "Deshacer Cambios",
					"noMulti": "Este registro puede ser editado individualmente, pero no como parte de un grupo.",
					"info": "Los elementos seleccionados contienen diferentes valores para este registro. Para editar y establecer todos los elementos de este registro con el mismo valor, haga clic o pulse aquí, de lo contrario conservarán sus valores individuales."
				}
			},
			"info": "Mostrando desde _START_ hasta _END_ de un total de _TOTAL_ registros",
			"stateRestore": {
				"creationModal": {
					"button": "Crear",
					"name": "Nombre:",
					"order": "Clasificación",
					"paging": "Paginación",
					"select": "Seleccionar",
					"columns": {
						"search": "Búsqueda de Columna",
						"visible": "Visibilidad de Columna"
					},
					"title": "Crear Nuevo Estado",
					"toggleLabel": "Incluir:",
					"scroller": "Posición de desplazamiento",
					"search": "Búsqueda",
					"searchBuilder": "Búsqueda avanzada"
				},
				"removeJoiner": "y",
				"removeSubmit": "Eliminar",
				"renameButton": "Cambiar Nombre",
				"duplicateError": "Ya existe un Estado con este nombre.",
				"emptyStates": "No hay Estados guardados",
				"removeTitle": "Remover Estado",
				"renameTitle": "Cambiar Nombre Estado",
				"emptyError": "El nombre no puede estar vacío.",
				"removeConfirm": "¿Seguro que quiere eliminar %s?",
				"removeError": "Error al eliminar el Estado",
				"renameLabel": "Nuevo nombre para %s:"
			},
			"infoThousands": "."
		},

		// Ajax
		processing: true,
		serverSide: true,
		responsive: true,

		ajax: {
			url: "tablaSolicitudCanjeData",
			type: "POST",
			dataType: "json",
			error: function(xhr, status, error) {
				if (xhr.status !== 204) {
					console.log("AJAX");
					console.error("status: " + status);
					console.error("error: " + error);
					console.error(xhr.status);
					location.reload();
				}
			}
		},

		"createdRow": function(row, data){
			let tdEstadoSolicitudCanje = row.children[5];
			let span = document.createElement('span');
			span.classList.add('estado__span-' + (data['idEstadoSolicitudCanje']));
			span.innerHTML = data['nombre_EstadoSolicitudCanje'];
			tdEstadoSolicitudCanje.innerHTML = '';
			tdEstadoSolicitudCanje.appendChild(span);
		},

		/*
			index
			idSolicitudCanje
			fechaHora_SolicitudCanje
			nombreTecnico
			idTecnico
			idVentaIntermediada
			puntosComprobante_SolicitudCanje
			nombre_EstadoSolicitudCanje

			// Campos compuestos
			nombreTecnico_idTecnico
			idVentaIntermediada_puntosGenerados
		*/

		columns: [
			{data: 'index', name: 'index', class: 'celda-centered'},
			{data: 'idSolicitudCanje', name: 'idSolicitudCanje', class: 'celda-centered idSolicitudCanje'},
			{data: 'fechaHora_SolicitudCanje', name: 'fechaHora_SolicitudCanje', class: 'celda-centered'},
			{
				data: null, // Indica que no se tomará un solo campo
				render: function (data) {
					return `${data.nombreTecnico} <br>
							<small>DNI: ${data.idTecnico}</small>`;
				},
				name: 'nombreTecnico_idTecnico', 
			},
			{
				data: null, // Indica que no se tomará un solo campo
				render: function (data) {
					return `${data.idVentaIntermediada} <br>
							<small>Puntos Generados: ${data.puntosComprobante_SolicitudCanje}</small>`;
				},
				name: 'idVentaIntermediada_puntosGenerados', // Nombre para búsquedas,
			},
			{data: 'nombre_EstadoSolicitudCanje', name: 'nombre_EstadoSolicitudCanje', class: 'celda-centered estado__celda'},
			{data: 'details', name: 'details', class: 'celda-centered', orderable: false},
			{data: 'actions', name: 'actions', class: 'celda-centered', orderable: false},
		],
	});
});
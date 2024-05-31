<div class="container">
    <div class="row g-4">
        <!-- Formulario de Tareas -->
        <div class="col-md-6">
            <form class="bg-light p-4 rounded-lg m-3 bg-primary" style="
    background: linear-gradient(
        to right,
        #4c24ee,
        #4624ee,
        #245aee,
        #24a7ee
    );
" method="POST" action="/crear-tarea">
                <h2 class="text-lg font-semibold mb-4">Crear Tarea</h2>
                <div class="mb-3">
                    <label for="descripcion" class="form-label">Descripcion:</label>
                    <input type="text" id="descripcion" name="descripcion" class="form-control" value="<?php echo isset($form_data['descripcion']) ? s($form_data['descripcion']) : ''; ?>">
                </div>

                <div class="mb-3">
                    <label for="fecha_inicio" class="form-label">Fecha Inicio:</label>
                    <input type="date" id="fecha_inicio" name="fecha_inicio" class="form-control" value="<?php echo isset($form_data['fecha_inicio']) ? s($form_data['fecha_inicio']) : ''; ?>">
                </div>

                <div class="mb-3">
                    <label for="fecha_final" class="form-label">Fecha Final:</label>
                    <input type="date" id="fecha_final" name="fecha_final" class="form-control" value="<?php echo isset($form_data['fecha_final']) ? s($form_data['fecha_final']) : ''; ?>">
                </div>

                <div class="mb-3">
                    <label for="id_actividad" class="form-label">ID Actividad:</label>
                    <input type="number" id="id_actividad" name="id_actividad" class="form-control" value="<?php echo isset($form_data['id_actividad']) ? s($form_data['id_actividad']) : ''; ?>">
                </div>

                <div class="mb-3">
                    <label for="presupuesto" class="form-label">Presupuesto:</label>
                    <input type="number" id="presupuesto" name="presupuesto" class="form-control" value="<?php echo isset($form_data['presupuesto']) ? s($form_data['presupuesto']) : ''; ?>">
                </div>

                <div class="d-grid mb-2">
                    <input type="submit" value="Crear Actividad" class="btn btn-success">
                </div>
                <div class="d-grid">
                    <button id="actualizarTarea" class="btn btn-info">Actualizar Tarea</button>
                </div>
            </form>

        </div>
        <!-- Tablas -->
        <div class="col-md-6 pt-4">
            <!-- Tabla Actividades-->
            <div class="table-responsive" style="max-height: 600px;">
                <table class="table table-striped table-hover">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Descripcion</th>
                            <th>Fecha inicio</th>
                            <th>Fecha final</th>
                            <th>ID Proyecto</th>
                            <th>Responsable</th>
                            <th>Estado</th>
                            <th>Presupuesto</th>
                        </tr>
                    </thead>
                    <tbody id="actividades-tbody">
                        <!-- Los datos de los proyectos se insertarán aquí -->
                    </tbody>
                </table>
            </div>

            <!-- Tabla Tareas-->
            <div class="table-responsive" style="max-height: 600px;">
                <table class="table table-striped table-hover">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Descripcion</th>
                            <th>Fecha inicio</th>
                            <th>Fecha final</th>
                            <th>ID Actividad</th>
                            <th>Estado</th>
                            <th>Presupuesto</th>
                        </tr>
                    </thead>
                    <tbody id="tareas-tbody">
                        <!-- Los datos de los tareas se insertarán aquí -->
                    </tbody>
                </table>
            </div>

        </div>
    </div>
</div>


<script>
    async function fetchAndRenderData() {
        try {
            const [actividadesResponse, tareasResponse] = await Promise.all([
                fetch('/obteneractividades', {
                    method: 'GET',
                    headers: {
                        'Content-Type': 'application/json'
                    }
                }),
                fetch('/obtenertareas', {
                    method: 'GET',
                    headers: {
                        'Content-Type': 'application/json'
                    }
                }),
            ]);

            if (actividadesResponse.ok && tareasResponse.ok) {
                const [actividades, tareas] = await Promise.all([
                    actividadesResponse.json(),
                    tareasResponse.json()
                ]);

                const actividadesTbody = document.getElementById('actividades-tbody');
                actividadesTbody.innerHTML = '';
                actividades.forEach(actividad => {
                    const row = document.createElement('tr');

                    const idCell = document.createElement('td');
                    idCell.textContent = actividad.id;
                    row.appendChild(idCell);

                    const descripcionCell = document.createElement('td');
                    descripcionCell.textContent = actividad.descripcion;
                    row.appendChild(descripcionCell);

                    const fechainicioCell = document.createElement('td');
                    fechainicioCell.textContent = actividad.fecha_inicio;
                    row.appendChild(fechainicioCell);

                    const fechafinalCell = document.createElement('td');
                    fechafinalCell.textContent = actividad.fecha_final;
                    row.appendChild(fechafinalCell);

                    const idproyectoCell = document.createElement('td');
                    idproyectoCell.textContent = actividad.id_proyecto;
                    row.appendChild(idproyectoCell);

                    const responsableCell = document.createElement('td');
                    responsableCell.textContent = actividad.responsable;
                    row.appendChild(responsableCell);

                    const estadoCell = document.createElement('td');
                    estadoCell.textContent = actividad.estado;
                    row.appendChild(estadoCell);

                    const presupuestoCell = document.createElement('td');
                    presupuestoCell.textContent = actividad.presupuesto;
                    row.appendChild(presupuestoCell);

                    actividadesTbody.appendChild(row);
                });

                // Limpiar los cuerpos de las tablas antes de renderizar nuevos datos
                const tareasTbody = document.getElementById('tareas-tbody');
                tareasTbody.innerHTML = '';
                tareas.forEach(tarea => {
                    const row = document.createElement('tr');

                    const idCell = document.createElement('td');
                    idCell.textContent = tarea.id;
                    row.appendChild(idCell);

                    const descripcionCell = document.createElement('td');
                    descripcionCell.textContent = tarea.descripcion;
                    row.appendChild(descripcionCell);

                    const fechainicioCell = document.createElement('td');
                    fechainicioCell.textContent = tarea.fecha_inicio;
                    row.appendChild(fechainicioCell);

                    const fechafinalCell = document.createElement('td');
                    fechafinalCell.textContent = tarea.fecha_final;
                    row.appendChild(fechafinalCell);

                    const idactividadCell = document.createElement('td');
                    idactividadCell.textContent = tarea.id_actividad;
                    row.appendChild(idactividadCell);

                    const estadoCell = document.createElement('td');
                    estadoCell.textContent = tarea.estado;
                    row.appendChild(estadoCell);

                    const presupuestoCell = document.createElement('td');
                    presupuestoCell.textContent = tarea.presupuesto;
                    row.appendChild(presupuestoCell);
                    


                    tareasTbody.appendChild(row);
                });
                
            } else {
                if (!actividadesResponse.ok) {
                    console.error('Error fetching actividades:', response.statusText);
                }
                if (!tareasResponse.ok) {
                    console.error('Error fetching tareas:', proyectosResponse.statusText);
                }
            }
        } catch (error) {
            console.error('Error during fetch operations:', error);
        }
    }

    // Llamar a la función fetchAndRenderData cuando el DOM esté cargado
    document.addEventListener('DOMContentLoaded', fetchAndRenderData);

    // Configurar un intervalo para actualizar los datos cada 1 segundo
    setInterval(fetchAndRenderData, 1000);

    function setupSelect(selectId, currentState) {
        const selectElement = document.getElementById(selectId);
        selectElement.innerHTML = ''; // Limpia las opciones existentes

        // Lista de estados posibles
        const estados = ['En Proceso', 'Entregado', 'No Entregado'];

        // Crear y añadir las opciones al select
        estados.forEach(estado => {
            const option = document.createElement('option');
            option.value = estado;
            option.textContent = estado;
            if (estado === currentState) {
                option.selected = true;
            }
            selectElement.appendChild(option);
        });
    }

    function showToast(message, icon) {
        const Toast = Swal.mixin({
            toast: true,
            position: "top-end",
            showConfirmButton: false,
            timer: 3000,
            timerProgressBar: true,
            didOpen: (toast) => {
                toast.onmouseenter = Swal.stopTimer;
                toast.onmouseleave = Swal.resumeTimer;
            }
        });
        Toast.fire({
            icon: icon,
            title: message
        });
    }

    document.getElementById('actualizarTarea').addEventListener('click', async function(event) {
        event.preventDefault();

        const {
            value: searchQuery
        } = await Swal.fire({
            title: 'Buscar Tarea',
            input: 'text',
            inputPlaceholder: 'Ingrese la ID de la Tarea...',
            showCancelButton: true,
            inputValidator: (value) => {
                if (!value) {
                    return 'Por favor ingrese una ID';
                }
            }
        });

        if (searchQuery) {
            try {
                const response = await fetch('/obtenertareas', {
                    method: 'GET',
                    headers: {
                        'Content-Type': 'application/json'
                    }
                });

                const tareas = await response.json();
                const tarea = tareas.find(tar => tar.id.toString() === searchQuery);

                if (tarea) {
                    const tableContent = `
                <div class="max-w-md mx-auto bg-white shadow-md rounded my-6 dark:bg-gray-800 d-flex justify-content-center">
                    <div class="overflow-x-auto">
                        <table class="min-w-full bg-blue-100 dark:bg-blue-900 border border-blue-500 dark:border-blue-700">
                            <thead>
                                <tr>
                                    <th class="px-6 py-3 border-b-2 border-blue-500 dark:border-blue-700 text-left text-sm leading-4 font-medium text-blue-600 dark:text-blue-300 uppercase">ID</th>
                                    <td class="px-6 py-4 whitespace-no-wrap border-b border-blue-500 dark:border-blue-700 text-blue-800 dark:text-blue-200">${tarea.id}</td>
                                </tr>
                                <tr>
                                    <th class="px-6 py-3 border-b-2 border-blue-500 dark:border-blue-700 text-left text-sm leading-4 font-medium text-blue-600 dark:text-blue-300 uppercase">Descripcion</th>
                                    <td class="px-6 py-4 whitespace-no-wrap border-b border-blue-500 dark:border-blue-700 text-blue-800 dark:text-blue-200">${tarea.descripcion}</td>
                                </tr>
                                <tr>
                                    <th class="px-6 py-3 border-b-2 border-blue-500 dark:border-blue-700 text-left text-sm leading-4 font-medium text-blue-600 dark:text-blue-300 uppercase">Fecha_inicio</th>
                                    <td class="px-6 py-4 whitespace-no-wrap border-b border-blue-500 dark:border-blue-700 text-blue-800 dark:text-blue-200">${tarea.fecha_inicio}</td>
                                </tr>
                                <tr>
                                    <th class="px-6 py-3 border-b-2 border-blue-500 dark:border-blue-700 text-left text-sm leading-4 font-medium text-blue-600 dark:text-blue-300 uppercase">Fecha_final</th>
                                    <td class="px-6 py-4 whitespace-no-wrap border-b border-blue-500 dark:border-blue-700 text-blue-800 dark:text-blue-200">${tarea.fecha_final}</td>
                                </tr>
                                <tr>
                                    <th class="px-6 py-3 border-b-2 border-blue-500 dark:border-blue-700 text-left text-sm leading-4 font-medium text-blue-600 dark:text-blue-300 uppercase">ID Actividad</th>
                                    <td class="px-6 py-4 whitespace-no-wrap border-b border-blue-500 dark:border-blue-700 text-blue-800 dark:text-blue-200">${tarea.id_actividad}</td>
                                </tr>
                                <tr>
                                    <th class="px-6 py-3 border-b-2 border-blue-500 dark:border-blue-700 text-left text-sm leading-4 font-medium text-blue-600 dark:text-blue-300 uppercase">Estado</th>
                                    <td class="px-6 py-4 whitespace-no-wrap border-b border-blue-500 dark:border-blue-700 text-blue-800 dark:text-blue-200">${tarea.estado}</td>
                                </tr>
                                <tr>
                                    <th class="px-6 py-3 border-b-2 border-blue-500 dark:border-blue-700 text-left text-sm leading-4 font-medium text-blue-600 dark:text-blue-300 uppercase">Presupuesto</th>
                                    <td class="px-6 py-4 whitespace-no-wrap border-b border-blue-500 dark:border-blue-700 text-blue-800 dark:text-blue-200">${tarea.presupuesto}</td>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
                `;

                    Swal.fire({
                        title: 'Coincidencia Encontrada',
                        html: tableContent,
                        showCancelButton: true,
                        confirmButtonColor: "#3085d6",
                        cancelButtonColor: "#d33",
                        confirmButtonText: "Sí, deseo modificar!"
                    }).then((result) => {
                        if (result.isConfirmed) {
                            Swal.fire({
                                title: 'Modificar Tarea',
                                html: `
                            <input id="swal-id" class="swal2-input" placeholder="ID" value="${tarea.id}" readonly>
                            <input id="swal-descripcion" class="swal2-input" placeholder="Descripcion" value="${tarea.descripcion}">
                            <input type="date" id="swal-fecha_inicio" class="swal2-input" placeholder="Fecha Inicio" value="${tarea.fecha_inicio}">
                            <input type="date" id="swal-fecha_final" class="swal2-input" placeholder="Fecha Final" value="${tarea.fecha_final}">
                            <input type="number" id="swal-id_actividad" class="swal2-input" value="${tarea.id_actividad}">
                            <select id="swal-estado" class="swal2-control mt-2"></select>
                            <input type="number" id="swal-presupuesto" class="swal2-input" value="${tarea.presupuesto}">`,
                                focusConfirm: false,
                                didOpen: () => {
                                    setupSelect('swal-estado', tarea.estado);
                                },
                                preConfirm: () => {
                                    return {
                                        id: document.getElementById('swal-id').value,
                                        descripcion: document.getElementById('swal-descripcion').value,
                                        fecha_inicio: document.getElementById('swal-fecha_inicio').value,
                                        fecha_final: document.getElementById('swal-fecha_final').value,
                                        id_actividad: document.getElementById('swal-id_actividad').value,estado: document.getElementById('swal-estado').value,
                                        presupuesto: document.getElementById('swal-presupuesto').value
                                    };
                                },
                                showCancelButton: true,
                                confirmButtonText: 'Guardar Cambios',
                                cancelButtonText: 'Cancelar',
                                showLoaderOnConfirm: true,
                                allowOutsideClick: () => !Swal.isLoading(),
                            }).then(async (result) => {
                                if (result.isConfirmed) {
                                    const updatedActividad = result.value;
                                    try {
                                        const response = await fetch('/actualizartareas', {
                                            method: 'PUT',
                                            headers: {
                                                'Content-Type': 'application/json'
                                            },
                                            body: JSON.stringify(updatedActividad)
                                        });

                                        const data = await response.json();

                                        if (data.success) {
                                            showToast(data.success, 'success');
                                        } else if (data.error) {
                                            showToast(data.error, 'error');
                                        }
                                    } catch (error) {
                                        showToast('Error al actualizar la tarea', 'error');
                                    }
                                }
                            });
                        }
                    });
                } else {
                    Swal.fire({
                        title: 'No se encontró coincidencia',
                        text: 'Intente con otra ID',
                        icon: 'error'
                    });
                }
            } catch (error) {
                Swal.fire({
                    title: "Error!",
                    text: "Hubo un problema al buscar la tarea.",
                    icon: "error"
                });
            }
        }
    });

    <?php if (!empty($message)) : ?>
        showToast("<?php echo $message; ?>", "<?php echo (empty($alertas['error'])) ? 'success' : 'error'; ?>");
    <?php endif; ?>
</script>
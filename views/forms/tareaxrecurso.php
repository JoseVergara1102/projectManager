<div class="container">
    <div class="row g-4">
        <!-- Formulario de TareasxRecursos -->
        <div class="col-md-6">
            <form class="bg-light p-4 rounded-lg m-3 bg-primary" style="
    background: linear-gradient(
        to right,
        #4c24ee,
        #4624ee,
        #245aee,
        #24a7ee
    );
" method="POST" action="/crear-tareaxrecurso">
                <h2 class="text-lg font-semibold mb-4">Crear TareaxRecurso</h2>
                <div class="mb-3">
                    <label for="id_tarea" class="form-label">ID Tarea:</label>
                    <input type="number" id="id_tarea" name="id_tarea" class="form-control" value="<?php echo isset($form_data['id_tarea']) ? s($form_data['id_tarea']) : ''; ?>">
                </div>

                <div class="mb-3">
                    <label for="id_recurso" class="form-label">ID Recurso:</label>
                    <input type="number" id="id_recurso" name="id_recurso" class="form-control" value="<?php echo isset($form_data['id_recurso']) ? s($form_data['id_recurso']) : ''; ?>">
                </div>

                <div class="mb-3">
                    <label for="cantidades" class="form-label">Cantidades:</label>
                    <input type="number" id="cantidades" name="cantidades" class="form-control" value="<?php echo isset($form_data['cantidades']) ? s($form_data['cantidades']) : ''; ?>">
                </div>

                <div class="d-grid mb-2">
                    <input type="submit" value="Crear TareaxRecurso" class="btn btn-success">
                </div>
                <div class="d-grid">
                    <button id="actualizarTareaxRecurso" class="btn btn-info">Actualizar TareaxRecurso</button>
                </div>
            </form>
        </div>
        <!-- Tablas -->
        <div class="col-md-6 pt-4">
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
                        <!-- Los datos de las tareas se insertarán aquí -->
                    </tbody>
                </table>
            </div>

            <!-- Tabla Recursos-->
            <div class="table-responsive" style="max-height: 600px;">
                <table class="table table-striped table-hover">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Descripcion</th>
                            <th>Valor</th>
                            <th>Unidad</th>
                        </tr>
                    </thead>
                    <tbody id="recursos-tbody">
                        <!-- Los datos de los recursos se insertarán aquí -->
                    </tbody>
                </table>
            </div>

            <!-- Tabla TareaxRecursos-->
            <div class="table-responsive" style="max-height: 600px;">
                <table class="table table-striped table-hover">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>ID Tarea</th>
                            <th>ID Recurso</th>
                            <th>Cantidades</th>
                        </tr>
                    </thead>
                    <tbody id="tareaxrecursos-tbody">
                        <!-- Los datos de las tareasxrecursos se insertarán aquí -->
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script>
    async function fetchAndRenderData() {
        try {
            const [tareasResponse, recursosResponse, tareaxrecursosResponse] = await Promise.all([
                fetch('/obtenertareas', {
                    method: 'GET',
                    headers: {
                        'Content-Type': 'application/json'
                    }
                }),
                fetch('/obtenerrecursos', {
                    method: 'GET',
                    headers: {
                        'Content-Type': 'application/json'
                    }
                }),
                fetch('/obtenertareaxrecursos', {
                    method: 'GET',
                    headers: {
                        'Content-Type': 'application/json'
                    }
                })
            ]);

            // Verificar el estado de las respuestas
            if (!tareasResponse.ok) {
                throw new Error(`Error fetching tareas: ${tareasResponse.statusText}`);
            }
            if (!recursosResponse.ok) {
                throw new Error(`Error fetching recursos: ${recursosResponse.statusText}`);
            }
            if (!tareaxrecursosResponse.ok) {
                throw new Error(`Error fetching tareaxrecursos: ${tareaxrecursosResponse.statusText}`);
            }

            const [tareas, recursos, tareasxrecursos] = await Promise.all([
                tareasResponse.json(),
                recursosResponse.json(),
                tareaxrecursosResponse.json()
            ]);

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

            const recursosTbody = document.getElementById('recursos-tbody');
            recursosTbody.innerHTML = '';
            recursos.forEach(recurso => {
                const row = document.createElement('tr');

                const idCell = document.createElement('td');
                idCell.textContent = recurso.id;
                row.appendChild(idCell);

                const descripcionCell = document.createElement('td');
                descripcionCell.textContent = recurso.descripcion;
                row.appendChild(descripcionCell);

                const valorCell = document.createElement('td');
                valorCell.textContent = recurso.valor;
                row.appendChild(valorCell);

                const unidadCell = document.createElement('td');
                unidadCell.textContent = recurso.unidad;
                row.appendChild(unidadCell);

                recursosTbody.appendChild(row);
            });

            const tareaxrecursosTbody = document.getElementById('tareaxrecursos-tbody');
            tareaxrecursosTbody.innerHTML = '';
            tareasxrecursos.forEach(tareaxrecurso => {
                const row = document.createElement('tr');

                const idCell = document.createElement('td');
                idCell.textContent = tareaxrecurso.id;
                row.appendChild(idCell);

                const idtareaCell = document.createElement('td');
                idtareaCell.textContent = tareaxrecurso.id_tarea;
                row.appendChild(idtareaCell);

                const idrecursoCell = document.createElement('td');
                idrecursoCell.textContent = tareaxrecurso.id_recurso;
                row.appendChild(idrecursoCell);

                const cantidadesCell = document.createElement('td');
                cantidadesCell.textContent = tareaxrecurso.cantidades;
                row.appendChild(cantidadesCell);

                tareaxrecursosTbody.appendChild(row);
            });
        } catch (error) {
            console.error('Error during fetch operations:', error);
        }
    }

    // Llamar a la función fetchAndRenderData cuando el DOM esté cargado
    document.addEventListener('DOMContentLoaded', fetchAndRenderData);

    // Configurar un intervalo para actualizar los datos cada 1 segundo
    setInterval(fetchAndRenderData, 1000);


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

    document.getElementById('actualizarTareaxRecurso').addEventListener('click', async function(event) {
        event.preventDefault();

        const {
            value: searchQuery
        } = await Swal.fire({
            title: 'Buscar TareaxRecurso',
            input: 'text',
            inputPlaceholder: 'Ingrese la ID de la TareaxRecurso...',
            showCancelButton: true,
            inputValidator: (value) => {
                if (!value) {
                    return 'Por favor ingrese una ID';
                }
            }
        });

        if (searchQuery) {
            try {
                const response = await fetch('/obtenertareaxrecursos', {
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
                                    <th class="px-6 py-3 border-b-2 border-blue-500 dark:border-blue-700 text-left text-sm leading-4 font-medium text-blue-600 dark:text-blue-300 uppercase">ID Tarea</th>
                                    <td class="px-6 py-4 whitespace-no-wrap border-b border-blue-500 dark:border-blue-700 text-blue-800 dark:text-blue-200">${tarea.id_tarea}</td>
                                </tr>
                                <tr>
                                    <th class="px-6 py-3 border-b-2 border-blue-500 dark:border-blue-700 text-left text-sm leading-4 font-medium text-blue-600 dark:text-blue-300 uppercase">ID Recurso</th>
                                    <td class="px-6 py-4 whitespace-no-wrap border-b border-blue-500 dark:border-blue-700 text-blue-800 dark:text-blue-200">${tarea.id_recurso}</td>
                                </tr>
                                <tr>
                                    <th class="px-6 py-3 border-b-2 border-blue-500 dark:border-blue-700 text-left text-sm leading-4 font-medium text-blue-600 dark:text-blue-300 uppercase">Cantidades</th>
                                    <td class="px-6 py-4 whitespace-no-wrap border-b border-blue-500 dark:border-blue-700 text-blue-800 dark:text-blue-200">${tarea.cantidades}</td>
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
                                title: 'Modificar TareaxRecurso',
                                html: `
                            <input id="swal-id" class="swal2-input" placeholder="ID" value="${tarea.id}" readonly>
                            <input id="swal-id_tarea" class="swal2-input" placeholder="Descripcion" value="${tarea.id_tarea}">
                            <input id="swal-id_recurso" class="swal2-input" placeholder="Descripcion" value="${tarea.id_recurso}">
                            <input id="swal-cantidades" class="swal2-input" placeholder="Descripcion" value="${tarea.cantidades}">`,
                                focusConfirm: false,
                                preConfirm: () => {
                                    return {
                                        id: document.getElementById('swal-id').value,
                                        id_tarea: document.getElementById('swal-id_tarea').value,
                                        id_recurso: document.getElementById('swal-id_recurso').value,
                                        cantidades: document.getElementById('swal-cantidades').value
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
                                        const response = await fetch('/actualizartareaxrecursos', {
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
                                        showToast('Error al actualizar la tareaxrecurso', 'error');
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
                    text: "Hubo un problema al buscar la tareaxrecurso.",
                    icon: "error"
                });
            }
        }
    });

    <?php if (!empty($message)) : ?>
        showToast("<?php echo $message; ?>", "<?php echo (empty($alertas['error'])) ? 'success' : 'error'; ?>");
    <?php endif; ?>
</script>
<div class="container">
    <div class="row g-4">
        <!-- Formulario de Proyectos -->
        <div class="col-md-6">
            <form class="bg-light p-4 rounded-lg m-3 bg-primary" style="
    background: linear-gradient(
        to right,
        #4c24ee,
        #4624ee,
        #245aee,
        #24a7ee
    );
" method="POST" action="/crear-proyecto">
                <h2 class="text-lg font-semibold mb-4">Crear Proyecto</h2>
                <div class="mb-3">
                    <label for="descripcion" class="form-label">Descripcion:</label>
                    <input type="text" id="descripcion" name="descripcion" class="form-control" value="<?php echo isset($form_data['descripcion']) ? s($form_data['descripcion']) : ''; ?>">
                </div>

                <div class="mb-3">
                    <label for="fecha_inicio" class="form-label">Fecha Inicio:</label>
                    <input type="date" id="fecha_inicio" name="fecha_inicio" class="form-control" value="<?php echo isset($form_data['fecha_inicio']) ? s($form_data['fecha_inicio']) : ''; ?>">
                </div>

                <div class="mb-3">
                    <label for="fecha_entrega" class="form-label">Fecha Entrega:</label>
                    <input type="date" id="fecha_entrega" name="fecha_entrega" class="form-control" value="<?php echo isset($form_data['fecha_entrega']) ? s($form_data['fecha_entrega']) : ''; ?>">
                </div>

                <div class="mb-3">
                    <label for="valor" class="form-label">Valor:</label>
                    <input type="number" id="valor" name="valor" class="form-control" value="<?php echo isset($form_data['valor']) ? s($form_data['valor']) : ''; ?>">
                </div>

                <div class="mb-3">
                    <label for="lugar" class="form-label">Lugar:</label>
                    <input type="text" id="lugar" name="lugar" class="form-control" value="<?php echo isset($form_data['lugar']) ? s($form_data['lugar']) : ''; ?>">
                </div>

                <div class="mb-3">
                    <label for="responsable" class="form-label">Responsable:</label>
                    <input type="number" id="responsable" name="responsable" class="form-control" value="<?php echo isset($form_data['responsable']) ? s($form_data['responsable']) : ''; ?>">
                </div>

                <div class="d-grid mb-2">
                    <input type="submit" value="Crear Proyecto" class="btn btn-success">
                </div>
                <div class="d-grid">
                    <button id="actualizarProyecto" class="btn btn-info">Actualizar Proyecto</button>
                </div>
            </form>

        </div>
        <!-- Tablas -->
        <div class="col-md-6 pt-4">
            <!-- Tabla personas -->
            <div class="table-responsive" style="max-height: 600px;">
                <table class="table table-striped table-hover">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nombre</th>
                            <th>Apellido</th>
                            <th>Email</th>
                        </tr>
                    </thead>
                    <tbody id="personas-tbody">
                        <!-- Los datos de las personas se insertarán aquí -->
                    </tbody>
                </table>
            </div>
            <!-- Tabla Proyectos-->
            <div class="table-responsive" style="max-height: 600px;">
                <table class="table table-striped table-hover">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Descripcion</th>
                            <th>Fecha inicio</th>
                            <th>Fecha entrega</th>
                            <th>Valor</th>
                            <th>Lugar</th>
                            <th>Responsable</th>
                            <th>Estado</th>
                        </tr>
                    </thead>
                    <tbody id="proyectos-tbody">
                        <!-- Los datos de los proyectos se insertarán aquí -->
                    </tbody>
                </table>
            </div>

        </div>
    </div>
</div>


<script>
    async function fetchAndRenderData() {
        try {
            const [response, proyectosResponse] = await Promise.all([
                fetch('/obtenerpersonas', {
                    method: 'GET',
                    headers: {
                        'Content-Type': 'application/json'
                    }
                }),
                fetch('/obtenerproyectos', {
                    method: 'GET',
                    headers: {
                        'Content-Type': 'application/json'
                    }
                })
            ]);

            if (response.ok && proyectosResponse.ok) {
                const [personas, proyectos] = await Promise.all([
                    response.json(),
                    proyectosResponse.json()
                ]);

                // Limpiar los cuerpos de las tablas antes de renderizar nuevos datos
                const personasTbody = document.getElementById('personas-tbody');
                personasTbody.innerHTML = '';
                personas.forEach(persona => {
                    const row = document.createElement('tr');

                    const idCell = document.createElement('td');
                    idCell.textContent = persona.id;
                    row.appendChild(idCell);

                    const nombreCell = document.createElement('td');
                    nombreCell.textContent = persona.nombre;
                    row.appendChild(nombreCell);

                    const apellidoCell = document.createElement('td');
                    apellidoCell.textContent = persona.apellido;
                    row.appendChild(apellidoCell);

                    const emailCell = document.createElement('td');
                    emailCell.textContent = persona.email;
                    row.appendChild(emailCell);

                    personasTbody.appendChild(row);
                });

                const proyectosTbody = document.getElementById('proyectos-tbody');
                proyectosTbody.innerHTML = '';
                proyectos.forEach(proyecto => {
                    const row = document.createElement('tr');

                    const idCell = document.createElement('td');
                    idCell.textContent = proyecto.id;
                    row.appendChild(idCell);

                    const descripcionCell = document.createElement('td');
                    descripcionCell.textContent = proyecto.descripcion;
                    row.appendChild(descripcionCell);

                    const fechainicioCell = document.createElement('td');
                    fechainicioCell.textContent = proyecto.fecha_inicio;
                    row.appendChild(fechainicioCell);

                    const fechaentregaCell = document.createElement('td');
                    fechaentregaCell.textContent = proyecto.fecha_entrega;
                    row.appendChild(fechaentregaCell);

                    const valorCell = document.createElement('td');
                    valorCell.textContent = proyecto.valor;
                    row.appendChild(valorCell);

                    const lugarCell = document.createElement('td');
                    lugarCell.textContent = proyecto.lugar;
                    row.appendChild(lugarCell);

                    const responsableCell = document.createElement('td');
                    responsableCell.textContent = proyecto.responsable;
                    row.appendChild(responsableCell);

                    const estadoCell = document.createElement('td');
                    estadoCell.textContent = proyecto.estado;
                    row.appendChild(estadoCell);

                    proyectosTbody.appendChild(row);
                });
            } else {
                if (!response.ok) {
                    console.error('Error fetching personas:', response.statusText);
                }
                if (!proyectosResponse.ok) {
                    console.error('Error fetching proyectos:', proyectosResponse.statusText);
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

    document.getElementById('actualizarProyecto').addEventListener('click', async function(event) {
        event.preventDefault();

        const {
            value: searchQuery
        } = await Swal.fire({
            title: 'Buscar Proyecto',
            input: 'text',
            inputPlaceholder: 'Ingrese la ID del proyecto...',
            showCancelButton: true,
            inputValidator: (value) => {
                if (!value) {
                    return 'Por favor ingrese una ID';
                }
            }
        });

        if (searchQuery) {
            try {
                const response = await fetch('/obtenerproyectos', {
                    method: 'GET',
                    headers: {
                        'Content-Type': 'application/json'
                    }
                });

                const proyectos = await response.json();
                const proyecto = proyectos.find(pro => pro.id.toString() === searchQuery);

                if (proyecto) {
                    const tableContent = `
                <div class="max-w-md mx-auto bg-white shadow-md rounded my-6 dark:bg-gray-800 d-flex justify-content-center">
                    <div class="overflow-x-auto">
                        <table class="min-w-full bg-blue-100 dark:bg-blue-900 border border-blue-500 dark:border-blue-700">
                            <thead>
                                <tr>
                                    <th class="px-6 py-3 border-b-2 border-blue-500 dark:border-blue-700 text-left text-sm leading-4 font-medium text-blue-600 dark:text-blue-300 uppercase">ID</th>
                                    <td class="px-6 py-4 whitespace-no-wrap border-b border-blue-500 dark:border-blue-700 text-blue-800 dark:text-blue-200">${proyecto.id}</td>
                                </tr>
                                <tr>
                                    <th class="px-6 py-3 border-b-2 border-blue-500 dark:border-blue-700 text-left text-sm leading-4 font-medium text-blue-600 dark:text-blue-300 uppercase">Descripcion</th>
                                    <td class="px-6 py-4 whitespace-no-wrap border-b border-blue-500 dark:border-blue-700 text-blue-800 dark:text-blue-200">${proyecto.descripcion}</td>
                                </tr>
                                <tr>
                                    <th class="px-6 py-3 border-b-2 border-blue-500 dark:border-blue-700 text-left text-sm leading-4 font-medium text-blue-600 dark:text-blue-300 uppercase">Fecha_inicio</th>
                                    <td class="px-6 py-4 whitespace-no-wrap border-b border-blue-500 dark:border-blue-700 text-blue-800 dark:text-blue-200">${proyecto.fecha_inicio}</td>
                                </tr>
                                <tr>
                                    <th class="px-6 py-3 border-b-2 border-blue-500 dark:border-blue-700 text-left text-sm leading-4 font-medium text-blue-600 dark:text-blue-300 uppercase">Fecha_entrega</th>
                                    <td class="px-6 py-4 whitespace-no-wrap border-b border-blue-500 dark:border-blue-700 text-blue-800 dark:text-blue-200">${proyecto.fecha_entrega}</td>
                                </tr>
                                <tr>
                                    <th class="px-6 py-3 border-b-2 border-blue-500 dark:border-blue-700 text-left text-sm leading-4 font-medium text-blue-600 dark:text-blue-300 uppercase">Valor</th>
                                    <td class="px-6 py-4 whitespace-no-wrap border-b border-blue-500 dark:border-blue-700 text-blue-800 dark:text-blue-200">${proyecto.valor}</td>
                                </tr>
                                <tr>
                                    <th class="px-6 py-3 border-b-2 border-blue-500 dark:border-blue-700 text-left text-sm leading-4 font-medium text-blue-600 dark:text-blue-300 uppercase">Lugar</th>
                                    <td class="px-6 py-4 whitespace-no-wrap border-b border-blue-500 dark:border-blue-700 text-blue-800 dark:text-blue-200">${proyecto.lugar}</td>
                                </tr>
                                <tr>
                                    <th class="px-6 py-3 border-b-2 border-blue-500 dark:border-blue-700 text-left text-sm leading-4 font-medium text-blue-600 dark:text-blue-300 uppercase">Responsable</th>
                                    <td class="px-6 py-4 whitespace-no-wrap border-b border-blue-500 dark:border-blue-700 text-blue-800 dark:text-blue-200">${proyecto.responsable}</td>
                                </tr>
                                <tr>
                                    <th class="px-6 py-3 border-b-2 border-blue-500 dark:border-blue-700 text-left text-sm leading-4 font-medium text-blue-600 dark:text-blue-300 uppercase">Estado</th>
                                    <td class="px-6 py-4 whitespace-no-wrap border-b border-blue-500 dark:border-blue-700 text-blue-800 dark:text-blue-200">${proyecto.estado}</td>
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
                                title: 'Modificar Proyecto',
                                html: `
                            <input id="swal-id" class="swal2-input" placeholder="ID" value="${proyecto.id}" readonly>
                            <input id="swal-descripcion" class="swal2-input" placeholder="Descripcion" value="${proyecto.descripcion}">
                            <input type="date" id="swal-fecha_inicio" class="swal2-input" placeholder="Fecha Inicio" value="${proyecto.fecha_inicio}">
                            <input type="date" id="swal-fecha_entrega" class="swal2-input" placeholder="Fecha Entrega" value="${proyecto.fecha_entrega}">
                            <input type="number" id="swal-valor" class="swal2-input" value="${proyecto.valor}">
                            <input id="swal-lugar" class="swal2-input" placeholder="Lugar" value="${proyecto.lugar}">
                            <input type="number" id="swal-responsable" class="swal2-input" value="${proyecto.responsable}">
                            <select id="swal-estado" class="swal2-control mt-2">
                            </select>`,
                                focusConfirm: false,
                                didOpen: () => {
                                    setupSelect('swal-estado', proyecto.estado);
                                },
                                preConfirm: () => {
                                    return {
                                        id: document.getElementById('swal-id').value,
                                        descripcion: document.getElementById('swal-descripcion').value,
                                        fecha_inicio: document.getElementById('swal-fecha_inicio').value,
                                        fecha_entrega: document.getElementById('swal-fecha_entrega').value,
                                        valor: document.getElementById('swal-valor').value,
                                        lugar: document.getElementById('swal-lugar').value,
                                        responsable: document.getElementById('swal-responsable').value,
                                        estado: document.getElementById('swal-estado').value
                                    };
                                },
                                showCancelButton: true,
                                confirmButtonText: 'Guardar Cambios',
                                cancelButtonText: 'Cancelar',
                                showLoaderOnConfirm: true,
                                allowOutsideClick: () => !Swal.isLoading(),
                            }).then(async (result) => {
                                if (result.isConfirmed) {
                                    const updatedProyecto = result.value;
                                    try {
                                        const response = await fetch('/actualizarproyectos', {
                                            method: 'PUT',
                                            headers: {
                                                'Content-Type': 'application/json'
                                            },
                                            body: JSON.stringify(updatedProyecto)
                                        });

                                        const data = await response.json();

                                        if (data.success) {
                                            showToast(data.success, 'success');
                                        } else if (data.error) {
                                            showToast(data.error, 'error');
                                        }
                                    } catch (error) {
                                        showToast('Error al actualizar el proyecto', 'error');
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
                    text: "Hubo un problema al buscar el proyecto.",
                    icon: "error"
                });
            }
        }
    });

    <?php if (!empty($message)) : ?>
        showToast("<?php echo $message; ?>", "<?php echo (empty($alertas['error'])) ? 'success' : 'error'; ?>");
    <?php endif; ?>
</script>
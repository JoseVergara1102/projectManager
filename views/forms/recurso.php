<div class="container">
    <div class="row g-4">
        <!-- Formulario de Recursos -->
        <div class="col-md-6">
            <form class="bg-light p-4 rounded-lg m-3 bg-primary" style="
    background: linear-gradient(
        to right,
        #4c24ee,
        #4624ee,
        #245aee,
        #24a7ee
    );
" method="POST" action="/crear-recurso">
                <h2 class="text-lg font-semibold mb-4">Crear Recurso</h2>
                <div class="mb-3">
                    <label for="descripcion" class="form-label">Descripcion:</label>
                    <input type="text" id="descripcion" name="descripcion" class="form-control" value="<?php echo isset($form_data['descripcion']) ? s($form_data['descripcion']) : ''; ?>">
                </div>

                <div class="mb-3">
                    <label for="valor" class="form-label">Valor:</label>
                    <input type="number" id="valor" name="valor" class="form-control" value="<?php echo isset($form_data['valor']) ? s($form_data['valor']) : ''; ?>">
                </div>

                <div class="mb-3">
                        <label for="unidad" class="form-label">Unidad:</label>
                        <select id="unidad" name="unidad" class="form-control">
                            <option value="" disabled <?php echo !isset($form_data['unidad']) ? 'selected' : ''; ?>>Seleccione la unidad</option>

                            <option value="Metro" <?php echo isset($form_data['unidad']) && $form_data['unidad'] == 'Metro' ? 'selected' : ''; ?>>Metro</option>

                            <option value="Kilogramo" <?php echo isset($form_data['unidad']) && $form_data['unidad'] == 'Kilogramo' ? 'selected' : ''; ?>>Kilogramo</option>

                            <option value="Segundo" <?php echo isset($form_data['unidad']) && $form_data['unidad'] == 'Segundo' ? 'selected' : ''; ?>>Segundo</option>
                            
                            <option value="Amperio" <?php echo isset($form_data['unidad']) && $form_data['unidad'] == 'Amperio' ? 'selected' : ''; ?>>Amperio</option>

                            <option value="Kelvin" <?php echo isset($form_data['unidad']) && $form_data['unidad'] == 'Kelvin' ? 'selected' : ''; ?>>Kelvin</option>
                            
                            <option value="Mol" <?php echo isset($form_data['unidad']) && $form_data['unidad'] == 'Mol' ? 'selected' : ''; ?>>Mol</option>

                            <option value="Slug" <?php echo isset($form_data['unidad']) && $form_data['unidad'] == 'Slug' ? 'selected' : ''; ?>>Slug</option>
                            
                            <option value="Litro" <?php echo isset($form_data['unidad']) && $form_data['unidad'] == 'Litro' ? 'selected' : ''; ?>>Litro</option>

                            <option value="Newton" <?php echo isset($form_data['unidad']) && $form_data['unidad'] == 'Newton' ? 'selected' : ''; ?>>Newton</option>
                            
                            <option value="Joule" <?php echo isset($form_data['unidad']) && $form_data['unidad'] == 'Joule' ? 'selected' : ''; ?>>Joule</option>

                            <option value="Voltio" <?php echo isset($form_data['unidad']) && $form_data['unidad'] == 'Voltio' ? 'selected' : ''; ?>>Voltio</option>

                        </select>
                    </div>

                <div class="d-grid mb-2">
                    <input type="submit" value="Crear Recurso" class="btn btn-success">
                </div>
                <div class="d-grid">
                    <button id="actualizarRecurso" class="btn btn-info">Actualizar Recurso</button>
                </div>
            </form>

        </div>
        <!-- Tablas -->
        <div class="col-md-6 pt-4">
            <!-- Tabla recursos -->
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

        </div>
    </div>
</div>


<script>
    async function fetchAndRenderData() {
        try {
            const [response] = await Promise.all([
                fetch('/obtenerrecursos', {
                    method: 'GET',
                    headers: {
                        'Content-Type': 'application/json'
                    }
                })
            ]);

            if (response.ok) {
                const [recursos] = await Promise.all([
                    response.json()
                ]);

                // Limpiar los cuerpos de las tablas antes de renderizar nuevos datos
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
            } else {
                if (!response.ok) {
                    console.error('Error fetching recursos:', response.statusText);
                }
            }
        } catch (error) {
            console.error('Error during fetch recursos:', error);
        }
    }

    // Llamar a la función fetchAndRenderData cuando el DOM esté cargado
    document.addEventListener('DOMContentLoaded', fetchAndRenderData);

    // Configurar un intervalo para actualizar los datos cada 1 segundo
    setInterval(fetchAndRenderData, 1000);

    function setupSelect(selectId, currentState) {
        const selectElement = document.getElementById(selectId);
        selectElement.innerHTML = ''; // Limpia las opciones existentes

        // Lista de recursos posibles
        const recursos = ['Metro', 'Kilogramo', 'Segundo', 'Amperio', 'Kelvin', 'Mol', 'Slug', 'Litro', 'Newton', 'Joule', 'Voltio'];

        // Crear y añadir las opciones al select
        recursos.forEach(recurso => {
            const option = document.createElement('option');
            option.value = recurso;
            option.textContent = recurso;
            if (recurso === currentState) {
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

    document.getElementById('actualizarRecurso').addEventListener('click', async function(event) {
        event.preventDefault();

        const {
            value: searchQuery
        } = await Swal.fire({
            title: 'Buscar Recurso',
            input: 'text',
            inputPlaceholder: 'Ingrese la ID del recurso...',
            showCancelButton: true,
            inputValidator: (value) => {
                if (!value) {
                    return 'Por favor ingrese una ID';
                }
            }
        });

        if (searchQuery) {
            try {
                const response = await fetch('/obtenerrecursos', {
                    method: 'GET',
                    headers: {
                        'Content-Type': 'application/json'
                    }
                });

                const recursos = await response.json();
                const recurso = recursos.find(rec => rec.id.toString() === searchQuery);

                if (recurso) {
                    const tableContent = `
                <div class="max-w-md mx-auto bg-white shadow-md rounded my-6 dark:bg-gray-800 d-flex justify-content-center">
                    <div class="overflow-x-auto">
                        <table class="min-w-full bg-blue-100 dark:bg-blue-900 border border-blue-500 dark:border-blue-700">
                            <thead>
                                <tr>
                                    <th class="px-6 py-3 border-b-2 border-blue-500 dark:border-blue-700 text-left text-sm leading-4 font-medium text-blue-600 dark:text-blue-300 uppercase">ID</th>
                                    <td class="px-6 py-4 whitespace-no-wrap border-b border-blue-500 dark:border-blue-700 text-blue-800 dark:text-blue-200">${recurso.id}</td>
                                </tr>
                                <tr>
                                    <th class="px-6 py-3 border-b-2 border-blue-500 dark:border-blue-700 text-left text-sm leading-4 font-medium text-blue-600 dark:text-blue-300 uppercase">Descripcion</th>
                                    <td class="px-6 py-4 whitespace-no-wrap border-b border-blue-500 dark:border-blue-700 text-blue-800 dark:text-blue-200">${recurso.descripcion}</td>
                                </tr>
                                <tr>
                                    <th class="px-6 py-3 border-b-2 border-blue-500 dark:border-blue-700 text-left text-sm leading-4 font-medium text-blue-600 dark:text-blue-300 uppercase">Valor</th>
                                    <td class="px-6 py-4 whitespace-no-wrap border-b border-blue-500 dark:border-blue-700 text-blue-800 dark:text-blue-200">${recurso.valor}</td>
                                </tr>
                                <tr>
                                    <th class="px-6 py-3 border-b-2 border-blue-500 dark:border-blue-700 text-left text-sm leading-4 font-medium text-blue-600 dark:text-blue-300 uppercase">Unidad</th>
                                    <td class="px-6 py-4 whitespace-no-wrap border-b border-blue-500 dark:border-blue-700 text-blue-800 dark:text-blue-200">${recurso.unidad}</td>
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
                                title: 'Modificar Recurso',
                                html: `
                            <input id="swal-id" class="swal2-input" placeholder="ID" value="${recurso.id}" readonly>
                            <input id="swal-descripcion" class="swal2-input" placeholder="Descripcion" value="${recurso.descripcion}">
                            <input type="number" id="swal-valor" class="swal2-input" value="${recurso.valor}">
                            <select id="swal-unidad" class="swal2-control mt-2">
                            </select>`,
                                focusConfirm: false,
                                didOpen: () => {
                                    setupSelect('swal-unidad', recurso.unidad);
                                },
                                preConfirm: () => {
                                    return {
                                        id: document.getElementById('swal-id').value,
                                        descripcion: document.getElementById('swal-descripcion').value,
                                        valor: document.getElementById('swal-valor').value,
                                        unidad: document.getElementById('swal-unidad').value
                                    };
                                },
                                showCancelButton: true,
                                confirmButtonText: 'Guardar Cambios',
                                cancelButtonText: 'Cancelar',
                                showLoaderOnConfirm: true,
                                allowOutsideClick: () => !Swal.isLoading(),
                            }).then(async (result) => {
                                if (result.isConfirmed) {
                                    const updatedRecurso = result.value;
                                    try {
                                        const response = await fetch('/actualizarrecursos', {
                                            method: 'PUT',
                                            headers: {
                                                'Content-Type': 'application/json'
                                            },
                                            body: JSON.stringify(updatedRecurso)
                                        });

                                        const data = await response.json();

                                        if (data.success) {
                                            showToast(data.success, 'success');
                                        } else if (data.error) {
                                            showToast(data.error, 'error');
                                        }
                                    } catch (error) {
                                        showToast('Error al actualizar el recurso', 'error');
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
                    text: "Hubo un problema al buscar el recurso.",
                    icon: "error"
                });
            }
        }
    });

    <?php if (!empty($message)) : ?>
        showToast("<?php echo $message; ?>", "<?php echo (empty($alertas['error'])) ? 'success' : 'error'; ?>");
    <?php endif; ?>
</script>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manejador de proyectos</title>
    <link rel="icon" href="../includes/icon.svg" type="image/png">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.3.1/css/all.css" integrity="sha384-mzrmE5qonljUremFsqc01SB46JvROS7bZs3IO2EmfFsd15uHvIt+Y8vEf7N7fWAU" crossorigin="anonymous">
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/toastify-js/src/toastify.min.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" />
    <link rel="stylesheet" type="text/css" href="views/styles.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body>
    <?php if (isset($_SESSION['token'])) : ?>
        <nav class="navbar navbar-expand-sm bgnavbar navbar-dark d-flex justify-content-center">
            <!-- Brand -->
            <a class="navbar-brand" href="/index">Manejador de proyectos</a>
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link" href="/crear-persona">Persona</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="/crear-proyecto">Proyecto</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="/crear-actividad">Actividad</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="/crear-tarea">Tarea</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="/crear-recurso">Recurso</a>
                </li>

                <li class="nav-item">
                    <a class="nav-link" href="/crear-tareaxrecurso">TareaxRecurso</a>
                </li>

                <li class="nav-item">
                    <a class="nav-link" href="/crear-tareaxpersona">TareaxPersona</a>
                </li>

                <li class="nav-item">
                    <a class="nav-link" href="/logout">Cerrar Sesión</a>
                </li>

                
            </ul>
        </nav>
    <?php endif; ?>

    <?php echo $content; ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <script src="//cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/toastify-js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            <?php
            if (isset($alertas)) {
                foreach ($alertas as $key => $mensajes) {
                    foreach ($mensajes as $mensaje) {
                        echo "Toastify({ text: '" . addslashes($mensaje) . "', duration: 2000, gravity: 'top', backgroundColor: 'linear-gradient(to right, #6300f7, #5e00ff)' }).showToast();";
                    }
                }
            }
            ?>
        });
    </script>
</body>
</html>

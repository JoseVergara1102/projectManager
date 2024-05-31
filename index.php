<?php
require_once __DIR__ . '/vendor/autoload.php';
require_once './includes/router.php'; 
require_once './includes/db.php'; 
require_once './includes/ActiveRecord.php';
require_once './includes/funciones.php';
require_once './classes/JWT.php';
require_once './classes/AuthMiddleware.php';

use Dotenv\Dotenv;
$dotenv = Dotenv::createImmutable(__DIR__);
$dotenv->load();

ActiveRecord::setDB(Database::getConnection());

$router = new Router();



// Iniciar Sesión
$router->get('/login', 'LoginController@login'); 
$router->post('/login', 'LoginController@login'); 
$router->get('/logout', 'LoginController@logout');

// Recuperar Password
$router->get('/olvide', 'LoginController@olvide');
$router->post('/olvide', 'LoginController@olvide');
$router->get('/recuperar', 'LoginController@recuperar');
$router->post('/recuperar','LoginController@recuperar');

// Crear Cuenta
$router->get('/crear-cuenta', 'LoginController@crear');
$router->post('/crear-cuenta', 'LoginController@crear');

// Confirmar cuenta
$router->get('/confirmar-cuenta', 'LoginController@confirmar');
$router->get('/mensaje', 'LoginController@mensaje');



// Registra el middleware para la ruta protegida
$router->get('/index', 'LoginController@index', ['middleware' => 'AuthMiddleware']);




/*
    -----------------------  RUTAS PARA LA TABLA PERSONAS -------------------------
*/
$router->get('/crear-persona', 'PersonaController@crear', ['middleware' => 'AuthMiddleware']);
$router->post('/crear-persona', 'PersonaController@crear', ['middleware' => 'AuthMiddleware']);
$router->get('/obtenerpersonas', 'PersonaController@obtenerPersonas', ['middleware' => 'AuthMiddleware']);
$router->put('/actualizarpersonas', 'PersonaController@actualizarPersona', ['middleware' => 'AuthMiddleware']);

/*
    -----------------------  RUTAS PARA LA TABLA PROYECTOS -------------------------
*/
$router->get('/crear-proyecto', 'ProyectoController@crear', ['middleware' => 'AuthMiddleware']);
$router->post('/crear-proyecto', 'ProyectoController@crear', ['middleware' => 'AuthMiddleware']);
$router->get('/obtenerproyectos', 'ProyectoController@obtenerProyectos', ['middleware' => 'AuthMiddleware']);
$router->put('/actualizarproyectos', 'ProyectoController@actualizarProyecto', ['middleware' => 'AuthMiddleware']);

/*
    -----------------------  RUTAS PARA LA TABLA ACTIVIDADES -------------------------
*/
$router->get('/crear-actividad', 'ActividadController@crear', ['middleware' => 'AuthMiddleware']);
$router->post('/crear-actividad', 'ActividadController@crear', ['middleware' => 'AuthMiddleware']);
$router->get('/obteneractividades', 'ActividadController@obtenerActividades', ['middleware' => 'AuthMiddleware']);
$router->put('/actualizaractividades', 'ActividadController@actualizarActividad', ['middleware' => 'AuthMiddleware']);

/*
    -----------------------  RUTAS PARA LA TABLA TAREAS -------------------------
*/
$router->get('/crear-tarea', 'TareaController@crear', ['middleware' => 'AuthMiddleware']);
$router->post('/crear-tarea', 'TareaController@crear', ['middleware' => 'AuthMiddleware']);
$router->get('/obtenertareas', 'TareaController@obtenerTareas', ['middleware' => 'AuthMiddleware']);
$router->put('/actualizartareas', 'TareaController@actualizarTarea', ['middleware' => 'AuthMiddleware']);

/*
    -----------------------  RUTAS PARA LA TABLA RECURSOS -------------------------
*/
$router->get('/crear-recurso', 'RecursoController@crear', ['middleware' => 'AuthMiddleware']);
$router->post('/crear-recurso', 'RecursoController@crear', ['middleware' => 'AuthMiddleware']);
$router->get('/obtenerrecursos', 'RecursoController@obtenerRecursos', ['middleware' => 'AuthMiddleware']);
$router->put('/actualizarrecursos', 'RecursoController@actualizarRecurso', ['middleware' => 'AuthMiddleware']);

/*
    -----------------------  RUTAS PARA LA TABLA TAREAXRECURSOS -------------------------
*/
$router->get('/crear-tareaxrecurso', 'TareaxRecursoController@crear', ['middleware' => 'AuthMiddleware']);
$router->post('/crear-tareaxrecurso', 'TareaxRecursoController@crear', ['middleware' => 'AuthMiddleware']);
$router->get('/obtenertareaxrecursos', 'TareaxRecursoController@obtenerTareaxRecursos', ['middleware' => 'AuthMiddleware']);
$router->put('/actualizartareaxrecursos', 'TareaxRecursoController@actualizarTareaxRecurso', ['middleware' => 'AuthMiddleware']);

/*
    -----------------------  RUTAS PARA LA TABLA TAREAXPERSONAS -------------------------
*/
$router->get('/crear-tareaxpersona', 'TareaxPersonaController@crear', ['middleware' => 'AuthMiddleware']);
$router->post('/crear-tareaxpersona', 'TareaxPersonaController@crear', ['middleware' => 'AuthMiddleware']);
$router->get('/obtenertareaxpersonas', 'TareaxPersonaController@obtenerTareaxPersonas', ['middleware' => 'AuthMiddleware']);
$router->put('/actualizartareaxpersonas', 'TareaxPersonaController@actualizarTareaxPersona', ['middleware' => 'AuthMiddleware']);




// Ejecutamos nuestro enrutador
$router->comprobarRutas();

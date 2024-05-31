<?php
require_once './Models/ProyectoModel.php';
require_once './includes/router.php';
require_once './classes/JWT.php';
class ProyectoController
{
    public static function crear(Router $router)
    {
        $proyecto = new proyectoModel;
        $alertas = [];
        $message = '';

        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }

        $token = isset($_SESSION['token']) ? $_SESSION['token'] : null;

        if ($token) {
            $jwt = new JWT();
            $payload = $jwt->decode($token);

            if ($payload) {
                if ($_SERVER["REQUEST_METHOD"] == "POST") {
                    $_SESSION['form_data'] = $_POST;
                    try {
                        $proyecto->sincronizar($_POST);
                        $alertas = $proyecto->validarNuevaCuenta();
                        if (empty($alertas['error'])) {
                            $resultado = $proyecto->guardar();
                            if ($resultado) {
                                $message = 'Successfully added into your database!';
                                unset($_SESSION['form_data']); // Limpiamos el form data si se guardó correctamente.
                            } else {
                                $alertas['error'][] = 'Error occurred while saving.';
                            }
                        }
                    } catch (\Throwable $th) {
                        $alertas['error'][] = $th->getMessage();
                    }
                }

                $form_data = $_SESSION['form_data'] ?? [];

                $router->render('/forms/proyecto', [
                    'alertas' => $alertas,
                    'message' => $message,
                    'form_data' => $form_data,
                    'payload' => $payload
                ]);
            } else {
                unset($_SESSION['token']);
                header('Location: /login');
                exit;
            }
        } else {
            unset($_SESSION['token']);
            header('Location: /login');
            exit;
        }
    }

    public static function obtenerProyectos()
    {
        $proyecto = new ProyectoModel;
        $proyectosArray = [];

        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }

        $token = isset($_SESSION['token']) ? $_SESSION['token'] : null;

        if ($token) {
            $jwt = new JWT();
            $payload = $jwt->decode($token);

            if ($payload) {

                if ($_SERVER["REQUEST_METHOD"] == "GET") {
                    try {
                        $proyectosArray = ProyectoModel::obtenerTodosProyectos();
                    } catch (\Throwable $th) {
                        $proyecto::setAlerta('error', $th->getMessage());
                    }
                }
                echo json_encode($proyectosArray);
            } else {
                unset($_SESSION['token']);
                header('Location: /login');
                exit;
            }
        } else {
            unset($_SESSION['token']);
            header('Location: /login');
            exit;
        }
    }

    public static function actualizarProyecto()
    {
        header('Content-Type: application/json');
        $alertas = [];
        $proyecto = new ProyectoModel;

        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }

        $token = isset($_SESSION['token']) ? $_SESSION['token'] : null;

        if ($token) {
            $jwt = new JWT();
            $payload = $jwt->decode($token);

            if ($payload) {

                $datos = json_decode(file_get_contents("php://input"), true);

                try {
                    if (empty($datos)) {
                        echo json_encode(['error' => 'No se recibieron datos para actualizar']);
                        return;
                    }

                    $encontrado = ProyectoModel::find($datos['id']);

                    if ($encontrado) {
                        $proyecto->sincronizar($datos);
                        $alertas = $proyecto->validarNuevaCuenta();

                        if (!empty($alertas['error'])) {
                            echo json_encode(['error' => $alertas['error']]);
                            return;
                        }
                        $proyecto->guardar();
                        echo json_encode(['success' => 'Proyecto actualizado exitosamente!']);
                    } else {
                        echo json_encode(['error' => 'No se encontró un proyecto con el ID proporcionado']);
                    }
                } catch (\Throwable $th) {
                    echo json_encode(['error' => $th->getMessage()]);
                }
            } else {
                unset($_SESSION['token']);
                header('Location: /login');
                exit;
            }
        } else {
            unset($_SESSION['token']);
            header('Location: /login');
            exit;
        }
    }
}

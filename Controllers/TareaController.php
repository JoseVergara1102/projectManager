<?php
require_once './Models/TareaModel.php';
require_once './includes/router.php';
require_once './classes/JWT.php';
class TareaController
{
    public static function crear(Router $router)
    {
        $tarea = new TareaModel;
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
                        $tarea->sincronizar($_POST);
                        $alertas = $tarea->validarNuevaCuenta();
                        if (empty($alertas['error'])) {
                            $resultado = $tarea->guardar();
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

                $router->render('/forms/tarea', [
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

    public static function obtenerTareas()
    {
        $tarea = new TareaModel;
        $tareasArray = [];

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
                        $tareasArray = TareaModel::obtenerTodasTareas();
                    } catch (\Throwable $th) {
                        $tarea::setAlerta('error', $th->getMessage());
                    }
                }
                echo json_encode($tareasArray);
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

    public static function actualizarTarea()
    {
        header('Content-Type: application/json');
        $alertas = [];
        $tarea = new TareaModel;

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

                    $encontrado = TareaModel::find($datos['id']);

                    if ($encontrado) {
                        $tarea->sincronizar($datos);
                        $alertas = $tarea->validarNuevaCuenta();

                        if (!empty($alertas['error'])) {
                            echo json_encode(['error' => $alertas['error']]);
                            return;
                        }
                        $tarea->guardar();
                        echo json_encode(['success' => 'Tarea actualizada exitosamente!']);
                    } else {
                        echo json_encode(['error' => 'No se encontró ninguna tarea con el ID proporcionado']);
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

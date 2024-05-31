<?php
require_once './Models/TareaxPersonaModel.php';
require_once './includes/router.php';
require_once './classes/JWT.php';
class TareaxPersonaController
{
    public static function crear(Router $router)
    {
        $tareaxpersona = new TareaxPersonaModel;
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
                        $tareaxpersona->sincronizar($_POST);
                        $alertas = $tareaxpersona->validarNuevaCuenta();
                        if (empty($alertas['error'])) {
                            $resultado = $tareaxpersona->guardar();
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

                $router->render('/forms/tareaxpersona', [
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

    public static function obtenerTareaxPersonas()
    {
        $tareaxpersona = new TareaxPersonaModel;
        $tareaxpersonasArray = [];

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
                        $tareaxpersonasArray = TareaxPersonaModel::obtenerTodasTareasxPersonas();
                    } catch (\Throwable $th) {
                        $tareaxpersona::setAlerta('error', $th->getMessage());
                    }
                }
                echo json_encode($tareaxpersonasArray);
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

    public static function actualizarTareaxPersona()
    {
        header('Content-Type: application/json');
        $alertas = [];
        $tareaxpersona = new TareaxPersonaModel;

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

                    $encontrado = TareaxPersonaModel::find($datos['id']);

                    if ($encontrado) {
                        $tareaxpersona->sincronizar($datos);
                        $alertas = $tareaxpersona->validarNuevaCuenta();

                        if (!empty($alertas['error'])) {
                            echo json_encode(['error' => $alertas['error']]);
                            return;
                        }
                        $tareaxpersona->guardar();
                        echo json_encode(['success' => 'TareaxPersona actualizado exitosamente!']);
                    } else {
                        echo json_encode(['error' => 'No se encontró una tareaxpersona con el ID proporcionado']);
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

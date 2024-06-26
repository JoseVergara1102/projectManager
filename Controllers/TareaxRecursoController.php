<?php
require_once './Models/TareaxRecursoModel.php';
require_once './includes/router.php';
require_once './classes/JWT.php';
class TareaxRecursoController
{
    public static function crear(Router $router)
    {
        $tareaxrecurso = new TareaxRecursoModel;
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
                        $tareaxrecurso->sincronizar($_POST);
                        $alertas = $tareaxrecurso->validarNuevaCuenta();
                        if (empty($alertas['error'])) {
                            $resultado = $tareaxrecurso->guardar();
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

                $router->render('/forms/tareaxrecurso', [
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

    public static function obtenerTareaxRecursos()
    {
        $tareaxrecurso = new TareaxRecursoModel;
        $tareaxrecursosArray = [];

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
                        $tareaxrecursosArray = TareaxRecursoModel::obtenerTodasTareasxRecursos();
                    } catch (\Throwable $th) {
                        $tareaxrecurso::setAlerta('error', $th->getMessage());
                    }
                }
                echo json_encode($tareaxrecursosArray);
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

    public static function actualizarTareaxRecurso()
    {
        header('Content-Type: application/json');
        $alertas = [];
        $tareaxrecurso = new TareaxRecursoModel;

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

                    $encontrado = TareaxRecursoModel::find($datos['id']);

                    if ($encontrado) {
                        $tareaxrecurso->sincronizar($datos);
                        $alertas = $tareaxrecurso->validarNuevaCuenta();

                        if (!empty($alertas['error'])) {
                            echo json_encode(['error' => $alertas['error']]);
                            return;
                        }
                        $tareaxrecurso->guardar();
                        echo json_encode(['success' => 'TareaxRecurso actualizado exitosamente!']);
                    } else {
                        echo json_encode(['error' => 'No se encontró una tareaxrecurso con el ID proporcionado']);
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

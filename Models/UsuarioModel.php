<?php

require_once 'includes/ActiveRecord.php';

class UsuarioModel extends ActiveRecord
{


    // Base de datos
    protected static $tabla = 'personas';
    protected static $columnasDB = ['id', 'nombre', 'apellido', 'email', 'direccion', 'password', 'telefono', 'sexo', 'fecha_nacimiento', 'profesion', 'rol', 'confirmado', 'token'];

    public $id;
    public $nombre;
    public $apellido;
    public $email;
    public $direccion;
    public $password;
    public $telefono;
    public $sexo;
    public $fecha_nacimiento;
    public $profesion;
    public $rol;

    public $confirmado;
    public $token;


    public function __construct($args = [])
    {
        $this->id = $args['id'] ?? null;
        $this->nombre = $args['nombre'] ?? '';
        $this->apellido = $args['apellido'] ?? '';
        $this->email = $args['email'] ?? '';
        $this->direccion = $args['direccion'] ?? '';
        $this->password = $args['password'] ?? '';
        $this->telefono = $args['telefono'] ?? '';
        $this->sexo = $args['sexo'] ?? '';
        $this->fecha_nacimiento = $args['fecha_nacimiento'] ?? '';
        $this->profesion = $args['profesion'] ?? '';
        $this->rol = $args['rol'] ?? 'Default';
        $this->confirmado = $args['confirmado'] ?? '0';
        $this->token = $args['token'] ?? '';
    }


    // Mensajes de validación para la creación de una cuenta
    public function validarNuevaCuenta()
    {
        if (!$this->nombre) {
            self::$alertas['error'][] = 'El Nombre es Obligatorio';
        }
        if (!$this->apellido) {
            self::$alertas['error'][] = 'El Apellido es Obligatorio';
        }
        if (!$this->email || !filter_var($this->email, FILTER_VALIDATE_EMAIL)) {
            self::$alertas['error'][] = 'El Email es Obligatorio o no contiene formato válido.';
        }
        if (!$this->direccion) {
            self::$alertas['error'][] = 'La dirección es obligatoria';
        }
        if (!$this->password) {
            self::$alertas['error'][] = 'El Password es Obligatorio';
        }
        if (strlen($this->password) < 6) {
            self::$alertas['error'][] = 'El password debe contener al menos 6 caracteres';
        }
        if (!$this->telefono) {
            self::$alertas['error'][] = 'El telefono es obligatorio';
        }

        if (empty($this->sexo) || !is_string($this->sexo) || ($this->sexo != 'Masculino' && $this->sexo != 'Femenino')) {
            self::$alertas['error'][] = 'El sexo es obligatorio';
        }

        if (empty($this->fecha_nacimiento)) {
            self::$alertas['error'][] = 'La fecha de nacimiento es obligatoria';
        } else {
            $fecha_nacimiento = strtotime($this->fecha_nacimiento);
            $fecha_minima = strtotime('-18 years', strtotime(date('Y-m-d')));

            if ($fecha_nacimiento > $fecha_minima) {
                self::$alertas['error'][] = 'Debe tener al menos 18 años de edad';
            }
        }

        if (!$this->profesion) {
            self::$alertas['error'][] = 'La profesión es obligatoria';
        }


        return self::$alertas;
    }


    public function validarLogin()
    {
        if (!$this->email || !filter_var($this->email, FILTER_VALIDATE_EMAIL)) {
            self::$alertas['error'][] = 'El email es Obligatorio';
        }
        if (!$this->password) {
            self::$alertas['error'][] = 'El Password es Obligatorio';
        }

        return self::$alertas;
    }

    public function validarEmail()
    {
        if (!$this->email || !filter_var($this->email, FILTER_VALIDATE_EMAIL)) {
            self::$alertas['error'][] = 'El email es Obligatorio';
        }
        return self::$alertas;
    }

    public function validarPassword()
    {
        if (!$this->password) {
            self::$alertas['error'][] = 'El Password es obligatorio';
        }
        if (strlen($this->password) < 6) {
            self::$alertas['error'][] = 'El Password debe tener al menos 6 caracteres';
        }

        return self::$alertas;
    }

    // Revisa si el usuario ya existe
    public static function existeUsuario($email)
    {
        $query = "SELECT * FROM " . static::$tabla . " WHERE email = :email";
        $resultado = self::$db->prepare($query);
        $resultado->execute([':email' => $email]);
        $usuario = $resultado->fetch(PDO::FETCH_ASSOC);

        if ($usuario) {
            return new UsuarioModel($usuario);
        } else {
            return false;
        }
    }

    public function hashPassword()
    {
        $this->password = password_hash($this->password, PASSWORD_BCRYPT);
    }

    public function crearToken()
    {
        $this->token = uniqid();
    }

    public function comprobarPasswordAndVerificado($password)
    {
        $resultado = password_verify($password, $this->password);

        if (!$resultado || !$this->confirmado) {
            self::$alertas['error'][] = 'Password Incorrecto o tu cuenta no ha sido confirmada';
        } else {
            return true;
        }
    }

    public static function obtenerTodosUsuarios()
    {
        $personas = self::all();
        return array_map(function ($personas) {
            return [
                'id' => $personas->id,
                'nombre' => $personas->nombre,
                'apellido' => $personas->apellido,
                'email' => $personas->email,
                'sexo' => $personas->sexo,
                'direccion' => $personas->direccion,
                'telefono' => $personas->telefono,
                'fecha_nacimiento' => $personas->fecha_nacimiento,
                'profesion' => $personas->profesion
            ];
        }, $personas);
    }
}

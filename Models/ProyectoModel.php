<?php
require_once './includes/ActiveRecord.php';
require_once './Models/UsuarioModel.php';

class ProyectoModel extends ActiveRecord
{

    // Base de datos
    protected static $tabla = 'proyectos';
    protected static $columnasDB = ['id', 'descripcion', 'fecha_inicio', 'fecha_entrega', 'valor','lugar', 'responsable', 'estado'];

    public $id;
    public $descripcion;
    public $fecha_inicio;
    public $fecha_entrega;
    public $valor;

    public $lugar;
    public $responsable;
    public $estado;

    public static $alertas = [
        'error' => [],
        'success' => []
    ];

    public function __construct($args = [])
    {
        $this->id = $args['id'] ?? null;
        $this->descripcion = $args['descripcion'] ?? '';
        $this->fecha_inicio = $args['fecha_inicio'] ?? '';
        $this->fecha_entrega = $args['fecha_entrega'] ?? '';
        $this->valor = $args['valor'] ?? '';
        $this->lugar = $args['lugar'] ?? '';
        $this->responsable = $args['responsable'] ?? '';
        $this->estado = $args['estado'] ?? 'En Proceso';
    }

    public function validarNuevaCuenta()
    {
        self::$alertas = ['error' => [], 'success' => []];

        // Validación de la descripción
        if (empty($this->descripcion) || !is_string($this->descripcion)) {
            self::$alertas['error'][] = 'La descripción del proyecto es obligatoria';
        }

        // Validación de fechas
        if (empty($this->fecha_inicio) || empty($this->fecha_entrega) ) {
            self::$alertas['error'][] = 'Las fechas deben ser obligatorias';
        } elseif (strtotime($this->fecha_entrega) < strtotime($this->fecha_inicio)) {
            self::$alertas['error'][] = 'La fecha de entrega no puede ser anterior a la fecha de inicio';
        }

        // Validación del valor
        if (empty($this->valor) || !is_numeric($this->valor) || $this->valor <= 0) {
            self::$alertas['error'][] = 'El valor del proyecto es un número obligatorio';
        }

        // Validación del lugar
        if (empty($this->lugar) || !is_string($this->lugar)) {
            self::$alertas['error'][] = 'El lugar del proyecto es obligatorio';
        }

        // Validación del responsable
        if (empty($this->responsable) || !is_numeric($this->responsable) || !UsuarioModel::where('id', $this->responsable) || $this->responsable <= 0) {
            self::$alertas['error'][] = 'El resposable debe tener una ID válida';
        }


        return self::$alertas;
    }
    public static function obtenerTodosProyectos()
    {
        $proyectos = self::all();
        return array_map(function ($proyecto) {
            return [
                'id' => $proyecto->id,
                'descripcion' => $proyecto->descripcion,
                'fecha_inicio' => $proyecto->fecha_inicio,
                'fecha_entrega' => $proyecto->fecha_entrega,
                'valor' => $proyecto->valor,
                'lugar' => $proyecto->lugar,
                'responsable' => $proyecto->responsable,
                'estado' => $proyecto->estado
            ];
        }, $proyectos);

    }
}

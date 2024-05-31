<?php
require_once './includes/ActiveRecord.php';

class RecursoModel extends ActiveRecord
{

    // Base de datos
    protected static $tabla = 'recursos';
    protected static $columnasDB = ['id', 'descripcion', 'valor', 'unidad'];

    public $id;
    public $descripcion;
    public $valor;
    public $unidad;

    public static $alertas = [
        'error' => [],
        'success' => []
    ];

    public function __construct($args = [])
    {
        $this->id = $args['id'] ?? null;
        $this->descripcion = $args['descripcion'] ?? '';
        $this->valor = $args['valor'] ?? '';
        $this->unidad = $args['unidad'] ?? '';
    }

    public function validarNuevaCuenta()
    {
        self::$alertas = ['error' => [], 'success' => []];

        // Validación de la descripción
        if (empty($this->descripcion) || !is_string($this->descripcion)) {
            self::$alertas['error'][] = 'El descripción de la tarea es obligatoria';
        }


        // Validación del valor
        if (empty($this->valor) || !is_numeric($this->valor) || $this->valor <= 0) {
            self::$alertas['error'][] = 'El valor debe ser un número válido';
        }

        // Validación de la unidad
        if (empty($this->unidad)) {
            self::$alertas['error'][] = 'Debe ingresar una unidad válida';
        }

    
        return self::$alertas;
    }
    public static function obtenerTodosRecursos()
    {
        $recursos = self::all();
        return array_map(function ($recurso) {
            return [
                'id' => $recurso->id,
                'descripcion' => $recurso->descripcion,
                'valor' => $recurso->valor,
                'unidad' => $recurso->unidad
            ];
        }, $recursos);
    }
}

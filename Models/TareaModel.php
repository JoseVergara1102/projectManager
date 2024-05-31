<?php
require_once './includes/ActiveRecord.php';
require_once './Models/ActividadModel.php';

class TareaModel extends ActiveRecord
{

    // Base de datos
    protected static $tabla = 'tareas';
    protected static $columnasDB = ['id', 'descripcion', 'fecha_inicio', 'fecha_final', 'id_actividad', 'estado', 'presupuesto'];

    public $id;
    public $descripcion;
    public $fecha_inicio;
    public $fecha_final;
    public $id_actividad;

    public $estado;
    public $presupuesto;

    public static $alertas = [
        'error' => [],
        'success' => []
    ];

    public function __construct($args = [])
    {
        $this->id = $args['id'] ?? null;
        $this->descripcion = $args['descripcion'] ?? '';
        $this->fecha_inicio = $args['fecha_inicio'] ?? '';
        $this->fecha_final = $args['fecha_final'] ?? '';
        $this->id_actividad = $args['id_actividad'] ?? '';
        $this->estado = $args['estado'] ?? 'En Proceso';
        $this->presupuesto = $args['presupuesto'] ?? '';
    }

    public function validarNuevaCuenta()
    {
        self::$alertas = ['error' => [], 'success' => []];

        // Validación de la descripción
        if (empty($this->descripcion) || !is_string($this->descripcion)) {
            self::$alertas['error'][] = 'El descripción de la tarea es obligatoria';
        }


        // Validación de fechas
        if (empty($this->fecha_inicio) || empty($this->fecha_final)) {
            self::$alertas['error'][] = 'Las fechas deben ser obligatorias';
        } elseif (strtotime($this->fecha_final) < strtotime($this->fecha_inicio)) {
            self::$alertas['error'][] = 'La fecha final no puede ser anterior a la fecha de inicio';
        } elseif (strtotime($this->fecha_final) > strtotime(ActividadModel::getCampoPorId($this->id_actividad, 'fecha_final'))) {
            self::$alertas['error'][] = 'La fecha final de la tarea NO puede ser mayor a la fecha de final de la actividad asociada';
        } elseif (strtotime($this->fecha_inicio) < strtotime(ActividadModel::getCampoPorId($this->id_actividad, 'fecha_inicio'))) {
            self::$alertas['error'][] = 'La fecha de inicio de la tarea NO puede ser menor a la fecha de inicio de la actividad asociada';
        }


        // Validación de la actividad
        if (empty($this->id_actividad) || !is_numeric($this->id_actividad) || !ActividadModel::where('id', $this->id_actividad) || $this->id_actividad <= 0) {
            self::$alertas['error'][] = 'La actividad debe tener una ID válida';
        }


        // Validación del presupuesto
        if (empty($this->presupuesto) || !is_numeric($this->presupuesto) || $this->presupuesto <= 0) {
            self::$alertas['error'][] = 'El presupuesto de la tarea es un número obligatorio';
        }

        if ($this->presupuesto > ActividadModel::getCampoPorId($this->id_actividad, 'presupuesto')) {
            self::$alertas['error'][] = 'El presupuesto de la tarea debe tener un valor inferior al de la actividad asociada.';
        }

        if ($this->estado === 'En proceso' && ActividadModel::getCampoPorId($this->id_actividad, 'estado') === 'Entregado') {
            self::$alertas['error'][] = 'El estado de tu tarea NO puede ser asignado en proceso si el estado de la actividad ha sido entregada';
        }

        if ($this->estado === 'En proceso' && ActividadModel::getCampoPorId($this->id_actividad, 'estado') === 'No Entregado') {
            self::$alertas['error'][] = 'El estado de tu tarea NO puede ser asignado en proceso si la actividad NO ha sido entregada';
        }

        if($this->presupuesto + TareaModel::obtenerSuma('id_actividad', $this->id_actividad, 'presupuesto') > ActividadModel::getCampoPorId($this->id_actividad, 'presupuesto')){
            self::$alertas['error'][] = 'La suma de los presupuestos de las tareas relacionadas a una actividad NO pueden ser mayor al presupuesto de la actividad';
        }


        return self::$alertas;
    }
    public static function obtenerTodasTareas()
    {
        $tareas = self::all();
        return array_map(function ($tarea) {
            return [
                'id' => $tarea->id,
                'descripcion' => $tarea->descripcion,
                'fecha_inicio' => $tarea->fecha_inicio,
                'fecha_final' => $tarea->fecha_final,
                'id_actividad' => $tarea->id_actividad,
                'estado' => $tarea->estado,
                'presupuesto' => $tarea->presupuesto
            ];
        }, $tareas);
    }
}

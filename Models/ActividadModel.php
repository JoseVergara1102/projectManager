<?php
require_once './includes/ActiveRecord.php';
require_once './Models/ProyectoModel.php';
require_once './Models/UsuarioModel.php';

class ActividadModel extends ActiveRecord
{

    // Base de datos
    protected static $tabla = 'actividades';
    protected static $columnasDB = ['id', 'descripcion', 'fecha_inicio', 'fecha_final', 'id_proyecto', 'responsable', 'estado', 'presupuesto'];

    public $id;
    public $descripcion;
    public $fecha_inicio;
    public $fecha_final;
    public $id_proyecto;

    public $responsable;
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
        $this->id_proyecto = $args['id_proyecto'] ?? '';
        $this->responsable = $args['responsable'] ?? '';
        $this->estado = $args['estado'] ?? 'En Proceso';
        $this->presupuesto = $args['presupuesto'] ?? '';
    }

    public function validarNuevaCuenta()
    {
        self::$alertas = ['error' => [], 'success' => []];

        // Validación de la descripción
        if (empty($this->descripcion) || !is_string($this->descripcion)) {
            self::$alertas['error'][] = 'El descripción de la actividad es obligatoria';
        }


        // Validación de fechas
        if (empty($this->fecha_inicio) || empty($this->fecha_final)) {
            self::$alertas['error'][] = 'Las fechas deben ser obligatorias';
        } elseif (strtotime($this->fecha_final) < strtotime($this->fecha_inicio)) {
            self::$alertas['error'][] = 'La fecha final no puede ser anterior a la fecha de inicio';
        } elseif (strtotime($this->fecha_final) > strtotime(ProyectoModel::getCampoPorId($this->id_proyecto, 'fecha_entrega'))) {
            self::$alertas['error'][] = 'La fecha final de la actividad NO puede ser mayor a la fecha de entrega del proyecto asociado';
        } elseif (strtotime($this->fecha_inicio) < strtotime(ProyectoModel::getCampoPorId($this->id_proyecto, 'fecha_inicio'))) {
            self::$alertas['error'][] = 'La fecha de inicio de la actividad NO puede ser menor a la fecha de inicio del proyecto asociado';
        }


        // Validación del proyecto
        if (empty($this->id_proyecto) || !is_numeric($this->id_proyecto) || !ProyectoModel::where('id', $this->id_proyecto) || $this->id_proyecto <= 0) {
            self::$alertas['error'][] = 'El proyecto debe tener una ID válida';
        }

        // Validación del responsable
        if (empty($this->responsable) || !is_numeric($this->responsable) || !UsuarioModel::where('id', $this->responsable) || $this->responsable <= 0) {
            self::$alertas['error'][] = 'El resposable debe tener una ID válida';
        } else {
        }

        // Validación del presupuesto
        if (empty($this->presupuesto) || !is_numeric($this->presupuesto) || $this->presupuesto <= 0) {
            self::$alertas['error'][] = 'El presupuesto de la actividad es un número obligatorio';
        }

        if ($this->presupuesto > ProyectoModel::getCampoPorId($this->id_proyecto, 'valor')) {
            self::$alertas['error'][] = 'El presupuesto de la actividad debe tener un valor inferior al del proyecto relacionado.';
        }

        if ($this->estado === 'En proceso' && ProyectoModel::getCampoPorId($this->id_proyecto, 'estado') === 'Entregado') {
            self::$alertas['error'][] = 'El estado de tu actividad NO puede ser asignado en proceso si el estado del proyecto ha sido entregado';
        }

        if ($this->estado === 'En proceso' && ProyectoModel::getCampoPorId($this->id_proyecto, 'estado') === 'No Entregado') {
            self::$alertas['error'][] = 'El estado de tu actividad NO puede ser asignado en proceso si el proyecto NO ha sido entregado';
        }

        if($this->presupuesto + ActividadModel::obtenerSuma('id_proyecto', $this->id_proyecto, 'presupuesto') > ProyectoModel::getCampoPorId($this->id_proyecto, 'valor')){
            self::$alertas['error'][] = 'La suma de los presupuestos de las actividades relacionadas a un proyecto NO pueden ser mayor al presupuesto del proyecto';
        }





        return self::$alertas;
    }
    public static function obtenerTodasActividades()
    {
        $actividades = self::all();
        return array_map(function ($actividad) {
            return [
                'id' => $actividad->id,
                'descripcion' => $actividad->descripcion,
                'fecha_inicio' => $actividad->fecha_inicio,
                'fecha_final' => $actividad->fecha_final,
                'id_proyecto' => $actividad->id_proyecto,
                'responsable' => $actividad->responsable,
                'estado' => $actividad->estado,
                'presupuesto' => $actividad->presupuesto
            ];
        }, $actividades);
    }
}

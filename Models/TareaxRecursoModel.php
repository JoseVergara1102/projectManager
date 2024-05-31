<?php
require_once './includes/ActiveRecord.php';
require_once './Models/TareaModel.php';
require_once './Models/RecursoModel.php';

class TareaxRecursoModel extends ActiveRecord
{

    // Base de datos
    protected static $tabla = 'tareaxrecurso';
    protected static $columnasDB = ['id', 'id_tarea', 'id_recurso', 'cantidades'];

    public $id;
    public $id_tarea;
    public $id_recurso;
    public $cantidades;

    public static $alertas = [
        'error' => [],
        'success' => []
    ];

    public function __construct($args = [])
    {
        $this->id = $args['id'] ?? null;
        $this->id_tarea = $args['id_tarea'] ?? '';
        $this->id_recurso = $args['id_recurso'] ?? '';
        $this->cantidades = $args['cantidades'] ?? '';
    }

    public function validarNuevaCuenta()
    {
        self::$alertas = ['error' => [], 'success' => []];
 
        // Validación de la tarea
        if (empty($this->id_tarea) || !is_numeric($this->id_tarea) || $this->id_tarea <= 0 || !TareaModel::where('id', $this->id_tarea)) {
            self::$alertas['error'][] = 'El ID de la tarea debe ser un ID válido registrado';
        }

        // Validación del recurso
        if (empty($this->id_recurso) || !is_numeric($this->id_recurso) || $this->id_recurso <= 0 || !RecursoModel::where('id', $this->id_recurso)) {
            self::$alertas['error'][] = 'El ID del recurso debe ser un ID válido registrado';
        }

        // Validación de las cantidades
        if (empty($this->cantidades) || !is_numeric($this->cantidades) || $this->cantidades <= 0) {
            self::$alertas['error'][] = 'La cantidad debe ser un valor numérico válido';
        }
  
  
        return self::$alertas;
    }
    public static function obtenerTodasTareasxRecursos()
    {
        $tareasxrecursos = self::all();
        return array_map(function ($tareasxrecurso) {
            return [
                'id' => $tareasxrecurso->id,
                'id_tarea' => $tareasxrecurso->id_tarea,
                'id_recurso' => $tareasxrecurso->id_recurso,
                'cantidades' => $tareasxrecurso->cantidades
            ];
        }, $tareasxrecursos);
    }
}

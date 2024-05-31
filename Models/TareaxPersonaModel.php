<?php
require_once './includes/ActiveRecord.php';
require_once './Models/TareaModel.php';
require_once './Models/UsuarioModel.php';

class TareaxPersonaModel extends ActiveRecord
{

    // Base de datos
    protected static $tabla = 'tareaxpersona';
    protected static $columnasDB = ['id', 'id_tarea', 'id_persona', 'duracion'];

    public $id;
    public $id_tarea;
    public $id_persona;
    public $duracion;

    public static $alertas = [
        'error' => [],
        'success' => []
    ];

    public function __construct($args = [])
    {
        $this->id = $args['id'] ?? null;
        $this->id_tarea = $args['id_tarea'] ?? '';
        $this->id_persona = $args['id_persona'] ?? '';
        $this->duracion = $args['duracion'] ?? '';
    }

    public function validarNuevaCuenta()
    {
        self::$alertas = ['error' => [], 'success' => []];
 
        // Validación de la tarea
        if (empty($this->id_tarea) || !is_numeric($this->id_tarea) || $this->id_tarea <= 0 || !TareaModel::where('id', $this->id_tarea)) {
            self::$alertas['error'][] = 'El ID de la tarea debe ser un ID válido registrado';
        }

        // Validación de la persona
        if (empty($this->id_persona) || !is_numeric($this->id_persona) || $this->id_persona <= 0 || !UsuarioModel::where('id', $this->id_persona)) {
            self::$alertas['error'][] = 'El ID de la persona debe ser un ID válido registrado';
        }

        // Validación de la duracion
        if (empty($this->duracion) || !is_numeric($this->duracion) || $this->duracion <= 0) {
            self::$alertas['error'][] = 'La duración debe ser un valor numérico válido';
        }
  
  
        return self::$alertas;
    }
    public static function obtenerTodasTareasxPersonas()
    {
        $tareasxpersonas = self::all();
        return array_map(function ($tareasxpersona) {
            return [
                'id' => $tareasxpersona->id,
                'id_tarea' => $tareasxpersona->id_tarea,
                'id_persona' => $tareasxpersona->id_persona,
                'duracion' => $tareasxpersona->duracion
            ];
        }, $tareasxpersonas);
    }
}

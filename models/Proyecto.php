<?php
namespace Model;

use Model\ActiveRecord;

class Proyecto extends ActiveRecord {
    protected static $tabla = 'proyectos';
    protected static $columnasDB = ['id', 'proyecto', 'url', 'propietarioid'];

    public $id;
    public $proyecto;
    public $url;
    public $propietarioid;

    public function __construct($args = [])
    {
        $this->id = $args['id'] ?? null;
        $this->proyecto = $args['proyecto'] ?? '';
        $this->url = $args['url'] ?? '';
        $this->propietarioid = $args['propietarioid'] ?? '';
    }

    public function validarProyecto() {
        if(!$this->proyecto) {
            self::$alertas['error'][] = 'El Nombre del Proyecto es Obligatorio';
        }
        return self::$alertas;
    }

 public function eliminar() {
    // Elimina las tareas asociadas a este proyecto
    $tareas = \Model\Tarea::belongsTo('proyectoid', $this->id);
    if($tareas) {
        foreach($tareas as $tarea) {
            $tarea->eliminar(); // <-- Ya es objeto, no necesitas new
        }
    }
    // Ahora elimina el proyecto
    parent::eliminar();
}
}

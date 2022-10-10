<?php 

namespace Model;

class Proyecto extends ActiveRecord {
    protected static $tabla = 'proyectos';
    protected static $columnasDB = ['id','proyecto','url','propetarioId'];

    public function __construct($args = [])
    {
        $this->id = $args['id'] ?? null;
        $this->proyecto = $args['proyecto'] ?? '';
        $this->url = $args['url'] ?? '';
        $this->propetarioId = $args['propetarioId'] ?? '';
    }

    public function validarProyecto(){
        if(!$this->proyecto) {
            self::$alertas['error'][] = 'El nombre del Proyecto es Obligatorio';
        }
        return self::$alertas;
    }
}
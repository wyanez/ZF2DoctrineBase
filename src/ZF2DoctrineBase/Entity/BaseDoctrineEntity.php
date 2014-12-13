<?php
namespace ZF2DoctrineBase\Entity;

/**
*  Clase base para cuqlquier entidad del negocio persisitble en Base de Datos 
*  @author William Yanez - Septiembre 2013 
*/

abstract class BaseDoctrineEntity implements Entity{

    public function getArrayCopy(){
        $props= $this->getArrayProperties();
        $data=array();
        foreach ($props as $field) {
            $data[$field]= $this->$field;
        }
        return $data;
    }

    public function getArrayProperties(){
        $data=array();
        $reflect = new \ReflectionClass($this);
        $props   = $reflect->getProperties();
        foreach ($props as $property) {
            $field = $property->getName();
            $data[]= $field;
        }
        return $data;
    }

    public function __get($field){
        $value=$this->get_property($field);
        if(is_null($value)){ 
            //$class=get_class($this);
            //echo "<pre>__get: Propiedad $field no definida en $class\n</pre>";
            return null;
        } 
        return $value;
    }

    public function __set($field,$value){
        $this->set_property($field,$value);
    }

    protected function get_property($field){
        if (property_exists($this,$field)){
            $class=get_class($this);
            $reflector = new \ReflectionClass($class);
            $prop = $reflector->getProperty($field);
            $prop->setAccessible(true);
            return $prop->getValue($this);
        }
        return null;
    }

    protected function set_property($field,$value){
        if (property_exists($this,$field)){
            $class=get_class($this);
            $reflector = new \ReflectionClass($class);
            $prop = $reflector->getProperty($field);
            $prop->setAccessible(true);
            $prop->setValue($this, $value);
        }
        //else echo "<pre>__set: Propiedad $field no definida\n</pre>";
    }

}

?>
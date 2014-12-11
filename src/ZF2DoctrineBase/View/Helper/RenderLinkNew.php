<?php
    namespace ZF2DoctrineBase\View\Helper;

    use Zend\View\Helper\AbstractHelper;

    /**
    * RenderLinkNew
    * View helper para renderizar un boton de Agregar. Actualizado para Bootstrap3 
    * @author William Yanez
    */
    class RenderLinkNew extends AbstractHelper{

        public function __construct(){  
        }

        public function __invoke($url,$title='Agregar'){
            $str= "<a href='$url' class='btn btn-default' title='$title'>"; 
            $str.="<span class='glyphicon glyphicon-plus'></span>$title</a>";            
            return $str; 
        }

    }
?>
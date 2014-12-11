<?php
    namespace ZF2DoctrineBase\View\Helper;

    use Zend\View\Helper\AbstractHelper;

    /**
    * RenderLinkEdit
    * View helper para renderizar un link de Editar. Actualizado para Bootstrap3 
    * @author William Yanez
    */
    class RenderLinkEdit extends AbstractHelper{

        public function __construct(){  
        }

        public function __invoke($url,$title='Editar'){
            $str= "<a href='$url'>"; 
            $str.='<span class="glyphicon glyphicon-edit" title="'.$title.'"></span></a>';
            return $str; 
        }

    }
?>
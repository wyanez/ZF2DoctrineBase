<?php
    namespace ZF2DoctrineBase\View\Helper;

    use Zend\View\Helper\AbstractHelper;

    /**
    * RenderLinkDelete
    * View helper para renderizar un link de Eliminar. Actualizado para Bootstrap3 
    * @author William Yanez
    */
    class RenderLinkDelete extends AbstractHelper{

        public function __construct(){  
        }

        public function __invoke($url){
            $str= "<a href='$url' onClick=\"return confirm('Está seguro de eliminar ?')\">"; 
            $str.='<span class="glyphicon glyphicon-remove" title="Eliminar"></span></a>';            
            return $str; 
        }

    }
?>
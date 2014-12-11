<?php
    namespace ZF2DoctrineBase\View\Helper;

    use Zend\View\Helper\AbstractHelper;

    /**
    * RenderLinkBack
    * View helper para renderizar un botón de Regresar. Actualizado para Bootstrap3 
    * @author William Yanez
    */
    
    class RenderLinkBack extends AbstractHelper{

        public function __construct(){  
        }

        public function __invoke($urlBase,$controller=null){
            if(is_null($controller)) $url=$this->getView()->url($urlBase);
            else $url=$this->getView()->url($urlBase,array('controller'=>$controller));
            
            $str= "<a href='$url' class='btn btn-default'>Regresar</a>";           
            return $str; 
        }
    }
?>
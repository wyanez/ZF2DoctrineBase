<?php
    namespace ZF2DoctrineBase\View\Helper;

    use Zend\View\Helper\AbstractHelper;

    /**
    * RenderButtonDelete
    * View helper para renderizar un boton de Eliminar. Actualizado para Bootstrap3 
    * @author William Yanez
    */
    class RenderButtonDelete extends AbstractHelper{

        public function __construct(){  
        }

        public function __invoke($url,$title='Eliminar este Registro'){
            $str= "<a href='$url' onClick=\"return confirm('Está seguro de eliminar ?')\""; 
            $str.=" class='btn btn-danger'>$title</a>";          
            return $str; 
        }

    }
?>
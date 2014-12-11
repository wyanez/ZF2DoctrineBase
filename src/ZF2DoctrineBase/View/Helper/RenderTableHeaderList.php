<?php
    namespace Base\View\Helper;

    use Zend\View\Helper\AbstractHelper;

    /**
    * RenderTableHeaderList
    * View helper para renderizar el encabezado de las tablas de listados, 
    * con ordenamiento activado desde la cabecera de cada columna. 
    * @author William Yanez
    */
    class RenderTableHeaderList extends AbstractHelper{

        public function __construct(){  
        }

        public function __invoke($list_titles,$list_fields,$urlBase,$arrSort){
            $str= ""; 
            foreach ($list_titles as $pos=>$titulo){ 
                $field=$list_fields[$pos];
                $order=$arrSort[$field];
                $urlSort=$urlBase."?sort=".$field."&order=".$order; 
                $str .= "<th><a href='$urlSort'>$titulo</a></th>";
            }
            $str .= "<th>Acciones</th>";
            return $str; 
        }

    }
?>
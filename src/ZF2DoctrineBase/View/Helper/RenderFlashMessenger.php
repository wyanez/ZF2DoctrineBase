<?php
    namespace ZF2DoctrineBase\View\Helper;

    use Zend\View\Helper\AbstractHelper;

    /**
    * RenderFlashMessenger
    * View helper para renderizar un FlashMessenger. Actualizado para Bootstrap3 
    * @author William Yanez
    */
    class RenderFlashMessenger extends AbstractHelper{

        public function __construct(){  
        }

        // tipo: success,info,danger,warning
        public function __invoke($tipo='success'){
            if ($this->getView()->flashMessenger()->hasMessages()){ 
                $display_flash='true';
            } 
            else{
                $display_flash='none';
            } 

            $str= "<div id='flash_msg' style='display: $display_flash'>";
            $str.= "<div id='flash_msg_alert' class='alert alert-$tipo alert-dismissable'>";
            $str.= "<button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>";
            $str.= "<span id='flash_txt'>".$this->getView()->flashMessenger()->render()."</span>";
            $str.= "</div>";
            $str.= "</div>";

            return $str; 
        }
    }
?>
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
            $html='';
            if ($this->getView()->flashMessenger()->hasErrorMessages()){
                $render_flash = $this->getView()->flashMessenger()->render('error'); 
                $html .= $this->_renderHtmlFlashBs3('danger','true',$render_flash);
            }
            if ($this->getView()->flashMessenger()->hasWarningMessages()){
                $render_flash = $this->getView()->flashMessenger()->render('warning');
                $html .= $this->_renderHtmlFlashBs3('warning','true',$render_flash);
            }
            if ($this->getView()->flashMessenger()->hasMessages()){
                $render_flash = $this->getView()->flashMessenger()->render();
                $html .= $this->_renderHtmlFlashBs3('success','true',$render_flash);    
            }
            return $html;
        }
        
        private function _renderHtmlFlashBs3($tipo,$display_flash,$render_flash){
            $strHtml= "<div id='flash_msg' style='display: $display_flash'>";
            $strHtml.= "<div id='flash_msg_alert' class='alert alert-$tipo alert-dismissable'>";
            $strHtml.= "<button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>";
            $strHtml.= "<span id='flash_txt'>".$render_flash."</span>";
            $strHtml.= "</div>";
            $strHtml.= "</div>";
            return $strHtml;
        }
    }
?>
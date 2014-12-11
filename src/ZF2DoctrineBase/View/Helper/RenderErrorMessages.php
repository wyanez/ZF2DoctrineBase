<?php
    namespace ZF2DoctrineBase\View\Helper;

    use Zend\View\Helper\AbstractHelper;

    /**
    * RenderErrorMessages
    * View helper para renderizar los mensajes de error asociados al Form. 
    * Actualizado para Bootstrap3 
    * @author William Yanez
    */
    class RenderErrorMessages extends AbstractHelper{

        public function __construct(){  
        }

        public function __invoke($form){
             if (count($form->getMessages())==0) return '';

            $str = "<div class='alert alert-danger alert-dismissable'>"; 
            $str .="<button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>";
            $str .="<strong>Por favor corrija lo siguiente:</strong><br/>"; 
            
            if(is_null($form->getBaseFieldset())) $base=$form;
            else $base=$form->getBaseFieldset();

            foreach ($base->getElements() as $formElement) {
                if(count($formElement->getMessages())>0){
                    $label = $formElement->getLabel();
                    $str .="<strong>$label</strong>";
                    $str .= $this->getView()->formElementErrors($formElement);      
                }//if
            }//for
            $str .="</div>";
            $str .="<a onclick='toggleDisplayError()' id='link-error-show'>Ver errores</a>";
            $str .="<div id='error-show' style='display:none'>";
            $str .="<pre>". print_r($form->getMessages(),true)."</pre>"; 
            $str .="</div>"; 
            
            $auxJs = <<<'JS'
            <script>
            function toggleDisplayError(){
                $("#error-show").toggle();

                if ($("#error-show").is(':hidden')) $("#link-error-show").html("Ver Errores");
                else $("#link-error-show").html("Ocultar Errores"); 
            }
            </script>
JS;
            $str .= $auxJs;
            return $str; 
        }

    }
?>
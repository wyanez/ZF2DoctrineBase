<?php
    namespace ZF2DoctrineBase\View\Helper;

    use Zend\View\Helper\AbstractHelper;

    /**
    * RenderFormElement
    * View helper para renderizar un Elemento de un Form/Fieldset. 
    * Actualizado para Bootstrap3 
    * @author William Yanez
    */
    class RenderFormElement extends AbstractHelper{

        public function __construct(){  
        }

        public function __invoke($element,$width=5){
            if (($element instanceof \Zend\Form\Element\Submit) || 
                ($element instanceof \Zend\Form\Element\Csrf) ||
                ($element instanceof \Zend\Form\Element\Button) ){
                return "";
            } //endif
            
            $str= ""; 
            $view = $this->getView();
            $nameElement = $element->getName();

            $style = count($element->getMessages())>0 ? "has-error":"";   
            $str="<div class='form-group $style'>";

            if (!($element instanceof \Zend\Form\Element\Button) && 
                !($element instanceof \Zend\Form\Element\Hidden))
            $str.= $view->formLabel($element);   
                           
            $str.="<div class='col-md-$width'>";

            if ($element instanceof \Zend\Form\Element\Text){
                 $str.= $view->formText($element); 
            }
            elseif ($element instanceof \Zend\Form\Element\Textarea)   
                $str.= $view->formTextarea($element); 
            elseif ($element instanceof \Zend\Form\Element\Select)   
                $str.= $view->formSelect($element);
            elseif ($element instanceof \Zend\Form\Element\Checkbox)
                $str.= $view->formCheckbox($element);
            else
              $str.= $view->formElement($element);  
            
            $str.='</div>';
            $str.='</div>';       
            return $str; 
        }
    }
?>
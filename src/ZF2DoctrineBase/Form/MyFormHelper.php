<?php
namespace ZF2DoctrineBase\Form;

/**
* Colección de métodos utilitarios para usar en la creacion de Form/Fieldset
* @author William Yanez
*/

class MyFormHelper
{
  
    public static function getElementText($nameElement,$label='',$widthElement=0,$widthLabel=2,$required=false,$idElement=null){
        $element = array(
                'type' => 'Text',
                'name' => $nameElement,
                'options' => array('label' => $label,
                                   'label_attributes' => array('class'  => "col-md-$widthLabel control-label"),
                                  ), 
                'attributes' =>array(
                    'class' => 'form-control',
                )
            );
        if($widthElement>0) $element['attributes']['maxlength'] = $widthElement;
        if (!is_null($idElement)) $element['attributes']['id']= $idElement;
        if($required) $element['attributes']['required'] = 'true';

        return $element;
    }

    public static function getElementNumber($nameElement,$label='',$widthLabel=2,$required=false,$step=1,$min=0,$max=null,$idElement=null){
        $element = array(
                'type' => 'Number',
                'name' => $nameElement,
                'options' => array('label' => $label,
                                   'label_attributes' => array('class'  => "col-md-$widthLabel control-label"),
                                  ), 
                'attributes' =>array(
                    'class' => 'form-control',
                    'step'=> $step,
                    'min' => $min,
                )
        );
        if (!is_null($idElement)) $element['attributes']['id']= $idElement;
        if (!is_null($max)) $element['attributes']['max']= $idElement;
        if($required) $element['attributes']['required'] = 'true';
        
        return $element;
    }

    public static function getElementTextArea($nameElement,$label,$widthElement,$rows,$cols,$widthLabel=2,$idElement=null){
        $element = array(
            'type' => 'Textarea',
            'name' => $nameElement,
            'options' => array('label' => $label,
                               'label_attributes' => array('class'  => "col-md-$widthLabel control-label"),
                              ), 
            'attributes' =>array(
                'class' => 'form-control',
                'maxlength' => $widthElement,
                'rows'=>$rows, 
                'cols'=>$cols
            ),
        );
        if (!is_null($idElement)) $element['attributes']['id']= $idElement;
        return $element;
    }

    public static function getElementCheckbox($name,$label,$widthLabel=2,$idElement=null){
        $element = array(
                'name' => $name,
                'type' => 'Checkbox',
                'options' => array(
                     'label' => $label,
                     'label_attributes' => array('class'  => "col-md-$widthLabel control-label"),
                     'checked_value' =>1,
                     'unchecked_value' =>0   
                ),                
        );    
        if (!is_null($idElement)) $element['attributes']=array('id' => $idElement);
        return $element;        

    }

    public static function getElementCsrf($name ='csrf'){
        return array(
            'type' => 'Csrf',
            'name' => $name,
            'options' => array(
             'csrf_options' => array(
                 'timeout' => 1500 //25 minutos 
              ),
             )
        );
    }
    
    public static function getElementSubmitBtn($label='Guardar',$name='submit',$id='submitbutton'){
        return array(
            'name' => $name,
            'type' => 'Submit',
            'attributes' => array(
                'value' => $label,
                'id' => $id,
                'class' => 'btn btn-primary'
            )
        );    
    }
    
    public static function getElementCollection($name,$fieldset,$count = 1){
        return array(
            'type'    => 'Zend\Form\Element\Collection',
            'name'    => $name,
            'options' => array(
                'count'           => $count,
                'should_create_template' => true,
                'allow_add' => true,
                'allow_remove' => true,
                'target_element' => $fieldset,
            )
        );
    }

    public static function getElementButton($name,$label,$title='',$cssclass='btn-primary',$id=null){
        $element = array(
            'type'    => 'Button',
            'name'    => $name,
            'options' => array('label' => $label),
            'attributes' => array(
                'class' => "btn $cssclass",
                'title' => $title
            )        
        );
        if (!is_null($id)) $element['attributes']['id']= $id;
        return $element;        
    }

    public static function getElementDoctrineSelect($objectManager,$targetClass,$name,$label,$labelGenerator,$emptyOption='',$widthLabel=2,$id=null,$style=null,$class=null){
        $element = array(
               'type' =>'DoctrineModule\Form\Element\ObjectSelect',
               'name' => $name,
                'options' => array(
                    'object_manager' => $objectManager,
                    'target_class'   => $targetClass,
                    'empty_option'   => $emptyOption, 
                ),
                'attributes' =>array(),
        );
        if($label!=''){
            $element['options']['label'] = $label;
            $element['options']['label_attributes'] = array('class'  => "col-md-$widthLabel control-label");
        }

        if (is_string($labelGenerator)) 
             $element['options']['property'] = $labelGenerator;
        else $element['options']['label_generator'] = $labelGenerator;

        if (!is_null($id)) $element['attributes']['id']= $id;
        if (!is_null($style)) $element['attributes']['style']= $style;
        if (!is_null($class)) $element['attributes']['class']= $class;

        return $element;
    }

    public static function getElementSelect($name,$label,$options,$widthLabel=2,$id=null,$class=null){
        $element = array(
                'name' => $name,
                'type' => 'Select',
                'options' => array('label' => $label,
                                   'label_attributes' => array('class'  => "col-md-$widthLabel control-label",
                                   'empty_option' => '')
                ),
                'attributes' =>  array(
                    'options' => $options,
                ),
        );
        if (!is_null($id)) $element['attributes']['id']= $id;
        if (!is_null($class)) $element['attributes']['class']= $class;

        return $element;
     }

     public static function getElementHidden($name,$id=null){
        $element= array(
            'type' => 'Hidden',
            'name' => $name
        );
        if (!is_null($id)) $element['attributes'] = array('id' => $id);
        return $element;
    }      

    public static function getElementFieldset($type,$name,$label){
        return array(
           'type' => $type,
           'name' => $name,
           'options' => array(
               'label' => $label
           )
        );
    }

    public static function getElementFile($name,$label='',$widthLabel=2,$id=null){
        $element= array(
            'type' => 'File',
            'name' => $name,
            'options' => array('label' => $label,
                               'label_attributes' => array(
                                  'class'  => "col-md-$widthLabel control-label"),
                              ), 
            'attributes' =>array(
                'class' => 'form-control',
            )
        );
        if (!is_null($id)) $element['attributes'] = array('id' => $id);
        return $element;
    }

}

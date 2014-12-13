<?php
namespace ZF2DoctrineBase\Form;

use Zend\Form\Form;
use ZF2DoctrineBase\Form\MyFormHelper;

class BuscadorForm extends Form 
{ 
    public function __construct()
    {
        parent::__construct('Buscador');
        $this->add(MyFormHelper::getElementHidden('buscador','buscador'));
        $this->get('buscador')->setAttribute('style','width:100%');   
    }


}
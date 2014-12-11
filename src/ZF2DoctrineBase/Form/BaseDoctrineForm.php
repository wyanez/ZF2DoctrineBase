<?php
namespace ZF2DoctrineBase\Form;

use DoctrineModule\Persistence\ObjectManagerAwareInterface;
use Doctrine\Common\Persistence\ObjectManager;
use DoctrineModule\Stdlib\Hydrator\DoctrineObject as DoctrineHydrator;

use Zend\Form\Form;

class BaseDoctrineForm extends Form implements ObjectManagerAwareInterface
{
    /*
    * @var \Doctrine\Common\Persistence\ObjectManager
    */
    protected $objectManager;

    public function __construct($name='')
    {
        parent::__construct($name);        
    }

    /**
     * Get the object manager
     *
     * @return ObjectManager
     */
    public function getObjectManager(){
        return $this->objectManager;
    }

     /**
     * Set the object manager
     *
     * @param ObjectManager $objectManager
     */
    public function setObjectManager(ObjectManager $objectManager){
        $this->objectManager= $objectManager;
    }

    public function setEntity($entityInstanceEmpty){
         $this->setHydrator(new DoctrineHydrator($this->objectManager))
              ->setObject($entityInstanceEmpty);
    }

    /**
     * Metodo para agregar los elementos que formaran parte de la GUI.
     * La idea es que sea llamado despues del setEntity y asi tener inicializado el objectManager e Hydrator 
     * asociado al Form. 
     * Debe ser sobreescrito por las clases hijas si se desea agregar componentes que usen el objectManager. 
     */
    public function setupGui(){
        //Implementacion vacia. Por defecto no hace nada
    }

}

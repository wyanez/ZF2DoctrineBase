<?php
namespace ZF2DoctrineBase\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use DoctrineModule\Persistence\ObjectManagerAwareInterface;
use Doctrine\Common\Persistence\ObjectManager;

/**
* BaseDoctrineController
* Controlador Base para todos los controladores que usen Doctrine2 ORM
* @author William Yanez
*/
abstract class BaseDoctrineController extends AbstractActionController implements ObjectManagerAwareInterface
{
    /**
    *   @var Zend\Form\Form
    */
    protected $form;

    /**
    *   @var Doctrine\ORM\EntityManager
    */
    protected $objectManager;

    /**
     * Set the object manager
     *
     * @param ObjectManager $objectManager
     */
    public function setObjectManager(ObjectManager $objectManager){
        $this->objectManager= $objectManager;
    }

    /**
     * Get the object manager
     *
     * @return ObjectManager
     */
    public function getObjectManager(){
        if($this->objectManager == null){  
            $this->objectManager = $this->getServiceLocator()->get('Doctrine\ORM\EntityManager');
        }   
        return $this->objectManager;
    }

    protected function doctrineDebug($entity){
        echo "<pre>".\Doctrine\Common\Util\Debug::dump($entity)."</pre>";
    }

    protected function getLog(){
        return $this->getServiceLocator()->get('Application\Log');
    }
    
}
?>
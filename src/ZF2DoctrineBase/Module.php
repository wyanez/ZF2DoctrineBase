<?php
/**
 * ZF2DoctrineBase Module
 *
 * @link https://github.com/wryanez/ZF2DoctrineBase canonical source repository
 * @license http://framework.zend.com/license/new-bsd New BSD License
 */

namespace ZF2DoctrineBase;

use Zend\EventManager\EventInterface;
use Zend\ModuleManager\Feature\AutoloaderProviderInterface;
use Zend\ModuleManager\Feature\BootstrapListenerInterface;
use Zend\ModuleManager\Feature\ConfigProviderInterface;
use Zend\ModuleManager\Feature\ControllerPluginProviderInterface;
use Zend\ModuleManager\Feature\ViewHelperProviderInterface;
use Zend\Mvc\ApplicationInterface;

/**
 * Base Module for ZF2DoctrineBase
 * @author William Yanez <wyanez@gmail.com>
 */

class Module implements
    AutoloaderProviderInterface,
    BootstrapListenerInterface,
    ConfigProviderInterface,
    ControllerPluginProviderInterface,
    ViewHelperProviderInterface
{
    /**
     * {@inheritDoc}
     */
    public function onBootstrap(EventInterface $event)
    {
        /* @var $app \Zend\Mvc\ApplicationInterface */
        $app  = $event->getTarget();
    }

    /**
     * {@inheritDoc}
     */
    public function getViewHelperConfig()
    {
        return array(
          'invokables' => array(
            'renderFlashMessenger' =>'ZF2DoctrineBase\View\Helper\RenderFlashMessenger',
            'renderLinkEdit' =>'ZF2DoctrineBase\View\Helper\RenderLinkEdit',
            'renderLinkDelete' =>'ZF2DoctrineBase\View\Helper\RenderLinkDelete',
            'renderLinkNew' =>'ZF2DoctrineBase\View\Helper\RenderLinkNew', 
            'renderLinkBack' =>'ZF2DoctrineBase\View\Helper\RenderLinkBack', 
            'renderButtonDelete' =>'ZF2DoctrineBase\View\Helper\RenderButtonDelete',
            'renderErrorMessages' =>'ZF2DoctrineBase\View\Helper\RenderErrorMessages',
            'renderTableHeaderList' =>'ZF2DoctrineBase\View\Helper\RenderTableHeaderList',
            'myFormCollection' =>'ZF2DoctrineBase\View\Helper\MyFormCollection',
            'renderFormElement' =>'ZF2DoctrineBase\View\Helper\RenderFormElement',
          ),  
        );
    }

    /**
     * {@inheritDoc}
     */
    public function getControllerPluginConfig()
    {
        return array(
            //'factories' => array(
            //),
        );
    }

    /**
     * {@inheritDoc}
     */
    public function getAutoloaderConfig()
    {
        return array(
            'Zend\Loader\StandardAutoloader' => array(
                'namespaces' => array(
                    __NAMESPACE__ => __DIR__ . '/../../src/' . __NAMESPACE__,
                ),
            ),
        );
    }

    /**
     * {@inheritDoc}
     */
    public function getConfig()
    {
        return include __DIR__ . '/../../config/module.config.php';
    }
}

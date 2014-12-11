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
use Zend\ModuleManager\Feature\ViewHelperProviderInterface;
use Zend\ModuleManager\Feature\ServiceProviderInterface;

use Zend\Mvc\ApplicationInterface;

use Zend\Log\Writer\FirePhp,
    Zend\Log\Writer\FirePhp\FirePhpBridge,
    Zend\Log\Writer\Stream,
    Zend\Log\Logger,
    Zend\Log\Filter\Priority;
    
/**
 * Base Module for ZF2DoctrineBase
 * @author William Yanez <wyanez@gmail.com>
 */

class Module implements
    AutoloaderProviderInterface,
    BootstrapListenerInterface,
    ConfigProviderInterface,
    ViewHelperProviderInterface,
    ServiceProviderInterface
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

    /**
     * {@inheritDoc}
     */
    public function getServiceConfig()
    {
        return array(
            'factories' => array(
                'ZF2DoctrineBase\Log' => function ($sm) {
                    $log = new Logger();
                    $firephp_writer = new FirePhp(new FirePhpBridge(\FirePHP::getInstance(true)));
                    $log->addWriter($firephp_writer);

                    //Si no existe el directorio de log lo creamos
                    $log_dir= getcwd().'/data/log/';
                    if (!is_dir($log_dir)) mkdir($log_dir,0755,true);

                    $stream_writer = new Stream($log_dir.'zf2doctrinebase-'.-date('Ymd').'.log');
                    $log->addWriter($stream_writer);

                    $filter = new Priority(Logger::INFO); //ToDo En producción cambiar a Logger::ERROR
                    $stream_writer->addFilter($filter);

                    $log->info('Logging enabled');
                    return $log;
                },
            ),
        );
    }

}

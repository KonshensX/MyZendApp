<?php

namespace Post;

use Post\Model\Post;
use Post\Model\PostTable;
use Zend\Db\ResultSet\ResultSet;
use Zend\Db\TableGateway\TableGateway;
use Zend\ModuleManager\Feature\AutoloaderProviderInterface;
use Zend\ModuleManager\Feature\ConfigProviderInterface;

class Module implements AutoloaderProviderInterface, ConfigProviderInterface
{
    public function getAutoloaderConfig()
    {
        return array(
            'Zend\Loader\ClassMapAutoloader' => array(
                __DIR__ . '/autoload_classmap.php',
            ),
            'Zend\Loader\StandardAutoloader' => array(
                'namespaces' => array(
                    __NAMESPACE__ => __DIR__ . '/src/' . __NAMESPACE__,
                ),
            ),
        );
    }

    public function getConfig()
    {
        return include __DIR__ . '/config/module.config.php';
    }

    //Add this method:
    public function getServiceConfig()
    {
        return array(
            'factories' => array(
                'Post\Model\PostTable' => function($sm) {
                    $tableGateway = $sm->get('PostTableGateway');
                    $table = new PostTable($tableGateway);
                    return $table;
                },
                'PostTableGateway' => function ($sm) {
                    $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
                    $resultSetPrototype = new ResultSet();
                    $resultSetPrototype->setArrayObjectPrototype(new Post());
                    return new TableGateway('Post', $dbAdapter, null, $resultSetPrototype);
                },
            ),
        );
    }
}
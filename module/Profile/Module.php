<?php
namespace Profile;

use Profile\Model\Profile;
use Profile\Model\ProfileTable;
use Zend\Db\ResultSet\ResultSet;
use Zend\Db\TableGateway\TableGateway;

class Module
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

    public function getServiceConfig()
    {
        return array(
            'factories' => array(
                'Profile\Model\ProfileTable' => function($sm) {
                    $tableGateway = $sm->get('ProfileTableGateway');
                    $table = new ProfileTable($tableGateway);
                    return $table;
                },
                'ProfileTableGateway' => function ($sm) {
                    $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
                    $resultSetPrototype = new ResultSet();
                    $resultSetPrototype->setArrayObjectPrototype(new Profile());
                    return new TableGateway('Profile', $dbAdapter, null, $resultSetPrototype);
                },
            ),
        );
    }
}
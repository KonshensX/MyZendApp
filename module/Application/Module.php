<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2014 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Application;

use Application\Form\SearchForm;
use Profile\Model\Profile;
use Profile\Model\ProfileTable;
use Zend\Authentication\AuthenticationService;
use Zend\Mvc\ModuleRouteListener;
use Zend\Mvc\MvcEvent;

class Module
{
    protected $profileTable;
    private $serviceManager;

    public function onBootstrap(MvcEvent $e)
    {
        $eventManager        = $e->getApplication()->getEventManager();
        $moduleRouteListener = new ModuleRouteListener();
        $moduleRouteListener->attach($eventManager);
        $app = $e->getParam('application');
        $this->serviceManager = $app->getServiceManager();
        $app->getEventManager()->attach(MvcEvent::EVENT_RENDER, array($this, 'setFormToView'), 100);
    }

    public function setFormToView ($event) {
        $auth = new AuthenticationService();

        if ($auth->getIdentity()) {
            $profile = $this->getProfileTable()->getProfile($auth->getIdentity());
        }
        //var_dump(Form\SearchForm::class);
        //die();
        //$form = new SearchForm();
        $profile = array();

        $viewModel = $event->getViewModel();
        $viewModel->setVariables(array(
            //'form' => $form,
            'profile' => $profile
        ));
    }



    public function getConfig()
    {
        return include __DIR__ . '/config/module.config.php';
    }

    public function getAutoloaderConfig()
    {
        return array(
            'Zend\Loader\StandardAutoloader' => array(
                'namespaces' => array(
                    __NAMESPACE__ => __DIR__ . '/src/' . __NAMESPACE__,
                ),
            ),
        );
    }

    public function getProfileTable () {

        if (!$this->profileTable) {
            $sm = $this->serviceManager;
            $this->profileTable = $sm->get(ProfileTable::class);
        }
        return $this->profileTable;
    }

}

<?php

namespace Profile\Controller;

use Profile\Form\ProfileForm;
use Profile\Model\ProfileTable;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Helper\ViewModel;

class ProfileController extends AbstractActionController {

    protected $profileTable;

    public function indexAction()
    {
        $repo = $this->getProfileTable()->fetchAll();
        //TODO
        return new ViewModel(array(
            '$repo' => $repo
        ));
    }

    public function addAction() {

    }

    public function displayAction () {

        $form = new ProfileForm();
        return array(
            'form' => $form
        );
    }

    public function getProfileTable () {

        if (!$this->profileTable) {
            $sm = $this->getServiceLocator();
            $this->profileTable = $sm->get(ProfileTable::class);
        }
        return $this->profileTable;
    }

}
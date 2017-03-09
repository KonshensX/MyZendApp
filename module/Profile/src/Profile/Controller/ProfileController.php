<?php

namespace Profile\Controller;

use Profile\Form\ImageUploadForm;
use Profile\Form\ProfileForm;
use Profile\Model\Profile;
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
        $form = new ProfileForm();

        $request = $this->getRequest();

        if ($request->isPost()) {
            // Make certain to merge the files info!
            $post = array_merge_recursive(
                $request->getPost()->toArray(),
                $request->getFiles()->toArray()
            );
            $profile = new Profile();
            $form->setData($post);
            if ($form->isValid()) {
                $data = $form->getData();
                //Gets the file name
                $filename = (explode('\\', $data['image-file']['tmp_name'])[1]);
                $profile->image = $filename;
                $data['image'] = $filename;
                $profile->exchangeArray($data);
                $this->getProfileTable()->saveProfile($profile);
                return $this->redirect()->toRoute('profile');
            }
        }

        return array(
            'form' => $form
        );
    }

    public function displayAction () {

        $id = (int) $this->params()->fromRoute('id', 1);
        if (!$id) {
            return $this->redirect()->toRoute('post', array('action' => 'index'));
        }
        //Need the id of the profile to get information from the database
        $profile = $this->getProfileTable()->getProfile(array('id' => '2'));

        return array(
            'profile' => $profile
        );
    }

    public function getProfileTable () {

        if (!$this->profileTable) {
            $sm = $this->getServiceLocator();
            $this->profileTable = $sm->get(ProfileTable::class);
        }
        return $this->profileTable;
    }

    public function uploadAction () {
        $form = new ImageUploadForm();

        $request = $this->getRequest();

        if ($request->isPost()) {
            $post = array_merge_recursive(
                $request->getPost()->toArray(),
                $request->getFiles()->toArray()
            );

            $form->setData($post);

            //When the data is valid
            if ($form->isValid()) {
                $data = $form->getData();
            }

        }
        return array(
            'form' => $form
        );
    }


}
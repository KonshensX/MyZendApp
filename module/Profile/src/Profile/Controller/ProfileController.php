<?php

namespace Profile\Controller;

use Profile\Form\ImageUploadForm;
use Profile\Form\ProfileForm;
use Profile\Entity\Profile;
use Profile\Model\ProfileTable;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Helper\ViewModel;
use Doctrine\ORM\EntityManager;

class ProfileController extends AbstractActionController {

    protected $profileTable;
    /**
     * @var DoctrineORMEntityManager
     */
    protected $em;

    public function getEntityManager () {
        if (null == $this->em) {
            $this->em = $this->getServiceLocator()->get('doctrine.entitymanager.orm_default');
        }
        return $this->em;
    }

    public function indexAction()
    {
        $repo = $this->getEntityManager()->getRepository('Profile\Entity\Profile')->findAll();
        //$repo = $this->getProfileTable()->fetchAll();
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
        $profile = $this->getEntityManager()->getRepository(Profile::class)->findOneBy(array('id' => $id));

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

    public function testingAction () {
        $repo = $this->getEntityManager()->getRepository(Profile::class)->findAll();
        echo "<pre>";
        print_r($repo);
        echo "<pre>";
        die();
        return array(
            '$repo' => $repo
        );
    }


}
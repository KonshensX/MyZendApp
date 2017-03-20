<?php

namespace Profile\Controller;

use Application\Entity\Post;
use Application\Entity\Profile;
use Application\Entity\Salam;
use Imagine\Gd\Imagine;
use Imagine\Image\Box;
use Imagine\Image\Point;
use Profile\Form\ImageUploadForm;
use Profile\Form\ProfileForm;
use Profile\Model\ProfileTable;
use Zend\Authentication\AuthenticationService;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Helper\ViewModel;
use Doctrine\ORM\EntityManager;
use Zend\View\Model\JsonModel;

class ProfileController extends AbstractActionController {
    protected $postTable;
    protected $profileTable;
    /**
     * @var DoctrineORMEntityManager
     */
    protected $em;

    /**
     * @return EntityManager
     */
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

    /**
     * @return array|\Zend\Http\Response
     */
    public function displayAction () {

        $id = (int) $this->params()->fromRoute('id', 0);
        if (!$id) {
            return $this->redirect()->toRoute('post', array('action' => 'index'));
        }
        //Need the id of the profile to get information from the database
        //The profile is the current user logged in
        $auth = new AuthenticationService();
        $profile = $this->getEntityManager()->getRepository(Profile::class)->findOneBy(array('id' => $auth->getIdentity()));
        //$posts = $this->getPostTable()->getPostBy(array('owner' => $profile->username));
        $posts = $this->getEntityManager()->getRepository(Post::class)->findBy(['owner' => $profile->username]);

        return array(
            'profile' => $profile,
            'posts' => $posts
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

        if ($request->isXMLHttpRequest()) {
            if ($request->isPost()) {

                $post = array_merge_recursive(
                    $request->getPost()->toArray(),
                    $request->getFiles()->toArray()
                );
                $form->setData($post);
                //When the form is actually valid

                if ($form->isValid()) {
                    //Do stuff with the image
                    //Data gon' come through an ajax request
                    $x = 0;
                    $y = 0;
                    $width = 0;
                    $height = 0;
                    $tmp_file = $_FILES['file-0']['tmp_name'];
                    $filename = $_FILES['file-0']['name'];
                    if(isset($_POST)) {
                        $x = $_POST['x'];
                        $y = $_POST['y'];
                        $width = $_POST['width'];
                        $height = $_POST['height'];
                    }
                    $imagine = new Imagine();
                    $image = $imagine->open($tmp_file);
                    $temp = explode('.', $filename);

                    $filename = $temp[0] . $temp[1] . '.' . $temp[2];
                    $image
                        ->crop(new Point($x, $y), new Box($width, $height))
                        ->save(getcwd() . '/data/uploads/profile/' . $filename);

                    /*
                    //This updates the cover
                    $this->getAdapter();
                    $keys = array(
                        'cover' => $filename,
                    );
                     This should be replaced with some doctrine 2 goodness
                    $sql = new Sql($this->adapter);
                    $update = $sql->update();
                    $update->table('profile');
                    $update->set($keys);
                    $update->where(array('id' => $id));


                    $statement = $sql->prepareStatementForSqlObject($update);
                    $result = $statement->execute();
                    */

                    //Get the profile using doctrine and save the data using doctine
                    $auth = new AuthenticationService();
                    /**
                     * @var \Application\Entity\Profile
                     */
                    $profile = $this->getEntityManager()->getRepository(\Application\Entity\Profile::class)
                                    ->findOneBy([
                                        'id' => $auth->getIdentity()
                                    ]);

                    $profile->image = $filename;

                    $this->getEntityManager()->persist($profile);
                    $this->getEntityManager()->flush();


                    return json_encode(array(
                        'message' => 'success'
                    ));
                }
            }
        }


        return new JsonModel(array(
            'message' => 'failed'
        ));
    }

    public function editAction() {
        $auth = new AuthenticationService();

        $id = $auth->getIdentity();

        if (!$id) {
            return $this->redirect()->toRoute('post', array('action' => 'index'));
        }
        /**
         * @var \Application\Entity\Profile
         */
        $profile = $this->getEntityManager()->getRepository(\Application\Entity\Profile::class)->findOneBy(['id' => $id]);

        $profileForm = new ProfileForm();
        $imageForm = new ImageUploadForm();
        $profileForm->bind($profile);
        $request = $this->getRequest();
        if ($request->isPost()) {
            $post = array_merge_recursive(
                $request->getPost()->toArray()
            );
            $profileForm->setData($post);
            $profile->exchangeArray($post);

            $this->getEntityManager()->persist($profile);
            $this->getEntityManager()->flush();

            $this->flashMessenger()->addMessage(array('message' => 'success'));        }

        return array(
            'imageForm' => $imageForm,
            'profileForm' => $profileForm,
            'profile' => $profile
        );
    }


}

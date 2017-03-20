<?php

namespace Post\Controller;

use Application\Entity\Category;
use Application\Entity\Post;
use Doctrine\ORM\EntityManager;
use Post\Model\PostTable;
use Post\Form\CoverForm;
use Post\Form\PostForm;
use Profile\Model\ProfileTable;
use Application\Entity\Profile;
use Zend\Authentication\AuthenticationService;
use Zend\Db\Sql\Sql;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\JsonModel;
use Imagine\Gd\Imagine;
use Imagine\Image\Point;
use Imagine\Image\Box;
use Imagine\Image\ImageInterface;

class PostController extends AbstractActionController {

    protected $adapter;
    protected $postTable;
    protected $mapper;
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

    public function __construct()
    {
        //$this->mapper = $mapper;
    }

    public function displayAction () {
        $id = (int) $this->params()->fromRoute('id', 0);

        if (!$id) {
            return $this->redirect()->toRoute('post', array('action' => 'index'));
        }
        $post = $this->getPostTable()->getPostBy(array('id' => $id));

        return array(
            'post' => $post[0]
        );
    }

    public function coverAction () {
        $id = (int) $this->params()->fromRoute('id', 0);

        $request = $this->getRequest();
        if ($request->isXMLHttpRequest()) {
            $id = $this->params()->fromPost('post_id');
        }

        if (!$id) {
            $this->redirect()->toRoute('post', array('action' => 'index'));
        }

        $form = new CoverForm();

        $post = $this->getPostTable()->getPost($id);



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

                    $filename = $temp[0] . '.' . $temp[1];
                    var_dump($filename);
                    $image
                        ->crop(new Point($x, $y), new Box($width, $height))
                        ->save(getcwd() . '/data/uploads/covers/' . $filename);

                    //This updates the cover
                    $this->getAdapter();
                    $keys = array(
                        'cover' => $filename,
                    );
                    $sql = new Sql($this->adapter);
                    $update = $sql->update();
                    $update->table('post');
                    $update->set($keys);
                    $update->where(array('id' => $id));

                    $statement = $sql->prepareStatementForSqlObject($update);
                    $result = $statement->execute();

                    //$post->cover = $filename;

                    //$this->getPostTable()->savePost($post);

                    //Save the name of the image to the database
                    return json_encode(array(
                        'message' => 'success'
                    ));
                }
            }
        }

        return array(
            'form'  => $form,
            'id'    => $id
        );
    }

    public function indexAction()
    {

        $auth = new AuthenticationService();

        //get the paginator from the post table
        $paginator = $this->getPostTable()->fetchAll(true);

        //set the current page tp what has been passed in query string, or to 1 if none set
        $paginator->setCurrentPageNumber((int) $this->params()->fromQuery('page', 1));
        //set the number of items per page to 10
        $paginator->setItemCountPerPage(10);

        return array(
            'paginator' => $paginator
        );

    }
    //TODO: Needs more work
    //Try to upload a file an image //DONE
    //try to crop the photo before inserting it
    //Talking about the cover
    public function addAction () {
        $auth = new AuthenticationService();
        if (!$auth->getIdentity()) {
            return $this->redirect()->toRoute('post', array('action' => 'index'));
        }
        $form = new PostForm($this->getServiceLocator());

        $request = $this->getRequest();

        if ($request->isPost()) {
            $post = array_merge_recursive(
                $request->getPost()->toArray()
            );
            $form->setData($post);
            /*
            echo "<pre>";
                var_dump(!is_array($post));
                echo "</pre>";
            die();
            */

            //When the form is valid
            if ($form->isValid()) {
                $data = $form->getData();
                //get the file name to store in the database
                //$filename = (explode('\\', $data['image-file']['tmp_name'])[1]);
                //$data['cover'] = $filename;
                $data['date'] = new \DateTime("now");

                //Getting the user using doctrine.
                $currentUser = $this->getEntityManager()->getRepository(\Application\Entity\Profile::class)->findOneBy(array('id' => $auth->getIdentity()));
                $data['owner'] = $currentUser->username;
                //Set the data from the request to the post Object
                $postObject = new \Application\Entity\Post();
                $postObject->exchangeArray($data);
                $category = $this->getEntityManager()->getRepository(Category::class)->findOneBy(array('id' => $data['category_id']));
                $postObject->setCategory($category);

                //Getting the current user username

                //Getting the user using Zend_Db
                //$currentUser = $this->getProfileTable()->getProfile($auth->getIdentity());


                //$data['cover'] = null;

                //Inserting to the database using gateway and Zend stuff
                //$postClass->exchangeArray($data);


                //Saving the post object to the database using Zend
                //$myID = $this->getPostTable()->savePost($postClass);

                //Saving to the database using doctrine .
                $this->getEntityManager()->persist($postObject);
                $this->getEntityManager()->flush();

                //After adding the post redirect to the cover action to crop and save the cover, include the id of the post
                return $this->redirect()->toRoute('post', array('action' => 'cover', 'id' => $postObject->id));
            }
        }

        return array(
            'form' => $form
        );
    }

    //This is a testing action using doctrine
    public function newAction () {

        $form = $this->getServiceLocator()->get(PostForm::class);
        echo "<pre>";
        var_dump($form);
        die();

        return array(
            'form' => $form,
        );
    }

    public function editAction () {
        //TODO
        $auth = new AuthenticationService();
        $id = (int) $this->params()->fromRoute('id', 0);
        if (!$id) {
            return $this->redirect()->toRoute('post', array(
                'action' => 'index'
            ));
        }
        //Post class
        /**
         * @var Post
         */
        $post = $this->getEntityManager()->getRepository(Post::class)->findOneBy(['id' => $id]);

        $form = new PostForm($this->getServiceLocator());

        //I need to update the category dropdown with the id
        //It's not being binded with the correct data since the post object has a Category type instead of the integer ID
        $form->bind($post);

        $request = $this->getRequest();
        /**
         * @var Profile
         */
        $user = $this->getEntityManager()->getRepository(Profile::class)->findOneBy(['id' => $auth->getIdentity()]);
        if ($request->isPost()) {
            $data = $request->getPost()->toArray();
            $data['owner'] = $user->username;

            $post->exchangeArray($data);
            $this->getEntityManager()->persist($post);
            //Not really sure if the flush is needed when updating an entity
            $this->getEntityManager()->flush();

            return $this->redirect()->toRoute('post', array('action' => 'display', 'id' => $post->id));

        }

        return array(
          'form' => $form
        );
    }

    public function deleteAction () {
        //TODO
    }

    public function getPostTable () {

        if (!$this->postTable) {
            $sm = $this->getServiceLocator();
            $this->postTable = $sm->get(PostTable::class);
        }
        return $this->postTable;
    }


    public function getAdapter()
    {
        if (!$this->adapter) {
            $sm = $this->getServiceLocator();
            $this->adapter = $sm->get('Zend\Db\Adapter\Adapter');
        }
        return $this->adapter;
    }

    public function redirectAction()
    {
        return $this->redirect()->toRoute('post', array('action' => 'cover', 'id' => 46));
    }

    public function testingAction() {
        return array();
    }

    public function searchAction () {

        $request = $this->getRequest()->getContent();

        $postTitle = $this->params()->fromPost('search');

        $repo = $this->getPostTable()->getPostByTitle(array('title' => $postTitle));
        return array(
            'posts' => $repo,
            'title' => $postTitle
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

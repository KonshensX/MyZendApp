<?php

namespace Post\Controller;

use Post\Form\CoverForm;
use Post\Model\PostImageMapper;
use Post\Model\PostTable;
use Post\Model\Post;
use Post\Form\PostForm;
use Zend\Authentication\AuthenticationService;
use Zend\Db\Sql\Sql;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\JsonModel;
use Imagine\Gd\Imagine;
use Imagine\Image\Box;
use Imagine\Image\Point;
use Imagine\Image\ImageInterface;

class PostController extends AbstractActionController {

    protected $adapter;
    protected $postTable;
    protected $mapper;

    public function __construct()
    {
        //$this->mapper = $mapper;
    }


    public function displayAction () {
        $id = (int) $this->params()->fromRoute('id', 0);

        if (!$id) {
            return $this->redirect()->toRoute('post', array('action' => 'index'));
        }

        $post = $this->getPostTable()->getPost(array('id' => $id));
        //var_dump($post);

        return array(
            'post' => $post
        );
    }

    public function coverAction () {
        $id = (int) $this->params()->fromRoute('id', 0);

        $request = $this->getRequest();
        if ($request->isXMLHttpRequest()) {
            echo "post";
            var_dump($this->params()->fromPost());
            die();
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

                    $filename = $temp[0] . $temp[1] . '.' . $temp[2];
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
        $form = new PostForm();

        $request = $this->getRequest();

        if ($request->isPost()) {

            $postClass = new Post();
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
                $tempdate = new \DateTime("now");
                $tempdate = $tempdate->format('Y-m-d H:i:s');
                $data['date'] = $tempdate;
                $data['owner'] = "Current user";
                //$data['cover'] = null;

                $postClass->exchangeArray($data);

                $myID = $this->getPostTable()->savePost($postClass);

                //After adding the post redirect to the cover action to crop and save the cover, include the id of the post
                return $this->redirect()->toRoute('post', array('action' => 'cover', 'id' => $myID));
            }
        }

        return array(
            'form' => $form
        );
    }

    public function editAction () {
        //TODO
        $id = (int) $this->params()->fromRoute('id', 0);
        if (!$id) {
            return $this->redirect()->toRoute('post', array(
                'action' => 'index'
            ));
        }
        //Post class

        $form = new PostForm();
        $form->bind($post);

        $form->get('submit')->setAttribute('value', 'Edit');

        $request = $this->getRequest();

        if ($request->isPost()) {
            var_dump($form->getData());
            die("This was reached!");
        }

        return;
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
        //$repo = $this->getEntityManager()
        //$repo = $this->mapper->findAll();
        //var_dump($repo);
        die('"fuck"');
    }


}
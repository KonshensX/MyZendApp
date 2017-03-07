<?php

namespace Post\Controller;

use Post\CoverForm;
use Post\Model\PostTable;
use Post\Model\Post;
use Post\Form\PostForm;
use Zend\Mvc\Controller\AbstractActionController;

class PostController extends AbstractActionController {

    protected $postTable;

    public function coverAction () {
        $form = new CoverForm();

        $request = $this->getRequest();

        if ($request->isPost()) {

            //When the form is actually valid
            if ($form->isValid()) {
                //Do stuff with the image
                //Data gon' come through an ajax request
            }
        }

        return array(
            'form' => $form
        );
    }

    public function indexAction()
    {
        $repo = $this->getPostTable()->fetchAll();

        return array(
            'posts' => $repo,
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
            $post = array_merge_recursive(
                $request->getPost()->toArray(),
                $request->getFiles()->toArray()
            );
            $postClass = new Post();

            $form->setData($post);

            //When the form is valid
            if ($form->isValid()) {

                $data = $form->getData();
                //get the file name to store in the database
                $filename = (explode('\\', $data['image-file']['tmp_name'])[1]);
                $data['cover'] = $filename;
                $tempdate = new \DateTime("now");
                $tempdate = $tempdate->format('Y-m-d H:i:s');
                $data['date'] = $tempdate;
                $data['owner'] = "Current user";
                $data['cover'] = $filename;
                /*echo "<pre>";
                var_dump($data);
                echo "</pre>";
                die();*/

                $postClass->exchangeArray($data);

                $this->getPostTable()->savePost($postClass);

                return $this->redirect()->toRoute('post/index');
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
        $post = new Post();
        $post->id = 22;
        $post->title = "My Post Title";
        $post->price = 25.99;
        $post->imagepath = "\\localfff";

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

}
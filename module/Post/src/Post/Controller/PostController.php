<?php

namespace Post\Controller;

use Post\Model\MyTable;
use Post\Model\PostTable;
use Post\Model\Post;
use Post\Form\PostForm;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Helper\ViewModel;

class PostController extends AbstractActionController {

    protected $postTable;

    public function indexAction()
    {
        $repo = $this->getPostTable()->fetchAll();

        //$fuck = new PostTable(null);

        return new ViewModel(array(
            'posts' => $repo,
        ));

    }
    //TODO: Needs more work
    //Try to upload a file an image
    public function addAction () {
        $form = new PostForm();

        $form->get('submit')->setValue('Add');

        $request = $this->getRequest();

        if ($request->isPost()) {
            echo "<pre>";
            var_dump($request->getFiles());
            echo "</pre>";
            die("This was reached!");
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
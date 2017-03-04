<?php

namespace Post\Controller;

use Post\Model\PostTable;
use Post\Model\Post;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Helper\ViewModel;

class PostController extends AbstractActionController {

    protected $postTable;

    public function indexAction()
    {
        $repo = $this->getPostTable()->fetchAll();

        return new ViewModel(array(
            'posts' => $repo,
        ));

    }

    public function addAction () {
        //TODO
    }

    public function editAction () {
        //TODO
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
<?php

namespace Post\Model;

use Zend\Db\ResultSet\ResultSet;
use Zend\Db\Sql\Select;
use Zend\Db\TableGateway\TableGateway;
use Zend\Paginator\Adapter\DbSelect;
use Zend\Paginator\Paginator;
use Zend\Stdlib\Response;

class PostTable
{

    protected $tableGateway;

    public function __construct(TableGateway $tableGateway)
    {
        $this->tableGateway = $tableGateway;
    }

    public function fetchAll($paginated = false)
    {
        if ($paginated) {
            //create a new sleect object for the table post
            $select  = new Select('post');
            //create new resultSet based on the post entity
            $resultSet = new ResultSet();
            $resultSet->setArrayObjectPrototype(new Post());
            //create a new pagination adapter object
            $paginationAdapter = new DbSelect(
                //our configured select object,
                $select,
                $this->tableGateway->getAdapter(),
                $resultSet
            );
            $paginator = new Paginator($paginationAdapter);
            return $paginator;
        }
        $resultSet = $this->tableGateway->select();
        return $resultSet;
    }

    

    public function getPost($id)
    {
        $id  = (int) $id;
        $rowset = $this->tableGateway->select(array('id' => $id));
        $row = $rowset->current();
        if (!$row) {
            throw new \Exception("Could not find row $id");
        }
        return $row;
    }

    public function savePost(Post $post)
    {
        $data = array(
            'title' => $post->title,
            'description'  => $post->description,
            'date' => $post->date,
            'owner' => $post->owner,
            'price' => $post->price,
            'phone' => $post->phone,
            'email' => $post->email,
            'cover' => $post->cover
        );

        $id = (int) $post->id;
        if ($id == 0) {
            $this->tableGateway->insert($data);
        } else {
            if ($this->getPost($id)) {
                $this->tableGateway->update($data, array('id' => $id));
            } else {
                throw new \Exception('Post id does not exist');
            }
        }
        return $this->tableGateway->getLastInsertValue();
    }

    public function deletePost($id)
    {
        $this->tableGateway->delete(array('id' => (int) $id));
    }

}
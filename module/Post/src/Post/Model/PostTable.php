<?php

namespace Post\Model;

use Zend\Db\TableGateway\TableGateway;

class PostTable
{

    protected $tableGateway;

    public function __construct(TableGateway $tableGateway)
    {
        $this->tableGateway = $tableGateway;
    }

    public function fetchAll()
    {
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
    }

    public function deletePost($id)
    {
        $this->tableGateway->delete(array('id' => (int) $id));
    }

}
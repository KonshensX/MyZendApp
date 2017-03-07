<?php

namespace Image\Model;

use Zend\Db\TableGateway\TableGateway;

class ImageTable  {
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

    public function getImage($id)
    {
        $id  = (int) $id;
        $rowset = $this->tableGateway->select(array('id' => $id));
        $row = $rowset->current();
        if (!$row) {
            throw new \Exception("Could not find row $id");
        }
        return $row;
    }

    public function saveImage(Image $Image)
    {
        $data = array(
            'artist' => $Image->artist,
            'title'  => $Image->title,
        );

        $id = (int) $Image->id;
        if ($id == 0) {
            $this->tableGateway->insert($data);
        } else {
            if ($this->getImage($id)) {
                $this->tableGateway->update($data, array('id' => $id));
            } else {
                throw new \Exception('Image id does not exist');
            }
        }
    }

    public function deleteImage($id)
    {
        $this->tableGateway->delete(array('id' => (int) $id));
    }
}
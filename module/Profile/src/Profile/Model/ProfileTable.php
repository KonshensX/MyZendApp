<?php

namespace Profile\Model;

use Zend\Db\TableGateway\TableGateway;

class ProfileTable {

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

    public function getProfile($id)
    {
        $id  = (int) $id;
        $rowset = $this->tableGateway->select(array('id' => $id));
        $row = $rowset->current();
        if (!$row) {
            throw new \Exception("Could not find row $id");
        }
        return $row;
    }

    public function saveProfile(Profile $Profile)
    {
        $data = array(
            'username'  => $Profile->username,
            'firstname' => $Profile->firstname,
            'lastname'  => $Profile->lastname,
            'mobile'    => $Profile->mobile,
            'interests' => $Profile->interests,
            'occupation' => $Profile->occupation,
            'about' => $Profile->about,
            'image' => $Profile->image
        );

        $id = (int) $Profile->id;
        if ($id == 0) {
            $this->tableGateway->insert($data);
        } else {
            if ($this->getProfile($id)) {
                $this->tableGateway->update($data, array('id' => $id));
            } else {
                throw new \Exception('Profile id does not exist');
            }
        }
    }

    public function deleteProfile($id)
    {
        $this->tableGateway->delete(array('id' => (int) $id));
    }

}
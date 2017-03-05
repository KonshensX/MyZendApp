<?php

namespace Profile\Model;

class Profile {
    public $id;
    public $username;
    public $firstname;
    public $lastname;
    public $mobile;
    public $interests;
    public $occupation;
    public $about;
    public $image;

    public function exchangeArray($data) {
        $this->id = (!empty($data['id'])) ? $data['id'] : null;
        $this->username = (!empty($data['username'])) ? $data['username'] : null;
        $this->firstname = (!empty($data['firstname'])) ? $data['firstname'] : null;
        $this->lastname = (!empty($data['lastname'])) ? $data['lastname'] : null;
        $this->mobile = (!empty($data['mobile'])) ? $data['mobile'] : null;
        $this->interests = (!empty($data['interests'])) ? $data['interests'] : null;
        $this->occupation = (!empty($data['occupation'])) ? $data['occupation'] : null;
        $this->about = (!empty($data['about'])) ? $data['about'] : null;
        $this->image = (!empty($data['image'])) ? $data['image'] : null;
    }

}
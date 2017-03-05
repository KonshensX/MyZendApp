<?php

namespace Post\Model;

class Post {
    public $id;
    public $title;
    public $price;
    public $imagepath;

    public function exchangeArray ($data) {
        $this->id = (!empty($data['id'])) ? $data['id'] : null;
        $this->title = (!empty($data['title'])) ? $data['title'] : null;
        $this->price = (!empty($data['price'])) ? $data['price'] : null;
        $this->imagepath = (!empty($data['imagepath'])) ? $data['imagepath'] : null;
    }
}


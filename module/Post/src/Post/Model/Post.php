<?php

namespace Post\Model;

class Post {
    public $id;
    public $title;
    public $description;
    public $date;
    public $owner;
    public $price;
    public $phone;
    public $email;
    public $cover;
    public $views;

    protected $images;

    public function exchangeArray ($data) {
        $this->id = (!empty($data['id'])) ? $data['id'] : null;
        $this->title = (!empty($data['title'])) ? $data['title'] : null;
        $this->description = (!empty($data['description'])) ? $data['description'] : null;
        $this->date = (!empty($data['date'])) ? $data['date'] : null;
        $this->owner = (!empty($data['owner'])) ? $data['owner'] : null;
        $this->price = (!empty($data['price'])) ? $data['price'] : null;
        $this->phone = (!empty($data['phone'])) ? $data['phone'] : null;
        $this->email = (!empty($data['email'])) ? $data['email'] : null;
        $this->cover = (!empty($data['cover'])) ? $data['cover'] : null;
    }

    public function getArrayCopy()
    {
        return get_object_vars($this);
    }

    /**
     * @return mixed
     */
    public function getImages()
    {
        return $this->images;
    }

    /**
     * @param mixed $images
     */
    public function setImages($images)
    {
        $this->images = $images;
    }


}


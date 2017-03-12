<?php

namespace Post\Model;

class Image {
    public $id;
    public $post_id;
    public $name;

    public function exchangeData($data)
    {
        $this->id = (!empty($data['id'])) ? $data['id'] : null;
        $this->post_id = (!empty($data['post_id'])) ? $data['post_id'] : null;
        $this->name = (!empty($data['name'])) ? $data['name'] : null;
    }
}
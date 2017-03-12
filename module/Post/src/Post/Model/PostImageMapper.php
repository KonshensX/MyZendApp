<?php

namespace Post\Model;

use Image\Model\ImageTable;

class PostImageMapper {
    protected $post;
    protected $image;

    public function __construct(PostTable $postTable, ImageTable $imageTable)
    {
        $this->post = $postTable;
        $this->image = $imageTable;
    }

    public function findAll () {
        $postResult = $this->post->getTableGateway()->select();
        $postResult->buffer();

        $posts = iterator_to_array($postResult);
        foreach ($posts as $post) {
            $imagerow = $this->image->getTableGateway()->select(array('post-id' => $post->id));
            $post->setImages(iterator_to_array($imagerow));
        }

        return $posts;
    }
}
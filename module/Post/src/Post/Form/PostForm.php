<?php

namespace Post\Form;

use Zend\Form\Element\File;
use Zend\Form\Form;


class PostForm extends Form {

    public function __construct($name = null)
    {
        parent::__construct('post');

        $this->add(array(
            'name' => 'id',
            'type' => 'Hidden'
        ));
        $this->add(array(
            'name' => 'title',
            'type' => 'Text',
            'options' => array(
                'label' => 'The Title'
            )
        ));

        $this->add(array(
            'name' => 'price',
            'type' => 'Text',
            'options' => array(
                'label' => 'The Price'
            )
        ));
        $file = new File('image-file');
        $file->setLabel('Image')
            ->setAttribute('id', 'image-file');
        $this->add($file);

        $this->add(array(
            'name' => 'submit',
            'type' => 'Submit',
            'attributes' => array(
                'value' => 'Save',
                'id' => 'submitbutton'
            )
        ));
    }
}
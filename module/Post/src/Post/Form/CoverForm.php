<?php

namespace Post\Form;

use Zend\Form\Element\File;
use Zend\Form\Form;



class CoverForm extends Form {
    public function __construct($name = null)
    {
        parent::__construct($name);
        $file = new File('cover-file');
        $file->setLabel('Cover')
            ->setAttribute('onChange', 'previewImage()');
        $this->add($file);
    }
    
    
}
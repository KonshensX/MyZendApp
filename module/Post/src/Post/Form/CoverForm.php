<?php

namespace Post\Form;

use Zend\Form\Element\File;
use Zend\InputFilter;
use Zend\Form\Element;
use Zend\Form\Form;

class CoverForm extends Form {
    public function __construct($name = null)
    {
        parent::__construct($name);
        $file = new File('cover-file');
        
        $file->setLabel('Cover')
            ->setAttribute('id', 'image-input')
            ->setAttribute('onChange', 'previewImage()');
        $this->add($file);
        $this->addInputFilter();
    }

    public function addInputFilter()
    {
        $inputFilter = new InputFilter\InputFilter();

        // File Input
        $fileInput = new InputFilter\FileInput('cover-file');
        $fileInput->setRequired(false);

        // You only need to define validators and filters
        // as if only one file was being uploaded. All files
        // will be run through the same validators and filters
        // automatically.
        $fileInput->getValidatorChain()
            ->attachByName('filesize',      array('max' => 204800))
            ->attachByName('filemimetype',  array('mimeType' => 'image/png,image/x-png, image/jpg, image/jpeg'))
            ->attachByName('fileimagesize', array('maxWidth' => 2000, 'maxHeight' => 2000));

        // All files will be renamed, i.e.:
        //   ./data/tmpuploads/avatar_4b3403665fea6.png,
        //   ./data/tmpuploads/avatar_5c45147660fb7.png
        /*$fileInput->getFilterChain()->attachByName(
            'filerenameupload',
            array(
                'target'    => './data/uploads/posts/avatar.png',
                'randomize' => true,
            )
        );*/
        $inputFilter->add($fileInput);

        $this->setInputFilter($inputFilter);
    }
    
}
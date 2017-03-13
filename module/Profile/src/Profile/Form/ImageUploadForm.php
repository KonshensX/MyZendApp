<?php

namespace Profile\Form;

use Zend\InputFilter;
use Zend\Form\Element;
use Zend\Form\Form;

class ImageUploadForm extends Form
{
    public function __construct($name = null, $options = array())
    {
        parent::__construct($name, $options);
        $this->addElements();
        //$this->addInputFilter();
    }

    public function addElements()
    {
        // File Input
        $file = new Element\File('image-file');
        $file->setLabel('Avatar Image Upload')
            ->setAttribute('onChange', 'previewAvatar()')
            ->setAttribute('id', 'image');

        $this->add($file);
    }
    /*
    public function addInputFilter()
    {
        $inputFilter = new InputFilter\InputFilter();

        // File Input
        $fileInput = new InputFilter\FileInput('image-file');
        $fileInput->setRequired(true);

        // You only need to define validators and filters
        // as if only one file was being uploaded. All files
        // will be run through the same validators and filters
        // automatically.
        $fileInput->getValidatorChain()
            ->attachByName('filesize',      array('max' => 204800))
            ->attachByName('filemimetype',  array('mimeType' => 'image/png,image/x-png, image/jpeg, image/jpg'))
            ->attachByName('fileimagesize', array('maxWidth' => 2000, 'maxHeight' => 2000));

        // All files will be renamed, i.e.:
        //   ./data/tmpuploads/avatar_4b3403665fea6.png,
        //   ./data/tmpuploads/avatar_5c45147660fb7.png
        $fileInput->getFilterChain()->attachByName(
            'filerenameupload',
            array(
                'target'    => './data/uploads/avatar.png',
                'randomize' => true,
            )
        );
        $inputFilter->add($fileInput);

        $this->setInputFilter($inputFilter);
    }
    */
}
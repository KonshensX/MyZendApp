<?php

namespace Profile\Form;

use Zend\Form\Element\File;
use Zend\InputFilter;
use Zend\Form\Element;
use Zend\Form\Form;

class ProfileForm extends Form {

    public function __construct($name = null)
    {
        parent::__construct ('profile');

        $this->add(array(
            'name' => 'id',
            'type' => 'Hidden'
        ));
        $this->add(array(
            'name' => 'username',
            'type' => 'Text',
            'options' => array(
                'label' => 'Username'
            ),
            'attributes' => array(
                'class' => 'form-control',
            )
        ));
        $this->add(array(
            'name' => 'firstname',
            'type' => 'Text',
            'options' => array(
                'label' => 'First name'
            ),
            'attributes' => array(
                'class' => 'form-control',
            )
        ));
        $this->add(array(
            'name' => 'lastname',
            'type' => 'Text',
            'options' => array(
                'label' => 'Last name'
            ),
            'attributes' => array(
                'class' => 'form-control',
            )
        ));
        $this->add(array(
            'name' => 'mobile',
            'type' => 'Text',
            'options' => array(
                'label' => 'Mobile'
            ),
            'attributes' => array(
                'class' => 'form-control',
            )
        ));
        $this->add(array(
            'name' => 'interests',
            'type' => 'Text',
            'options' => array(
                'label' => 'Interests'
            ),
            'attributes' => array(
                'class' => 'form-control',
            )
        ));
        $this->add(array(
            'name' => 'occupation',
            'type' => 'Text',
            'options' => array(
                'label' => 'Occupation'
            ),
            'attributes' => array(
                'class' => 'form-control',
            )
        ));
        $this->add(array(
            'name' => 'about',
            'type' => 'Text',
            'options' => array(
                'label' => 'About'
            ),
            'attributes' => array(
                'class' => 'form-control',
            )
        ));
        $file = new File('image-file');
        $file->setLabel('Avatar')->setAttribute('id', 'image-file')->setAttribute('onChange', 'previewImage()');
        $this->add($file);

        $this->addInputFilter();
    }

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
            ->attachByName('filemimetype',  array('mimeType' => 'image/png,image/x-png'))
            ->attachByName('fileimagesize', array('maxWidth' => 2000, 'maxHeight' => 2000));

        // All files will be renamed, i.e.:
        //   ./data/tmpuploads/avatar_4b3403665fea6.png,
        //   ./data/tmpuploads/avatar_5c45147660fb7.png
        $fileInput->getFilterChain()->attachByName(
            'filerenameupload',
            array(
                'target'    => './data/uploads/profile/avatar.png',
                'randomize' => true,
            )
        );
        $inputFilter->add($fileInput);

        $this->setInputFilter($inputFilter);
    }

}
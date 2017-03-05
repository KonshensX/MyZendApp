<?php

namespace Profile\Form;

use Zend\Form\Element\File;
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
                'placeholder' => 'Username'
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
                'placeholder' => 'First name'
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
                'placeholder' => 'Last name'
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
                'placeholder' => 'Mobile'
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
                'placeholder' => 'Interests'
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
                'placeholder' => 'Occupation'
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
                'placeholder' => 'About'
            )
        ));
        $file = new File('Image');
        $file->setLabel('Avatar')->setAttribute('id', 'avatar');
        $this->add($file);

        $this->add(array(
            'name' => 'save',
            'type' => 'Submit',
            'attributes' => array(
                'value' => 'Save Changes',
                'class' => 'btn btn-warning btn-round pull-right'
            )
        ));
    }

}
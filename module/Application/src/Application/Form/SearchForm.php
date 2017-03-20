<?php

namespace Application\Form;

use Zend\Form\Form;

class SearchForm extends Form {

    public function __construct($name = null)
    {
        parent::__construct('search');

        $this->add(array(
            'name' => 'search',
            'type' => 'Text',
            'attributes' => array(
                'placeholder' => 'Search...',
                'class' => 'form-control'
            )
        ));

    }
}
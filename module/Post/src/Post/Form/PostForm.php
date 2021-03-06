<?php

namespace Post\Form;

use Application\Entity\Category;
use Zend\Form\Element\File;
use Zend\InputFilter;
use Zend\Form\Element;
use Zend\Form\Form;


class PostForm extends Form {
    protected $em;
    private $sm;
    /**
     * @return EntityManager
     */
    public function getEntityManager () {
        if (null == $this->em) {
            $this->em = $this->sm->get('doctrine.entitymanager.orm_default');
        }
        return $this->em;
    }

    public function __construct($sm)
    {
        parent::__construct('post');
        $this->sm = $sm;
        $this->getEntityManager();
        $categories = $this->getEntityManager()->getRepository(Category::class)->findAll();
        $newCategories = [];
        foreach ($categories as $category) {
            $newCategories[$category->id] = $category->name;
        }

        $this->add(array(
            'name' => 'id',
            'type' => 'Hidden'
        ));
        $this->add(array(
            'name' => 'title',
            'type' => 'Text',
            'options' => array(
                'label' => 'The Title'
            ),
            'attributes' => array(
                'class' => 'form-control',
                'required' => 'required'
            )
        ));

        $this->add(array(
            'name' => 'category_id',
            'type' => 'Select',
            'options' => array(
                'options' => $newCategories
            ),
            'attributes' => array(
                'class' => 'form-control',
            )
        ));

        $this->add(array(
            'name' => 'description',
            'type' => 'Text',
            'options' => array (
                'label' => 'Description'
            ),
            'attributes' => array(
                'class' => 'form-control',
                'required' => 'required'
            )
        ));

        $this->add(array(
            'name' => 'price',
            'type' => 'Number',
            'options' => array (
                'label' => 'Price'
            ),
            'attributes' => array(
                'class' => 'form-control',
                'required' => 'required'
            )
        ));

        $this->add(array(
            'name' => 'phone',
            'type' => 'Text',
            'options' => array (
                'label' => 'Phone'
            ),
            'attributes' => array(
                'class' => 'form-control',
                'required' => 'required'
            )
        ));

        $this->add(array(
            'name' => 'email',
            'type' => 'Email',
            'options' => array (
                'label' => 'Email'
            ),
            'attributes' => array(
                'class' => 'form-control',
                'required' => 'required',
                'row' => '7'
            )
        ));

        //This need to be a file input with a preview to insert the cover
        //now the cover is being handled in different action
        $file = new File('image-file');
        $file->setLabel('Image')
            ->setAttribute('id', 'image-file')
            ->setAttribute('onChange', 'previewImage()')
            ->setAttribute('required', 'false');
        //$this->add($file);

        //End of file input

       // $this->addInputFilter();
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
            ->attachByName('filemimetype',  array('mimeType' => 'image/png,image/x-png, image/jpg, image/jpeg'))
            ->attachByName('fileimagesize', array('maxWidth' => 2000, 'maxHeight' => 2000));

        // All files will be renamed, i.e.:
        //   ./data/tmpuploads/avatar_4b3403665fea6.png,
        //   ./data/tmpuploads/avatar_5c45147660fb7.png
        $fileInput->getFilterChain()->attachByName(
            'filerenameupload',
            array(
                'target'    => './data/uploads/posts/avatar.png',
                'randomize' => true,
            )
        );
        $inputFilter->add($fileInput);

        $this->setInputFilter($inputFilter);
    }
}

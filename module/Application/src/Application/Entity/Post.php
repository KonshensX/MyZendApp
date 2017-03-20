<?php

namespace Application\Entity;

use Doctrine\ORM\Mapping as ORM;
use Zend\InputFilter\InputFilter;
use Zend\InputFilter\InputFilterAwareInterface;
use Zend\InputFilter\InputFilterInterface;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * Class Post
 * @ORM\Entity
 * @ORM\Table(name="post")
 */
class Post implements InputFilterAwareInterface {

    protected $inputFilter;

    /**
     * @var ArrayCollection
     *
     * @ORM\ManyToOne(targetEntity="Category", inversedBy="posts")
     * @ORM\JoinColumn(name="category_id", referencedColumnName="id")
     **/
    private $category;

    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    public $id;
    /**
     * @ORM\Column(type="string")
     */
    public $title;

    /**
     * @ORM\Column(type="string")
     */
    public $description;

    /**
     * @ORM\Column(type="date")
     */
    public $date;

    /**
     * @ORM\Column(type="string")
     */
    public $owner;

    /**
     * @ORM\Column(type="decimal")
     */
    public $price;

    /**
     * @ORM\Column(type="string")
     */
    public $phone;

    /**
     * @ORM\Column(type="string")
     */
    public $email;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    public $cover;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    public $views;


    /**
     * Magic getter to expose protected properties.
     *
     * @param string $property
     * @return mixed
     */
    public function __get($property)
    {
        return $this->$property;
    }

    /**
     * Magic setter to save protected properties.
     *
     * @param string $property
     * @param mixed $value
     */
    public function __set($property, $value)
    {
        $this->$property = $value;
    }

    /**
     * Convert the object to an array.
     *
     * @return array
     */
    public function getArrayCopy()
    {
        return get_object_vars($this);
    }

    /**
     * Populate from an array.
     *
     * @param array $data
     */
    public function exchangeArray ($data = array())
    {
        $this->id = $data['id'];
        $this->title = $data['title'];
        $this->description = $data['description'];
        $this->date = new \DateTime("now");
        $this->phone = $data['phone'];
        $this->price = $data['price'];
        $this->email = $data['email'];
        //This is causing some problems because the array above is kinda ruined
        $this->owner = $data['owner'];
    }

    /**
     * Set input filter
     *
     * @param  InputFilterInterface $inputFilter
     * @return InputFilterAwareInterface
     */
    public function setInputFilter(InputFilterInterface $inputFilter)
    {
        // TODO: Implement setInputFilter() method.
    }

    /**
     * Retrieve input filter
     *
     * @return InputFilterInterface
     */
    public function getInputFilter()
    {
        if (!$this->inputFilter) {
            $inputFilter = new InputFilter();

            $inputFilter->add(array(
                'name'     => 'id',
                'required' => true,
                'filters'  => array(
                    array('name' => 'Int'),
                ),
            ));

            $inputFilter->add(array(
                'name'     => 'title',
                'required' => true,
                'filters'  => array(
                    array('name' => 'StripTags'),
                    array('name' => 'StringTrim'),
                ),
                'validators' => array(
                    array(
                        'name'    => 'StringLength',
                        'options' => array(
                            'encoding' => 'UTF-8',
                            'min'      => 1,
                            'max'      => 100,
                        ),
                    ),
                ),
            ));

            $inputFilter->add(array(
                'name'     => 'description',
                'required' => true,
                'filters'  => array(
                    array('name' => 'StripTags'),
                    array('name' => 'StringTrim'),
                ),
                'validators' => array(
                    array(
                        'name'    => 'StringLength',
                        'options' => array(
                            'encoding' => 'UTF-8',
                            'min'      => 1,
                            'max'      => 100,
                        ),
                    ),
                ),
            ));

            $inputFilter->add(array(
                'name'     => 'date',
                'required' => true,
                'filters'  => array(
                    array('name' => 'StripTags'),
                    array('name' => 'StringTrim'),
                ),
                'validators' => array(
                    array(
                        'name'    => 'StringLength',
                        'options' => array(
                            'encoding' => 'UTF-8',
                            'min'      => 1,
                            'max'      => 100,
                        ),
                    ),
                ),
            ));

            $inputFilter->add(array(
                'name'     => 'owner',
                'required' => true,
                'filters'  => array(
                    array('name' => 'StripTags'),
                    array('name' => 'StringTrim'),
                ),
                'validators' => array(
                    array(
                        'name'    => 'StringLength',
                        'options' => array(
                            'encoding' => 'UTF-8',
                            'min'      => 1,
                            'max'      => 100,
                        ),
                    ),
                ),
            ));

            $inputFilter->add(array(
                'name'     => 'price',
                'required' => true,
                'filters'  => array(
                    array('name' => 'StripTags'),
                    array('name' => 'StringTrim'),
                ),
                'validators' => array(
                    array(
                        'name'    => 'StringLength',
                        'options' => array(
                            'encoding' => 'UTF-8',
                            'min'      => 1,
                            'max'      => 100,
                        ),
                    ),
                ),
            ));

            $inputFilter->add(array(
                'name'     => 'phone',
                'required' => true,
                'filters'  => array(
                    array('name' => 'StripTags'),
                    array('name' => 'StringTrim'),
                ),
                'validators' => array(
                    array(
                        'name'    => 'StringLength',
                        'options' => array(
                            'encoding' => 'UTF-8',
                            'min'      => 1,
                            'max'      => 100,
                        ),
                    ),
                ),
            ));

            $inputFilter->add(array(
                'name'     => 'email',
                'required' => true,
                'filters'  => array(
                    array('name' => 'StripTags'),
                    array('name' => 'StringTrim'),
                ),
                'validators' => array(
                    array(
                        'name'    => 'StringLength',
                        'options' => array(
                            'encoding' => 'UTF-8',
                            'min'      => 1,
                            'max'      => 100,
                        ),
                    ),
                ),
            ));
            $this->inputFilter = $inputFilter;
        }

        return $this->inputFilter;
    }

    /**
     * @return mixed
     */
    public function getCategory()
    {
        return $this->category;
    }

    /**
     * @param mixed $category
     */
    public function setCategory($category)
    {
        $this->category = $category;
    }
}

<?php
/**
 * @file
 *
 */

namespace Article\Form;


use Application\Form\AbstractForm;
use Doctrine\Common\Persistence\ObjectManager;
use Zend\Form\Fieldset;
use Zend\InputFilter\InputFilterProviderInterface;
use DoctrineModule\Stdlib\Hydrator\DoctrineObject as DoctrineHydrator;

class AddForm extends AbstractForm implements InputFilterProviderInterface
{
    /**
     * @var ObjectManager
     */
    protected $objectManager;

    /**
     * @inheritdoc
     */
    public function __construct(ObjectManager $objectManager, DoctrineHydrator $doctrineHydrator, $name = 'article',
        $options = array()
    ) {
        parent::__construct($name, $options);
        $this->setHydrator($doctrineHydrator);
        $this->setObjectManager($objectManager);
    }

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();

        $this->setAttribute('class', 'form-horizontal');

        $this->add(
            array(
                'name' => 'title',
                'type' => 'text',
                'options' => array(
                    'label' => $this->translate('Title'),
                    'label_attributes' => array(
                        'class' => 'col-sm-1 control-label',
                    ),
                    'wrapper_class' => 'col-sm-10',
                    'help' => $this->translate('Article title'),
                ),
                'attributes' => array(
                    'id' => 'title',
                    'class' => 'form-control',
                ),
            )
        );

        $this->add(
            array(
                'name' => 'thumbnail',
                'type' => 'imageFile',
                'options' => array(
                    'label' => $this->translate('Thumbnail'),
                    'label_attributes' => array(
                        'class' => 'col-sm-1 control-label',
                    ),
                    'wrapper_class' => 'col-sm-10',
                    'help' => $this->translate('Article main image'),
                ),
                'attributes' => array(
                    'id' => 'thumbnail',
                    'class' => '',
                ),
            )
        );

        $this->add(
            array(
                'name' => 'text',
                'type' => 'textarea',
                'options' => array(
                    'label' => $this->translate('Content'),
                    'label_attributes' => array(
                        'class' => 'col-sm-1 control-label',
                    ),
                    'wrapper_class' => 'col-sm-10',
                    'help' => $this->translate('Article main content'),
                ),
                'attributes' => array(
                    'id' => 'editor',
                    'class' => 'form-control',
                ),
            )
        );

        $this->add(
            array(
                'name' => 'author',
                'type' => 'DoctrineModule\Form\Element\ObjectSelect',
                'options' => array(
                    'label' => $this->translate('Author'),
                    'label_attributes' => array(
                        'class' => 'col-sm-1 control-label',
                    ),
                    'wrapper_class' => 'col-sm-1',
                    'object_manager' => $this->getObjectManager(),
                    'target_class' => 'User\Entity\User',
                    'property' => 'fullName',
                ),
                'attributes' => array(
                    'id' => 'author',
                    'class' => 'form-control',
                ),
            )
        );

        $this->add(
            (new Fieldset('actions'))->add(
                array(
                    'name' => 'submit',
                    'type' => 'submit',
                    'options' => array(
                        'disable_group_wrapper' => true,
                    ),
                    'attributes' => array(
                        'value' => $this->translate('Save'),
                        'class' => 'btn btn-primary',
                    ),
                )
            )
                ->setAttribute('class', 'form-group')
                ->setOption('wrapper_class', 'col-sm-offset-1 col-sm-10 btn-group btn-group-lg')
        );
    }

    /**
     * @inheritdoc
     */
    public function getInputFilterSpecification()
    {
        return array(
            'title' => array(
                'required' => true,
                'filters' => array(
                    array('name' => 'StripTags'),
                    array('name' => 'StringTrim'),
                ),
                'validators' => array(
                    array(
                        'name' => 'StringLength',
                        'options' => array(
                            'min' => 4,
                            'max' => 255,
                        ),
                    ),
                ),
            ),
            'text' => array(
                'required' => true,
                'filters' => array(
                    array('name' => 'StringTrim'),
                ),
            ),
            'thumbnail' => array(
                'required' => false,
                'filters' => array(
                    array(
                        'name' => 'filerenameupload',
                        'options' => array(
                            'target' => getcwd() . '/public/files/thumbnail.png',
                            'randomize' => true,
                        ),
                    ),
                ),
                'validators' => array(
                    array(
                        'name' => 'filesize',
                        'options' => array('max' => 1024 * 1024 * 20),
                    ),
                    array(
                        'name' => 'filemimetype',
                        'options' => array(
                            'mimeType' => 'image/png,image/x-png,image/jpeg',
                        ),
                    ),
                    array(
                        'name' => 'fileimagesize',
                        'options' => array('maxWidth' => 1920, 'maxHeight' => 1600),
                    ),
                ),
            ),
        );
    }

    /**
     * @return ObjectManager
     */
    public function getObjectManager()
    {
        return $this->objectManager;
    }

    /**
     * @param ObjectManager $objectManager
     *
     * @return AddForm
     */
    public function setObjectManager(ObjectManager $objectManager)
    {
        $this->objectManager = $objectManager;

        return $this;
    }
}
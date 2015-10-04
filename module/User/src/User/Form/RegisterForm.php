<?php
/**
 * @file
 *
 */

namespace User\Form;


use Application\Form\AbstractForm;
use Doctrine\Common\Persistence\ObjectRepository;
use Zend\InputFilter\InputFilterProviderInterface;
use DoctrineModule\Stdlib\Hydrator\DoctrineObject as DoctrineHydrator;
use Zend\Stdlib\Hydrator\NamingStrategy\UnderscoreNamingStrategy;


class RegisterForm extends AbstractForm implements InputFilterProviderInterface
{
    /**
     * @var ObjectRepository
     */
    protected $objectRepository;

    public function __construct(ObjectRepository $objectManager, DoctrineHydrator $doctrineHydrator, $name = 'register',
        $options = array()
    ) {
        parent::__construct($name, $options);
        $this->setHydrator($doctrineHydrator);
        $this->setObjectRepository($objectManager);
    }

    public function init()
    {
        parent::init();

        $this->add(
            array(
                'name' => 'full_name',
                'type' => 'text',
                'options' => array(
                    'label' => $this->translate('Full name'),
                ),
            )
        );

        $this->add(
            array(
                'name' => 'company',
                'type' => 'text',
                'options' => array(
                    'label' => $this->translate('Company'),
                ),
            )
        );

        $this->add(
            array(
                'name' => 'email',
                'options' => array(
                    'label' => $this->translate('Email'),
                ),
                'attributes' => array(
                    'type' => 'text'
                ),
            )
        );

        $this->add(
            array(
                'name' => 'password',
                'type' => 'password',
                'options' => array(
                    'label' => $this->translate('Password'),
                ),
                'attributes' => array(
                    'type' => 'password',
                ),
            )
        );

        $this->add(
            array(
                'name' => 'submit',
                'type' => 'submit',
                'attributes' => array(
                    'value' => $this->translate('Register'),
                ),
            )
        );
    }

    /**
     * @inheritdoc
     */
    public function getInputFilterSpecification()
    {
        return array(
            'full_name' => array(
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
            'email' => array(
                'required' => true,
                'filters' => array(
                    array('name' => 'StripTags'),
                    array('name' => 'StringTrim'),
                ),
                'validators' => array(
                    array(
                        'name' => 'DoctrineModule\Validator\NoObjectExists',
                        'options' => array(
                            'object_repository' => $this->getObjectRepository(),
                            'fields' => 'email'
                        ),
                    ),
                    array(
                        'name' => 'EmailAddress',
                        'options' => array(
                            'useMxCheck' => false,
                        ),
                    ),
                ),
            ),
            'password' => array(
                'required' => true,
                'filters' => array(
                    array('name' => 'StringTrim'),
                ),
                'validators' => array(
                    array(
                        'name' => 'StringLength',
                        'options' => array(
                            'min' => 4,
                        ),
                    ),
                ),
            ),
        );
    }

    /**
     * @return ObjectRepository
     */
    public function getObjectRepository()
    {
        return $this->objectRepository;
    }

    /**
     * @param ObjectRepository $objectRepository
     *
     * @return RegisterForm
     */
    public function setObjectRepository(ObjectRepository $objectRepository)
    {
        $this->objectRepository = $objectRepository;

        return $this;
    }
}
<?php
/**
 * @file
 *
 */

namespace User\Form;

use Application\Form\AbstractForm;
use Zend\Authentication\AuthenticationServiceInterface;
use Zend\InputFilter\InputFilterProviderInterface;

class LoginForm extends AbstractForm implements InputFilterProviderInterface
{
    /**
     * @var AuthenticationServiceInterface
     */
    protected $authService;

    /**
     * @inheritdoc
     */
    public function __construct(AuthenticationServiceInterface $authenticationService, $name = 'login', $options = array())
    {
        parent::__construct($name, $options);

        $this->setAuthService($authenticationService);
    }

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();

        $this->setAttribute('class', 'form-inline');

        $this->add(
            array(
                'name' => 'email',
                'options' => array(
                    'glyphicon' => 'envelope',
                    'feedback' => TRUE,
                ),
                'attributes' => array(
                    'type' => 'text',
                    'placeholder' => $this->translate('Email'),
                    'class' => 'form-control',
                    'id' => 'email',
                ),
            )
        );

        $this->add(
            array(
                'name' => 'password',
                'type' => 'password',
                'options' => array(
                    'feedback' => TRUE,
                    'glyphicon' => 'lock',
                ),
                'attributes' => array(
                    'type' => 'password',
                    'placeholder' => $this->translate('Password'),
                    'class' => 'form-control',
                    'id' => 'password',
                ),
            )
        );

        $this->add(
            array(
                'name' => 'remember_me',
                'type' => 'checkbox',
                'options' => array(
                    'use_hidden_element' => false,
                    'label' => $this->translate('Remember me'),
                    'label_options' => array(
                        'always_wrap' => true,
                    ),
                ),
            )
        );

        $this->add(
            array(
                'name' => 'csrf',
                'type' => 'csrf',
            )
        );

        $this->add(
            array(
                'name' => 'submit',
                'type' => 'submit',
                'attributes' => array(
                    'value' => $this->translate('Sign in'),
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
            'csrf' => array(
                'required' => true,
                'allow_empty' => false,
                'continue_if_empty' => false,
                'break_on_failure' => true,
            ),
            'email' => array(
                'required' => true,
                'filters' => array(
                    array('name' => 'StringTrim'),
                ),
            ),
            'password' => array(
                'required' => true,
                'filters' => array(
                    array('name' => 'StringTrim'),
                ),
                'validators' => array(
                    array(
                        'name' => 'Zend\Authentication\Validator\Authentication',
                        'options' => array(
                            'identity' => 'email',
                            'credential' => 'password',
                            'service' => $this->getAuthService(),
                            'adapter' => $this->getAuthService()->getAdapter(),
                        ),
                    ),
                ),
            ),
            'remember_me' => array(
                'required' => false,
                'allow_empty' => true,
            ),
        );
    }

    /**
     * @return AuthenticationServiceInterface
     */
    public function getAuthService()
    {
        return $this->authService;
    }

    /**
     * @param AuthenticationServiceInterface $authService
     *
     * @return LoginForm
     */
    public function setAuthService($authService)
    {
        $this->authService = $authService;

        return $this;
    }
}
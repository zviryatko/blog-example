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
                'attributes' => array(
                    'type' => 'text',
                    'placeholder' => $this->translate('Email'),
                ),
            )
        );

        $this->add(
            array(
                'name' => 'password',
                'type' => 'password',
                'attributes' => array(
                    'type' => 'password',
                    'placeholder' => $this->translate('Password'),
                ),
            )
        );

        $this->add(
            array(
                'name' => 'remember_me',
                'type' => 'checkbox',
                'label' => $this->translate('Remember me'),
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
                            'service' => $this->getAuthService(),
                            'adapter' => $this->getAuthService()->getAdapter(),
                        ),
                    ),
                ),
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
<?php
/**
 * @file
 * Class AuthController
 */

namespace User\Controller;

use Application\Controller\AbstractController;
use User\Entity\User;
use User\Service\UserService;
use Zend\Authentication\AuthenticationServiceInterface;
use Zend\View\Model\ViewModel;

/**
 * Class AuthController
 *
 * @package User\Controller
 */
class AuthController extends AbstractController
{
    /**
     * @var AuthenticationServiceInterface
     */
    protected $authService;

    public function __construct(AuthenticationServiceInterface $authenticationService)
    {
        $this->setAuthService($authenticationService);
    }

    public function loginAction()
    {
        $identity = $this->identity();
        if ($identity instanceof User) {
            return $this->redirect()->toRoute('user');
        }

        /** @var \User\Form\LoginForm $form */
        $form = $this->getServiceLocator()->get('FormElementManager')->get('User\Form\LoginForm');
        if ($this->getRequest()->isPost()) {
            $form->setData($this->getRequest()->getPost());
            if ($form->isValid()) {
                $destination = trim($this->getRequest()->getQuery('destination', 'home'), '/');

                return $this->redirect()->toRoute($destination);
            }
        }

        $model = new ViewModel(array('form' => $form));
        $model->setTemplate('basic/form.phtml');

        return $model;
    }

    public function registerAction()
    {
        /** @var \User\Form\RegisterForm $form */
        $form = $this->getServiceLocator()->get('FormElementManager')->get('User\Form\RegisterForm');
        $user = new User();
        $form->bind($user);
        if ($this->getRequest()->isPost()) {
            $form->setData($this->getRequest()->getPost());
            if ($form->isValid()) {
                // @todo: remove this, @see UserService::hashPassword().
                $pass = $user->getPassword();
                $user->setPassword(UserService::hashPassword($pass));

                $objectManager = $this
                    ->getServiceLocator()
                    ->get('Doctrine\ORM\EntityManager');

                $objectManager->persist($user);
                $objectManager->flush();

                $this->flashMessenger()->addSuccessMessage('Thank you for registration!');
                return $this->redirect()->toRoute('home');
            }
        }

        $model = new ViewModel(array('form' => $form));
        $model->setTemplate('basic/form.phtml');

        return $model;
    }

    public function logoutAction()
    {
        $identity = $this->identity();
        if ($identity instanceof User) {
            $this->getAuthService()->clearIdentity();
        }

        return $this->redirect()->toRoute('home');
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
     * @return self
     */
    public function setAuthService($authService)
    {
        $this->authService = $authService;

        return $this;
    }
}
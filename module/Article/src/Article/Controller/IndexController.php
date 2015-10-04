<?php

namespace Article\Controller;

use Application\Controller\AbstractController;
use Article\Entity\Article;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\Persistence\ObjectRepository;
use Zend\View\Model\ViewModel;

class IndexController extends AbstractController
{
    /**
     * @var ObjectRepository
     */
    protected $repository;

    /**
     * @var ObjectManager
     */
    protected $objectManager;

    public function __construct(ObjectManager $objectManager)
    {
        $this->setObjectManager($objectManager);
        $this->setRepository($objectManager->getRepository('Article\Entity\Article'));
    }

    public function indexAction()
    {
        $articles = $this->getRepository()->findAll();

        return new ViewModel(array('articles' => $articles));
    }

    /**
     * @param Article $article
     *
     * @return ViewModel
     */
    public function viewAction(Article $article)
    {
        return new ViewModel(array('article' => $article));
    }

    /**
     * @return ObjectRepository
     */
    public function getRepository()
    {
        return $this->repository;
    }

    /**
     * @param ObjectRepository $repository
     *
     * @return IndexController
     */
    public function setRepository(ObjectRepository $repository)
    {
        $this->repository = $repository;

        return $this;
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
     * @return IndexController
     */
    public function setObjectManager(ObjectManager $objectManager)
    {
        $this->objectManager = $objectManager;

        return $this;
    }


}


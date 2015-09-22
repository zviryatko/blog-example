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

    public function addAction()
    {
        /** @var \Article\Form\AddForm $form */
        $form = $this->getServiceLocator()->get('FormElementManager')->get('Article\Form\AddForm');
        $article = new Article();
        $form->bind($article);
        if ($this->getRequest()->isPost()) {
            $form->setData($this->getRequest()->getPost());
            if ($form->isValid()) {

                $objectManager = $this->getObjectManager();
                $objectManager->persist($article);
                $objectManager->flush();

                $this->flashMessenger()->addSuccessMessage('Article added!');

                return $this->redirect()->toRoute('article/view', array('id' => $article->getId()));
            }
        }

        $model = new ViewModel(array('form' => $form));
        $model->setTemplate('basic/form.phtml');

        return $model;
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

    public function editAction(Article $article)
    {
        /** @var \Article\Form\AddForm $form */
        $form = $this->getServiceLocator()->get('FormElementManager')->get('Article\Form\EditForm');
        $request = $this->getRequest();
        $form->bind($article);
        if ($request->isPost()) {
            $actions = $request->getPost('actions');
            // Process delete.
            if (!empty($actions['delete'])) {
                return $this->redirect()->toRoute('article/delete', array('id' => $article->getId()));
            }

            $post = array_merge_recursive(
                $request->getPost()->toArray(),
                $request->getFiles()->toArray()
            );

            $form->setData($post);
            if ($form->isValid()) {
                $objectManager = $this->getObjectManager();
                $objectManager->persist($article);
                $objectManager->flush();

                $this->flashMessenger()->addSuccessMessage('Article updated!');

                return $this->redirect()->toRoute('article/view', array('id' => $article->getId()));
            }
        }

        $model = new ViewModel(array('form' => $form));
        $model->setTemplate('basic/form.phtml');

        return $model;
    }

    public function deleteAction(Article $article)
    {
        $form = $this->confirmForm('Are your really wont to delete this article?', 'Delete', 'Cancel');
        if ($this->getRequest()->isPost()) {
            $form->setData($this->getRequest()->getPost());
            if ($form->isValid()) {
                $data = $form->getData();
                if (!empty($data['decline'])) {
                    return $this->redirect()->toRoute('article/view', array('id' => $article->getId()));
                }
                elseif (!empty($data['confirm'])) {
                    $objectManager = $this->getObjectManager();
                    $objectManager->remove($article);
                    $objectManager->flush();

                    $this->flashMessenger()->addInfoMessage('Article deleted!');

                    return $this->redirect()->toRoute('article');
                }
            }
        }

        $model = new ViewModel(array('form' => $form));
        $model->setTemplate('basic/confirm.phtml');

        return $model;
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


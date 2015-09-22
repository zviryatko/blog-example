<?php
/**
 * @file
 *
 */

namespace Application\View\Helper\Navigation;

use Zend\Navigation\Page\AbstractPage;

class Menu extends \Zend\View\Helper\Navigation\Menu
{
    protected $liClass = 'menu-item';
    protected $liHasMenuClass = 'has-sub-menu';

    /**
     * Get list item class.
     *
     * @param AbstractPage $page
     *
     * @return string
     */
    public function getLiClass(AbstractPage $page)
    {
        /* @var $escaper \Zend\View\Helper\EscapeHtmlAttr */
        $escaper = $this->getView()->plugin('escapeHtmlAttr');

        $liClasses = array($this->liClass);
        if ($this->getAddClassToListItem()) {
            $liClasses[] = $page->getClass();
        }
        if ($page->hasPages()) {
            $liClasses[] = $this->getLiHasMenuClass();
        }
        if ($page->isActive(true)) {
            $liClasses[] = $this->getLiActiveClass();
        }

        return $escaper(implode(' ', $liClasses));
    }

    /**
     * @param string $liClass
     *
     * @return Menu
     */
    public function setLiClass($liClass)
    {
        if (is_string($liClass)) {
            $this->liClass = $liClass;
        }

        return $this;
    }

    /**
     * @return string
     */
    public function getLiHasMenuClass()
    {
        /* @var $escaper \Zend\View\Helper\EscapeHtmlAttr */
        $escaper = $this->getView()->plugin('escapeHtmlAttr');

        return $escaper($this->liHasMenuClass);
    }

    /**
     * @param string $liHasMenuClass
     *
     * @return Menu
     */
    public function setLiHasMenuClass($liHasMenuClass)
    {
        if (is_string($liHasMenuClass)) {
            $this->liHasMenuClass = $liHasMenuClass;
        }

        return $this;
    }
}
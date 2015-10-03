<?php
/**
 * @file
 *
 */

namespace Admin\View\Helper\Navigation;

use Zend\View\Helper\Navigation\Breadcrumbs;
use Zend\Navigation;
use Zend\Navigation\Page\AbstractPage;
use Zend\Permissions\Acl;
use Zend\View;
use Zend\View\Exception;

class AdminBreadcrumbs extends Breadcrumbs
{
    /**
     * @inheritdoc
     */
    protected $separator = '';

    /**
     * @inheritdoc
     */
    public function renderStraight($container = null)
    {
        $this->parseContainer($container);
        if (null === $container) {
            $container = $this->getContainer();
        }

        // find deepest active
        if (!$active = $this->findActive($container)) {
            return '';
        }

        $active = $active['page'];

        // put the deepest active page last in breadcrumbs
        if ($this->getLinkLast()) {
            $html = $this->htmlify($active);
        } else {
            $html = $this->htmlifyLabel($active);
        }
        $html = "<li class='active'>{$html}</li>";

        // walk back to root
        while ($parent = $active->getParent()) {
            if ($parent instanceof AbstractPage) {
                // prepend crumb to html
                $html = '<li class="active">' . $this->htmlify($parent)
                    . $this->getSeparator()
                    . $html . '</li>';
            }

            if ($parent === $container) {
                // at the root of the given container
                break;
            }

            $active = $parent;
        }

        $html = "<ol class='breadcrumb'>{$html}</ol>";

        return strlen($html) ? $this->getIndent() . $html : '';
    }

    /**
     * @inheritdoc
     */
    public function htmlify(AbstractPage $page)
    {
        $title = $this->translate($page->getTitle(), $page->getTextDomain());

        // get attribs for anchor element
        $attribs = array(
            'id'     => $page->getId(),
            'title'  => $title,
            'class'  => $page->getClass(),
            'href'   => $page->getHref(),
            'target' => $page->getTarget()
        );

        return '<a' . $this->htmlAttribs($attribs) . '>' . $this->htmlifyLabel($page) . '</a>';
    }

    public function htmlifyLabel(AbstractPage $page)
    {
        $label = $this->translate($page->getLabel(), $page->getTextDomain());

        /** @var \Zend\View\Helper\EscapeHtml $escaper */
        $escaper = $this->view->plugin('escapeHtml');
        $label   = $escaper($label);

        if (isset($page->icon)) {
            $attribs = array(
                'class' => 'fa fa-' . $page->icon,
            );
            $label = '<i ' . $this->htmlAttribs($attribs) . '></i> ' . $label;
        }

        return $label;
    }
}
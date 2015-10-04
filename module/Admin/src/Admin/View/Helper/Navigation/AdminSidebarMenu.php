<?php
/**
 * @file
 *
 */

namespace Admin\View\Helper\Navigation;

use Zend\View\Helper\Navigation\Menu;
use Zend\Navigation;
use Zend\Navigation\Page\AbstractPage;
use Zend\Permissions\Acl;
use Zend\View;
use Zend\View\Exception;
use RecursiveIteratorIterator;
use Zend\Navigation\AbstractContainer;

class AdminSidebarMenu extends Menu
{
    /**
     * @inheritdoc
     */
    protected $addClassToListItem = true;

    /**
     * @inheritdoc
     */
    protected $ulClass = 'sidebar-menu';

    /**
     * @inheritdoc
     */
    protected function renderNormalMenu(
        AbstractContainer $container,
        $ulClass,
        $indent,
        $minDepth,
        $maxDepth,
        $onlyActive,
        $escapeLabels,
        $addClassToListItem,
        $liActiveClass
    ) {
        $html = '';

        // find deepest active
        $found = $this->findActive($container, $minDepth, $maxDepth);
        /* @var $escaper \Zend\View\Helper\EscapeHtmlAttr */
        $escaper = $this->view->plugin('escapeHtmlAttr');

        if ($found) {
            $foundPage  = $found['page'];
            $foundDepth = $found['depth'];
        } else {
            $foundPage = null;
        }

        // create iterator
        $iterator = new RecursiveIteratorIterator(
            $container,
            RecursiveIteratorIterator::SELF_FIRST
        );
        if (is_int($maxDepth)) {
            $iterator->setMaxDepth($maxDepth);
        }

        // iterate container
        $prevDepth = -1;
        foreach ($iterator as $page) {
            $depth = $iterator->getDepth();
            $isActive = $page->isActive(true);
            if ($depth < $minDepth || !$this->accept($page)) {
                // page is below minDepth or not accepted by acl/visibility
                continue;
            } elseif ($onlyActive && !$isActive) {
                // page is not active itself, but might be in the active branch
                $accept = false;
                if ($foundPage) {
                    if ($foundPage->hasPage($page)) {
                        // accept if page is a direct child of the active page
                        $accept = true;
                    } elseif ($foundPage->getParent()->hasPage($page)) {
                        // page is a sibling of the active page...
                        if (!$foundPage->hasPages(!$this->renderInvisible) ||
                            is_int($maxDepth) && $foundDepth + 1 > $maxDepth) {
                            // accept if active page has no children, or the
                            // children are too deep to be rendered
                            $accept = true;
                        }
                    }
                }

                if (!$accept) {
                    continue;
                }
            }

            // make sure indentation is correct
            $depth -= $minDepth;
            $myIndent = $indent . str_repeat('        ', $depth);

            if ($depth > $prevDepth) {
                // start new ul tag
                if ($ulClass && $depth ==  0) {
                    $ulClass = ' class="' . $escaper($ulClass) . '"';
                } else {
                    $ulClass = ' class="treeview-menu"';
                }
                $html .= $myIndent . '<ul' . $ulClass . '>' . PHP_EOL;
            } elseif ($prevDepth > $depth) {
                // close li/ul tags until we're at current depth
                for ($i = $prevDepth; $i > $depth; $i--) {
                    $ind = $indent . str_repeat('        ', $i);
                    $html .= $ind . '    </li>' . PHP_EOL;
                    $html .= $ind . '</ul>' . PHP_EOL;
                }
                // close previous li tag
                $html .= $myIndent . '    </li>' . PHP_EOL;
            } else {
                // close previous li tag
                $html .= $myIndent . '    </li>' . PHP_EOL;
            }

            // render li tag and page
            $liClasses = [];
            // Is page active?
            if ($isActive) {
                $liClasses[] = $liActiveClass;
            }
            // Add CSS class from page to <li>
            if ($addClassToListItem && $page->getClass()) {
                $liClasses[] = $page->getClass();
            }
            // Add class if item has sub-menu.
            $isTreeView = $page->hasPages() && $depth <= $maxDepth;
            if ($isTreeView) {
                $liClasses[] = 'treeview';
            }

            $liClass = empty($liClasses) ? '' : ' class="' . $escaper(implode(' ', $liClasses)) . '"';

            $html .= $myIndent . '    <li' . $liClass . '>' . PHP_EOL
                . $myIndent . '        ' . $this->htmlify($page, $escapeLabels, $addClassToListItem, $isTreeView) . PHP_EOL;

            // store as previous depth for next iteration
            $prevDepth = $depth;
        }

        if ($html) {
            // done iterating container; close open ul/li tags
            for ($i = $prevDepth+1; $i > 0; $i--) {
                $myIndent = $indent . str_repeat('        ', $i-1);
                $html .= $myIndent . '    </li>' . PHP_EOL
                    . $myIndent . '</ul>' . PHP_EOL;
            }
            $html = rtrim($html, PHP_EOL);
        }

        return $html;
    }

    /**
     * @inheritdoc
     */
    public function htmlify(AbstractPage $page, $escapeLabel = true, $addClassToListItem = false, $isTreeView = false)
    {
        // get attribs for element
        $attribs = [
            'id'     => $page->getId(),
            'title'  => $this->translate($page->getTitle(), $page->getTextDomain()),
        ];

        if ($addClassToListItem === false) {
            $attribs['class'] = $page->getClass();
        }

        // does page have a href?
        $href = $page->getHref();
        if ($href) {
            $element = 'a';
            $attribs['href'] = $href;
            $attribs['target'] = $page->getTarget();
        } else {
            $element = 'span';
        }

        return '<' . $element . $this->htmlAttribs($attribs) . '>' . $this->htmlifyLabel($page, $escapeLabel, $isTreeView) . '</' . $element . '>';
    }

    /**
     * Returns an HTML string of page label with icon support.
     *
     * @param  AbstractPage $page page to generate HTML for
     * @param  bool $escapeLabel Whether or not to escape the label
     * @return string
     */
    public function htmlifyLabel(AbstractPage $page, $escapeLabel = true, $isTreeView = false)
    {
        $label = $this->translate($page->getLabel(), $page->getTextDomain());

        if ($escapeLabel) {
            /** @var \Zend\View\Helper\EscapeHtml $escaper */
            $escaper = $this->view->plugin('escapeHtml');
            $label = $escaper($label);
        }
        $label = '<span>' . $label . '</span>';

        if (isset($page->icon)) {
            $attribs = array(
                'class' => 'fa fa-' . $page->icon,
            );
            $label = '<i ' . $this->htmlAttribs($attribs) . '></i> ' . $label;
        }

        if ($isTreeView) {
            $label .= '<i class="fa fa-angle-left pull-right"></i>';
        }

        return $label;
    }
}
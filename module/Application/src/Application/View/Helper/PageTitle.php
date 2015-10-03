<?php
/**
 * @file
 *
 */

namespace Application\View\Helper;


use Zend\View\Helper\HeadTitle;

class PageTitle extends HeadTitle
{
    /**
     * Registry key for placeholder
     *
     * @var string
     */
    protected $regKey = 'Application_View_Helper_PageTitle';

    /**
     * @inheritdoc
     */
    public function toString($indent = null)
    {
        $indent = (null !== $indent)
            ? $this->getWhitespace($indent)
            : $this->getIndent();

        $output = $this->renderTitle();

        if (empty($output)) {
            return '';
        }

        /** @var PageDescription $pageDescription */
        $pageDescription = $this->getView()->plugin('pageDescription');

        $output .= $pageDescription->toString(' ');

        return $indent . '<h1>' . $output . '</h1>';
    }
}
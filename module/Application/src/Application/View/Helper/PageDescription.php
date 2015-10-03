<?php
/**
 * @file
 *
 */

namespace Application\View\Helper;


use Zend\View\Helper\HeadTitle;

class PageDescription extends HeadTitle
{
    /**
     * Registry key for placeholder
     *
     * @var string
     */
    protected $regKey = 'Application_View_Helper_PageDescription';

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

        return $indent . '<small>' . $output . '</small>';
    }
}
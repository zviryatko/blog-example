<?php
/**
 * @file
 *
 */

namespace Application\View\Helper;

use Zend\Form\ElementInterface;
use Zend\Form\View\Helper\FormFile;

class FormImageFile extends FormFile
{
    /**
     * @inheritdoc
     */
    public function render(ElementInterface $element)
    {
        $element->resetOriginalType();
        $output = parent::render($element);
        $escapeHtmlAttrHelper = $this->getView()->plugin('escapeHtmlAttr');
        $serverUrlHelper = $this->getView()->plugin('serverUrl');
        $value = $element->getValue();
        if (!empty($value)) {
            $src = $escapeHtmlAttrHelper($serverUrlHelper() . '/files/' . $value);
            $output = sprintf('<div class="thumbnail col-sm-2"><img src="%s" /></div>', $src) . $output;
        }

        return $output;
    }
}
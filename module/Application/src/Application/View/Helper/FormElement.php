<?php
/**
 * @file
 *
 */

namespace Application\View\Helper;


use Zend\Form\View\Helper\FormElement as FormElementHelper;

class FormElement extends FormElementHelper
{
    public function __construct()
    {
        $this->addType('imagefile', 'formimagefile');
    }
}
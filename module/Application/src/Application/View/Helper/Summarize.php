<?php
/**
 * @file
 *
 */

namespace Application\View\Helper;

use Aboustayyef\Summarizer;
use Zend\Form\View\Helper\AbstractHelper;

class Summarize extends AbstractHelper
{
    public function __invoke($text, $sentences = 3)
    {
        $stripTags = $this->getView()->plugin('stripTags');
        $summarizer = new Summarizer();
        $summarizer->text = $stripTags($text);

        return $summarizer->summarize($sentences);
    }
}
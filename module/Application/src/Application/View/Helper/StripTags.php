<?php
/**
 * @file
 *
 */

namespace Application\View\Helper;

use Zend\View\Helper\AbstractHelper;
use Zend\Filter\StripTags as StripTagsFilter;

class StripTags extends AbstractHelper
{
    public function __invoke($value)
    {
        $escaper = $this->getView()->plugin('escapehtml');
        $filter = new StripTagsFilter();

        return $escaper($filter->filter($value));
    }
}
<?php
/**
 * @file
 *
 */

namespace Article\View\Helper;


use Article\Entity\Article;
use Zend\Form\View\Helper\AbstractHelper;

class ArticleMetadataHelper extends AbstractHelper
{
    public function __invoke(Article $article)
    {
        $escaper = $this->getView()->plugin('escapehtml');

        // todo: get date format from config.
        $date = $article->getCreated()->format('d-m-Y h:i:s');

        return sprintf('<em class="published">%s</em>', $escaper($date));
    }
}
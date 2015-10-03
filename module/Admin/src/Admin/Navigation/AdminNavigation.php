<?php
/**
 * @file
 *
 */

namespace Admin\Navigation;

use Zend\Mvc\Router\RouteInterface;
use Zend\Navigation\Navigation;

class AdminNavigation extends Navigation
{
    /**
     * @inheritdoc
     */
    public function __construct(RouteInterface $route)
    {
        $pages = array();
        parent::__construct($pages);
    }
}
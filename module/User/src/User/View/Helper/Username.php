<?php
/**
 * @file
 *
 */

namespace User\View\Helper;

use User\Entity\User;
use Zend\View\Helper\AbstractHelper;

class Username extends AbstractHelper
{
    public function __invoke(User $user)
    {
        $escaper = $this->getView()->plugin('escapehtml');
        $url = $this->getView()->plugin('url');

        $href = $url('user/view', array('id' => $user->getId()));
        $name = $escaper($user->getFullName());

        return sprintf('<a class="user-view" href="%s">%s</a>', $href, $name);
    }
}
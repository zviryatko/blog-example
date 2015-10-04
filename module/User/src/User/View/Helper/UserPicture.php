<?php
/**
 * @file
 *
 */

namespace User\View\Helper;

use User\Entity\User;
use Zend\View\Helper\AbstractHelper;
use Zend\View\Helper\EscapeHtml;
use Zend\View\Helper\Gravatar;

class UserPicture extends AbstractHelper
{
    public function __invoke(User $user, $options, $attributes, $link = false)
    {
        /** @var Gravatar $gravatar */
        $gravatar = $this->getView()->plugin('gravatar');

        $picture = $gravatar($user->getEmail(), $options, $attributes)->getImgTag();

        if (!$link) {
            return $picture;
        }

        $url = $this->getView()->plugin('url');
        $href = $url('user/view', array('id' => $user->getId()));

        return sprintf('<a class="user-view" href="%s">%s</a>', $href, $picture);
    }
}
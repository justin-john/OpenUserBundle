<?php

/*
 * This file is part of the HWIOAuthBundle package.
 *
 * (c) Hardware.Info <opensource@hardware.info>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Open\UserBundle\OAuth\Response;

use HWI\Bundle\OAuthBundle\OAuth\Response\AdvancedUserResponseInterface as HWIAdvancedUserResponseInterface;

/**
 * UserResponseInterface
 *
 * @author Justin <justin@amt.in>
 */
interface AdvancedUserResponseInterface extends HWIAdvancedUserResponseInterface
{
    /**
     * Get the email address.
     *
     * @return null|string
     */
    public function getEmail();

    /**
     * Get the url to the profile picture.
     *
     * @return null|string
     */
    public function getProfilePicture();
}

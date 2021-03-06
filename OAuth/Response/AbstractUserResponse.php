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

use HWI\Bundle\OAuthBundle\OAuth\Response\AbstractUserResponse as HWIAbstractUserResponse;

use HWI\Bundle\OAuthBundle\OAuth\ResourceOwnerInterface;

use Symfony\Component\Security\Core\Exception\AuthenticationException;

/**
 * AbstractUserResponse
 *
 * @author Alexander <iam.asm89@gmail.com>
 */
abstract class AbstractUserResponse extends HWIAbstractUserResponse
{
    /**
     * @var array
     */
    protected $response;

    /**
     * @var ResourceOwnerInterface
     */
    protected $resourceOwner;

    /**
     * @var mixed
     */
    protected $accessToken;

    /**
     * {@inheritdoc}
     */
    public function getAccessToken()
    {
        return $this->accessToken;
    }

    /**
     * {@inheritdoc}
     */
    public function setAccessToken($accessToken)
    {
        $this->accessToken = $accessToken;
    }

    /**
     * {@inheritdoc}
     */
    public function getResponse()
    {
        return $this->response;
    }

    /**
     * {@inheritdoc}
     */
    public function setResponse($response)
    {
        $this->response = json_decode($response, true);

        if (json_last_error() != \JSON_ERROR_NONE) {
            throw new AuthenticationException(sprintf('Not a valid JSON response.'));
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getResourceOwner()
    {
        return $this->resourceOwner;
    }

    /**
     * {@inheritdoc}
     */
    public function setResourceOwner(ResourceOwnerInterface $resourceOwner)
    {
        $this->resourceOwner = $resourceOwner;
    }
}

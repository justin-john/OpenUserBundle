<?php

namespace Sewolabs\UserBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;

class SewolabsUserBundle extends Bundle
{
	public function getParent()
    {
        return 'FOSUserBundle';
    }
}

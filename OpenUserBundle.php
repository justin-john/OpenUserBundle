<?php

namespace Open\UserBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;

class OpenUserBundle extends Bundle
{
	public function getParent()
    {
        return 'FOSUserBundle';
    }
}

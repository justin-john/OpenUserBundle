<?php

namespace Open\UserBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class DefaultControllerTest extends WebTestCase
{
    public function testIndex()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/hello/Open');

        $this->assertTrue($crawler->filter('html:contains("Welcome to OpenUserBundle")')->count() > 0);
    }
}

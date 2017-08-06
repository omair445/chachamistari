<?php

namespace AppBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class UserControllerTest extends WebTestCase
{
    public function testUsersignup()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/webservice/signup_user');
    }

    public function testUserlogin()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/webservice/login_user');
    }

}

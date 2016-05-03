<?php

namespace AppBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class CoffeeControllerControllerTest extends WebTestCase
{
    public function testAddcoffee()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/admin/add/coffee_shop');
    }

    public function testEditcoffeeshop()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/admin/edit/{coffee_shop_id}');
    }

    public function testViewcoffeeshop()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/view/{coffee_shop_id}');
    }
}

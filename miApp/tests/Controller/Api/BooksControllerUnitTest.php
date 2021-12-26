<?php

namespace App\Tests\Controller\Api;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class BooksControllerUnitTest extends WebTestCase
{
    public function testCreateBookInvalidData()
    {
        $client = static::createClient();
        $client->request(
            'POST',
            '/api/books',
            [],
            [],
            ['CONTENT-TYPE' => 'application/json'],
            '{"title": ""}'
        );

        $this->assertEquals(400, $client->getResponse()->getStatusCode());
    }
}
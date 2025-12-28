<?php

namespace App\Tests\Controller;

use App\Entity\Author;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class AuthorControllerTest extends WebTestCase {
    public function testGetAuthors(): void {
        $client = static::createClient();
        $entityManager = $client->getContainer()->get('doctrine')->getManager();

        $author = new Author(
            name: 'J.K. Rowling',
            country: 'UK',
        );

        $entityManager->persist($author);
        $entityManager->flush();

        $client->request('GET', '/api/authors');

        $this->assertResponseIsSuccessful();
        $responseContent = json_decode($client->getResponse()->getContent(), true);

        $this->assertIsArray($responseContent);
        $this->assertGreaterThanOrEqual(1, count($responseContent));

        $this->assertSame('J.K. Rowling', $responseContent[0]['name']);
    }

    public function testCreateAuthor(): void {
        $client = static::createClient();

        $client->request(
            'POST',
            '/api/authors',
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            json_encode([
                'name' => 'Stephen King',
                'country' => 'USA'
            ])
        );

        $this->assertResponseStatusCodeSame(201);

        $response = json_decode($client->getResponse()->getContent(), true);

        $this->assertSame('Stephen King', $response['name']);

        $this->assertArrayHasKey('id', $response);
    }

    public function testUpdateAuthor(): void {
        $client = static::createClient();
        $entityManager = $client->getContainer()->get('doctrine')->getManager();

        $author = new Author(
            name: 'J.K. Rowling',
            country: 'UK',
        );

        $entityManager->persist($author);
        $entityManager->flush();
        $authorId = $author->getId();

        $client->request(
            'PUT',
            '/api/author/' . $authorId,
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            json_encode([
                'name' => 'Henryk Sienkiewicz',
                'country' => 'PL'
            ])
        );

        $this->assertResponseIsSuccessful();

        $response = json_decode($client->getResponse()->getContent(), true);
        $this->assertSame('Henryk Sienkiewicz', $response['name']);
        $this->assertSame('PL', $response['country']);

        $entityManager->clear();
        $updatedAuthor = $entityManager->getRepository(Author::class)->find($authorId);

        $this->assertEquals('Henryk Sienkiewicz', $updatedAuthor->getName());
    }

    public function testDestroyAuthor(): void {
        $client = static::createClient();
        $entityManager = $client->getContainer()->get('doctrine')->getManager();

        $author = new Author(
            name: 'J.K. Rowling',
            country: 'UK');
        $entityManager->persist($author);
        $entityManager->flush();

        $id = $author->getId();

        $client->request('DELETE', sprintf('/api/author/%s', $id));

        $this->assertResponseStatusCodeSame(200);

        $client->request('GET', sprintf('/api/author/%s', $id));
        $this->assertResponseStatusCodeSame(400);
    }
}

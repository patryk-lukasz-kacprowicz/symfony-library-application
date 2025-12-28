<?php

namespace App\Tests\Controller;

use App\Entity\Author;
use App\Entity\Book;
use PHPUnit\Framework\MockObject\Exception;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class BookControllerTest extends WebTestCase {
    /**
     * @throws Exception
     */
    public function testGetBooks(): void {
        $client = static::createClient();
        $entityManager = $client->getContainer()->get('doctrine')->getManager();

        $author = new Author(
            name: 'J.K. Rowling',
            country: 'UK',
        );

        $book = new Book(
            author: $author,
            name: 'Harry Potter and the Deathly Hallows – Part 2',
            amount: 100,
            price: 99.99,
            currency: 'PLN'
        );

        $entityManager->persist($author);
        $entityManager->persist($book);
        $entityManager->flush();

        $client->request('GET', '/api/books');

        $this->assertResponseIsSuccessful();
        $responseContent = json_decode($client->getResponse()->getContent(), true);

        $this->assertIsArray($responseContent);
        $this->assertGreaterThanOrEqual(1, count($responseContent));

        $this->assertSame($author->getName(), $responseContent[0]['author']['name']);
        $this->assertSame($author->getCountry(), $responseContent[0]['author']['country']);

        $this->assertSame('Harry Potter and the Deathly Hallows – Part 2', $responseContent[0]['name']);
        $this->assertSame(100, $responseContent[0]['amount']);
        $this->assertSame(99.99, $responseContent[0]['price']);
        $this->assertSame('PLN', $responseContent[0]['currency']);
    }

    public function testCreateBook(): void {
        $client = static::createClient();
        $entityManager = $client->getContainer()->get('doctrine')->getManager();

        $author = new Author(
            name: 'J.K. Rowling',
            country: 'UK',
        );
        $entityManager->persist($author);
        $entityManager->flush();

        $client->request(
            'POST',
            '/api/books',
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            json_encode([
                'authorId' => $author->getId(),
                'name' => 'Harry Potter and the Deathly Hallows – Part 2',
                'amount' => 105,
                'price' => 109.99,
                'currency' => 'PLN',
            ])
        );

        $this->assertResponseStatusCodeSame(201);

        $response = json_decode($client->getResponse()->getContent(), true);
        $this->assertSame('J.K. Rowling', $response['author']['name']);
        $this->assertSame('UK', $response['author']['country']);

        $this->assertSame('Harry Potter and the Deathly Hallows – Part 2', $response['name']);
        $this->assertSame(105, $response['amount']);
        $this->assertSame(109.99, $response['price']);
        $this->assertSame('PLN', $response['currency']);

        $this->assertArrayHasKey('id', $response);
    }

    public function testCreateBookWithoutAuthor(): void {
        $client = static::createClient();

        $client->request(
            'POST',
            '/api/books',
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            json_encode([
                'authorId' => 999,
                'name' => 'Harry Potter and the Deathly Hallows – Part 2',
                'amount' => 105,
                'price' => 109.99,
                'currency' => 'PLN',
            ])
        );

        $this->assertResponseStatusCodeSame(400);
    }

    public function testUpdateBook(): void {
        $client = static::createClient();
        $entityManager = $client->getContainer()->get('doctrine')->getManager();

        $author = new Author(
            name: 'Andrzej Sapkowski',
            country: 'PL'
        );
        $book = new Book(
            $author,
            name:'Witcher',
            amount: 5,
            price: 50.00,
            currency: 'PLN',
            visible: true
        );

        $entityManager->persist($author);
        $entityManager->persist($book);
        $entityManager->flush();

        $client->request(
            'PUT',
            sprintf('/api/book/%s', $book->getId()),
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            json_encode([
                'authorId' => $author->getId(),
                'name' => 'Witcher',
                'amount' => 15,
                'price' => 19.99,
                'currency' => 'PLN',
                'visible' => true
            ])
        );

        $this->assertResponseStatusCodeSame(200);
        $response = json_decode($client->getResponse()->getContent(), true);

        $this->assertEquals(15, $response['amount']);
        $this->assertEquals(19.99, $response['price']);
    }

    public function testDestroyBook(): void {
        $client = static::createClient();
        $entityManager = $client->getContainer()->get('doctrine')->getManager();

        $author = new Author(
            name: 'Andrzej Sapkowski',
            country: 'PL'
        );
        $book = new Book(
            $author,
            name:'Witcher',
            amount: 5,
            price: 50.00,
            currency: 'PLN',
            visible: true
        );

        $entityManager->persist($author);
        $entityManager->persist($book);
        $entityManager->flush();

        $id = $book->getId();

        $client->request('DELETE', sprintf('/api/book/%s', $id));

        $this->assertResponseStatusCodeSame(200);

        $client->request('GET', sprintf('/api/book/%s', $id));
        $this->assertResponseStatusCodeSame(400);
    }
}

<?php

namespace App\Tests\Controller;

use App\Entity\Author;
use App\Entity\Book;
use App\Entity\Review;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ReviewControllerTest extends WebTestCase {
    public function testGetReviews(): void {
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
        );
        $review = new Review(
            book: $book,
            score: 9,
            message: 'This book is awesome!'
        );


        $entityManager->persist($author);
        $entityManager->persist($book);
        $entityManager->persist($review);
        $entityManager->flush();

        $client->request('GET', '/api/reviews');

        $this->assertResponseIsSuccessful();
        $responseContent = json_decode($client->getResponse()->getContent(), true);

        $this->assertIsArray($responseContent);
        $this->assertGreaterThanOrEqual(1, count($responseContent));

        $this->assertSame('Andrzej Sapkowski', $responseContent[0]['book']['author']['name']);
        $this->assertSame('PL', $responseContent[0]['book']['author']['country']);

        $this->assertEquals('Witcher', $responseContent[0]['book']['name']);
        $this->assertEquals(5, $responseContent[0]['book']['amount']);
        $this->assertEquals(50.00, $responseContent[0]['book']['price']);
        $this->assertEquals('PLN', $responseContent[0]['book']['currency']);

        $this->assertEquals(9, $responseContent[0]['score']);
        $this->assertEquals('This book is awesome!', $responseContent[0]['message']);
    }

    public function testCreateReview(): void {
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
        );

        $entityManager->persist($author);
        $entityManager->persist($book);
        $entityManager->flush();

        $client->request(
            'POST',
            '/api/reviews',
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            json_encode([
                'bookId' => $book->getId(),
                'score' => 3,
                'message' => 'This book is nice.'
            ])
        );

        $this->assertResponseStatusCodeSame(201);

        $response = json_decode($client->getResponse()->getContent(), true);

        $this->assertEquals('Witcher', $response['book']['name']);
        $this->assertEquals(5, $response['book']['amount']);
        $this->assertEquals(50.00, $response['book']['price']);
        $this->assertEquals('PLN', $response['book']['currency']);

        $this->assertSame(3, $response['score']);
        $this->assertSame('This book is nice.', $response['message']);

        $this->assertArrayHasKey('id', $response);
    }

    public function testCreateReviewWithoutBook(): void {
        $client = static::createClient();

        $client->request(
            'POST',
            '/api/reviews',
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            json_encode([
                'bookId' => 999,
                'score' => 3,
                'message' => 'This book is nice.'
            ])
        );

        $this->assertResponseStatusCodeSame(400);
    }

    public function testUpdateReview(): void {
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
        );
        $review = new Review(
            book: $book,
            score: 4,
            message: 'This book is nice.'
        );


        $entityManager->persist($author);
        $entityManager->persist($book);
        $entityManager->persist($review);
        $entityManager->flush();

        $client->request(
            'PUT',
            sprintf('/api/review/%s', $review->getId()),
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            json_encode([
                'bookId' => $book->getId(),
                'score' => 9,
                'message' => 'This book is awesome!',
            ])
        );

        $this->assertResponseIsSuccessful();

        $response = json_decode($client->getResponse()->getContent(), true);
        $this->assertSame(9, $response['score']);
        $this->assertSame('This book is awesome!', $response['message']);
    }

    public function testDestroyReview(): void {
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
        );
        $review = new Review(
            book: $book,
            score: 9,
            message: 'This book is awesome!'
        );


        $entityManager->persist($author);
        $entityManager->persist($book);
        $entityManager->persist($review);
        $entityManager->flush();

        $id = $author->getId();

        $client->request('DELETE', sprintf('/api/review/%s', $id));

        $this->assertResponseStatusCodeSame(200);

        $client->request('GET', sprintf('/api/review/%s', $id));
        $this->assertResponseStatusCodeSame(400);
    }
}

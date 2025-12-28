<?php

namespace App\Tests\Entity;

use App\Entity\Author;
use App\Entity\Book;
use PHPUnit\Framework\MockObject\Exception;
use PHPUnit\Framework\TestCase;

class BookTest extends TestCase {
    /**
     * @throws Exception
     */
    public function testBookCreate(): void {
        $author = $this->createMock(Author::class);

        $book = new Book(
            author: $author,
            name: 'Krzyżacy',
            amount: 100,
            price: 19.99,
            currency: 'PLN',
            visible: true
        );

        $this->assertEquals($author, $book->getAuthor());
        $this->assertEquals('Krzyżacy', $book->getName());
        $this->assertEquals(100, $book->getAmount());
        $this->assertEquals(19.99, $book->getPrice());
        $this->assertEquals('PLN', $book->getCurrency());
    }

    /**
     * @throws Exception
     */
    public function testBookUpdate(): void {
        $author = $this->createMock(Author::class);
        $newAuthor = $this->createMock(Author::class);

        $book = new Book(
            author: $author,
            name: 'Krzyżacy',
            amount: 100,
            price: 19.99,
            currency: 'PLN',
            visible: true
        );

        $book->update(
            newAuthor: $newAuthor,
            newName: 'Pan Tadeusz',
            newAmount: 50,
            newPrice: 49.39,
            newCurrency: 'EUR',
        );

        $this->assertEquals($newAuthor, $book->getAuthor());
        $this->assertEquals('Pan Tadeusz', $book->getName());
        $this->assertEquals(50, $book->getAmount());
        $this->assertEquals(49.39, $book->getPrice());
        $this->assertEquals('EUR', $book->getCurrency());
    }
}

<?php

namespace App\Tests\Entity;

use App\Entity\Book;
use App\Entity\Review;
use PHPUnit\Framework\MockObject\Exception;
use PHPUnit\Framework\TestCase;

class ReviewTest extends TestCase {
    /**
     * @throws Exception
     */
    public function testReviewCreate(): void {
        $book = $this->createMock(Book::class);

        $review = new Review(
            book: $book,
            score: 9,
            message: 'This book is awesome!',
        );

        $this->assertEquals($book, $review->getBook());
        $this->assertEquals(9, $review->getScore());
        $this->assertEquals('This book is awesome!', $review->getMessage());
    }

    /**
     * @throws Exception
     */
    public function testReviewUpdate(): void {
        $book = $this->createMock(Book::class);

        $review = new Review(
            book: $book,
            score: 9,
            message: 'This book is awesome!',
        );

        $review->update(
            newScore: 4,
            newMessage: 'This book is great!',
        );

        $this->assertEquals($book, $review->getBook());
        $this->assertEquals(4, $review->getScore());
        $this->assertEquals('This book is great!', $review->getMessage());
    }
}

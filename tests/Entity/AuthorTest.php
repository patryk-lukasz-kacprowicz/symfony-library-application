<?php

namespace App\Tests\Entity;

use App\Entity\Author;
use PHPUnit\Framework\TestCase;

class AuthorTest extends TestCase {
    public function testAuthorCreate(): void {
        $author = new Author(
            name: 'J.K. Rowling',
            country: 'UK',
        );

        $this->assertEquals('J.K. Rowling', $author->getName());
        $this->assertEquals('UK', $author->getCountry());
        $this->assertCount(0, $author->getBooks());
    }

    public function testAuthorUpdate(): void {
        $author = new Author(
            name: 'J.K. Rowling',
            country: 'UK',
        );

        $author->update(
            newName: 'Henryk Sienkiewicz',
            newCountry: 'PL',
        );

        $this->assertEquals('Henryk Sienkiewicz', $author->getName());
        $this->assertEquals('PL', $author->getCountry());
    }
}

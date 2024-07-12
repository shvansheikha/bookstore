<?php

namespace App\Repositories;

use App\Models\Book;

class BookRepository
{
    public function getAllBooks()
    {
        return Book::where('user_id', auth()->id())->get();
    }

    public function getBook(Book $book)
    {
        return $book->load('author');
    }

    public function createBook(array $data)
    {
        return Book::create(array_merge(['user_id' => auth()->id()], $data));
    }

    public function updateBook(Book $book, array $data)
    {
        $book->update($data);

        return $book->fresh();
    }

    public function deleteBook(Book $book)
    {
        $book->delete();

        return $book;
    }
}

<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreBookRequest;
use App\Http\Requests\UpdateBookRequest;
use App\Http\Resources\BookResource;
use App\Models\Book;
use App\Repositories\BookRepository;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class BookController extends Controller
{
    public function __construct(private readonly BookRepository $repository)
    {
    }

    public function index(): AnonymousResourceCollection
    {
        $books = $this->repository->getAllBooks();

        return BookResource::collection($books);
    }

    public function show(Book $book): BookResource
    {
        $this->authorize('view', $book);

        $book = $this->repository->getBook($book);

        return BookResource::make($book);
    }

    public function store(StoreBookRequest $request): BookResource
    {
        $book = $this->repository->createBook($request->validated());

        return BookResource::make($book);
    }

    public function update(UpdateBookRequest $request, Book $book): BookResource
    {
        $this->authorize('update', $book);

        $book = $this->repository->updateBook($book, $request->validated());

        return BookResource::make($book);
    }

    public function delete(Book $book)
    {
        $this->authorize('delete', $book);

        $this->repository->deleteBook($book);

        return response()->json([], 204);
    }
}

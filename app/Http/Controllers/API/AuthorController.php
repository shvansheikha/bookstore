<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreAuthorRequest;
use App\Http\Resources\AuthorResource;
use App\Models\Author;

class AuthorController extends Controller
{
    public function index()
    {
        $authors = Author::query()->where('user_id', auth()->id())->get();

        return AuthorResource::collection($authors);
    }

    public function show(Author $author)
    {
        $this->authorize('view', $author);

        return AuthorResource::make($author);
    }

    public function store(StoreAuthorRequest $request)
    {
        $author = Author::query()->create(array_merge(['user_id' => auth()->id()], $request->validated()));

        return AuthorResource::make($author);
    }

    public function update(StoreAuthorRequest $request, Author $author)
    {
        $this->authorize('update', $author);

        $author->update($request->validated());

        return AuthorResource::make($author->fresh());
    }

    public function delete(Author $author)
    {
        $this->authorize('delete', $author);

        $author->delete();

        return response()->json([], 204);
    }
}

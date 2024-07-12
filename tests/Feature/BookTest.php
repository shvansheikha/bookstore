<?php

namespace Tests\Feature;

use App\Models\Author;
use App\Models\Book;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class BookTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    public function test_it_can_get_books_list()
    {
        $user = User::factory()->create();
        Book::factory()
            ->count(3)
            ->for($user)
            ->create();

        $this->actingAs(user: $user)
            ->getJson(route('api.books.index'))
            ->assertSuccessful()
            ->assertJsonStructure(['data' => ['*' => ['name', 'description', 'user_id', 'author_id']]]);
    }

    public function test_it_cant_see_non_own_book()
    {
        $user = User::factory()->create();
        $otherUser = User::factory()->create();
        $book = Book::factory()
            ->for($otherUser)
            ->create();

        $this->actingAs(user: $user)
            ->getJson(route('api.books.show', $book->id))
            ->assertStatus(403);
    }

    public function test_it_can_see_book()
    {
        $user = User::factory()->create();
        $book = Book::factory()
            ->for($user)
            ->create();

        $this->actingAs(user: $user)
            ->getJson(route('api.books.show', $book->id))
            ->assertJsonStructure(['data' => [
                'name',
                'description',
                'user_id',
                'author_id',
                'author' => ['id', 'name', 'user_id']
            ]]);
    }

    public function test_it_can_store_book()
    {
        $user = User::factory()->create();
        $author = Author::factory()->for($user)->create();

        $payload = [
            'name' => $this->faker->words(3, true),
            'description' => $this->faker->text,
            'author_id' => $author->id
        ];

        $this->actingAs(user: $user)
            ->postJson(route('api.books.store'), $payload)
            ->assertJsonStructure(['data' => ['name', 'description', 'user_id', 'author_id']]);

        $this->assertDatabaseHas('books', [
            'name' => $payload['name'],
            'description' => $payload['description'],
            'author_id' => $payload['author_id'],
            'user_id' => $user->id,
        ]);
    }

    public function test_it_cant_update_non_own_book()
    {
        $user = User::factory()->create();
        $otherUser = User::factory()->create();
        $book = Book::factory()
            ->for($otherUser)
            ->create();

        $payload = [
            'name' => $this->faker->words(3, true)
        ];

        $this->actingAs(user: $user)
            ->putJson(route('api.books.update', $book->id), $payload)
            ->assertForbidden();
    }

    public function test_it_can_update_book()
    {
        $user = User::factory()->create();
        $author = Author::factory()->create();
        $book = Book::factory()
            ->for($user)
            ->create();

        $payload = [
            'name' => 'updated name',
            'description' => 'updated description',
            'author_id' => $author->id
        ];

        $this->actingAs(user: $user)
            ->putJson(route('api.books.update', $book->id), $payload)
            ->assertSuccessful()
            ->assertJsonPath('data.name', $payload['name'])
            ->assertJsonPath('data.description', $payload['description'])
            ->assertJsonPath('data.author_id', $payload['author_id']);

        $this->assertDatabaseHas('books', [
            'name' => $payload['name'],
            'description' => $payload['description'],
            'author_id' => $payload['author_id']
        ]);
    }

    public function test_it_cant_delete_non_own_book()
    {
        $user = User::factory()->create();
        $anotherUser = User::factory()->create();
        $book = Book::factory()
            ->for($anotherUser)
            ->create();

        $this->actingAs(user: $user)
            ->deleteJson(route('api.books.destroy', $book->id))
            ->assertForbidden();

        $this->assertDatabaseHas('books', ['id' => $book->id]);
    }

    public function test_it_can_delete_non_own_book()
    {
        $user = User::factory()->create();
        $book = Book::factory()
            ->for($user)
            ->create();

        $this->actingAs(user: $user)
            ->deleteJson(route('api.books.destroy', $book->id))
            ->assertSuccessful();

        $this->assertDatabaseMissing('books', ['id' => $book->id]);
    }
}

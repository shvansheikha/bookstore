<?php

namespace Tests\Feature;

use App\Models\Author;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class AuthorTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    public function test_it_can_get_authors_list()
    {
        $user = User::factory()->create();
        Author::factory()
            ->count(3)
            ->for($user)
            ->create();

        $this->actingAs(user: $user)
            ->getJson(route('api.authors.index'))
            ->assertSuccessful()
            ->assertJsonStructure(['data' => ['*' => ['id', 'name', 'user_id']]]);
    }

    public function test_it_cant_see_non_own_author()
    {
        $user = User::factory()->create();
        $otherUser = User::factory()->create();
        $author = Author::factory()
            ->for($otherUser)
            ->create();

        $this->actingAs(user: $user)
            ->getJson(route('api.authors.show', $author->id))
            ->assertStatus(403);
    }

    public function test_it_can_see_author()
    {
        $user = User::factory()->create();
        $author = Author::factory()
            ->for($user)
            ->create();

        $this->actingAs(user: $user)
            ->getJson(route('api.authors.show', $author->id))
            ->assertJsonStructure(['data' => ['id', 'name', 'user_id']]);
    }

    public function test_it_can_store_author()
    {
        $user = User::factory()->create();

        $payload = [
            'name' => $this->faker->name
        ];

        $this->actingAs(user: $user)
            ->postJson(route('api.authors.store'), $payload)
            ->assertJsonStructure(['data' => ['id', 'name', 'user_id']]);

        $this->assertDatabaseHas('authors', [
            'name' => $payload['name'],
            'user_id' => $user->id,
        ]);
    }

    public function test_it_cant_update_non_own_author()
    {
        $user = User::factory()->create();
        $otherUser = User::factory()->create();
        $author = Author::factory()
            ->for($otherUser)
            ->create();

        $payload = [
            'name' => $this->faker->words(3, true)
        ];

        $this->actingAs(user: $user)
            ->putJson(route('api.authors.update', $author->id), $payload)
            ->assertForbidden();
    }

    public function test_it_can_update_author()
    {
        $user = User::factory()->create();
        $author = Author::factory()
            ->for($user)
            ->create();

        $payload = ['name' => 'updated name'];

        $this->actingAs(user: $user)
            ->putJson(route('api.authors.update', $author->id), $payload)
            ->assertSuccessful()
            ->assertJsonPath('data.name', $payload['name']);

        $this->assertDatabaseHas('authors', [
            'name' => $payload['name'],
            'user_id' => $user->id
        ]);
    }

    public function test_it_cant_delete_non_own_author()
    {
        $user = User::factory()->create();
        $anotherUser = User::factory()->create();
        $author = Author::factory()
            ->for($anotherUser)
            ->create();

        $this->actingAs(user: $user)
            ->deleteJson(route('api.authors.destroy', $author->id))
            ->assertForbidden();

        $this->assertDatabaseHas('authors', ['id' => $author->id]);
    }

    public function test_it_can_delete_non_own_author()
    {
        $user = User::factory()->create();
        $author = Author::factory()
            ->for($user)
            ->create();

        $this->actingAs(user: $user)
            ->deleteJson(route('api.authors.destroy', $author->id))
            ->assertSuccessful();

        $this->assertDatabaseMissing('authors', ['id' => $author->id]);
    }
}

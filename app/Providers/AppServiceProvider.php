<?php

namespace App\Providers;

use App\Models\Author;
use App\Models\Book;
use App\Policies\AuthorPolicy;
use App\Policies\BookPolicy;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AppServiceProvider extends ServiceProvider
{

    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        Gate::policy(Book::class, BookPolicy::class);
        Gate::policy(Author::class, AuthorPolicy::class);
    }
}

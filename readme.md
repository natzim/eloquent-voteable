# Eloquent Voteable

[![Build Status](https://img.shields.io/travis/natzim/eloquent-voteable.svg?style=flat-square)](https://travis-ci.org/natzim/eloquent-voteable)
[![GPA](https://img.shields.io/codeclimate/github/natzim/eloquent-voteable.svg?style=flat-square)](https://codeclimate.com/github/natzim/eloquent-voteable)
[![Code Coverage](https://img.shields.io/codeclimate/coverage/github/natzim/eloquent-voteable.svg?style=flat-square)](https://codeclimate.com/github/natzim/eloquent-voteable/coverage)

## Installation

Install the package using composer

```
composer require natzim/eloquent-voteable
```

Add the service provider to the `providers` array in `app/config/app.php`

```
'providers' => [
    // ...
    Natzim\EloquentVoteable\VoteableServiceProvider::class
]
```

Publish the config and migrations

```
php artisan vendor:publish
```

Run the migrations

```
php artsain migrate
```

## Documentation

This package is made up of three important files:

- `Natzim\EloquentVoteable\Voter`
- `Natzim\EloquentVoteable\Traits\VoterTrait`
- `Natzim\EloquentVoteable\Traits\VoteableTrait`

`Voter` is the main class where all the logic happens. You should never need to access it directly.

`VoterTrait` contains all the methods needed to allow a model to vote on another model.

`VoteableTrait` contains all the methods needed to allow a model to be voted on.

For example, you could have a user, who can vote on posts.

In the user model:

```php
use Natzim\EloquentVoteable\Traits\VoterInterface;
use Natzim\EloquentVoteable\Traits\VoterTrait;

class User extends Model implements VoterInterface
{
    use VoterTrait;

    // ...
}
```

In the post model:

```php
use Natzim\EloquentVoteable\Traits\VoteableInterface;
use Natzim\EloquentVoteable\Traits\VoteableTrait;

class Post extends Model implements VoteableInterface
{
    use VoteableTrait;

    // ...
}
```

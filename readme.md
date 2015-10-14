# Eloquent Voteable

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

## Usage

### Voter

`Voter` is applied to models that can vote. (e.g. users)

```php
use Natzim\EloquentVoteable\Traits\VoterInterface;
use Natzim\EloquentVoteable\Traits\VoterTrait;

class User extends Model implements VoterInterface
{
    use VoterTrait;

    // ...
}
```

#### Methods

##### Vote up

```php
$user->upVote($post);
// or
$user->vote($post, 1);
```

##### Vote down

```php
$user->downVote($post);
// or
$user->vote($post, -1);
```

##### Cancel vote

```php
$user->cancelVote($post);
// or
$user->vote($post, 0);
```

##### Get votes

```php
$user->votes;
```

##### Get previous vote

```php
$user->getVote($post);
```

### Voteable

`Voteable` is applied to models that can be voted on. (e.g. posts)

```php
use Natzim\EloquentVoteable\Traits\VoteableInterface;
use Natzim\EloquentVoteable\Traits\VoteableTrait;

class Post extends Model implements VoteableInterface
{
    use VoteableTrait;

    // ...
}
```

#### Methods

##### Get score

Score is the number of upvotes minus the number of downvotes.

```
$post->score();
// or
$post->votes()->sum('weight');
```

##### Vote up

```php
$post->upVoteBy($user);
// or
$post->voteBy($user, 1);
```

##### Vote down

```php
$post->downVoteBy($user);
// or
$post->voteBy($user, -1);
```

##### Cancel vote

```php
$post->cancelVoteBy($user);
// or
$post->voteBy($user, 0);
```

##### Get votes

```php
$post->votes;
```

##### Get previous vote by voter

```php
$post->getVoteBy($user);
```

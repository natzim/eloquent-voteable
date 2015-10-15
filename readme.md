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

In the code examples, `$voter` implements `VoterInterface` and `$voteable` implements `VoteableInterface`.

### Voters

Voters are any model that can vote on voteables.

Voter models should implement `VoterInterface` and use `Votes`:

```php
use Natzim\EloquentVoteable\Contracts\VoterInterface;
use Natzim\EloquentVoteable\Traits\Votes;

class User extends Model implements VoterInterface
{
    use Votes;

    // ...
}
```

#### Methods

##### Get votes by a voter

```php
$voter->votes;
```

Returns: `Collection`

##### Vote on voteable

```php
$voter->vote($voteable, 1); // Upvote $voteable
```

**NOTE: Vote weights must be between -127 and 127, as it is stored as a `tinyInteger`**

Returns: `Vote`

##### Vote up a voteable

```php
$voter->upVote($voteable);
```

Returns: `Vote`

##### Vote down a voteable

```php
$voter->downVote($voteable);
```

Returns: `Vote`

##### Cancel a vote on a voteable

```php
$voter->cancelVote($voteable);
```

Returns: `null`

##### Get the previous vote on a voteable

```php
$voter->getVote($voteable);
```
Returns: `Vote`

### Voteables

Voteables are any model that can be voted on by voters.

Voteable classes should implement `VoteableInterface` and use `Voteable`:

```php
use Natzim\EloquentVoteable\Contracts\VoteableInterface;
use Natzim\EloquentVoteable\Traits\Voteable;

class Post extends Model implements VoteableInterface
{
    use Voteable;

    // ...
}
```

#### Methods

##### Get the score of a voteable

```php
$voteable->score();
```

Returns: `int`

##### Get votes on a voteable

```php
$voteable->votes;
```

Returns: `Collection`

##### Vote on a voteable

```php
$voteable->voteBy($voter, 1); // Upvote $voteable
```

**NOTE: Vote weights must be between -127 and 127, as it is stored as a `tinyInteger`**

Returns: `Vote`

##### Vote up a voteable

```php
$voteable->upVoteBy($voter);
```

Returns: `Vote`

##### Vote down a voteable

```php
$voteable->downVoteBy($voter);
```

Returns: `Vote`

##### Cancel a vote on a voteable

```php
$voteable->cancelVoteBy($voter);
```

Returns: `null`

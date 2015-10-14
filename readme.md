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

<?php

use Illuminate\Database\Eloquent\Model;
use Natzim\EloquentVoteable\Contracts\VoteableInterface;
use Natzim\EloquentVoteable\Traits\Voteable;

class Post extends Model implements VoteableInterface
{
    use Voteable;

    public $timestamps = false;
}

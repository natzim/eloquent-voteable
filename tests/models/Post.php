<?php

use Illuminate\Database\Eloquent\Model;
use Natzim\EloquentVoteable\Traits\VoteableInterface;
use Natzim\EloquentVoteable\Traits\VoteableTrait;

class Post extends Model implements VoteableInterface
{
    use VoteableTrait;

    public $timestamps = false;
}

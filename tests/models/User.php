<?php

use Illuminate\Database\Eloquent\Model;
use Natzim\EloquentVoteable\Contracts\VoterInterface;
use Natzim\EloquentVoteable\Traits\Votes;

class User extends Model implements VoterInterface
{
    use Votes;

    public $timestamps = false;
}

<?php

use Illuminate\Database\Eloquent\Model;
use Natzim\EloquentVoteable\Traits\VoterInterface;
use Natzim\EloquentVoteable\Traits\VoterTrait;

class User extends Model implements VoterInterface
{
    use VoterTrait;

    public $timestamps = false;
}

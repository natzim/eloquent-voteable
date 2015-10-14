<?php

namespace Natzim\EloquentVoteable\Traits;

use Illuminate\Database\Eloquent\Model;

interface VoteableInterface
{
    public function votes();
    public function score();
    public function getVoteBy(Model $voter);
    public function voteBy(Model $voter, $weight);
    public function upVoteBy(Model $voter);
    public function downVoteBy(Model $voter);
    public function cancelVoteBy(Model $voter);
}

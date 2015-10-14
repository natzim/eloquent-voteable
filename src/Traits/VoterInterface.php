<?php

namespace Natzim\EloquentVoteable\Traits;

use Illuminate\Database\Eloquent\Model;

interface VoterInterface
{
    public function getVote(Model $model);
    public function vote(Model $model, $weight);
    public function upVote(Model $model);
    public function downVote(Model $model);
    public function cancelVote(Model $model);
}

<?php

namespace Natzim\EloquentVoteable\Traits;

use Natzim\EloquentVoteable\Traits\VoteableInterface;

interface VoterInterface
{
    public function getVote(VoteableInterface $voteable);
    public function vote(VoteableInterface $voteable, $weight);
    public function upVote(VoteableInterface $voteable);
    public function downVote(VoteableInterface $voteable);
    public function cancelVote(VoteableInterface $voteable);
}

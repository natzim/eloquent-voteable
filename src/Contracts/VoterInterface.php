<?php

namespace Natzim\EloquentVoteable\Contracts;

interface VoterInterface
{
    public function votes();
    public function getVote(VoteableInterface $voteable);
    public function vote(VoteableInterface $voteable, $weight);
    public function upVote(VoteableInterface $voteable);
    public function downVote(VoteableInterface $voteable);
    public function cancelVote(VoteableInterface $voteable);
}

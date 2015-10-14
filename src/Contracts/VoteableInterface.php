<?php

namespace Natzim\EloquentVoteable\Contracts;

interface VoteableInterface
{
    public function votes();
    public function score();
    public function getVoteBy(VoterInterface $voter);
    public function voteBy(VoterInterface $voter, $weight);
    public function upVoteBy(VoterInterface $voter);
    public function downVoteBy(VoterInterface $voter);
    public function cancelVoteBy(VoterInterface $voter);
}

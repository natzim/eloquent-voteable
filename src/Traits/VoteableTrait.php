<?php

namespace Natzim\EloquentVoteable\Traits;

use Natzim\EloquentVoteable\Models\Vote;
use Natzim\EloquentVoteable\Traits\VoterInterface;
use Natzim\EloquentVoteable\Voter;

trait VoteableTrait
{
    /**
     * Get votes made on resource.
     *
     * @return mixed
     */
    public function votes()
    {
        return $this->morphMany(Vote::class, 'voteable');
    }

    /**
     * Get the resource's score.
     *
     * @return int
     */
    public function score()
    {
        return $this->votes()->sum('weight');
    }

    /**
     * Get a voter's previous vote on resource.
     *
     * @return Vote|null
     */
    public function getVoteBy(VoterInterface $voter)
    {
        return Voter::get($voter, $this);
    }

    /**
     * Vote on resource by voter.
     *
     * @param  VoterInterface $voter  VoterInterface voting on resource.
     * @param  int            $weight Weight of the vote.
     * @return Vote|null              Newly created vote or null if deleted.
     */
    public function voteBy(VoterInterface $voter, $weight)
    {
        return Voter::vote($voter, $this, $weight);
    }

    /**
     * Upvote resource by voter.
     *
     * @param  VoterInterface $voter
     * @return Vote
     */
    public function upVoteBy(VoterInterface $voter)
    {
        return $this->voteBy($voter, 1);
    }

    /**
     * Downvote resource by voter.
     *
     * @param  VoterInterface $voter
     * @return Vote
     */
    public function downVoteBy(VoterInterface $voter)
    {
        return $this->voteBy($voter, -1);
    }

    /**
     * Cancel vote on resource by voter.
     *
     * @param  VoterInterface $voter
     * @return Vote
     */
    public function cancelVoteBy(VoterInterface $voter)
    {
        return $this->voteBy($voter, 0);
    }
}

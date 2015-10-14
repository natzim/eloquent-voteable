<?php

namespace Natzim\EloquentVoteable\Traits;

use Natzim\EloquentVoteable\Voter;
use Natzim\EloquentVoteable\Models\Vote;
use Natzim\EloquentVoteable\Traits\VoteableInterface;

trait VoterTrait
{
    /**
     * Get votes that the voter has made.
     *
     * @return mixed
     */
    public function votes()
    {
        return $this->morphMany(Vote::class, 'voter');
    }

    /**
     * Get previous vote by voter on a voteable.
     *
     * @return Vote|null
     */
    public function getVote(VoteableInterface $voteable)
    {
        return Voter::get($this, $voteable);
    }

    /**
     * Vote on a voteable.
     *
     * @param  VoteableInterface $voteable
     * @param  int               $weight
     * @return Vote
     */
    public function vote(VoteableInterface $voteable, $weight)
    {
        return Voter::vote($this, $voteable, $weight);
    }

    /**
     * Upvote a voteable.
     *
     * @param  VoteableInterface $voteable
     * @return Vote
     */
    public function upVote(VoteableInterface $voteable)
    {
        return $this->vote($voteable, 1);
    }

    /**
     * Downvote a voteable.
     *
     * @param  VoteableInterface $voteable
     * @return Vote
     */
    public function downVote(VoteableInterface $voteable)
    {
        return $this->vote($voteable, -1);
    }

    /**
     * Cancel a vote on a voteable.
     *
     * @param  VoteableInterface $voteable
     * @return Vote
     */
    public function cancelVote(VoteableInterface $voteable)
    {
        return $this->vote($voteable, 0);
    }
}

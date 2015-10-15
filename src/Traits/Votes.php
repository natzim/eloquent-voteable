<?php

namespace Natzim\EloquentVoteable\Traits;

use Natzim\EloquentVoteable\Contracts\VoteableInterface;
use Natzim\EloquentVoteable\Models\Vote;

trait Votes
{
    /**
     * Get votes that the voter has made.
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphMany
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
        return Vote::get($this, $voteable);
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
        return Vote::store($this, $voteable, $weight);
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

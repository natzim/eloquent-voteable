<?php

namespace Natzim\EloquentVoteable\Traits;

use Natzim\EloquentVoteable\Contracts\VoterInterface;
use Natzim\EloquentVoteable\Models\Vote;

trait Voteable
{
    /**
     * Get votes made on resource.
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphMany
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
        return Vote::get($voter, $this);
    }

    /**
     * Vote on resource by voter.
     *
     * @param  VoterInterface $voter
     * @param  int            $weight
     * @return Vote|null
     */
    public function voteBy(VoterInterface $voter, $weight)
    {
        return Vote::store($voter, $this, $weight);
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

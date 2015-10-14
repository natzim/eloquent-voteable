<?php

namespace Natzim\EloquentVoteable\Traits;

use Illuminate\Database\Eloquent\Model;
use Natzim\EloquentVoteable\Exceptions\NotVoteableException;
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
     * Get previous vote by voter on given resource.
     *
     * @return Vote
     */
    public function getVote(Model $model)
    {
        return $this->votes()
            ->where('voteable_id', $model->getKey())
            ->where('voteable_type', get_class($model))
            ->first();
    }

    /**
     * Vote on a given resource.
     *
     * @throws NotVoteableException if the model being voted on does not
     *      implement `VoteableInterface`.
     *
     * @param  Model $model
     * @param  int   $weight
     * @return Vote
     */
    public function vote(Model $model, $weight)
    {
        if (! $model instanceof VoteableInterface) {
            throw new NotVoteableException;
        }

        return $model->voteBy($this, $weight);
    }

    /**
     * Upvote a given resource.
     *
     * @param  Model $model
     * @return Vote
     */
    public function upVote(Model $model)
    {
        return $this->vote($model, 1);
    }

    /**
     * Downvote a given resource.
     *
     * @param  Model $model
     * @return Vote
     */
    public function downVote(Model $model)
    {
        return $this->vote($model, -1);
    }

    /**
     * Cancel a vote on a given resource.
     *
     * @param  Model $model
     * @return Vote
     */
    public function cancelVote(Model $model)
    {
        return $this->vote($model, 0);
    }
}

<?php

namespace Natzim\EloquentVoteable\Traits;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Natzim\EloquentVoteable\Models\Vote;

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
     * @return Model
     */
    public function getVoteBy(Model $voter)
    {
        return $this->votes()
            ->where('voter_id', $voter->getKey())
            ->where('voter_type', get_class($voter))
            ->firstOrFail();
    }

    /**
     * Vote on resource by voter.
     *
     * @param  Model     $voter  Model voting on resource.
     * @param  int       $weight Weight of the vote.
     * @return Vote|null         Newly created vote or null if deleted.
     */
    public function voteBy(Model $voter, $weight)
    {
        try {
            $vote = $this->votes()
                ->where('voter_id', $voter->getKey())
                ->where('voter_type', get_class($voter))
                ->firstOrFail();

            if ($weight == 0 && config('voteable.cancel_behavior') === 'delete') {
                $vote->delete();

                return null;
            }

        } catch (ModelNotFoundException $e) {
            if ($weight == 0 && config('voteable.cancel_behavior') === 'delete') {
                // There is no previous vote, so there is nothing to delete
                return null;
            }

            $vote = new Vote;

            $vote->voteable()->associate($this);
            $vote->voter()->associate($voter);
        }

        $vote->weight = $weight;

        $vote->save();

        return $vote;
    }

    /**
     * Upvote resource by voter.
     *
     * @param  Model $voter
     * @return Vote
     */
    public function upVoteBy(Model $voter)
    {
        return $this->voteBy($voter, 1);
    }

    /**
     * Downvote resource by voter.
     *
     * @param  Model $voter
     * @return Vote
     */
    public function downVoteBy(Model $voter)
    {
        return $this->voteBy($voter, -1);
    }

    /**
     * Cancel vote on resource by voter.
     *
     * @param  Model $voter
     * @return Vote
     */
    public function cancelVoteBy(Model $voter)
    {
        return $this->voteBy($voter, 0);
    }
}

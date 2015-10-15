<?php

namespace Natzim\EloquentVoteable\Models;

use Illuminate\Database\Eloquent\Model;
use Natzim\EloquentVoteable\Contracts\VoteableInterface;
use Natzim\EloquentVoteable\Contracts\VoterInterface;

class Vote extends Model
{
    /**
     * Get voteable relations.
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphTo
     */
    public function voteable()
    {
        return $this->morphTo();
    }

    /**
     * Get voter relations.
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphTo
     */
    public function voter()
    {
        return $this->morphTo();
    }

    /**
     * Main vote function.
     *
     * @param  VoterInterface    $voter
     * @param  VoteableInterface $votable
     * @param  int               $weight
     * @return Vote|null
     */
    public static function store(VoterInterface $voter, VoteableInterface $voteable, $weight)
    {
        $vote = Vote::where('voter_id', $voter->getKey())
            ->where('voter_type', get_class($voter))
            ->where('voteable_id', $voter->getKey())
            ->where('voteable_type', get_class($voteable))
            ->first();

        if (is_null($vote)) {
            // Previous vote does not exist
            if ($weight == 0) {
                // Trying to cancel vote that does not exist
                return;
            }

            $vote = new Vote;
            $vote->voteable()->associate($voteable);
            $vote->voter()->associate($voter);
        }

        if ($weight == 0) {
            // Cancelling previous vote
            $vote->delete();
            return;
        }

        $vote->weight = $weight;
        $vote->save();

        return $vote;
    }

    /**
     * Get vote by voter and voteable.
     *
     * @param  VoterInterface    $voter
     * @param  VoteableInterface $voteable
     * @return Vote|null
     */
    public static function get(VoterInterface $voter, VoteableInterface $voteable)
    {
        return self::where('voter_id', $voter->getKey())
            ->where('voter_type', get_class($voter))
            ->where('voteable_id', $voteable->getKey())
            ->where('voteable_type', get_class($voteable))
            ->first();
    }
}

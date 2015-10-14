<?php

namespace Natzim\EloquentVoteable;

use Natzim\EloquentVoteable\Models\Vote;
use Natzim\EloquentVoteable\Traits\VoteableInterface;
use Natzim\EloquentVoteable\Traits\VoterInterface;

class Voter
{
    /**
     * Main vote function.
     *
     * @param  VoterInterface    $voter   Model voting on resource.
     * @param  VoteableInterface $votable Resource being voted on.
     * @param  int               $weight  Weight of the vote (usually -1, 0 or 1).
     * @return Vote|null                  Newly created vote or null if cancelled.
     */
    public static function vote(VoterInterface $voter, VoteableInterface $voteable, $weight)
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

    public static function get(VoterInterface $voter, VoteableInterface $voteable)
    {
        return Vote::where('voter_id', $voter->getKey())
            ->where('voter_type', get_class($voter))
            ->where('voteable_id', $voteable->getKey())
            ->where('voteable_type', get_class($voteable))
            ->first();
    }
}

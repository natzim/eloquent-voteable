<?php

namespace Natzim\EloquentVoteable\Models;

use Illuminate\Database\Eloquent\Model;

class Vote extends Model
{
    public function voteable()
    {
        return $this->morphTo();
    }

    public function voter()
    {
        return $this->morphTo();
    }
}

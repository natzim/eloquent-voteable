<?php

namespace Natzim\EloquentVoteable\Models;

use Illuminate\Database\Eloquent\Model;

class Vote extends Model
{
    protected $guarded = ['id', 'created_at', 'updated_at'];

    public function voteable()
    {
        return $this->morphTo();
    }

    public function voter()
    {
        return $this->morphTo();
    }
}

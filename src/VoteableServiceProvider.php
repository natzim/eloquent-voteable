<?php

namespace Natzim\EloquentVoteable;

use Illuminate\Support\ServiceProvider;

class VoteableServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->publishes([
            __DIR__.'/../database/migrations/create_votes_table.php' =>
            database_path('migrations/'.date('Y_m_d_His').'_create_votes_table.php'),
        ], 'migrations');
    }

    public function register()
    {
        //
    }
}

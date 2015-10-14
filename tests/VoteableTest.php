<?php

use Natzim\EloquentVoteable\Models\Vote;
use Orchestra\Testbench\TestCase;

class VoteableTest extends TestCase
{
    /*
     * Set up
     */

    public function setUp()
    {
        parent::setUp();

        // Migrate vote table
        $this->artisan('migrate', [
            '--database' => 'testing',
            '--realpath' => realpath(__DIR__.'/../database/migrations'),
        ]);

        // Migrate test tables
        $this->artisan('migrate', [
            '--database' => 'testing',
            '--realpath' => realpath(__DIR__.'/database/migrations'),
        ]);
    }

    protected function getEnvironmentSetUp($app)
    {
        $app['config']->set('database.default', 'testing');
        $app['config']->set('database.connections.testing', [
            'driver'   => 'sqlite',
            'database' => ':memory:',
            'prefix'   => '',
        ]);
    }

    protected function getPackageProviders()
    {
        return ['Natzim\EloquentVoteable\VoteableServiceProvider'];
    }

    /*
     * Helpers
     */

    protected function makeUser()
    {
        $user = new User;

        $user->name = str_random(10);

        $user->save();

        return $user;
    }

    protected function makePost()
    {
        $post = new Post;

        $post->title = str_random(10);

        $post->save();

        return $post;
    }

    /*
     * Tests
     */

    public function testVoterUpVoteResource()
    {
        $user = $this->makeUser();
        $post = $this->makePost();

        $user->upVote($post);

        $this->assertEquals($post->score(), 1);
    }

    public function testVoterDownVoteResource()
    {
        $user = $this->makeUser();
        $post = $this->makePost();

        $user->downVote($post);

        $this->assertEquals($post->score(), -1);
    }

    public function testVoterCancelVoteOnResource()
    {
        $user = $this->makeUser();
        $post = $this->makePost();

        // First upvote post
        $user->upVote($post);

        // Then cancel vote
        $user->cancelVote($post);

        $this->assertEquals($post->score(), 0);
    }

    public function testVoterGetVote()
    {
        $user = $this->makeUser();
        $post = $this->makePost();

        // First upvote post
        $user->upVote($post);

        // Then get vote
        $vote = $user->getVote($post);

        $this->assertInstanceOf(Vote::class, $vote);
    }

    public function testResourceUpVoteByVoter()
    {
        $user = $this->makeUser();
        $post = $this->makePost();

        $post->upVoteBy($user);

        $this->assertEquals($post->score(), 1);
    }

    public function testResourceDownVoteByVoter()
    {
        $user = $this->makeUser();
        $post = $this->makePost();

        $post->downVoteBy($user);

        $this->assertEquals($post->score(), -1);
    }

    public function testResourceCancelVoteByVoter()
    {
        $user = $this->makeUser();
        $post = $this->makePost();

        // First upvote post
        $post->upVoteBy($user);

        // Then cancel vote
        $post->cancelVoteBy($user);

        $this->assertEquals($post->score(), 0);
    }

    public function testResourceGetVoteByVoter()
    {
        $user = $this->makeUser();
        $post = $this->makePost();

        // First upvote post
        $post->upVoteBy($user);

        // Then get vote
        $vote = $post->getVoteBy($user);

        $this->assertInstanceOf(Vote::class, $vote);
    }

    public function testUpdateVoteWithSameWeight()
    {
        $user = $this->makeUser();
        $post = $this->makePost();

        // First vote
        $user->vote($post, 1);

        // Second vote
        $user->vote($post, 1);

        $this->assertEquals($post->score(), 1);
    }

    public function testUpdateVoteWithOppositeWeight()
    {
        $user = $this->makeUser();
        $post = $this->makePost();

        // Upvote
        $user->vote($post, 1);

        // Then downvote
        $user->vote($post, -1);

        $this->assertEquals($post->score(), -1);
    }

    public function testCancelVoteWithNoPreviousVote()
    {
        $user = $this->makeUser();
        $post = $this->makePost();

        $user->vote($post, 0);

        $this->assertEquals($post->score(), 0);
    }

    public function testVoterToVotesRelationship()
    {
        $user = $this->makeUser();
        $post = $this->makePost();

        $user->vote($post, 1);

        $this->assertEquals($user->votes->count(), 1);
    }

    public function testVoteableToVotesRelationship()
    {
        $user = $this->makeUser();
        $post = $this->makePost();

        $user->vote($post, 1);

        $this->assertEquals($post->votes->count(), 1);
    }
}

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

    public function testPositiveCustomVoteWeight()
    {
        $user = $this->makeUser();
        $post = $this->makePost();

        $vote = $user->vote($post, 20);

        $this->assertEquals($post->score(), 20);
        $this->assertInstanceOf(Vote::class, $vote);
        $this->assertEquals($vote->weight, 20);
    }

    public function testNegativeCustomVoteWeight()
    {
        $user = $this->makeUser();
        $post = $this->makePost();

        $vote = $user->vote($post, -20);

        $this->assertEquals($post->score(), -20);
        $this->assertInstanceOf(Vote::class, $vote);
        $this->assertEquals($vote->weight, -20);
    }

    public function testUpVoteFromVoter()
    {
        $user = $this->makeUser();
        $post = $this->makePost();

        $vote = $user->upVote($post);

        $this->assertEquals($post->score(), 1);
        $this->assertInstanceOf(Vote::class, $vote);
        $this->assertEquals($vote->weight, 1);
    }

    public function testDownVoteFromVoter()
    {
        $user = $this->makeUser();
        $post = $this->makePost();

        $vote = $user->downVote($post);

        $this->assertEquals($post->score(), -1);
        $this->assertInstanceOf(Vote::class, $vote);
        $this->assertEquals($vote->weight, -1);
    }

    public function testCancelVoteFromVoter()
    {
        $user = $this->makeUser();
        $post = $this->makePost();

        // First upvote post
        $user->upVote($post);

        // Then cancel vote
        $vote = $user->cancelVote($post);

        $this->assertEquals($post->score(), 0);
        $this->assertNull($vote);
    }

    public function testGetVoteFromVoter()
    {
        $user = $this->makeUser();
        $post = $this->makePost();

        // First upvote post
        $user->upVote($post);

        // Then get vote
        $vote = $user->getVote($post);

        $this->assertInstanceOf(Vote::class, $vote);
        $this->assertEquals($vote->weight, 1);
    }

    public function testUpVoteFromVoteable()
    {
        $user = $this->makeUser();
        $post = $this->makePost();

        $vote = $post->upVoteBy($user);

        $this->assertEquals($post->score(), 1);
        $this->assertInstanceOf(Vote::class, $vote);
        $this->assertEquals($vote->weight, 1);
    }

    public function testDownVoteFromVoteable()
    {
        $user = $this->makeUser();
        $post = $this->makePost();

        $vote = $post->downVoteBy($user);

        $this->assertEquals($post->score(), -1);
        $this->assertInstanceOf(Vote::class, $vote);
        $this->assertEquals($vote->weight, -1);
    }

    public function testCancelVoteFromVoteable()
    {
        $user = $this->makeUser();
        $post = $this->makePost();

        // First upvote post
        $post->upVoteBy($user);

        // Then cancel vote
        $vote = $post->cancelVoteBy($user);

        $this->assertEquals($post->score(), 0);
        $this->assertNull($vote);
    }

    public function testGetVoteFromVoteable()
    {
        $user = $this->makeUser();
        $post = $this->makePost();

        // First upvote post
        $post->upVoteBy($user);

        // Then get vote
        $vote = $post->getVoteBy($user);

        $this->assertInstanceOf(Vote::class, $vote);
        $this->assertEquals($vote->weight, 1);
    }

    public function testUpdateVoteWithSameWeight()
    {
        $user = $this->makeUser();
        $post = $this->makePost();

        // First vote
        $user->vote($post, 1);

        // Second vote
        $vote = $user->vote($post, 1);

        $this->assertEquals($post->score(), 1);
        $this->assertInstanceOf(Vote::class, $vote);
        $this->assertEquals($vote->weight, 1);
    }

    public function testUpdateVoteWithOppositeWeight()
    {
        $user = $this->makeUser();
        $post = $this->makePost();

        // Upvote
        $user->vote($post, 1);

        // Then downvote
        $vote = $user->vote($post, -1);

        $this->assertEquals($post->score(), -1);
        $this->assertInstanceOf(Vote::class, $vote);
        $this->assertEquals($vote->weight, -1);
    }

    public function testCancelVoteWithoutPreviousVote()
    {
        $user = $this->makeUser();
        $post = $this->makePost();

        $vote = $user->vote($post, 0);

        $this->assertEquals($post->score(), 0);
        $this->assertNull($vote);
    }

    public function testVoterToVotesRelationship()
    {
        $user = $this->makeUser();
        $post = $this->makePost();

        $vote = $user->vote($post, 1);

        $this->assertEquals($user->votes->count(), 1);
        $this->assertInstanceOf(Vote::class, $vote);
        $this->assertEquals($vote->weight, 1);
    }

    public function testVoteableToVotesRelationship()
    {
        $user = $this->makeUser();
        $post = $this->makePost();

        $vote = $user->vote($post, 1);

        $this->assertEquals($post->votes->count(), 1);
        $this->assertInstanceOf(Vote::class, $vote);
        $this->assertEquals($vote->weight, 1);
    }
}

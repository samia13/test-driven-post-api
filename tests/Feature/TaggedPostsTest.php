<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Tests\TestCase;
use App\Models\{Tag, User, Post};

class TaggedPostsTest extends TestCase
{
    use WithoutMiddleware, RefreshDatabase;

    private $taggedPosts;
    private $tags;
    private $user;

    public function setUp():void
    {
        parent::setUp();
        $tags =  Tag::factory(5)->create();
        $this->tags = $tags;
        $this->user = User::factory()->create()->first();

        $this->taggedPosts = Post::factory(5)->create([
            'user_id' => $this->user->id
        ])->each(function($post) use ($tags){
            // attach 3 random tags to each post
            $post->tags()->attach($tags->random(3));
        });
    }

    public function tearDown():void
    {
        parent::tearDown();
        unset($this->user);
        unset($this->tags);
        unset($this->taggedPosts);
    }

    public function test_can_get_all_posts_with_associated_tags()
    {
        $response = $this->getJson(route('posts.index'));

        $response->assertStatus(200)
        ->assertJsonCount(5);

        $this->assertEquals(3, count($response->json()[0]['tags']));
    }

    public function test_can_update_post_tags(){
        // Grab a post, in this case the first one 
        $post = $this->taggedPosts->first();

        // Update its title
        $tags = $this->tags->random(1)->pluck('id');

        // When the user Hit the endpoint to update $post
        $response = $this->putJson(route('posts.update',$post->id),['tags' => $tags]);

        // It should have one tag associated instead of 3
        $this->assertEquals(1, count($response->json()['tags']));
    }

}

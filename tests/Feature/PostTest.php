<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\{Post, User};
use Laravel\Passport\Passport;
use Illuminate\Support\Facades\Artisan;

class PostTest extends TestCase
{
    use RefreshDatabase;

    private $posts;
    private $user;

    public function setUp():void
    {
        parent::setUp();
        $this->user = User::factory()->create()->first();
        $this->posts = Post::factory()->count(5)->create(['user_id' => $this->user->id]);
        // dd($this->user, $this->posts);
    }

    public function tearDown():void
    {
        parent::tearDown();
        unset($this->user);
        unset($this->posts);
    }

    public function test_can_get_all_posts()
    {
        $response = $this->getJson(route('posts.index'));
        $response->assertStatus(200)
        ->assertJsonCount(5);
    }

    public function test_can_get_single_post()
    {
        $response = $this->getJson(route('posts.show', $this->posts->first()->id));
        $response->assertStatus(200);

        $this->assertEquals($this->posts->first()->title, $response->json()['title']);
    }
    public function getAuthenticatedToken(){
        Artisan::call('passport:install');
        Passport::actingAs(
            $this->user
        );
        return $this->user->createToken('passportToken')->accessToken;
    }

    public function test_authenticated_user_can_create_a_post(){
        

        $headers = [ 
            'Accept' => 'application/json',
            'Authorization' => 'Bearer '.$this->getAuthenticatedToken(),
        ];
    
        $response = $this->json('POST', route('posts.store'), [
            'title' => 'new post created via test',
            'excerpt' => 'post excerpt',
            'body' => 'post body',
            'user_id' => $this->user->id,
        ], $headers);
    
        $response->assertStatus(200);
    }

    public function test_should_throw_exception_model_not_found(){
        $response = $this->getJson(route('posts.show', 6));
        $response->assertStatus(404);
    }
}

<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use Illuminate\Support\Facades\Artisan;

class UserTest extends TestCase
{
    use RefreshDatabase;
    private $data;

    public function setUp():void
    {
        parent::setUp();
        $this->data = [
            'name' => 'samy code',
            'email'=> 'dummy@gmail.com',
            'password' => 'password'
        ];

    }

    public function tearDown():void
    {
        parent::tearDown();
        unset($this->data);
    }

    public function test_user_can_register(){
        // Hit regiter route with new user's data 
        $response = $this->postJson(route('register'),$this->data);

        // Check if 'samy code' was added to DB
        $this->assertDatabaseHas('users',['name' => 'samy code']);

        // And that the response json has a token key 
        $response->assertJsonStructure(['token']);
    }

    public function test_user_can_login(){
        // Hit regiter route with new user's data 
        $this->postJson(route('register'),$this->data);

        // Attempt to login
        $response = $this->postJson(route('login'),[
            'email'=> $this->data['email'],
            'password' => $this->data['password']
        ]);
        
        // The response json should have a token key 
        $response->assertJsonStructure(['token']);
        
    }
    
}

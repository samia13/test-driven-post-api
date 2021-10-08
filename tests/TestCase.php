<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Laravel\Passport\Passport;
use Illuminate\Support\Facades\Artisan;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;
    public function setUp():void
    {
        parent::setUp();
        Artisan::call('passport:install');
        // $this->withoutExceptionHandling();
 
    }
    
    // Authenticate the user and get headers
    public function getHeaders($user){
        Passport::actingAs(
            $user
        );
        $token = $user->createToken('passportToken')->accessToken;
        return [ 
            'Accept' => 'application/json',
            'Authorization' => 'Bearer '.$token,
        ];

    }
}

<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class RegistrationTest extends TestCase
{
    use RefreshDatabase;
    /**
     * testing for user registration
     *
     * @test
     * @return void
     */
    public function ItCreateUserWhenRegistrationRouteIsCalled():void
    {
        $data=['user_name'=>'John Doe','company_name'=>'Doe & Sons','email'=>'doe@gmail.com','password'=>'password'];
        $response = $this->post('/api/companies/register', $data);

        $response->assertStatus(200);

        $response->assertJson(["message"=> "Company Created"]);
    }

    /**
     * testing for api key fetch
     *
     * @test
     * @return void
     */
    public function ItFetchesAPIKeyWithEmail():void
    {
        $data=['user_name'=>'John Doe','company_name'=>'Doe & Sons','email'=>'doe@gmail.com','password'=>'password'];
        $response = $this->post('/api/companies/register', $data);

        $user=User::find(1);
        $response = $this->actingAs($user, 'api')->post('/api/companies/get/apikey', ['email'=>'doe@gmail.com']);

        $response->assertStatus(200);

        $response->assertJson(["message"=>"ApiKey Fetched"]);
    }
}

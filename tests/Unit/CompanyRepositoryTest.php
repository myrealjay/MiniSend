<?php

namespace Tests\Feature;

use App\Repositories\Company\CompanyRepository;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class CompanyRepositoryTest extends TestCase
{
    use RefreshDatabase;
    /**
     * Test that a user and company is created
     * @test
     * @return void
     */
    public function ItCreatesUserAndCompanyWhenDatatIsPassed():void
    {
        $repo= new CompanyRepository();
        $data=['user_name'=>'John Doe','company_name'=>'Doe & Sons','email'=>'doe@gmail.com','password'=>'password'];
        $repo->create($data);
        $this->assertDatabaseHas('users', ['name'=>'John Doe']);
        $this->assertDatabaseHas('companies', ['name'=>'Doe & Sons']);
    }

    /**
     * testing for api key creation
     *
     * @test
     * @return void
     */
    public function ItCreatesAPIKeyWhenUserIsRegistered():void
    {
        $repo= new CompanyRepository();
        $data=['user_name'=>'John Doe','company_name'=>'Doe & Sons','email'=>'doe@gmail.com','password'=>'password'];
        $company=$repo->create($data);
        $this->assertTrue($company->api_key !=null);
    }

    /**
     * testing for api key fetch
     *
     * @test
     * @return void
     */
    public function ItFindsAPIKeyByEmail():void
    {
        $repo= new CompanyRepository();
        $data=['user_name'=>'John Doe','company_name'=>'Doe & Sons','email'=>'doe@gmail.com','password'=>'password'];
        $repo->create($data);

        $key=$repo->getApiKey('doe@gmail.com');
        $this->assertTrue(($key!=null));
        $this->assertTrue(substr($key, 0, 8)==='mini_prk');
    }

    /**
     * testing for finding comppany by key
     *
     * @test
     * @return void
     */
    public function ItFindClientByApiKey():void
    {
        $repo= new CompanyRepository();
        $data=['user_name'=>'John Doe','company_name'=>'Doe & Sons','email'=>'doe@gmail.com','password'=>'password'];
        $repo->create($data);

        $key=$repo->getApiKey('doe@gmail.com');

        $company=$repo->getCompanyByKey($key);
        $this->assertTrue($company->name==='Doe & Sons');
    }
}

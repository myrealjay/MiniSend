<?php

namespace Tests\Feature;

use App\Models\Company;
use App\Models\CompanyEmail;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class CompanyMailTest extends TestCase
{
    use RefreshDatabase;
    /**
     * Testing for mail sending
     *
     * @test
     * @return void
     */
    public function ItSendsEmailCorrectly()
    {
        $data=['user_name'=>'John Doe','company_name'=>'Doe & Sons','email'=>'doe@gmail.com','password'=>'password'];
        $response = $this->post('/api/companies/register', $data);

        $user=User::find(1);
        $response = $this->actingAs($user, 'api')->post('/api/companies/get/apikey', ['email'=>'doe@gmail.com']);

        $company=Company::find(1);
        $key=$company->api_key;

        Storage::fake();
        $images = [
                    UploadedFile::fake()->image('uploaded_image.jpg', 4096, 4096),
                    UploadedFile::fake()->image('uploaded_image_two.jpg', 4096, 4096)
                  ];
        $data=[
            'from_email'=>'johndoe@gmail.com',
            'from_name'=>'Ken Ben',
            'to_email'=>'keneth@gmail.com',
            'to_name'=>'Mark Angel',
            'subject'=>'This is Test',
            'text_content'=>'Never Mind',
            'html_content'=>'<h1>I love this</h1>',
            'attachments'=>$images
        ];

        

        $response=$this->actingAs($user, 'api')->post('/api/mails/send', $data, ['minisend-key'=>$key]);
     
        $response->assertStatus(200);

        $response->assertJson(['message'=>'Email Sent Successfully']);

        $this->assertDatabaseHas('company_emails', ['to'=>json_encode(['name'=>'Mark Angel','email'=>'keneth@gmail.com'])]);
    }

    /**
     * testing for fetch all email route
     *
     * @test
     * @return void
     */
    public function ItFetchesPaginatedEmails():void
    {
        $data=['user_name'=>'John Doe','company_name'=>'Doe & Sons','email'=>'doe@gmail.com','password'=>'password'];
        $response = $this->post('/api/companies/register', $data);

        $user=User::find(1);

        $response = $this->actingAs($user, 'api')->post('/api/companies/get/apikey', ['email'=>'doe@gmail.com']);

        $company=Company::find(1);
        $key=$company->api_key;

        Storage::fake();
        $images = [
                    UploadedFile::fake()->image('uploaded_image.jpg', 4096, 4096),
                    UploadedFile::fake()->image('uploaded_image_two.jpg', 4096, 4096)
                  ];
        $data=[
            'from_email'=>'johndoe@gmail.com',
            'from_name'=>'Ken Ben',
            'to_email'=>'keneth@gmail.com',
            'to_name'=>'Mark Angel',
            'subject'=>'This is Test',
            'text_content'=>'Never Mind',
            'html_content'=>'<h1>I love this</h1>',
            'attachments'=>$images
        ];

        
        $response=$this->actingAs($user, 'api')->post('/api/mails/send', $data, ['minisend-key'=>$key]);
     
        $response->assertStatus(200);

        $response->assertJson(['message'=>'Email Sent Successfully']);

        $this->assertDatabaseHas('company_emails', ['to'=>json_encode(['name'=>'Mark Angel','email'=>'keneth@gmail.com'])]);

        $data=[
            "sender"=>"",
            "receiver"=>['email'=>'keneth@gmail.com','name'=>'Mark Angel'],
            "length"=>10,
            "subject"=>"",
            "status"=>"sent"
        ];

        $response=$this->actingAs($user, 'api')->post('/api/mails/get-all', $data, ['minisend-key'=>$key]);
        $response->assertStatus(200);
        $response->assertJson(["message"=>"Emails fetched Successfully"]);
    }

    /**
     * testing recipient email fetch
     *
     * @test
     * @return void
     */
    public function ItFecthesEmailsOfARecipient():void
    {
        $data=['user_name'=>'John Doe','company_name'=>'Doe & Sons','email'=>'doe@gmail.com','password'=>'password'];
        $response = $this->post('/api/companies/register', $data);

        $user=User::find(1);
        $response = $this->actingAs($user, 'api')->post('/api/companies/get/apikey', ['email'=>'doe@gmail.com']);

        $company=Company::find(1);
        $key=$company->api_key;

        Storage::fake();
        $images = [
                    UploadedFile::fake()->image('uploaded_image.jpg', 4096, 4096),
                    UploadedFile::fake()->image('uploaded_image_two.jpg', 4096, 4096)
                  ];
        $data=[
            'from_email'=>'johndoe@gmail.com',
            'from_name'=>'Ken Ben',
            'to_email'=>'keneth@gmail.com',
            'to_name'=>'Mark Angel',
            'subject'=>'This is Test',
            'text_content'=>'Never Mind',
            'html_content'=>'<h1>I love this</h1>',
            'attachments'=>$images
        ];

        $data2=[
            'from_email'=>'johndoe@gmail.com',
            'from_name'=>'Ken Ben',
            'to_email'=>'mark@gmail.com',
            'to_name'=>'Mark Angel',
            'subject'=>'This is Test',
            'text_content'=>'Never Mind',
            'html_content'=>'<h1>I love this</h1>',
            'attachments'=>$images
        ];

        $response=$this->actingAs($user, 'api')->post('/api/mails/send', $data, ['minisend-key'=>$key]);
     
        $response->assertStatus(200);

        $response->assertJson(['message'=>'Email Sent Successfully']);

        $response=$this->actingAs($user, 'api')->post('/api/mails/send', $data2, ['minisend-key'=>$key]);
     
        $response->assertStatus(200);


        $data=[
            "recipient"=>['name'=>'Mark Angel','email'=>'keneth@gmail.com'],
            "length"=>10,
        ];

        $response=$this->actingAs($user, 'api')->post('/api/mails/get-recipient', $data, ['minisend-key'=>$key]);
        $response->assertStatus(200);
        $response->assertJson(["message"=>"Emails fetched Successfully"]);
        $this->assertTrue(count($response->json()['data']['data'])==1);
    }

    /**
     * test it fetches a single mail
     *
     * @test
     * @return void
     */
    public function ItGetsSingleEmail()
    {
        $data=['user_name'=>'John Doe','company_name'=>'Doe & Sons','email'=>'doe@gmail.com','password'=>'password'];
        $response = $this->post('/api/companies/register', $data);

        $user=User::find(1);

        $response = $this->actingAs($user, 'api')->post('/api/companies/get/apikey', ['email'=>'doe@gmail.com']);

        $company=Company::find(1);
        $key=$company->api_key;

        Storage::fake();
        $images = [
                    UploadedFile::fake()->image('uploaded_image.jpg', 4096, 4096),
                    UploadedFile::fake()->image('uploaded_image_two.jpg', 4096, 4096)
                  ];
        $data=[
            'from_email'=>'johndoe@gmail.com',
            'from_name'=>'Ken Ben',
            'to_email'=>'keneth@gmail.com',
            'to_name'=>'Mark Angel',
            'subject'=>'This is Test',
            'text_content'=>'Never Mind',
            'html_content'=>'<h1>I love this</h1>',
            'attachments'=>$images
        ];

        $data2=[
            'from_email'=>'johndoe@gmail.com',
            'from_name'=>'Ken Ben',
            'to_email'=>'mark@gmail.com',
            'to_name'=>'Mark Angel',
            'subject'=>'This is Test',
            'text_content'=>'Never Mind',
            'html_content'=>'<h1>I love this</h1>',
            'attachments'=>$images
        ];


        $response=$this->actingAs($user, 'api')->post('/api/mails/send', $data, ['minisend-key'=>$key]);
     
        $response->assertStatus(200);

        $response->assertJson(['message'=>'Email Sent Successfully']);

        $response=$this->actingAs($user, 'api')->post('/api/mails/send', $data2, ['minisend-key'=>$key]);
     
        $response->assertStatus(200);

        $email=CompanyEmail::find(1);

        $response=$this->actingAs($user, 'api')->get("/api/mails/get-single/{$email->id}", ['minisend-key'=>$key]);
        $response->assertStatus(200);
        $response->assertJson(["message"=>"Email fetched Successfully"]);
    }

    /**
     * testing that API key is required
     *
     * @test
     * @return void
     */
    public function ItRequestApiKeyy()
    {
        $data=['user_name'=>'John Doe','company_name'=>'Doe & Sons','email'=>'doe@gmail.com','password'=>'password'];
        $response = $this->post('/api/companies/register', $data);

        $user=User::find(1);

        $response = $this->actingAs($user, 'api')->post('/api/companies/get/apikey', ['email'=>'doe@gmail.com']);

        $company=Company::find(1);
        $key=$company->api_key;

        Storage::fake();
        $images = [
                    UploadedFile::fake()->image('uploaded_image.jpg', 4096, 4096),
                    UploadedFile::fake()->image('uploaded_image_two.jpg', 4096, 4096)
                  ];
        $data=[
            'from_email'=>'johndoe@gmail.com',
            'from_name'=>'Ken Ben',
            'to_email'=>'keneth@gmail.com',
            'to_name'=>'Mark Angel',
            'subject'=>'This is Test',
            'text_content'=>'Never Mind',
            'html_content'=>'<h1>I love this</h1>',
            'attachments'=>$images
        ];

        $data2=[
            'from_email'=>'johndoe@gmail.com',
            'from_name'=>'Ken Ben',
            'to_email'=>'mark@gmail.com',
            'to_name'=>'Mark Angel',
            'subject'=>'This is Test',
            'text_content'=>'Never Mind',
            'html_content'=>'<h1>I love this</h1>',
            'attachments'=>$images
        ];


        $response=$this->actingAs($user, 'api')->post('/api/mails/send', $data, ['minisend-key'=>$key]);
     
        $response->assertStatus(200);

        $response->assertJson(['message'=>'Email Sent Successfully']);

        $response=$this->actingAs($user, 'api')->post('/api/mails/send', $data2, ['minisend-key'=>$key]);
     
        $response->assertStatus(200);

        $email=CompanyEmail::find(1);

        $response=$this->actingAs($user, 'api')->get("/api/mails/get-single/{$email->id}");
        $response->assertStatus(401);
    }
}

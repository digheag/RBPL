<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;

class AuthTest extends TestCase
{
    use RefreshDatabase, WithFaker;
    protected function setUp():void{
        parent::setUp();
        $this->withoutVite();
        
    }

    public function test_register_page_can_be_access():void{
        $response = $this->get('/register');
        $response->assertOk();
        $response->assertViewIs('register');
    }

    public function test_login_page_can_be_access(): void
    {
        $response = $this->get(route('login'));
        $response->assertOk();
        $response->assertViewIs('login');
    }

    public function test_register_page_cannot_be_access():void{
        $response = $this->get('/loginn');
        $response->assertNotFound();
    }

    public function test_login_page_cannot_be_access():void{
        $response = $this->get('/registered');
        $response->assertNotFound();
    }

    public function test_admin_can_login():void{
        $user = User::factory()->create([
            'role' => 'admin',
            'password' => bcrypt('admin'),
        ]);

        $response = $this->post('/admin/login',[
            'username' => $user->username,
            'password' => 'admin',
        ]); 

        $this->assertAuthenticated();
        $response->assertRedirect('admin/home');
    }

    public function test_admin_cannot_login():void{
        $user = User::factory()->create([
            'role' => 'admin',
            'password' => bcrypt('admin'),
        ]);

        $response = $this->post('/admin/login',[
            'username' => $user->username,
            'password' => 'salah',
        ]); 
        
         $response = $this->post('/admin/login',[
            'username' => 'tidakada',
            'password' => '123456',
        ]); 

        $this->assertGuest();
        $response->assertRedirect('/admin/login'); 
    }

    public function test_user_can_login():void{
        $user = User::factory()->create([
            'role' => 'users',
            'password' => bcrypt('123456'),
        ]);

        $response = $this->post(route('login'),[
            'username' => $user->username,
            'password' => '123456',
        ]); 

        $this->assertAuthenticated();
        $response->assertRedirect(route('users.property'));
    }

    public function test_user_wrong_password_cannot_login(){
        $user = User::factory()->create([
            'role' => 'users',
            'password' => bcrypt('123456'),
        ]);

        $response = $this->post(route('login'),[
            'username' => $user->username,
            'password' => 'salah',
        ]); 
        $this->assertGuest();
        $response->assertRedirect(route('login')); 
    }

    public function test_user_wrong_username_cannot_login(){
        $user = User::factory()->create([
            'role' => 'users',
            'password' => bcrypt('123456'),
        ]);

        $response = $this->post(route('login'),[
            'username' => 'tidakada',
            'password' => '123456',
        ]); 
        $this->assertGuest();
        $response->assertRedirect(route('login'));
    }

    public function test_user_can_register():void{
        $data = [
            'fullname' => $this->faker->name(),
            'email' => $this->faker->unique()->safeEmail(),
            'username' => $this->faker->unique()->regexify('[A-Za-z0-9]{8}'),
            'telp_number' => $this->faker->phoneNumber(),
            'profile' => 'default.png',
            'role' => 'users',
            'password' => 'password',
            'remember_token' => '0000000000',
        ];

        $response = $this->post('/register', $data);
        //cek masuk ke database atau gak
        $this->assertDatabaseHas('users', [
            'email' => $data['email']
        ]);
        $response->assertSessionDoesntHaveErrors(); //validasi 
        $response->assertRedirect(route('login'));
    }

    public function test_user_cannot_register(){
        $user = User::factory()->create([
            'username' => 'userbaru'
        ]);

        $response = $this->post('/register', [
            'fullname' => 'Test',
            'email' => 'test@mail.com',
            'username' => 'userbaru',
            'telp_number' => '08123456789',
            'profile' => 'default.png',
            'role' => 'users',
            'password' => 'password',
        ]);

        $this->assertDatabaseCount('users', 1);
        $response->assertSessionHasErrors('username'); //register gagal
    }

    public function test_agent_can_login(){
         $user = User::factory()->create([
            'role' => 'agent',
            'password' => bcrypt('123456'),
        ]);

        $response = $this->post(route('login'),[
            'username' => $user->username,
            'password' => '123456',
        ]); 

        $this->assertAuthenticated();
        $response->assertRedirect('/agent/appointment');
    }

    public function test_agent_wrong_password_cannot_login():void{
        $user = User::factory()->create([
            'role' => 'agent',
            'password' => bcrypt('123456'),
        ]);

        $response = $this->post(route('login'),[
            'username' => $user->username,
            'password' => 'salah',
        ]); 
        $this->assertGuest();
        $response->assertRedirect(route('login')); 
    }

    public function test_agent_wrong_username_cannot_login():void{
        $user = User::factory()->create([
            'role' => 'agent',
            'password' => bcrypt('123456'),
        ]);

        $response = $this->post(route('login'),[
            'username' => 'tidakada',
            'password' => '123456',
        ]); 
        $this->assertGuest();
        $response->assertRedirect(route('login'));
    }

}

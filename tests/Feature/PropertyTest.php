<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Auth;
use Tests\TestCase;
use App\Models\User;
use App\Models\Appoinment;
use App\Models\Property;

class PropertyTest extends TestCase
{
     use RefreshDatabase;
     protected $agent, $user;
    /**
     * test_guest_cannot_access_property
     * test_user_can_view_properties
     * test_user_cannot_create_property
     * test_agent_can_create_property
     * test_create_property_validation_error
     * test_agent_can_update_property
     * test_agent_can_delete_property
     */

    protected function setUp():void{
        parent::setUp();
        $this->agent = User::factory()->create([
            'role' => 'agent',
        ]);
        $this->actingAs($this->agent);

        $this->withoutVite();
    }

    public function test_guest_cannot_access_agent_property(): void
    {
       Auth::logout();
       $response = $this->get(route('agent.property'));
       $response->assertRedirect(route("login"));
    }

    public function test_guest_cannot_access_users_property(): void
    {
       Auth::logout();
       $response = $this->get(route('users.property'));
       $response->assertRedirect(route("login"));
    }

    public function test_user_can_view_property_page(){
        $this->user = User::factory()->create([
            'role' => 'users',
        ]);
        $this->actingAs($this->user);

        $response = $this->get(route('users.property'));
        $response->assertOk();
        $response->assertViewIs('users.property');
        $response->assertViewHas('properties');
    }

    public function test_user_cannot_view_property_page(){
        $this->user = User::factory()->create([
            'role' => 'users',
        ]);
        $this->actingAs($this->user);

        $response = $this->get('users/propert');
        $response->assertNotFound();
    }

    public function test_agent_can_view_property_page(){
        $response = $this->get(route('agent.property'));
        $response->assertOk();
        $response->assertViewIs('agent.property');
        $response->assertViewHas('properties');
    }

    public function test_agent_cannot_view_property_page(){
        $response = $this->get('agent/propert');
        $response->assertNotFound();
    }


    public function test_agent_can_create_property()
    {
        $appointment = Appoinment::factory()->create();

        $response = $this->post(
            route('agent.propertyStore', ['id' => $appointment->id]),[
                'propertyName' => 'Modern Building House',
                'propertyAddress' => 'BlokM',
                'propertyPrice' => 500000000,
                'propertyArea' => 710,
                'sold' => null,
                'description' => null,
                'images' => [
                    UploadedFile::fake()->create('test.jpg', 100),
                ],
            ]);
        
        $this->assertDatabaseHas('properties', [
            'appoinment_id' => $appointment->id,
        ]);
    }

    public function test_create_property_validation_error()
    {
        $appointment = Appoinment::factory()->create();

        $response = $this->post(
            route('agent.propertyStore', ['id' => $appointment->id]),
            [
            
                'propertyName' => '',
                'propertyAddress' => '',
                'propertyPrice' => '',
                'propertyArea' => '',
                'images' => [], 
            ]
        );

        $response->assertSessionHasErrors([
            'propertyName',
            'propertyAddress',
            'propertyPrice',
            'propertyArea',
            'images',
        ]);
    }

    public function test_agent_can_update_property(){
        Storage::fake('public');
        $appointment = Appoinment::factory()->create();

        //data property lama
        $property = Property::factory()->create([
        'appoinment_id' => $appointment->id,
        'name' => 'Old Name',
        ]);

        $response = $this->put(route("agent.propertyUpdate", ['id' => $appointment->id]), [
            'propertyName' => 'New Building House',
            'propertyAddress' => 'Jakarta',
            'propertyPrice' => 900000000,
            'propertyArea' => 1000,
            'sold' => null,
            'description' => 'Updated',

            'images' => [
                UploadedFile::fake()->create('new.jpg', 100),
            ],
        ]);
        $response->assertStatus(302);
        $this->assertDatabaseHas('properties', [
            'id' => $property->id,
            'name' => 'New Building House',
            'address' => 'Jakarta',
        ]);
    }

    public function test_agent_can_delete_property(){
        $appointment = Appoinment::factory()->create();
        $property = Property::factory()->create([
            'appoinment_id' => $appointment->id,
        ]);
        $response = $this->delete(route('agent.propertyDelete', ['id' => $property->id]));
        $response->assertStatus(302);
        $this->assertDatabaseMissing('properties', [
            'id' => $property->id,
        ]);
    }

    
}

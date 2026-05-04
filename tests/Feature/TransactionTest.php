<?php

namespace Tests\Feature;

use App\Models\Appoinment;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use App\Models\transaction;
use App\Models\Property;

class TransactionTest extends TestCase
{
    use RefreshDatabase;
    protected $user;
    protected function setUp():void{
        parent::setUp();
        $this->user = User::factory()->create([
            'role' => 'users',
        ]);
        $this->actingAs($this->user);
        $this->withoutVite();
    }

    public function test_user_can_access_transaction_list(): void
    {
        $response = $this->get(route('users.transaction'));
        $response->assertOk();
        $response->assertViewIs('users.transaction');
        $response->assertViewHas('transactions');
    }

    public function test_user_cannot_access_transaction_list():void{
        Auth::logout();
        $response = $this->get(route('users.transaction'));
        $response->assertRedirect(route("login"));
    }

    public function test_user_cannot_access_other_users_transactions():void{
    $appoinmentId = Appoinment::factory()->create();    
    $property = Property::factory()->create([
        'appoinment_id' => $appoinmentId->id,
    ]);
    $otherUser = User::factory()->create();
        $transaction = transaction::factory()->create([
            'property_id' => $property->id,
            'buyer_id' => $otherUser->id
        ]);
        $response = $this->get(route('users.detailTransaction', $transaction->id));
        $response->assertStatus(403);
    }

    public function test_user_can_see_method_transaction_after_choose_property():void{
    $appoinmentId = Appoinment::factory()->create();        
    $property = Property::factory()->create([
        'appoinment_id' => $appoinmentId->id,
    ]);
    $response = $this->withSession([
        'propertyId' => 1
    ])->get(route('users.transactionMethod'));

        $response = $this->get(route('users.transactionMethod', $property->id));
        $response->assertStatus(200);
        $response->assertSee('Pilih Metode');
    }

    public function test_user_must_select_property_before_creating_transaction():void{
        $response = $this->post(route('users.DirectStore'), [
                'amount' => 100000,
                'method' => 'direct'
        ]);
        $response->assertSessionHas('error');
    }

    public function test_user_can_create_property(): void
{
    $user = User::factory()->create([
        'role' => 'users'
    ]);

    $agent = User::factory()->create([
        'role' => 'agent'
    ]);

    $appoinment = Appoinment::factory()->create([
        'seller_id' => $user->id, // 🔥 penting
    ]);

    $property = Property::factory()->create([
        'appoinment_id' => $appoinment->id,
        'price' => 150000,
    ]);

//     dd([
//     'users' => \App\Models\User::all()->pluck('id'),
//     'agentId' => $agent->id,
//     'buyerId' => $user->id,
//     'sellerId' => $appoinment->seller_id,
//     'propertyId' => $property->id,
// ]);

    $response = $this->actingAs($user)
        ->withSession([
            'agentId' => $agent->id,
            'propertyId' => $property->id,
        ])
        ->post(route('users.DirectStore'));

    $response->assertRedirect(route('users.transaction'));

    $this->assertDatabaseHas('transactions', [
        'property_id' => $property->id,
        'transaction_type' => 'direct'
    ]);
}
}

<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

use App\Models\User;
use App\Models\Negotiation;
use App\Models\Property;
use App\Models\Agent;
use App\Models\Appoinment;

class NegotiationTest extends TestCase
{
    use RefreshDatabase;
    protected $user;
    
    protected function setUp(): void
    {
        parent::setUp();
        $this->withoutVite();

        $this->user = User::factory()->create([
            'role' => 'users',
        ]);
        $this->actingAs($this->user);
    }

    public function test_user_can_access_page_negotiation_list(): void
    {
        $response = $this->get(route('users.negotiation'));
        $response->assertStatus(200);
        $response->assertViewIs('users.negotiation');
        $response->assertViewHas('negotiations');
    }

    public function test_user_cannot_access_page_negotiation_list(): void
    {
        Auth::logout();
        $response = $this->get(route('users.negotiation'));
        $response->assertRedirect(route('login'));
    }

    public function test_user_can_access_page_negotiation_detail(): void
    {
        $seller = User::factory()->create();
        $buyer = User::factory()->create();
        $agentUser = User::factory()->create();

        $agent = Agent::factory()->create([
            'user_id' => $agentUser->id,
        ]);

        $appoinmentId = Appoinment::factory()->create();    
        $property = Property::factory()->create([
            'appoinment_id' => $appoinmentId->id,
        ]);

        $negotiation = Negotiation::factory()->create([
            'seller_id' => $seller->id,
            'buyer_id' => $buyer->id,
            'agent_id' => $agent->id,
            'property_id' => $property->id,
        ]);

        $response = $this->get(route('negotiation.detail', $negotiation->id));
        $response->assertStatus(200);
        $response->assertViewIs('users.negotiationDetail');
        $response->assertViewHas('negotiationId');
    }

    public function test_user_cannot_access_page_negotiation_detail(): void
    {
        Auth::logout();
        $response = $this->get(route('negotiation.detail', 1));
        $response->assertRedirect(route('login'));
    }

    public function test_buyer_can_create_negotiation(): void{
        $seller = User::factory()->create();
        $agentUser = User::factory()->create();

        $agent = Agent::factory()->create([
            'user_id' => $agentUser->id,
        ]);

        $appoinmentId = Appoinment::factory()->create([
            'seller_id' => $seller->id,
        ]);
   
            $property = Property::factory()->create([
                'appoinment_id' => $appoinmentId->id,
            ]);

            $response = $this->withSession([
                'propertyId' => $property->id,
                'agentId' => $agent->id,
            ])->post(route('users.negotiationStore'), [
                'negotiation_price' => 90000000,
                'negotiation_message' => 'Harga terlalu tinggi',
            ]);

            $response->assertStatus(302);

            $this->assertDatabaseHas('negotiations', [
                'property_id' => $property->id,
                'buyer_id' => $this->user->id,
                'seller_id' => $seller->id,
                'agent_id' => $agent->id,
            ]);
        }

        public function test_buyer_cannot_create_negotiation_with_invalid_session(): void{
            $response = $this->post(route('users.negotiationStore'), [
                'negotiation_price' => 90000000,
                'negotiation_message' => 'Harga terlalu tinggi',
            ]);

            $response->assertStatus(302);
            $response->assertRedirect(route('users.property'));
        }

        public function test_agent_can_approve_negotiation():void{
            Auth::logout();
            $seller = User::factory()->create();
            $buyer = User::factory()->create();
            $agentUser = User::factory()->create();

            $agent = Agent::factory()->create([
                'user_id' => $agentUser->id,
            ]);

            $appoinmentId = Appoinment::factory()->create();    
            $property = Property::factory()->create([
                'appoinment_id' => $appoinmentId->id,
            ]);

            $negotiation = Negotiation::factory()->create([
                'seller_id' => $seller->id,
                'buyer_id' => $buyer->id,
                'agent_id' => $agent->id,
                'property_id' => $property->id,
            ]);

            $this->actingAs($agentUser);

            $response = $this->patch(route('agent.approveNegotiation', $negotiation->id));
            $response->assertStatus(302);
            $response->assertRedirect(route('agent.negotiationDetail', $negotiation->id));

            $this->assertDatabaseHas('negotiations', [
                'id' => $negotiation->id,
                'is_agen_approve' => 1,
            ]); 
        }

    public function test_agent_can_reject_negotiation():void{
        Auth::logout();
            $seller = User::factory()->create();
            $buyer = User::factory()->create();
            $agentUser = User::factory()->create([
                'role' => 'agent',
            ]);

            $agent = Agent::factory()->create([
                'user_id' => $agentUser->id,
            ]);

            $appoinmentId = Appoinment::factory()->create();    
            $property = Property::factory()->create([
                'appoinment_id' => $appoinmentId->id,
            ]);

            $negotiation = Negotiation::factory()->create([
                'seller_id' => $seller->id,
                'buyer_id' => $buyer->id,
                'agent_id' => $agent->id,
                'property_id' => $property->id,
            ]);

           $this->actingAs($agentUser);
            $response = $this->get(route('agent.negotiationRejectionReason', $negotiation->id));
            $response->assertStatus(200);

            $response = $this->patch(
            route('agent.rejectNegotiation', $negotiation->id),
                [
                    'rejection_reason' => 'Harga terlalu rendah',
                ]
            );

            $response->assertStatus(302);

            $this->assertDatabaseHas('negotiations', [
                'id' => $negotiation->id,
                'is_agen_approve' => 0,
                'description' => 'Harga terlalu rendah',
            ]);
    }

    public function test_seller_can_approve_negotiation():void{
        Auth::logout();
            $seller = User::factory()->create();
            $buyer = User::factory()->create();
            $agentUser = User::factory()->create();

            $agent = Agent::factory()->create([
                'user_id' => $agentUser->id,
            ]);

            $appoinmentId = Appoinment::factory()->create();    
            $property = Property::factory()->create([
                'appoinment_id' => $appoinmentId->id,
            ]);

            $negotiation = Negotiation::factory()->create([
                'seller_id' => $seller->id,
                'buyer_id' => $buyer->id,
                'agent_id' => $agent->id,
                'property_id' => $property->id,
            ]);

            $this->actingAs($seller);

            $response = $this->patch(route('users.approveNegotiation', $negotiation->id));
            $response->assertStatus(302);
            $response->assertRedirect(route('negotiation.detail', $negotiation->id));

            $this->assertDatabaseHas('negotiations', [
                'id' => $negotiation->id,
                'is_seller_approve' => 1,
            ]); 
    }

    public function test_seller_can_reject_negotiation():void{
        Auth::logout();
            $seller = User::factory()->create();
            $buyer = User::factory()->create();
            $agentUser = User::factory()->create();

            $agent = Agent::factory()->create([
                'user_id' => $agentUser->id,
            ]);

            $appoinmentId = Appoinment::factory()->create();    
            $property = Property::factory()->create([
                'appoinment_id' => $appoinmentId->id,
            ]);

            $negotiation = Negotiation::factory()->create([
                'seller_id' => $seller->id,
                'buyer_id' => $buyer->id,
                'agent_id' => $agent->id,
                'property_id' => $property->id,
            ]);

           $this->actingAs($seller);
            $response = $this->get(route('users.negotiationRejectionReason', $negotiation->id));
            $response->assertStatus(200);

            $response = $this->patch(
            route('users.rejectNegotiation', $negotiation->id),
                [
                    'rejection_reason' => 'Harga terlalu rendah',
                ]
            );

            $response->assertStatus(302);

            $this->assertDatabaseHas('negotiations', [
                'id' => $negotiation->id,
                'is_seller_approve' => 0,
                'description' => 'Harga terlalu rendah',
            ]);
    }

    public function test_buyer_can_create_renegotiate(): void{
        $seller = User::factory()->create();
        $buyer = User::factory()->create();
        $agentUser = User::factory()->create();

        $agent = Agent::factory()->create([
            'user_id' => $agentUser->id,
        ]);

        $appoinmentId = Appoinment::factory()->create();    
        $property = Property::factory()->create([
            'appoinment_id' => $appoinmentId->id,
        ]);

        $negotiation = Negotiation::factory()->create([
            'seller_id' => $seller->id,
            'buyer_id' => auth::id(),
            'agent_id' => $agent->id,
            'property_id' => $property->id,

            'is_agen_approve' => 1,
            'is_seller_approve' => 0,
        ]);
        $response = $this->get(
        route('users.renegotiation', $negotiation->id)
        );

        $response->assertOk();

        $response = $this->patch(
            route('users.renegotiationUpdate', $negotiation->id),
            [
                'newPrice' => 250000000,
                'description' => 'Harga masih terlalu tinggi',
            ]
        );

        $response->assertStatus(302);

        $this->assertDatabaseHas('negotiations', [
            'id' => $negotiation->id,
            'offer_price' => 250000000,
            'description' => 'Harga masih terlalu tinggi',
            'is_agen_approve' => null,
            'is_seller_approve' => null,
        ]);
    }

}

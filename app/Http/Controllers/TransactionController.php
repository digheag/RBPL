<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\transaction;
use App\Models\Agent;
use App\Models\Property;
use App\Models\Negotiation;
use Illuminate\Support\Facades\Auth;


class TransactionController extends Controller
{
    public function negotiation()
    {
        $negotiations = Negotiation::with([
            'property',
        ])->get()->where('buyer_id', Auth::id());

        return view('users/negotiation',[
            'negotiations' => $negotiations,
        ]);
    }

    public function negotiationDetail($id)
    {
    $negotiations = Negotiation::with([
        'property',
        'agen.user',
        'seller',
    ])->findOrFail($id);

    return view('users/negotiationDetail', [
        "link" => '/users/negotiation',
        "title" => 'Detail Negosiasi',
        "negotiationId" => $id,
        "negotiation" => $negotiations,
        ]);
    }

    public function approveNegotiation($id)
    {
        $negotiation = Negotiation::findOrFail($id);

        if ($negotiation->seller_id !== Auth::id()) {
            abort(403);
        }

        $negotiation->is_seller_approve = 1;
        $negotiation->save();

        return redirect()->route('negotiation.detail', ['id' => $id])
            ->with('success', 'Negosiasi berhasil disetujui');
    }

    public function rejectNegotiation($id)
    {
        $negotiation = Negotiation::findOrFail($id);

        if ($negotiation->seller_id !== Auth::id()) {
            abort(403);
        }

        $negotiation->is_seller_approve = 0;
        $negotiation->save();

        return redirect()->route('negotiation.detail', ['id' => $id])
            ->with('success', 'Negosiasi berhasil ditolak');
    }

    public function transaction()
    {
        $transaction = transaction::with([
            'property.property_image'
        ])
        ->where('buyer_id', Auth::id())
        ->get();

        return view('users/transaction', [
            'transactions' => $transaction,
        ]);
    }

    public function transactionMethod()
    {
        $propertyId = session('propertyId');

        if (!$propertyId) {
        return redirect()->route('users.property')
            ->with('error', 'Session tidak ditemukan, ulangi dari awal');
        }
    
    return view('users/method-transaction',[
            'link' => route('property.detail', ['id' => $propertyId]),
            'title' => 'Transaction Method',
        ]);
    }

    public function transactionDetail($id){
        $transaction = transaction::with([
            'property'
        ])->findOrFail($id);

        if ($transaction->buyer_id !== Auth::id()) {
        abort(403);
        }

        return view('users/transactionDetail',[
            'transaction' => $transaction,
            'link' => route('users.transaction'),
            'title' => 'Detail Transaksi',
        ]);
    }

    public function transactionDirect(){
        $agentId = session('agentId');

        if (!$agentId) {
        return redirect()->route('users.property')
            ->with('error', 'Agent tidak ditemukan di session');
        }

        $agent = Agent::with([
        'user',
        'agentRegency.regency'
        ])->findOrFail($agentId);


        return view('users/direct-transaction',[
            'agent' => $agent,
            'link' => route('users.transactionMethod'),
            'title' => 'Transaksi Langsung',
        ]);
    }

    public function transactionDirectStore(){
        $agentId = session('agentId');
        $propertyId = session('propertyId');

        if (empty($agentId) || empty($propertyId)) {
            return redirect()->route('users.property')
                ->with('error', 'Session tidak valid');
        }

         $property = Property::with([
            'Appoinment'
         ])->findOrFail($propertyId);

         $transaction = Transaction::create([
            'property_id' => $propertyId,
            'seller_id' => $property->Appoinment->seller_id,
            'agent_id' => $agentId,
            'buyer_id' => Auth::id(),
            'deal_price' => $property->price,
            'transaction_type' => 'direct',
            'negotiation_id' => null,
         ]);

        session()->forget(['agentId', 'propertyId']);
        return redirect()->route('users.transaction')
        ->with('success', 'Transaksi berhasil dibuat');
    }

    public function addNegotiation(){
        $property = Property::find(session('propertyId'));
        return view('users/add-negotiation',[
            'link' => route('users.transactionMethod'),
            'title' => 'Add Negotiation',
            'property' => $property
        ]);
    }

    public function negotiationStore(Request $request){
        $propertyId = session('propertyId');
        $agentId = session('agentId');

        if (empty($agentId) || empty($propertyId)) {
            return redirect()->route('users.property')
                ->with('error', 'Session tidak valid');
        }

        $property = Property::with([
            'Appoinment'
         ])->findOrFail($propertyId);

        $request->validate([
            'negotiation_price' => 'required|numeric|min:1',
            'negotiation_message' => 'nullable|string',
        ]);

        $negotiation = Negotiation::create([
            'property_id' => $propertyId,
            'seller_id' =>  $property->Appoinment->seller_id,
            'agent_id' => $agentId,
            'buyer_id' => Auth::id(), 
            'offer_price' => $request->negotiation_price,
            'description' => $request->negotiation_message,
            'is_agen_approve' => null,
            'is_seller_approve' => null,
        ]);

        session()->forget(['agentId', 'propertyId']);
        return redirect()->route('users.negotiation') 
        ->with('success', 'Negosiasi berhasil diajukan');
    }

    public function negotiationTransaction(){
        $agentId = session('agentId');

        if (!$agentId) {
        return redirect()->route('users.property')
            ->with('error', 'Agent tidak ditemukan di session');
        }

        $agent = Agent::with([
        'user',
        'agentRegency.regency'
        ])->findOrFail($agentId);


        return view('users/negotiationTransaction',[
            'agent' => $agent,
            'link' => route('users.transactionMethod'),
            'title' => 'Transaksi Negosiasi',
        ]);
    }

    public function negotiationTransactionStore(Request $request){
        $agentId = session('agentId');
        $propertyId = session('propertyId');

        if (empty($agentId) || empty($propertyId)) {
            return redirect()->route('users.property')
                ->with('error', 'Session tidak valid');
        }

         $property = Property::with([
            'Appoinment'
         ])->findOrFail($propertyId);

         $transaction = Transaction::create([
            'property_id' => $propertyId,
            'seller_id' => $property->Appoinment->seller_id,
            'agent_id' => $agentId,
            'buyer_id' => Auth::id(),
            'deal_price' => $property->price,
            'transaction_type' => 'negotiation',
            'negotiation_id' => null,
         ]);

        session()->forget(['agentId', 'propertyId']);
        return redirect()->route('users.transaction')
        ->with('success', 'Transaksi berhasil dibuat');
    }
}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\transaction;
use App\Models\Agent;
use App\Models\Property;
use Illuminate\Support\Facades\Auth;


class TransactionController extends Controller
{
    public function negotiation()
    {
        return view('users/negotiation');
    }

    public function negotiationDetail($id)
    {
        return view('users/negotiation-detail', [
            "link" => '/users/negotiation',
            "title" => 'Detail Negosiasi',
            "negotiationId" => $id
        ]);
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

        session()->forget(['agent_id', 'property_id']);
        return redirect()->route('users.transaction')
        ->with('success', 'Transaksi berhasil dibuat');
    }
}

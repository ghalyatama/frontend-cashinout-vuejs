<?php

namespace App\Http\Controllers;

use App\Http\Resources\CashResource;
use App\Models\Cash;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;

class CashController extends Controller
{

    public function index()
    {
        $from = request('from');
        $to = request('to');

        if ($from && $to) {
            $debit = $this->getBalance($from, $to, ">=");
            $kredit = $this->getBalance($from, $to, "<");
            
            $balances = Auth::user()->cashes()->get('amount')->sum('amount');
            $transaction= Auth::user()->cashes()->whereBetween('when', [ $from, $to ])->latest()->get();    
        } else {
            $debit = $this->getBalance(now()->firstOfMonth(), now(), ">=");
            $kredit = $this->getBalance(now()->firstOfMonth(), now(), "<");
            
            $balances = Auth::user()->cashes()->get('amount')->sum('amount');
            $transaction= Auth::user()->cashes()->whereBetween('when', [ now()->firstOfMonth(), now() ])->latest()->get();
        }
        
        return response()->json([
            'balances' => formatPrice($balances),
            'debit' => formatPrice($debit),
            'kredit' => formatPrice($kredit),
            'transaction' => CashResource::collection($transaction),
            'now' => now()->format("Y-m-d"),
            'firstOfMounth' => now()->firstOfMonth()->format("Y-m-d")    
        ]);
    }

    public function store()
    {
        request()->validate([
            'name' => 'required',
            'amount' => 'required|numeric'
        ]);

        $slug =  Str::slug(request('name') . "-" . Str::random(5)) ;
        $when = request('when') ?? now();
        
        $cash = Auth::user()->cashes()->create([
            'name' =>request('name'),
            'slug' => $slug,
            'when' => $when,
            'amount' => request('amount'),
            'description' => request('description')
        ]);

        return response()->json([
            'message' => 'The transaction has been saved.',
            'cash' => new CashResource($cash)
        ]);
    }

    public function show(Cash $cash)
    {
        // if  (Auth::id() !== $cash->user_id) {
        //     abort(403);
        // }
        $this->authorize('show', $cash);
        return new CashResource($cash);
    }

    public function getBalance($from, $to, $operator)
    {
        return Auth::user()->cashes()->whereBetween('when', [ $from , $to])->where('amount',$operator,0)->get('amount')->sum('amount');
    }
}

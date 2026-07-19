<?php

namespace App\Http\Controllers;

use App\Models\Bill;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BillController extends Controller
{
    /**
     * Show all bills for the logged-in consumer.
     */
    public function index()
    {
        $user  = Auth::guard('web')->user();

        $bills = Bill::where('user_id', $user->id)
                     ->orderByDesc('month')
                     ->paginate(12);

        $latestUnpaid = Bill::where('user_id', $user->id)
                            ->where('paid_status', 'unpaid')
                            ->orderByDesc('month')
                            ->first();

        return view('consumer.bills', compact('bills', 'latestUnpaid', 'user'));
    }
}

<?php

namespace App\Http\Controllers\Bank;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Bank;

class FetchBankController extends Controller
{
    public function getBankDetails(Request $request)
    {
        try {
        $request->validate([
            'bank_id' => 'required|integer',
        ]);

        $bank = Bank::find($request->bank_id);

        if (!$bank) {
            return response()->json(['message' => 'Bank not found'], 404);
        }

        return response()->json([
            'user_id' => auth()->id(),
            'bank_id' => $bank->id,
            'bank_name' => $bank->name,
            'address' => $bank->address,
            'contact_person' => $bank->contact_person,
            'support_person' => $bank->support_person,
            'license_expiry_date' => $bank->license_expiry_date,
        ]);
    }catch (\Throwable $e) {
        \Log::error("Fetch Bank Error: " . $e->getMessage());
        return response()->json(['message' => 'Internal Server Error'], 500);
    }
}
}

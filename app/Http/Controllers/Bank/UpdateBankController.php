<?php
// app/Http/Controllers/Bank/UpdateBankController.php

namespace App\Http\Controllers\Bank;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Bank;
use Illuminate\Support\Facades\DB; // ✅ Required for DB::table()
use Illuminate\Support\Facades\Log; // ✅ Required for logging errors

class UpdateBankController extends Controller
{
    public function updateExpiry(Request $request)
    {
        try {
        $request->validate([
            'bank_id' => 'required|integer',
            'expiry_date' => 'required|date',
        ]);

        // $user = auth()->user();
        // Optional: check authenticated user (if using auth)
        if (!auth()->check()) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        $updated = DB::table('banks')
            ->where('id', $request->bank_id)
            ->update([
                'license_expiry_date' => $request->expiry_date,
                'updated_at' => now()
            ]);

        if ($updated) {
            return response()->json(['message' => 'Expiry date updated'], 200);
        } else {
            return response()->json(['message' => 'Bank not found or update failed'], 404);
        }
    }catch (\Throwable $e) {
            Log::error('Update Expiry Error: ' . $e->getMessage());
            return response()->json(['message' => 'Server Error: ' . $e->getMessage()], 500);
        }
    }
}

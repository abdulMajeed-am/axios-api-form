<?php
// app/Http/Controllers/Bank/UpdateBankController.php

namespace App\Http\Controllers\Bank;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB; 
use Illuminate\Support\Facades\Log;

class UpdateBankController extends Controller
{
    public function updateExpiry(Request $request)
    {
        try {
            $request->validate([
                'bank_id'     => 'required|string|max:50',
                'branch_id'   => 'nullable|integer',
                'expiry_date' => 'required|date',
            ]);

            // $user = auth()->user();
            // Optional: check authenticated user (if using auth)
            if (! auth()->check()) {
                return response()->json(['message' => 'Unauthorized'], 401);
            }

            $updated = false;

            // Update branch if branch_id is provided
            if ($request->branch_id) {
                $updated = DB::table('branches')
                    ->where('bank_id', $request->bank_id)
                    ->where('BranchId', $request->branch_id)
                    ->update([
                        'license_expiry_date' => $request->expiry_date,
                        'updated_at'          => now(),
                    ]);
            }

            // Fallback to bank if no branch_id or branch not found
            if (! $updated) {
                $updated = DB::table('banks')
                    ->where('bank_id', $request->bank_id)
                    ->update([
                        'license_expiry_date' => $request->expiry_date,
                        'updated_at'          => now(),
                    ]);
            }

            if ($updated) {
                return response()->json(['message' => 'Expiry date updated'], 200);
            } else {
                return response()->json(['message' => 'Bank or branch not found or update failed'], 404);
            }
        } catch (\Throwable $e) {
            Log::error('Update Expiry Error: ' . $e->getMessage());
            return response()->json(['message' => 'Server Error: ' . $e->getMessage()], 500);
        }
    }
}

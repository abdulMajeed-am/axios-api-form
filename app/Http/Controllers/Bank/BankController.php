<?php

namespace App\Http\Controllers\Bank;

use App\Http\Controllers\Controller;
use App\Models\Bank;
use App\Models\Branch;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class BankController extends Controller
{
    public function getBankDetails(Request $request)
    {
        // Ensure JSON response
        $request->headers->set('Accept', 'application/json');

        if (!Auth::guard('sanctum')->check()) {
            return response()->json(['message' => 'Unauthenticated'], 401);
}

        // Validate bank_id if provided
        if ($request->has('bank_id')) {
            $request->validate([
                'bank_id' => 'required|string|max:50|exists:banks,bank_id',
            ]);

            $bank = Bank::with('branches')->where('bank_id', $request->bank_id)->first();

            if (!$bank) {
                return response()->json(['message' => 'Bank not found'], 422);
            }
            return response()->json([
                'user_id' => Auth::guard('sanctum')->id(), // Explicit guard
                'bank_id' => $bank->bank_id,
                'bank_name' => $bank->bank_name,
                'address' => $bank->bank_address,
                'contact_person' => $bank->contact_person,
                'support_person' => $bank->our_support_person,
                'license_expiry_date' => $bank->license_expiry_date,
                'branches' => $bank->branches->map(function ($branch) {
                    return [
                        'branch_id' => $branch->BranchId,
                        'contact_person' => $branch->contact_person,
                    ];
                })->values(),
            ], 200);
        }

        // Return list of all banks
        $banks = Bank::all()->load('branches');
        $banks = $banks->map(function ($bank) {
            return [
                'bank_id' => $bank->bank_id,
                'bank_name' => $bank->bank_name,
                'branches' => $bank->branches->map(function ($branch) {
                    return [
                        'branch_id' => $branch->BranchId,
                        'contact_person' => $branch->contact_person,
                    ];
                })->values(),
            ];
        });
        return response()->json($banks, 200);
    }


    public function updateExpiry(Request $request)
    {
        $request->headers->set('Accept', 'application/json');
        // Early authentication check
        if (!Auth::guard('sanctum')->check()) {
            return response()->json(['message' => 'Unauthenticated'], 401);
        }

        $request->validate([
            'bank_id' => 'required|string|max:50|exists:banks,bank_id',
            'license_expiry_date' => 'required|date',
        ]);

        $bank = Bank::where('bank_id', $request->bank_id)->first();

        // if (!$bank) {
        //     return response()->json(['message' => 'Bank not found'], 422);
        // }

        $bank->license_expiry_date = $request->license_expiry_date;
        $bank->save();

        return response()->json(['message' => 'License expiry date updated successfully'], 200);
    }
}
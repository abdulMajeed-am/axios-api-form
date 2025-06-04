<?php

namespace App\Http\Controllers\Bank;

use App\Http\Controllers\Controller;
use App\Models\Bank;
use App\Models\Branch;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class BranchController extends Controller
{
    public function getBranchDetails(Request $request)
    {
        $request->headers->set('Accept', 'application/json');

        $request->validate([
            'bank_id' => 'required|string|max:50|exists:banks,bank_id',
            'branch_id' => 'required|integer',
        ]);

        $branch = Branch::where('bank_id', $request->bank_id)
                        ->where('BranchId', $request->branch_id)
                        ->first();

        if (!$branch) {
            return response()->json(['message' => 'Branch not found'], 422);
        }

        $bank = Bank::where('bank_id', $request->bank_id)->first();

        return response()->json([
            'user_id' => auth()->id(),
            'bank_id' => $bank->bank_id,
            'branch_id' => $branch->BranchId,
            'bank_name' => $bank->bank_name,
            'address' => $branch->bank_address,
            'contact_person' => $branch->contact_person,
            'support_person' => $branch->our_support_person,
            'license_expiry_date' => $branch->license_expiry_date,
        ], 200);
    }
}
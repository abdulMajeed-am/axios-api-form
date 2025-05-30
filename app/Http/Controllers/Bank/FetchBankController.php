<?php
namespace App\Http\Controllers\Bank;

use App\Http\Controllers\Controller;
use App\Models\Bank;
use App\Models\Branch;
use Illuminate\Http\Request;

class FetchBankController extends Controller
{
    public function getBankDetails(Request $request)
    {
        try {
            $request->validate([
                'bank_id' => 'required|string|max:50',
                'branch_id' => 'nullable|integer',
            ]);

            // Try to find the branch first if branch_id is provided
            if ($request->branch_id) {
                $branch = Branch::where('bank_id', $request->bank_id)
                    ->where('BranchId', $request->branch_id)
                    ->first();

                if ($branch) {
                    // Fetch bank for bank-level data
                    $bank = Bank::find($request->bank_id);
                    if (! $bank) {
                        return response()->json(['message' => 'Bank not found'], 404);
                    }

                    return response()->json([
                        'user_id'             => auth()->id(),
                        'bank_id'             => $bank->bank_id,
                        'bank_name'           => $bank->bank_name ?? 'Not provided',
                        'address'             => $branch->bank_address ?? $bank->bank_address ?? 'Not provided',
                        'contact_person'      => $branch->contact_person ?? $bank->contact_person ?? 'Not provided',
                        'support_person'      => $branch->our_support_person ?? $bank->our_support_person ?? 'Not assigned',
                        'license_expiry_date' => $branch->license_expiry_date ?? $bank->license_expiry_date ?? 'Not set',
                    ]);
                }
            }

            // Fallback to bank if no branch_id or branch not found
            $bank = Bank::find($request->bank_id);

            if (! $bank) {
                return response()->json(['message' => 'Bank not found'], 404);
            }

            return response()->json([
                'user_id'             => auth()->id(),
                'bank_id'             => $bank->bank_id,
                'bank_name'           => $bank->bank_name ?? 'Not provided',
                'address'             => $bank->bank_address ?? 'Not provided',
                'contact_person'      => $bank->contact_person ?? 'Not provided',
                'support_person'      => $bank->our_support_person ?? 'Not assigned',
                'license_expiry_date' => $bank->license_expiry_date ?? 'Not set',
            ]);
        } catch (\Throwable $e) {
            \Log::error("Fetch Bank Error: " . $e->getMessage());
            return response()->json(['message' => 'Internal Server Error'], 500);
        }
    }

    public function listBanks()
    {
        try {
            $banks = Bank::with('branches')->get()->map(function ($bank) {
                return [
                    'bank_id' => $bank->bank_id,
                    'bank_name' => $bank->bank_name ?? 'Not provided',
                    'branches' => $bank->branches->map(function ($branch) {
                        return [
                            'branch_id' => $branch->BranchId,
                            'contact_person' => $branch->contact_person ?? 'Not provided',
                        ];
                    })->values(),
                ];
            });

            return response()->json($banks, 200);
        } catch (\Throwable $e) {
            \Log::error("List Banks Error: " . $e->getMessage());
            return response()->json(['message' => 'Internal Server Error'], 500);
        }
    }

    public function listAllDetails()
    {
        try {
            $banks = Bank::with('branches')->get()->map(function ($bank) {
                return [
                    'bank_id' => $bank->bank_id,
                    'enum_id' => $bank->enum_id ?? null,
                    'bank_name' => $bank->bank_name ?? 'Not provided',
                    'taluk_town' => $bank->taluk_town ?? 'Not provided',
                    'email' => $bank->email ?? 'Not provided',
                    'name_in_invoice' => $bank->name_in_invoice ?? 'Not provided',
                    'gst_no' => $bank->gst_no ?? 'Not provided',
                    'invoice_to' => $bank->invoice_to ?? 'Not provided',
                    'bank_address' => $bank->bank_address ?? 'Not provided',
                    'contact_person' => $bank->contact_person ?? 'Not provided',
                    'contact_number' => $bank->contact_number ?? 'Not provided',
                    'customer_type' => $bank->customer_type ?? 'Not provided',
                    'version_type' => $bank->version_type ?? 'Not provided',
                    'license_expiry_date' => $bank->license_expiry_date ?? 'Not set',
                    'business_amount' => $bank->business_amount ?? 0.00,
                    'maintenance_amount' => $bank->maintenance_amount ?? 0.00,
                    'maintenance_freq' => $bank->maintenance_freq ?? 'Not provided',
                    'our_support_person' => $bank->our_support_person ?? 'Not assigned',
                    'created_at' => $bank->created_at ? $bank->created_at->toDateTimeString() : null,
                    'updated_at' => $bank->updated_at ? $bank->updated_at->toDateTimeString() : null,
                    'branches' => $bank->branches->map(function ($branch) {
                        return [
                            'id' => $branch->id,
                            'bank_id' => $branch->bank_id,
                            'BranchId' => $branch->BranchId,
                            'taluk_town' => $branch->taluk_town ?? 'Not provided',
                            'bank_address' => $branch->bank_address ?? 'Not provided',
                            'contact_person' => $branch->contact_person ?? 'Not provided',
                            'contact_number' => $branch->contact_number ?? 'Not provided',
                            'license_expiry_date' => $branch->license_expiry_date ?? 'Not set',
                            'business_amount' => $branch->business_amount ?? 0.00,
                            'maintenance_amount' => $branch->maintenance_amount ?? 0.00,
                            'maintenance_freq' => $branch->maintenance_freq ?? 'Not provided',
                            'our_support_person' => $branch->our_support_person ?? 'Not assigned',
                            'created_at' => $branch->created_at ? $branch->created_at->toDateTimeString() : null,
                            'updated_at' => $branch->updated_at ? $branch->updated_at->toDateTimeString() : null,
                        ];
                    })->values(),
                ];
            });

            return response()->json($banks, 200);
        } catch (\Throwable $e) {
            Log::error("List All Details Error: " . $e->getMessage(), ['exception' => $e]);
            return response()->json(['message' => 'Internal Server Error'], 500);
        }
    }
}

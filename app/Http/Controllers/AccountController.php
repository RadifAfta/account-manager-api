<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Account;

class AccountController extends Controller
{
    // GET /api/accounts
    public function index()
    {
        return response()->json(Account::all());
    }

    // POST /api/accounts
    public function store(Request $request)
    {
        $validated = $request->validate([
            'user_id' => 'required|integer',
            'title' => 'required|string',
            'username' => 'required|string',
            'password' => 'required|string',
            'url' => 'nullable|string',
            'notes' => 'nullable|string',
        ]);

        // Encrypt password before saving
        $validated['password'] = encrypt($validated['password']);

        $account = Account::create($validated);

        return response()->json($account, 201);
    }

    // GET /api/accounts/{id}
    public function show($id)
    {
        $account = Account::findOrFail($id);
        // Decrypt password before returning (optional, for demonstration)
        $account->password = decrypt($account->password);
        return response()->json($account);
    }

    // PUT /api/accounts/{id}
    public function update(Request $request, $id)
    {
        $account = Account::findOrFail($id);

        $validated = $request->validate([
            'user_id' => 'sometimes|integer',
            'title' => 'sometimes|string',
            'username' => 'sometimes|string',
            'password' => 'sometimes|string',
            'url' => 'nullable|string',
            'notes' => 'nullable|string',
        ]);

        if (isset($validated['password'])) {
            $validated['password'] = encrypt($validated['password']);
        }

        $account->update($validated);

        return response()->json($account);
    }

    // DELETE /api/accounts/{id}
    public function destroy($id)
    {
        $account = Account::findOrFail($id);
        $account->delete();

        return response()->json(['message' => 'Account deleted']);
    }
}

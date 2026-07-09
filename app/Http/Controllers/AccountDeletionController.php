<?php

namespace App\Http\Controllers;

use App\Mail\AccountDeletionRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class AccountDeletionController extends Controller
{
    // Account deletion itself is done manually from the admin back office
    // (there is no public self-service deletion API), so this form just
    // relays the request by email to the operator - this page's URL is
    // what Google Play's Data Safety form requires as the public account
    // deletion request destination.
    private const RECIPIENT = 'contact@jimmy-besse.fr';

    public function show()
    {
        return view('account-deletion');
    }

    public function submit(Request $request)
    {
        $request->validate([
            'email' => 'required|email|max:255',
            'message' => 'nullable|string|max:2000',
        ]);

        Mail::to(self::RECIPIENT)->send(
            new AccountDeletionRequest($request->email, $request->message)
        );

        return redirect()->route('account-deletion')->with('success', true);
    }
}

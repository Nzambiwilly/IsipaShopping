<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ContactController extends Controller
{
    public function index()
    {
        return view('visiteur.contact');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nom' => ['required', 'string', 'min:2', 'max:120'],
            'email' => ['required', 'email', 'max:120'],
            'message' => ['required', 'string', 'min:10', 'max:2000'],
        ]);

        Log::info('Nouveau message de contact ISIPA Shopping', $validated);

        return back()->with('success', 'Votre message a ete envoye. Nous vous contacterons rapidement.');
    }
}

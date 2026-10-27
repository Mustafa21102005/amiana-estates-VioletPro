<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreMessageRequest;
use App\Models\Message;
use Illuminate\Http\RedirectResponse;

class ContactController extends Controller
{
    public function store(StoreMessageRequest $request): RedirectResponse
    {
        // Real visitors never see the "website" field, so anything in it means a bot.
        // It gets the same thank-you page, so it learns nothing, but nothing is saved.
        if (! $request->filled('website')) {
            Message::create($request->validated());
        }

        return redirect(url()->previous() . '#contact')->with('contact_sent', true);
    }
}

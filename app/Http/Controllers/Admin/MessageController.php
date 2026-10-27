<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Message;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class MessageController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        return view('admin.messages.index', [
            'messages' => Message::latest()->orderByDesc('id')->paginate(10),
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(Message $message): View
    {
        $message->markAsRead();

        return view('admin.messages.show', [
            'message' => $message,
            'whatsappLink' => $message->whatsappLink(),
        ]);
    }

    /**
     * Marks the specified resource as unread.
     */
    public function unread(Message $message): RedirectResponse
    {
        $message->markAsUnread();

        return redirect()->route('admin.messages.index')->with('success', 'Marked as unread.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Message $message): RedirectResponse
    {
        $message->delete();

        return redirect()->route('admin.messages.index')->with('success', 'Message deleted.');
    }
}

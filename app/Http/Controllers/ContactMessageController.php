<?php

namespace App\Http\Controllers;

use App\Models\ContactMessage;
use Illuminate\Http\Request;

class ContactMessageController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'subject' => 'required|string|max:255',
            'message' => 'required|string',
        ]);

        $message = ContactMessage::create(array_merge($validated, [
            'user_id' => auth()->id(),
            'status' => 'pending'
        ]));

        return response()->json(['message' => 'Message sent successfully', 'data' => $message], 201);
    }

    public function index(Request $request)
    {
        $query = ContactMessage::query()->orderBy('created_at', 'desc');
        
        if ($status = $request->get('status')) {
            $query->where('status', $status);
        }

        return response()->json($query->paginate(20));
    }

    public function resolve($id)
    {
        $message = ContactMessage::findOrFail($id);
        $message->update(['status' => 'resolved']);

        return response()->json(['message' => 'Message resolved', 'data' => $message]);
    }
}

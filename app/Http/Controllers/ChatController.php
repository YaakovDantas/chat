<?php

namespace App\Http\Controllers;

use App\Models\Canal;
use App\Models\Message;
use Illuminate\Http\Request;

class ChatController extends Controller
{
    public function index()
    {
        $canals = Canal::all();
        return view('chat.index', compact('canals'));
    }

    public function index2()
    {
        $canals = Canal::all();
        return view('chat.index2', compact('canals'));
    }

    public function storeMessage(Request $request)
    {
        $request->validate([
            'canal_id' => 'required|exists:canals,id',
            'nome' => 'required|string|max:255',
            'texto' => 'required|string',
        ]);

        Message::create($request->all());

        return response()->json(['success' => true]);
    }

    public function getMessages($canalId)
    {
        $messages = Message::where('canal_id', $canalId)->get();
        return response()->json($messages);
    }
}

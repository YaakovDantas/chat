<?php
namespace App\Http\Controllers;

use App\Models\Canal;
use App\Models\Message;
use Illuminate\Http\Request;

class ChatSocketController extends Controller
{
    public function index()
    {
        $canals = Canal::all();
        return view('chat.index3', compact('canals'));
    }

    public function storeMessage(Request $request)
    {
        $request->validate([
            'canal_id' => 'required|exists:canals,id',
            'nome' => 'required|string|max:255',
            'texto' => 'required|string',
        ]);

        // Criar a mensagem no banco de dados
        $message = Message::create($request->all());
        event(new MessageSent($message, $request->canal_id));
        

        return response()->json(['success' => true]);
    }

    public function getMessages($canalId)
    {
        $messages = Message::where('canal_id', $canalId)->get();
        return response()->json($messages);
    }
}

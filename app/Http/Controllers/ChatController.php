<?php

namespace App\Http\Controllers;

use App\Models\Chat;
use App\Models\Message;
use App\Models\Student;
use App\Models\Tutor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ChatController extends Controller
{
    // View messenger home
    public function index(Request $request)
    {
        $user = Auth::user();
        $chats = Chat::query();

        if ($user->isStudent()) {
            $chats->where('student_id', $user->student->id)->with('tutor.user');
        } else {
            $chats->where('tutor_id', $user->tutor->id)->with('student.user');
        }

        $chats = $chats->with('latestMessage')->get();

        $activeChat = null;
        $messages = collect();

        if ($request->filled('chat_id')) {
            $activeChat = Chat::findOrFail($request->chat_id);
            
            // Security check
            if ($user->isStudent() && $activeChat->student_id !== $user->student->id) {
                abort(403);
            }
            if ($user->isTutor() && $activeChat->tutor_id !== $user->tutor->id) {
                abort(403);
            }

            // Mark incoming messages as read
            Message::where('chat_id', $activeChat->id)
                ->where('sender_id', '!=', $user->id)
                ->update(['is_read' => true]);

            $messages = Message::where('chat_id', $activeChat->id)
                ->with('sender')
                ->orderBy('created_at', 'asc')
                ->get();
        }

        return view('chat.index', compact('chats', 'activeChat', 'messages'));
    }

    // Start or open a chat with a tutor
    public function startChat($tutorId)
    {
        $student = Auth::user()->student;
        if (!$student) {
            return back()->with('error', 'Only students can start a chat.');
        }

        $tutor = Tutor::findOrFail($tutorId);

        $chat = Chat::firstOrCreate([
            'student_id' => $student->id,
            'tutor_id' => $tutor->id,
        ]);

        return redirect()->route('chat.index', ['chat_id' => $chat->id]);
    }

    // Send a message
    public function sendMessage(Request $request, $chatId)
    {
        $request->validate([
            'message' => 'required|string|max:1000',
        ]);

        $chat = Chat::findOrFail($chatId);
        $user = Auth::user();

        // Security check
        if ($user->isStudent() && $chat->student_id !== $user->student->id) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }
        if ($user->isTutor() && $chat->tutor_id !== $user->tutor->id) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $message = Message::create([
            'chat_id' => $chat->id,
            'sender_id' => $user->id,
            'message' => $request->message,
            'is_read' => false,
        ]);

        if ($request->ajax()) {
            return view('chat.partials.message', compact('message'))->render();
        }

        return back();
    }

    // Ajax polling: fetch messages
    public function fetchMessages(Request $request, $chatId)
    {
        $chat = Chat::findOrFail($chatId);
        $user = Auth::user();

        // Security check
        if ($user->isStudent() && $chat->student_id !== $user->student->id) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }
        if ($user->isTutor() && $chat->tutor_id !== $user->tutor->id) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        // Mark incoming messages as read
        Message::where('chat_id', $chat->id)
            ->where('sender_id', '!=', $user->id)
            ->where('is_read', false)
            ->update(['is_read' => true]);

        $messages = Message::where('chat_id', $chat->id)
            ->with('sender')
            ->orderBy('created_at', 'asc')
            ->get();

        return view('chat.partials.message_list', compact('messages'))->render();
    }
}

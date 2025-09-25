<?php

namespace App\Http\Controllers;

use App\Models\Group;
use App\Models\Message;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Notification;
use App\Notifications\NewMessageNotification;

class ChatController extends Controller
{
    public function index()
    {
        $groups = Auth::user()->groups()->with('users')->get();
        return view('chat.index', compact('groups'));
    }

    public function getUsers()
    {
        return \App\Models\User::all(['id', 'name']);
    }




    public function downloadFile($filename)
    {
        $path = storage_path('app/public/chat_files/' . $filename);

        if (!file_exists($path)) {
            abort(404);
        }

        return response()->download($path);
    }

    public function show(Group $group)
    {
        $user = Auth::user();
        Log::info('ChatController@show: Authenticated user', ['user_id' => $user ? $user->id : 'null']);

        $messages = Message::with(['user', 'replies.user', 'readers'])
            ->where('group_id', $group->id)
            ->whereNull('parent_id')
            ->orderBy('created_at')
            ->get();

        $allMimeTypes = $messages->pluck('mime_type')->unique()->filter()->values()->toArray();
        return view('chat.show', compact('group', 'messages', 'user', 'allMimeTypes'));
    }

    public function send(Request $request, Group $group)
    {
        $request->validate([
            'message' => 'nullable|string',
            'parent_id' => 'nullable|integer',
            'file' => 'nullable|file',
        ]);



        $user = Auth::user();

            if (Auth::user()->cannot('sendMessages', $group)) {

                return response()->json(['message' => 'You are not authorized to send messages to this group.'], 403);
            }

        try {
            // Log request data for debugging
            Log::info('ChatController@send: Request data', $request->all());

            // Create the new message
            $messageContent = $request->input('message');
            $file = $request->file('file');
            $filePath = null;
            $messageType = 'text';
            $mimeType = null;

            if ($file) {
                $filePath = $file->store('chat_files', 'public');
                $messageType = 'file'; // Or you could use $file->getClientMimeType() if you want more specific types
                $mimeType = $file->getClientMimeType();
            }

            $message = $group->messages()->create([
                'group_id' => $group->id,
                'user_id' => $user->id,
                'content' => $messageContent,
                'parent_id' => $request->parent_id,
                'type' => $messageType,
                'file_path' => $filePath,
                'mime_type' => $mimeType,
            ]);

            // Log successful message creation
            Log::info('ChatController@send: Message created successfully', ['message_id' => $message->id]);

            // Notify recipients
            $recipients = $group->users->where('id', '!=', $user->id);
            foreach ($recipients as $recipient) {
                $recipient->notify(new NewMessageNotification($message, $user->name));
            }

            // Broadcast message
            broadcast(new \App\Events\MessageSent($user, $message, $group))->toOthers();
        } catch (\Exception $e) {

            return response()->json(['status' => 'error', 'message' => 'Internal Server Error', 'error_details' => $e->getMessage()], 500);
        }

        return response()->json([
    'status' => 'success',
    'message' => [
        'id' => $message->id,
        'content' => $message->content,
        'type' => $message->type,
        'file_path' => $message->file_path,
        'mime_type' => $message->mime_type,
        'parent_id' => $message->parent_id,
        'created_at' => $message->created_at->toDateTimeString(),
        'user' => [
            'id' => $user->id,
            'name' => $user->name,
            'role' => $user->role,
        ],
    ],
    'group_id' => $group->id,
]);
    }

    public function markAsRead(Request $request, Message $message)
    {
        $user = Auth::user();
        if (!$message->readers->contains($user)) {
            $message->readers()->attach($user->id, ['read_at' => now()]);
        }
        return response()->json(['status' => 'success']);
    }

    public function fetchHistory(Request $request)
    {
        $messages = Message::with(['user'])
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return response()->json($messages);
    }

    public function getMessages(Request $request)
    {
        $lastMessageId = $request->query('after');

        $messages = Message::with('user')
            ->when($lastMessageId, function ($query, $lastMessageId) {
                return $query->where('id', '>', $lastMessageId);
            })
            ->orderBy('created_at', 'asc')
            ->get();

        return response()->json($messages);
    }
}

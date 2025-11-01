<?php

namespace App\Livewire;

use App\Events\MessageDeleted;
use App\Events\MessageEdited;
use App\Events\MessageSent;
use App\Events\MessagesRead;
use App\Models\Conversation;
use App\Models\Message;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Computed;
use Livewire\Component;
use Livewire\WithFileUploads;
use App\Models\Reaction;
use Livewire\Attributes\Layout;
#[Layout('layouts.app')]
class ChatBox extends Component
{
    use WithFileUploads;

    public ?Conversation $selectedConversation = null;
    public $body;
    public $attachment;
    public Collection $messages;
    public int $page = 1;
    public const PER_PAGE = 15;
    public bool $hasMorePages;
    public $editingMessageId = null;
    public $editingMessageBody = '';
    public $replyingToMessage = null;
    public string $filter = 'all';


    #[Computed]
    public function friends(): EloquentCollection
    {
        $friends = Auth::user()->getFriends();
        if ($this->filter === 'unread') {
            return $friends->filter(function ($friend) {
                return $friend->conversations()
                    ->whereHas('messages', fn($q) => $q->where('user_id', $friend->id)->whereNull('read_at'))
                    ->whereHas('users', fn($q) => $q->where('user_id', Auth::id()))
                    ->exists();
            });
        }
        return $friends;
    }

    public function clearSelection()
    {
        $this->selectedConversation = null;
    }

    public function mount()
    {
        $this->messages = collect();
    }

    public function markConversationAsRead()
    {
        if ($this->selectedConversation) {
            $this->selectedConversation->messages()->where('user_id', '!=', Auth::id())->whereNull('read_at')->update(['read_at' => now()]);
            broadcast(new MessagesRead($this->selectedConversation->id))->toOthers();
        }
    }

    public function viewConversation(int $friendId)
    {
        $this->page = 1;
        $userId = Auth::id();
        $conversation = Conversation::whereHas('users', fn($q) => $q->where('user_id', $userId))->whereHas('users', fn($q) => $q->where('user_id', $friendId))->withCount('users')->having('users_count', 2)->first();

        if (!$conversation) {
            $conversation = Conversation::create();
            $conversation->users()->attach([$userId, $friendId]);
        }

        $this->selectedConversation = $conversation;
        $paginator = $this->selectedConversation->messages()->with('user', 'parent.user', 'reactions.user')->latest()->paginate(self::PER_PAGE, ['*'], 'page', $this->page);
        $this->messages = collect($paginator->items())->reverse();
        $this->hasMorePages = $paginator->hasMorePages();

        $this->markConversationAsRead();
        $this->dispatch('conversation-selected', id: $this->selectedConversation->id);
        $this->dispatch('scroll-to-bottom');
    }

    public function loadMore()
    {
        if (!$this->hasMorePages) return;
        $this->page++;
        $paginator = $this->selectedConversation->messages()->with('user', 'parent.user', 'reactions.user')->latest()->paginate(self::PER_PAGE, ['*'], 'page', $this->page);
        $newMessages = collect($paginator->items())->reverse();
        $this->messages = $newMessages->concat($this->messages);
        $this->hasMorePages = $paginator->hasMorePages();
        $this->dispatch('retaining-scroll-position');
    }

    public function sendMessage()
    {
        if (!$this->selectedConversation) return;
        $recipient = $this->selectedConversation->users->where('id', '!=', Auth::id())->first();
        if (Auth::user()->friendshipStatusWith($recipient) === 'blocked') return;


        if (!$this->body && !$this->attachment) return;

        $filePath = null;
        if ($this->attachment) {
            $filePath = $this->attachment->store('uploads/message', 'public');
        }

        $message = $this->selectedConversation->messages()->create([
            'user_id' => Auth::id(),
            'body' => $this->body ?? '',
            'file_path' => $filePath,
            'parent_id' => $this->replyingToMessage?->id,
        ]);

        $this->messages->push($message->load('user', 'parent.user', 'reactions.user'));
        $this->dispatch('scroll-to-bottom');
        broadcast(new MessageSent($message))->toOthers();
        $this->reset(['body', 'attachment']);
        $this->cancelReplying();
    }

   public function loadNewMessage($messageId)
    {
        $newMessage = Message::find($messageId);

        if ($newMessage && !$this->messages->contains('id', $newMessage->id)) {
            $this->messages->push($newMessage);

            if ($this->selectedConversation && $this->selectedConversation->id === $newMessage->conversation_id) {
                $this->markConversationAsRead();
            }

            $this->dispatch('scroll-to-bottom');
        }
    }

    public function startEditing(int $messageId)
    {
        $message = Message::findOrFail($messageId);
        if ($message->user_id !== Auth::id()) return;
        $this->editingMessageId = $message->id;
        $this->editingMessageBody = $message->body;
    }

    public function cancelEditing()
    {
        $this->reset(['editingMessageId', 'editingMessageBody']);
    }

    public function updateMessage()
    {

        $message = Message::findOrFail($this->editingMessageId);
        if ($message->user_id !== Auth::id()) return;
        $message->update(['body' => $this->editingMessageBody, 'edited_at' => now()]);

        $index = $this->messages->search(fn($msg) => $msg->id === $message->id);
        $this->messages[$index] = $message;

        broadcast(new MessageEdited($message))->toOthers();
        $this->cancelEditing();
    }

    public function deleteMessage(int $messageId)
    {
        $message = Message::findOrFail($messageId);
        if ($message->user_id !== Auth::id()) return;
        $this->messages = $this->messages->filter(fn($msg) => $msg->id !== $messageId);
        broadcast(new MessageDeleted($message->id, $message->conversation_id))->toOthers();
        $message->delete();
    }

    public function startReplying(int $messageId)
    {
        $this->replyingToMessage = Message::with('user')->find($messageId);
    }

    public function cancelReplying()
    {
        $this->replyingToMessage = null;
    }

    public function react(int $messageId, string $emoji)
    {
        $reaction = Reaction::where('message_id', $messageId)->where('user_id', Auth::id())->first();
        if ($reaction) {
            if ($reaction->emoji === $emoji) $reaction->delete();
            else $reaction->update(['emoji' => $emoji]);
        } else {
            Reaction::create(['message_id' => $messageId, 'user_id' => Auth::id(), 'emoji' => $emoji]);
        }
        $this->messages = $this->messages->map(function ($message) use ($messageId) {
            if ($message->id === $messageId) return $message->fresh('reactions.user');
            return $message;
        });
    }

    public function render()
    {
        return view('livewire.chat-box');
    }
}

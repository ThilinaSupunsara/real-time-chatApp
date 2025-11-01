<?php

namespace App\Livewire;

use App\Models\Conversation;
use App\Models\Friendship;
use App\Models\Message;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Computed;
use Livewire\Component;
use Livewire\Attributes\Layout;
#[Layout('layouts.app')]
class Dashboard extends Component
{
    /**
     * Get the user's total number of friends.
     */
    #[Computed]
    public function totalFriends(): int
    {
        return Auth::user()->getFriends()->count();
    }

    /**
     * Get the count of pending friend requests.
     */
    #[Computed]
    public function pendingRequestsCount(): int
    {
        return Friendship::where('friend_id', Auth::id())
            ->where('status', 'pending')
            ->count();
    }

    /**
     * Get the total number of unread messages.
     */
    #[Computed]
    public function unreadMessagesCount(): int
    {

        $conversationIds = Auth::user()->conversations()->pluck('conversations.id');

        return Message::whereIn('conversation_id', $conversationIds)
            ->where('user_id', '!=', Auth::id())
            ->whereNull('read_at')
            ->count();
    }

    /**
     * Get the top 5 most recent conversations.
     */
    #[Computed]
    public function recentConversations(): Collection
    {
        $conversations = Auth::user()->conversations()
            ->with([
                'users' => fn($query) => $query->where('users.id', '!=', Auth::id()),
                'messages' => fn($query) => $query->latest()
            ])
            ->get();

        // Sort conversations by the created_at of their latest message
        return $conversations->sortByDesc(function ($convo) {
            return $convo->messages->first()?->created_at;
        })->take(5);
    }

    /**
     * Get the top 5 pending friend requests.
     */
    #[Computed]
    public function friendRequests(): Collection
    {
        return Friendship::where('friend_id', Auth::id())
            ->where('status', 'pending')
            ->with('sender') // Assumes a 'sender' relationship on the Friendship model
            ->take(5)
            ->get();
    }

    /**
     * Accept a friend request. (Copied from ShowUsers)
     */
    public function acceptRequest(int $friendshipId)
    {
        $friendship = Friendship::findOrFail($friendshipId);
        if ($friendship->friend_id === Auth::id()) {
            $friendship->update(['status' => 'accepted']);
            // Refresh computed properties
            unset($this->pendingRequestsCount);
            unset($this->friendRequests);
            unset($this->totalFriends);
        }
    }

    /**
     * Reject a friend request. (Copied from ShowUsers)
     */
    public function rejectRequest(int $friendshipId)
    {
        $friendship = Friendship::findOrFail($friendshipId);
        if ($friendship->friend_id === Auth::id()) {
            $friendship->delete();
            // Refresh computed properties
            unset($this->pendingRequestsCount);
            unset($this->friendRequests);
        }
    }

    public function render()
    {
        return view('livewire.dashboard');
    }
}

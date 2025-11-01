<?php

namespace App\Livewire;

use App\Models\Friendship;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\Attributes\Computed;
#[Layout('layouts.app')]
class ShowUsers extends Component
{
    public string $search = '';
    public string $activeTab = 'all';
    public function addFriend(int $recipientId)
    {
        Friendship::create([
            'user_id' => Auth::id(),
            'friend_id' => $recipientId,
            'status' => 'pending',
        ]);
    }

    public function acceptRequest(int $friendshipId)
    {
        $friendship = Friendship::findOrFail($friendshipId);
        if ($friendship->friend_id === Auth::id()) {
            $friendship->update(['status' => 'accepted']);
        }
    }

    public function cancelRequest(int $friendshipId)
    {
        $friendship = Friendship::findOrFail($friendshipId);
        // Ensure the authenticated user is either the sender or receiver before deleting
        if ($friendship->user_id === Auth::id() || $friendship->friend_id === Auth::id()) {
            $friendship->delete();
        }
    }

    public function render()
    {
        $users = User::where('id', '!=', Auth::id())->get();
        return view('livewire.show-users', [
            'users' => $users
        ]);
    }

    public function blockUser(int $userId)
    {
        $authId = Auth::id();

        // Find existing friendship to update it, or create a new one
        $friendship = Friendship::where(function ($query) use ($authId, $userId) {
            $query->where('user_id', $authId)->where('friend_id', $userId);
        })->orWhere(function ($query) use ($authId, $userId) {
            $query->where('user_id', $userId)->where('friend_id', $authId);
        })->first();

        if ($friendship) {
            $friendship->update([
                'status' => 'blocked',
                'blocked_by' => $authId,
            ]);
        } else {
            Friendship::create([
                'user_id' => $authId,
                'friend_id' => $userId,
                'status' => 'blocked',
                'blocked_by' => $authId,
            ]);
        }

    }

            #[Computed]
       public function users(): Collection
    {
        $authId = Auth::id();
        $query = User::query();

        switch ($this->activeTab) {
            case 'sent':
                $sentRequestUserIds = Friendship::where('user_id', $authId)->where('status', 'pending')->pluck('friend_id');
                $query->whereIn('id', $sentRequestUserIds);
                break;

            case 'received':
                $receivedRequestUserIds = Friendship::where('friend_id', $authId)->where('status', 'pending')->pluck('user_id');
                $query->whereIn('id', $receivedRequestUserIds);
                break;

            case 'blocked':
                $blockedUserIds = Friendship::where('status', 'blocked')
                    ->where('blocked_by', $authId)
                    ->get()
                    ->map(fn($friendship) => $friendship->user_id == $authId ? $friendship->friend_id : $friendship->user_id);
                $query->whereIn('id', $blockedUserIds);
                break;

            case 'all':
            default:
                $query->where('id', '!=', $authId);
                break;
        }

        // Apply search to the selected tab's query
        return $query->when($this->search, function ($q) {
            $q->where('name', 'like', '%' . $this->search . '%');
        })->get();
    }

       public function unblockUser(int $userId)
    {
        $authId = Auth::id();

        $friendship = Friendship::where('status', 'blocked')
            // ✅ THIS IS THE SECURITY FIX
            // It ONLY finds records where the current user is the blocker.
            ->where('blocked_by', $authId)
            ->where(function ($query) use ($authId, $userId) {
                $query->where(fn($q) => $q->where('user_id', $authId)->where('friend_id', $userId))
                      ->orWhere(fn($q) => $q->where('user_id', $userId)->where('friend_id', $authId));
            })
            ->first();

        // If a valid record is found, delete it.
        // A blocked user will never find a record, so this will fail for them.
        if ($friendship) {
            $friendship->delete();
        }
    }
}

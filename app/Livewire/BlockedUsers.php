<?php

namespace App\Livewire;

use App\Models\Friendship;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class BlockedUsers extends Component
{
   public function unblockUser(int $friendshipId) {
        $friendship = Friendship::where('id', $friendshipId)
            ->where('blocked_by', Auth::id())
            ->first();
        if ($friendship) {
            $friendship->delete(); // Or update status to 'rejected'
        }
    }
    public function render() {
        $blockedFriendships = Friendship::where('status', 'blocked')
            ->where('blocked_by', Auth::id())
            ->with(['user', 'friend'])
            ->get();
        return view('livewire.blocked-users', ['blockedFriendships' => $blockedFriendships]);
    }
}

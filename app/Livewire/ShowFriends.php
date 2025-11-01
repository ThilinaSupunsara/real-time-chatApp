<?php

namespace App\Livewire;
use App\Models\User;

use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\Attributes\Layout;
#[Layout('layouts.app')]
class ShowFriends extends Component
{
    public function render()
    {
        $friends = Auth::user()->getFriends();
        return view('livewire.show-friends', [
            'friends' => $friends
        ]);
    }
}

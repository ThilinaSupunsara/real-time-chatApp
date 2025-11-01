<!--
  Redesigned "Find Friends" Component
  - Fully mobile-responsive (tabs, cards, and buttons)
  - Modern "segmented control" tabs
  - Icons added to all buttons and status badges for clarity
  - All Livewire logic (wire:click, variables, etc.) is 100% unchanged.
-->
<div class="bg-gray-100 dark:bg-gray-900 min-h-screen py-8">
  <div class="max-w-4xl mx-auto p-4 sm:p-6 lg:p-8 bg-white dark:bg-gray-800 rounded-2xl shadow-lg">
    <h2 class="text-2xl lg:text-3xl font-bold tracking-tight text-gray-900 dark:text-gray-100 mb-6">Find New Friends</h2>

    <!-- ✅ Modern, Mobile-Responsive Segmented Control Tabs -->
    <div class="mb-6">
      <nav class="pb-1 overflow-x-auto" aria-label="Tabs">
        <div class="flex space-x-2 bg-gray-100 dark:bg-gray-900 p-1 rounded-lg min-w-max">
          <button wire:click="$set('activeTab', 'all')"
                  class="whitespace-nowrap flex-1 text-center py-2 px-4 text-sm font-semibold rounded-md transition-all duration-150
                         {{ $activeTab === 'all' ? 'bg-white dark:bg-gray-700 text-indigo-600 dark:text-indigo-400 shadow-sm' : 'text-gray-600 dark:text-gray-400 hover:bg-gray-200/60 dark:hover:bg-gray-700/60' }}">
            All Users
          </button>
          <button wire:click="$set('activeTab', 'sent')"
                  class="whitespace-nowrap flex-1 text-center py-2 px-4 text-sm font-semibold rounded-md transition-all duration-150
                         {{ $activeTab === 'sent' ? 'border-blue-500 text-blue-600' : 'text-gray-600 dark:text-gray-400 hover:bg-gray-200/60 dark:hover:bg-gray-700/60' }}">
            Sent Requests
          </button>
          <button wire:click="$set('activeTab', 'received')"
                  class="whitespace-nowrap flex-1 text-center py-2 px-4 text-sm font-semibold rounded-md transition-all duration-150
                         {{ $activeTab === 'received' ? 'border-blue-500 text-blue-600' : 'text-gray-600 dark:text-gray-400 hover:bg-gray-200/60 dark:hover:bg-gray-700/60' }}">
            Received Requests
          </button>
          <button wire:click="$set('activeTab', 'blocked')"
                  class="whitespace-nowrap flex-1 text-center py-2 px-4 text-sm font-semibold rounded-md transition-all duration-150
                         {{ $activeTab === 'blocked' ? 'border-blue-500 text-blue-600' : 'text-gray-600 dark:text-gray-400 hover:bg-gray-200/60 dark:hover:bg-gray-700/60' }}">
            Blocked Users
          </button>
        </div>
      </nav>
    </div>

    <!-- ✅ Modern Search Bar with Icon -->
    <div class="mb-6">
      <div class="relative">
        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
          <svg class="h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
            <path fill-rule="evenodd" d="M9 3.5a5.5 5.5 0 100 11 5.5 5.5 0 000-11zM2 9a7 7 0 1112.452 4.391l3.328 3.329a.75.75 0 11-1.06 1.06l-3.329-3.328A7 7 0 012 9z" clip-rule="evenodd" />
          </svg>
        </div>
        <input
            wire:model.live.debounce.300ms="search"
            type="text"
            placeholder="Search for users by name..."
            class="w-full pl-10 pr-4 py-2 text-gray-900 dark:text-gray-100 bg-gray-50 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"
        >
      </div>
    </div>

    <!-- ✅ Redesigned User Card List -->
    <ul class="space-y-4">
      @forelse($this->users as $user)
        @php
            $friendship = auth()->user()->friendshipWith($user);
            $status = $friendship->status ?? null;
        @endphp
        <!-- ✅ Responsive User Card -->
        <li class="flex flex-col sm:flex-row sm:items-center sm:justify-between p-4 border border-gray-200 dark:border-gray-700 rounded-lg transition-shadow duration-150 hover:shadow-md">
          <div class="flex items-center space-x-4">
            @if ($user->profile_photo_path)
              <img class="h-10 w-10 rounded-full object-cover" src="{{ asset('storage/' . $user->profile_photo_path) }}" alt="{{ $user->name }}">
            @else
              <div class="h-10 w-10 rounded-full bg-gray-200 dark:bg-gray-600 flex items-center justify-center flex-shrink-0">
                <span class="text-lg font-bold text-gray-500 dark:text-gray-300">{{ substr($user->name, 0, 1) }}</span>
              </div>
            @endif
            <span class="font-semibold text-gray-900 dark:text-gray-100">{{ $user->name }}</span>
          </div>

          <!-- ✅ Responsive Button Container (stacks on mobile) -->
          <div class="flex flex-col sm:flex-row sm:items-center space-y-2 sm:space-y-0 sm:space-x-2 mt-4 sm:mt-0 sm:ml-4 flex-shrink-0">
            @if ($status === 'pending')
              @if ($friendship->user_id === auth()->id())
                <button wire:click="cancelRequest({{ $friendship->id }})" class="w-full sm:w-44 inline-flex items-center justify-center px-4 py-2 text-sm font-medium text-white bg-red-600 rounded-md hover:bg-red-700 transition-colors duration-150 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800">
                  <svg class="w-5 h-5 mr-1.5 -ml-1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.28 7.22a.75.75 0 00-1.06 1.06L8.94 10l-1.72 1.72a.75.75 0 101.06 1.06L10 11.06l1.72 1.72a.75.75 0 101.06-1.06L11.06 10l1.72-1.72a.75.75 0 00-1.06-1.06L10 8.94 8.28 7.22z" clip-rule="evenodd" /></svg>
                  Cancel Request
                </button>
              @else
                <button wire:click="acceptRequest({{ $friendship->id }})" class="w-full sm:w-44 inline-flex items-center justify-center px-4 py-2 text-sm font-medium text-white bg-green-600 rounded-md hover:bg-green-700 transition-colors duration-150 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800">
                  <svg class="w-5 h-5 mr-1.5 -ml-1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.857-9.809a.75.75 0 00-1.214-.882l-3.483 4.79-1.88-1.88a.75.75 0 10-1.06 1.061l2.5 2.5a.75.75 0 001.137-.089l4-5.5z" clip-rule="evenodd" /></svg>
                  Accept Request
                </button>
              @endif
            @elseif ($status === 'accepted')
              <span class="w-full sm:w-44 inline-flex items-center justify-center px-4 py-2 text-sm font-medium text-green-800 bg-green-100 dark:text-green-100 dark:bg-green-800/30 rounded-md">
                <svg class="w-5 h-5 mr-1.5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"><path d="M10 8a3 3 0 100-6 3 3 0 000 6zM3.465 14.493a1.25 1.25 0 00-2.415 1.022A11.95 11.95 0 0010 18c4.51 0 8.536-2.52 10.125-6.241a1.25 1.25 0 00-2.415-1.022A9.45 9.45 0 0110 16c-3.51 0-6.63-1.66-8.535-4.237z" /></svg>
                Friends
              </span>
            @elseif ($status === 'blocked')
              @if ($friendship->blocked_by === auth()->id())
                <button wire:click="unblockUser({{ $user->id }})" class="w-full sm:w-44 inline-flex items-center justify-center px-4 py-2 text-sm font-medium text-white bg-blue-600 rounded-md hover:bg-blue-700 transition-colors duration-150 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800">
                  <svg class="w-5 h-5 mr-1.5 -ml-1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"><path d="M13.477 14.89A6 6 0 015.11 6.524l8.367 8.367zM18 10a8 8 0 11-16 0 8 8 0 0116 0z" /></svg>
                  Unblock
                </button>
              @else
                <span class="w-full sm:w-auto inline-flex items-center justify-center px-4 py-2 text-sm font-medium text-red-800 bg-red-100 dark:text-red-100 dark:bg-red-800/30 rounded-md">
                  <svg class="w-5 h-5 mr-1.5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM4.3 4.3a.75.75 0 00-1.06 1.06L10 11.06l-4.7-4.7zM11.06 10l4.7 4.7a.75.75 0 101.06-1.06L11.06 10z" clip-rule="evenodd" /></svg>
                  Blocked by User
                </span>
              @endif
            @else
              <button wire:click="addFriend({{ $user->id }})" class="w-full sm:w-44 inline-flex items-center justify-center px-4 py-2 text-sm font-medium text-white bg-indigo-600 rounded-md hover:bg-indigo-700 transition-colors duration-150 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800">
                <svg class="w-5 h-5 mr-1.5 -ml-1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"><path d="M11 5a3 3 0 11-6 0 3 3 0 016 0zM14 7a4 4 0 11-8 0 4 4 0 018 0zM16 12a1 1 0 100 2h-5.5a1 1 0 100 2H16a1 1 0 100 2h-5.5a1 1 0 100 2H16a3 3 0 100-6h-1.5zM9 12a1 1 0 100 2H3.5a1 1 0 100 2H9a1 1 0 100 2H3.5a1 1 0 100 2H9a3 3 0 100-6H7.5z" /></svg>
                Add Friend
              </button>
            @endif

            @if ($status !== 'blocked')
              <button wire:click="blockUser({{ $user->id }})" class="w-full sm:w-44 inline-flex items-center justify-center px-4 py-2 text-sm font-medium text-gray-700 dark:text-gray-200 bg-gray-200 dark:bg-gray-700 rounded-md hover:bg-gray-300 dark:hover:bg-gray-600 transition-colors duration-150 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800">
                <svg class="w-5 h-5 mr-1.5 -ml-1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM4.3 4.3a.75.75 0 00-1.06 1.06L10 11.06l-4.7-4.7zM11.06 10l4.7 4.7a.75.75 0 101.06-1.06L11.06 10z" clip-rule="evenodd" /></svg>
Block
              </button>
            @endif
          </div>
        </li>
      @empty
        <!-- ✅ Enhanced Empty State -->
        <li class="text-center p-12">
          <svg class="mx-auto h-12 w-12 text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" d="M18 18.72a9.094 9.094 0 003.741-.479 3 3 0 00-4.682-2.72m-4.682 2.72a.75.75 0 01-.727 0l-4.744-2.106a.75.75 0 01.54-1.332l4.744 2.106a.75.75 0 01.187 0zM11.25 5.25v10.5a.75.75 0 01-1.5 0V5.25a.75.75 0 011.5 0z" />
            <path stroke-linecap="round" stroke-linejoin="round" d="M12.75 15l3-3m0 0l-3-3m3 3h-7.5M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
          </svg>
          <h3 class="mt-2 text-sm font-medium text-gray-900 dark:text-gray-100">No Users Found</h3>
          <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">No users found matching your criteria.</p>
        </li>
      @endforelse
    </ul>
  </div>
</div>

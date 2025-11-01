<div class="bg-gray-100 dark:bg-gray-900 py-8 min-h-screen">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        {{-- Page Header --}}
        <header class="mb-8">
            <h1 class="text-3xl font-bold tracking-tight text-gray-900 dark:text-white">
                Welcome back, {{ auth()->user()->name }}!
            </h1>
            <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                Here's a summary of your activity.
            </p>
        </header>

        {{-- Stats Cards --}}
        <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-3">
            <div class="overflow-hidden rounded-2xl bg-white dark:bg-gray-800 shadow-lg">
                <div class="p-6">
                    <div class="flex items-center space-x-4">
                        <div class="flex-shrink-0 h-12 w-12 rounded-full bg-indigo-100 dark:bg-indigo-800/30 flex items-center justify-center">
                            <svg class="h-6 w-6 text-indigo-600 dark:text-indigo-400" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A1.75 1.75 0 0117.748 22H6.252a1.75 1.75 0 01-1.75-1.882z" /></svg>
                        </div>
                        <div class="min-w-0 flex-1">
                            <dl>
                                <dt class="truncate text-sm font-medium text-gray-500 dark:text-gray-400">Total Friends</dt>
                                <dd><div class="text-3xl font-bold text-gray-900 dark:text-white">{{ $this->totalFriends }}</div></dd>
                            </dl>
                        </div>
                    </div>
                </div>
                <div class="bg-gray-50 dark:bg-gray-900/50 px-6 py-4">
                    <div class="text-sm">
                        <a href="{{ route('friends') }}" wire:navigate class="font-medium text-indigo-600 dark:text-indigo-400 hover:text-indigo-500 inline-flex items-center">
                            View all
                            <svg class="ml-1 h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M3 10a.75.75 0 01.75-.75h10.638L10.23 5.29a.75.75 0 111.04-1.08l5.5 5.25a.75.75 0 010 1.08l-5.5 5.25a.75.75 0 11-1.04-1.08l4.158-3.96H3.75A.75.75 0 013 10z" clip-rule="evenodd" /></svg>
                        </a>
                    </div>
                </div>
            </div>

            <div class="overflow-hidden rounded-2xl bg-white dark:bg-gray-800 shadow-lg">
                <div class="p-6">
                    <div class="flex items-center space-x-4">
                        <div class="flex-shrink-0 h-12 w-12 rounded-full bg-indigo-100 dark:bg-indigo-800/30 flex items-center justify-center">
                            <svg class="h-6 w-6 text-indigo-600 dark:text-indigo-400" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M21.75 6.75v10.5a2.25 2.25 0 01-2.25 2.25h-15a2.25 2.25 0 01-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25m19.5 0v.243a2.25 2.25 0 01-1.07 1.916l-7.5 4.615a2.25 2.25 0 01-2.36 0L3.32 8.91a2.25 2.25 0 01-1.07-1.916V6.75" /></svg>
                        </div>
                        <div class="min-w-0 flex-1">
                            <dl>
                                <dt class="truncate text-sm font-medium text-gray-500 dark:text-gray-400">Unread Messages</dt>
                                <dd><div class="text-3xl font-bold text-gray-900 dark:text-white">{{ $this->unreadMessagesCount }}</div></dd>
                            </dl>
                        </div>
                    </div>
                </div>
                <div class="bg-gray-50 dark:bg-gray-900/50 px-6 py-4">
                    <div class="text-sm">
                        <a href="{{ route('chat') }}" wire:navigate class="font-medium text-indigo-600 dark:text-indigo-400 hover:text-indigo-500 inline-flex items-center">
                            Go to chat
                            <svg class="ml-1 h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M3 10a.75.75 0 01.75-.75h10.638L10.23 5.29a.75.75 0 111.04-1.08l5.5 5.25a.75.75 0 010 1.08l-5.5 5.25a.75.75 0 11-1.04-1.08l4.158-3.96H3.75A.75.75 0 013 10z" clip-rule="evenodd" /></svg>
                        </a>
                    </div>
                </div>
            </div>

            <div class="overflow-hidden rounded-2xl bg-white dark:bg-gray-800 shadow-lg">
                <div class="p-6">
                    <div class="flex items-center space-x-4">
                        <div class="flex-shrink-0 h-12 w-12 rounded-full bg-indigo-100 dark:bg-indigo-800/30 flex items-center justify-center">
                            <svg class="h-6 w-6 text-indigo-600 dark:text-indigo-400" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M19 7.5v3m0 0v3m0-3h3m-3 0h-3m-2.25-4.125a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zM4 19.235v-.11a6.375 6.375 0 0112 0v.11A1.875 1.875 0 0113.875 21H6.125A1.875 1.875 0 014 19.235z" /></svg>
                        </div>
                        <div class="min-w-0 flex-1">
                            <dl>
                                <dt class="truncate text-sm font-medium text-gray-500 dark:text-gray-400">Friend Requests</dt>
                                <dd><div class="text-3xl font-bold text-gray-900 dark:text-white">{{ $this->pendingRequestsCount }}</div></dd>
                            </dl>
                        </div>
                    </div>
                </div>
                <div class="bg-gray-50 dark:bg-gray-900/50 px-6 py-4">
                    <div class="text-sm">
                        <a href="{{ route('users', ['activeTab' => 'received']) }}" wire:navigate class="font-medium text-indigo-600 dark:text-indigo-400 hover:text-indigo-500 inline-flex items-center">
                            View requests
                            <svg class="ml-1 h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M3 10a.75.75 0 01.75-.75h10.638L10.23 5.29a.75.75 0 111.04-1.08l5.5 5.25a.75.75 0 010 1.08l-5.5 5.25a.75.75 0 11-1.04-1.08l4.158-3.96H3.75A.75.75 0 013 10z" clip-rule="evenodd" /></svg>
                        </a>
                    </div>
                </div>
            </div>
        </div>

        {{-- Main Content Grid --}}
        <div class="mt-8 grid grid-cols-1 gap-8 lg:grid-cols-3">
            <div class="lg:col-span-2">
                <h3 class="text-2xl font-bold tracking-tight text-gray-900 dark:text-white">Recent Conversations</h3>
                <div class="mt-4 overflow-hidden rounded-2xl bg-white dark:bg-gray-800 shadow-lg">
                    <ul class="divide-y divide-gray-200 dark:divide-gray-700">
                        @forelse ($this->recentConversations as $convo)
                            @php $recipient = $convo->users->first(); @endphp
                            @if ($recipient)
                                <li class="hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors">
                                    <a href="{{ route('chat') }}" wire:navigate class="flex items-center space-x-4 p-4 sm:p-6">
                                        <div class="flex-shrink-0">
                                            @if ($recipient->profile_photo_path)
                                                <img class="h-10 w-10 rounded-full object-cover" src="{{ asset('storage/' . $recipient->profile_photo_path) }}" alt="{{ $recipient->name }}">
                                            @else
                                                <div class="h-10 w-10 rounded-full bg-gray-200 dark:bg-gray-700 flex items-center justify-center"><span class="font-bold text-gray-500">{{ substr($recipient->name, 0, 1) }}</span></div>
                                            @endif
                                        </div>
                                        <div class="min-w-0 flex-1">
                                            <p class="truncate text-sm font-semibold text-gray-900 dark:text-white">{{ $recipient->name }}</p>
                                            <p class="truncate text-sm text-gray-500 dark:text-gray-400">{{ $convo->messages->first()?->body ?: '...' }}</p>
                                        </div>
                                        <div class="flex-shrink-0 text-sm text-gray-400">{{ $convo->messages->first()?->created_at->diffForHumans() }}</div>
                                    </a>
                                </li>
                            @endif
                        @empty
                            <li class="p-12 text-center">
                                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                  <path stroke-linecap="round" stroke-linejoin="round" d="M20.25 8.511c.884.284 1.5 1.128 1.5 2.097v4.286c0 1.136-.847 2.1-1.98 2.193l-3.72.372a11.25 11.25 0 01-5.58 0l-3.72-.372C3.347 17.1 2.5 16.136 2.5 15v-4.286c0-.97 0.616-1.813 1.5-2.097l6.75-2.25a.75.75 0 01.5 0l6.75 2.25z" />
                                  <path stroke-linecap="round" stroke-linejoin="round" d="M6.25 10.5h11.5c.414 0 .75.336.75.75v3c0 .414-.336.75-.75.75H6.25a.75.75 0 01-.75-.75v-3c0-.414.336-.75.75-.75z" />
                                </svg>
                                <h3 class="mt-2 text-sm font-semibold text-gray-900 dark:text-white">No recent conversations</h3>
                                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Start a new chat to see it here.</p>
                            </li>
                        @endforelse
                    </ul>
                </div>
            </div>

            <div class="lg:col-span-1">
                <h3 class="text-2xl font-bold tracking-tight text-gray-900 dark:text-white">Pending Friend Requests</h3>
                <div class="mt-4 overflow-hidden rounded-2xl bg-white dark:bg-gray-800 shadow-lg">
                    <ul class="divide-y divide-gray-200 dark:divide-gray-700">
                        @forelse ($this->friendRequests as $request)
                            <li class="p-4 sm:p-6">
                                <div class="flex items-center space-x-4">
                                    <div class="flex-shrink-0">
                                        @if ($request->sender->profile_photo_path)
                                            <img class="h-10 w-10 rounded-full object-cover" src="{{ asset('storage/' . $request->sender->profile_photo_path) }}" alt="{{ $request->sender->name }}">
                                        @else
                                            <div class="h-10 w-10 rounded-full bg-gray-200 dark:bg-gray-700 flex items-center justify-center"><span class="font-bold text-gray-500">{{ substr($request->sender->name, 0, 1) }}</span></div>
                                        @endif
                                    </div>
                                    <div class="min-w-0 flex-1">
                                        <p class="truncate text-sm font-semibold text-gray-900 dark:text-white">{{ $request->sender->name }}</p>
                                        <p class="truncate text-sm text-gray-500 dark:text-gray-400">Sent you a request</p>
                                    </div>
                                </div>
                                <div class="mt-4 flex space-x-3">
                                    <button wire:click="acceptRequest({{ $request->id }})" class="flex-1 inline-flex items-center justify-center gap-x-1.5 rounded-md bg-green-600 px-2.5 py-1.5 text-sm font-semibold text-white shadow-sm hover:bg-green-500 transition-colors">
                                        <svg class="h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M16.704 4.153a.75.75 0 01.143 1.052l-8 10.5a.75.75 0 01-1.127.075l-4.5-4.5a.75.75 0 011.06-1.06l3.894 3.893 7.48-9.817a.75.75 0 011.05-.143z" clip-rule="evenodd" /></svg>
                                        Accept
                                    </button>
                                    <button wire:click="rejectRequest({{ $request->id }})" class="flex-1 inline-flex items-center justify-center gap-x-1.5 rounded-md bg-white dark:bg-gray-700 px-2.5 py-1.5 text-sm font-semibold text-gray-900 dark:text-white shadow-sm ring-1 ring-inset ring-gray-300 dark:ring-gray-600 hover:bg-gray-50 dark:hover:bg-gray-600 transition-colors">
                                        <svg class="h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"><path d="M6.28 5.22a.75.75 0 00-1.06 1.06L8.94 10l-3.72 3.72a.75.75 0 101.06 1.06L10 11.06l3.72 3.72a.75.75 0 101.06-1.06L11.06 10l3.72-3.72a.75.75 0 00-1.06-1.06L10 8.94 6.28 5.22z" /></svg>
                                        Reject
                                    </button>
                                </div>
                            </li>
                        @empty
                            <li class="p-12 text-center">
                                <svg class="mx-auto h-12 w-12 text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                  <path stroke-linecap="round" stroke-linejoin="round" d="M19 7.5v3m0 0v3m0-3h3m-3 0h-3m-2.25-4.125a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zM4 19.235v-.11a6.375 6.375 0 0112 0v.11A1.875 1.875 0 0113.875 21H6.125A1.875 1.875 0 014 19.235z" />
                                </svg>
                                <h3 class="mt-2 text-sm font-semibold text-gray-900 dark:text-white">No pending requests</h3>
                                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">You're all caught up!</p>
                            </li>
                        @endforelse
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

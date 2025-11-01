<div class="bg-gray-100 dark:bg-gray-900 min-h-screen py-8">
  <div class="max-w-4xl mx-auto p-4 sm:p-6 lg:p-8 bg-white dark:bg-gray-800 rounded-2xl shadow-lg">
    <h2 class="text-2xl lg:text-3xl font-bold tracking-tight text-gray-900 dark:text-gray-100 mb-6">Your Friends</h2>

    <div class="flow-root">
      <ul class="space-y-4">
        @forelse($friends as $friend)
          <li class="flex flex-col sm:flex-row sm:items-center sm:justify-between p-4 border border-gray-200 dark:border-gray-700 rounded-lg transition-shadow duration-150 hover:shadow-md">
            <div class="flex items-center space-x-4">
              @if ($friend->profile_photo_path)
                <img class="h-10 w-10 rounded-full object-cover" src="{{ asset('storage/' . $friend->profile_photo_path) }}" alt="{{ $friend->name }}">
              @else
                <div class="h-10 w-10 rounded-full bg-gray-200 dark:bg-gray-600 flex items-center justify-center flex-shrink-0">
                  <span class="text-lg font-bold text-gray-500 dark:text-gray-300">{{ substr($friend->name, 0, 1) }}</span>
                </div>
              @endif
              <span class="font-semibold text-gray-900 dark:text-gray-100">{{ $friend->name }}</span>
            </div>

            <div class="mt-4 sm:mt-0 sm:ml-4 flex-shrink-0">
              <a href="{{ route('chat') }}"
                 wire:navigate
                 class="w-full sm:w-auto inline-flex items-center justify-center px-4 py-2 text-sm font-medium text-white bg-indigo-600 rounded-md hover:bg-indigo-700 transition-colors duration-150 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800">
                <svg class="w-5 h-5 mr-1.5 -ml-1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                  <path fill-rule="evenodd" d="M10 2a8 8 0 100 16 8 8 0 000-16zM5 7a1 1 0 011-1h8a1 1 0 110 2H6a1 1 0 01-1-1zm1 3a1 1 0 100 2h6a1 1 0 100-2H6z" clip-rule="evenodd" />
                </svg>
                Chat
              </a>
            </div>
          </li>
        @empty
          <li class="text-center p-12 border border-gray-200 dark:border-gray-700 border-dashed rounded-lg">
            <svg class="mx-auto h-12 w-12 text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" d="M18 18.72a9.094 9.094 0 003.741-.479 3 3 0 00-4.682-2.72m-4.682 2.72a.75.75 0 01-.727 0l-4.744-2.106a.75.75 0 01.54-1.332l4.744 2.106a.75.75 0 01.187 0zM11.25 5.25v10.5a.75.75 0 01-1.5 0V5.25a.75.75 0 011.5 0z" />
              <path stroke-linecap="round" stroke-linejoin="round" d="M15 12l3-3m0 0l-3-3m3 3H7.5" />
            </svg>
            <h3 class="mt-2 text-sm font-medium text-gray-900 dark:text-gray-100">No Friends Yet</h3>
            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">You don't have any friends yet. Go to the 'Users' page to add some!</p>
          </li>
        @endforelse
      </ul>
    </div>
  </div>
</div>

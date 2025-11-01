<x-app-layout>
   <div class="bg-gray-100 dark:bg-gray-900 min-h-screen py-8">
  <div class="max-w-3xl mx-auto p-4 sm:p-6 lg:p-8 bg-white dark:bg-gray-800 rounded-2xl shadow-lg">
    <h2 class="text-2xl lg:text-3xl font-bold tracking-tight text-gray-900 dark:text-gray-100 mb-6">Update Profile Information</h2>

    <div class="py-12 dark:bg-gray-800" >
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            <div class="max-w-xl">
                <livewire:profile.update-profile-information-form />
            </div>

            <div class="max-w-xl">
                <livewire:profile.update-password-form />
            </div>

            <div class="max-w-xl">
                <livewire:profile.delete-user-form />
            </div>

        </div>
    </div>

    </div>
  </div>
</x-app-layout>


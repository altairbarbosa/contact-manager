<x-app-layout>
    <div class="py-12">
        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 text-gray-900 dark:text-gray-100">
                <h3 class="text-2xl font-semibold mb-6">Contact Details: {{ $contact->name }}</h3>

                <div class="mb-4">
                    <p class="text-gray-700 dark:text-gray-300"><strong class="text-gray-900 dark:text-gray-200">Name:</strong> {{ $contact->name }}</p>
                </div>
                <div class="mb-4">
                    <p class="text-gray-700 dark:text-gray-300"><strong class="text-gray-900 dark:text-gray-200">Contact:</strong> {{ $contact->contact }}</p>
                </div>
                <div class="mb-4">
                    <p class="text-gray-700 dark:text-gray-300"><strong class="text-gray-900 dark:text-gray-200">Email:</strong> {{ $contact->email }}</p>
                </div>
                <div class="mb-4">
                    <p class="text-gray-700 dark:text-gray-300"><strong class="text-gray-900 dark:text-gray-200">Created At:</strong> {{ $contact->created_at->format('M d, Y H:i') }}</p>
                </div>
                <div class="mb-4">
                    <p class="text-gray-700 dark:text-gray-300"><strong class="text-gray-900 dark:text-gray-200">Last Updated:</strong> {{ $contact->updated_at->format('M d, Y H:i') }}</p>
                </div>

                <div class="mt-6 flex items-center">
                    <a href="{{ route('contacts.index') }}" class="inline-flex items-center px-4 py-2 border border-transparent rounded-md font-semibold text-xs text-gray-700 dark:text-gray-300 uppercase tracking-widest hover:bg-gray-50 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150 mr-4">
                        Back to List
                    </a>
                    @auth
                    <a href="{{ route('contacts.edit', $contact->id) }}" class="inline-flex items-center px-4 py-2 bg-blue-600 dark:bg-blue-700 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 dark:hover:bg-blue-600 focus:bg-blue-700 active:bg-blue-900 dark:active:bg-blue-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150 mr-4">
                        Edit
                    </a>
                    <form action="{{ route('contacts.destroy', $contact->id) }}" method="POST" class="inline-block" onsubmit="return confirm('Are you sure you want to delete this contact? This action cannot be undone.');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="inline-flex items-center px-4 py-2 bg-red-600 dark:bg-red-700 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-700 dark:hover:bg-red-600 focus:bg-red-700 active:bg-red-900 dark:active:bg-red-900 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 transition ease-in-out duration-150">
                            Delete
                        </button>
                    </form>
                    @endauth
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

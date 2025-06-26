<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Contact Details: {{ $contact->name }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <div class="mb-4">
                        <p class="text-gray-700"><strong class="text-gray-900">Name:</strong> {{ $contact->name }}</p>
                    </div>
                    <div class="mb-4">
                        <p class="text-gray-700"><strong class="text-gray-900">Contact:</strong> {{ $contact->contact }}</p>
                    </div>
                    <div class="mb-4">
                        <p class="text-gray-700"><strong class="text-gray-900">Email:</strong> {{ $contact->email }}</p>
                    </div>
                    <div class="mb-4">
                        <p class="text-gray-700"><strong class="text-gray-900">Created At:</strong> {{ $contact->created_at->format('M d, Y H:i') }}</p>
                    </div>
                    <div class="mb-4">
                        <p class="text-gray-700"><strong class="text-gray-900">Last Updated:</strong> {{ $contact->updated_at->format('M d, Y H:i') }}</p>
                    </div>

                    <div class="mt-6 flex items-center">
                        <a href="{{ route('contacts.index') }}" class="inline-flex items-center px-4 py-2 border border-transparent rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150 mr-4">
                            Back to List
                        </a>
                        @auth
                        <a href="{{ route('contacts.edit', $contact->id) }}" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 focus:bg-blue-700 active:bg-blue-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150 mr-4">
                            Edit
                        </a>
                        <form action="{{ route('contacts.destroy', $contact->id) }}" method="POST" class="inline-block" onsubmit="return confirm('Are you sure you want to delete this contact? This action cannot be undone.');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="inline-flex items-center px-4 py-2 bg-red-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-700 focus:bg-red-700 active:bg-red-900 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                Delete
                            </button>
                        </form>
                        @endauth
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
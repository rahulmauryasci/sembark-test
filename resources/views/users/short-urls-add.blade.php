<x-app-layout>
    
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Generate Short URL') }}
            <span class="ml-4 text-sm text-gray-600">
                @if (auth()->user()->isAdmin())
                    You are logged in as an Admin!
                @elseif (auth()->user()->isSuperUser())
                    You are logged in as a Super User!
                @else
                    You are logged in as a Member!
                @endif
            </span> 

        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <div class="flex items-center justify-between mb-4">
                        <h1 class="text-lg font-semibold">Generate Short URL</h1>
                    </div>

                    <form action="{{ route('url.store') }}" method="POST">
                        @csrf

                        <div class="mb-4">
                            <label for="original_url" class="block text-sm font-medium text-gray-700">Original URL</label>
                            <input type="url" name="original_url" id="original_url" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm p-2" required>
                        </div>

                        <div>
                            <button type="submit" class="border px-4 py-2 bg-blue-600 rounded-md hover:bg-blue-700">Generate Short URL</button>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>

</x-app-layout>

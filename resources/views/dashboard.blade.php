<x-app-layout>

    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
            <span class="ml-4 text-sm text-gray-600">
                @if (auth()->user()->isAdmin())
                    You are logged in as an Admin!
                @elseif (auth()->user()->isSuperUser())
                    You are logged in as a Super User!
                @else
                    You are logged in as a Member!
                @endif

                @if (session('success'))
                    <span class="ml-4 text-sm text-green-600">{{ session('success') }}</span>
                @endif
            </span>

        </h2>
    </x-slot>

    {{-- Company Members Section --}}
    @if (auth()->user()->isAdmin() || auth()->user()->isSuperUser())
        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 bg-white border-b border-gray-200">
                        <div class="flex items-center justify-between mb-4">
                            <h1 class="text-lg font-semibold">@if (auth()->user()->isSuperUser()) Client`s @else Member @endif
                                <span class="text-sm text-gray-600"> Total: {{ $users->count() }} </span>
                            </h1>

                            {{-- Invite Member --}}
                            <a href="{{ route('users.invite') }}" class="inline-flex items-center px-3 py-1.5 bg-blue-600 rounded-md hover:bg-blue-700">
                                Invite {{ auth()->user()->isAdmin() ? 'Member' : 'Client' }}
                            </a>

                        </div>

                        {{-- Members List with Short URL Links --}}
                        @if ($users->isEmpty())
                            <p class="text-sm text-gray-500">No members found.</p>
                        @else
                            <ul class="divide-y divide-gray-200">

                                <li class="py-4 flex items-center justify-between">
                                    <div class="flex items-center space-x-4">
                                        <div class="text-sm font-medium text-gray-900">Name</div>
                                    </div>
                                    <div class="flex items-center space-x-4">
                                        <div class="text-sm text-gray-500">User`s</div>
                                    </div>
                                    <div class="text-sm text-gray-500">
                                        Total Generated Links
                                    </div>
                                    <div class="text-sm text-gray-500">
                                        Total Url Hit`s
                                    </div>
                                </li>

                                @foreach ($users as $user)
                                    <li class="py-4 flex items-center justify-between">
                                        <div class="flex items-center space-x-4">
                                            <div class="text-sm font-medium text-gray-900">{{ $user->name }} {{ $user->isSuperUser() ? '(Super-Admin)' : ($user->isAdmin() ? '(Admin)' : '(Member)') }}</div>
                                        </div>
                                        <div class="flex items-center space-x-4">
                                            <div class="text-sm text-gray-500">{{ $user->members->count()-1 }}</div>
                                        </div>
                                        <span class="text-xs inline-flex items-center px-2.5 py-0.5 rounded-full bg-gray-100 text-gray-800">
                                            {{ $user->shortUrls->count() }}
                                        </span>
                                        <span class="text-xs inline-flex items-center px-2.5 py-0.5 rounded-full bg-gray-100 text-gray-800">
                                            {{ $user->shortUrls->sum('clicks') }}
                                        </span>
                                    </li>
                                @endforeach
                            </ul>

                            <div class="mt-4 border-t pt-4">
                                {{ $users->appends(request()->query())->links() }}
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    @endif

    {{-- Short URLs Section --}}
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <div class="flex items-center justify-between mb-4">
                        <h1 class="text-lg font-semibold">Generated short Links</h1>
                        {{-- Generate Short URL Link --}}
                        <div class="flex items-center space-x-2">
                            <a href="{{ route('url.shorten') }}" class="inline-flex items-center px-3 py-1.5 bg-gray-200 rounded-md hover:bg-gray-300">
                                Generate Short Url Link
                            </a>
                        </div>
                    </div>

                    @if ($users->isEmpty() && $shortUrls->isEmpty())
                        <p class="text-sm text-gray-500">No short links found.</p>
                    @else
                        <ul class="divide-y divide-gray-200">

                            <li class="py-4 flex items-center justify-between">
                                <div class="flex items-center space-x-4">
                                    <div class="text-sm text-gray-500">Long Url Link</div>
                                </div>
                                <div class="flex items-center space-x-4">
                                    <div class="text-sm text-gray-500">Short Url Link</div>
                                </div>
                                <div class="text-sm text-gray-500">
                                   Hit`s
                                </div>
                                <div class="text-sm text-gray-500">
                                   Created By
                                </div>
                                <div class="text-sm text-gray-500">
                                    Created At
                                </div>
                            </li>

                            @foreach ($shortUrls as $url)
                                <li class="py-4 flex items-center justify-between">
                                    <div class="flex items-center space-x-4">
                                        <a href="{{ $url->original_url }}" class="text-sm font-medium text-gray-900">{{ $url->original_url }}</a>
                                    </div>
                                    <div class="flex items-center space-x-4">
                                        <a href="{{ route('short.url', $url->short_code) }}" class="text-sm font-medium text-gray-900">{{ $url->short_code }}</a>
                                    </div>
                                    <span class="text-xs inline-flex items-center px-2.5 py-0.5 rounded-full bg-gray-100 text-gray-800">
                                        {{ $url->clicks }}
                                    </span>
                                    <span class="text-xs inline-flex items-center px-2.5 py-0.5 rounded-full bg-gray-100 text-gray-800">
                                        {{ $url->user->name }}
                                    </span>
                                    <span class="text-xs inline-flex items-center px-2.5 py-0.5 rounded-full bg-gray-100 text-gray-800">
                                        {{ $url->created_at->format('M d, Y') }}
                                    </span>
                                </li>
                            @endforeach
                        </ul>

                        <div class="mt-4 border-t pt-4">
                            {{ $shortUrls->appends(request()->query())->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

</x-app-layout>

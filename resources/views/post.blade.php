<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Publish Your Posts') }}
        </h2>
    </x-slot>

    @if( $status == "connected")
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class=" flex items-center justify-between pb-6">
                <div>
                    <h2 class="text-gray-600 font-semibold">{{ __('Posts') }} </h2>
                    <span class="text-xs">{{ __('All Shared Posts') }} ({{ $postCount }})</span>
                </div>
                <div class="flex items-center justify-end">
                    @livewire('new-post')
                </div>
            </div>
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <div class="-mx-4 sm:-mx-8 px-4 sm:px-8 py-4 overflow-x-auto">
                        @livewire('show-post')
                    </div>
                </div>
            </div>
        </div>
    </div>
    @else
    <div class="w-full py-12 text-center">
        <p class="font-bold">
            Please connect your facebook to gain access to share content.
        </p>
        <a href="/connect" class="text-indigo-500 font-bold">Connect your facebook</a>
    </div>
    @endif
</x-app-layout>
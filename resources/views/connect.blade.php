<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Connect Your Facebook Account') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="mb-4">
                @if(isset($account) && $account->status == "connected")
                <span class="bg-green-500 px-4 py-2 rounded-md text-white font-semibold tracking-wide cursor-pointer">{{ __('Connected') }}</span>
                <a href="/connect/disconnect" class="bg-indigo-500 px-4 py-2 rounded-md text-white font-semibold tracking-wide cursor-pointer">{{ __('Disconnect') }}</a>
                @else
                <a href="/connect/facebook/redirect" class="bg-indigo-500 px-4 py-2 rounded-md text-white font-semibold tracking-wide cursor-pointer">{{ __('Connect Facebook') }}</a>
                @endif
            </div>

            @if(isset($account) && $account->status == "connected")
            <div class="px-6 py-4 bg-white">
                <p class="font-sans font-light">Name : <span class="text-gray-500">{{ $account->name }}</span> </p>
                <p class="mt-2 font-sans font-light ">Email : <span class="text-gray-500">{{ $account->email }}</span></p>
            </div>

            <div class="mt-4 bg-white pt-2 px-6">
                <span class="font-bold">Pages Related to your account</span>
            </div>

            <div class="w-full px-6 bg-white border-b border-gray-200">
                <div class="-mx-4 sm:-mx-8 px-4 sm:px-8 py-4 overflow-x-auto">
                    <div class="inline-block min-w-full shadow rounded-lg overflow-hidden">
                        <table class="min-w-full leading-normal">
                            <thead>
                                <tr>
                                    <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                        Title
                                    </th>
                                    <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                        Tasks
                                    </th>
                                    <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                        category
                                    </th>
                                </tr>
                            </thead>

                            <tbody>
                                @forelse($pages as $page)
                                <tr>
                                    <td class="px-5 py-5 border-b border-gray-100 bg-white text-sm">
                                        <p class="text-gray-900 whitespace-no-wrap">{{ $page["name"] }}</p>
                                    </td>
                                    <td class="px-5 py-5 border-b border-gray-100 bg-white text-sm">
                                        <p class="text-gray-900 whitespace-no-wrap">{{ $page["tasks"] }}</p>
                                    </td>
                                    <td class="px-5 py-5 border-b border-gray-100 bg-white text-sm">
                                        <p class="text-gray-900 whitespace-no-wrap">{{ $page["category"] }}</p>
                                    </td>
                                </tr>
                                @empty
                                <tr colspan="4">
                                    <td class="px-5 py-5 border-b border-gray-100 bg-white text-sm">
                                        <p class="text-gray-900 whitespace-no-wrap">
                                            You don't have access to any page
                                        </p>
                                    </td>
                                </tr>
                                @endforelse

                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            @endif
        </div>
    </div>
</x-app-layout>
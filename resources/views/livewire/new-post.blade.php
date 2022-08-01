<div>
    <div class="ml-2 space-x-8">
        <button wire:click="toggleModal" class="bg-indigo-500 px-4 py-2 rounded-md text-white font-semibold tracking-wide cursor-pointer">{{ __('New Post') }}</button>
    </div>

    <div class="w-full h-full overflow-scroll overflow-x-hidden fixed top-0 left-0 bg-gray-500/50 py-2 @if(!$open) hidden @endif" id="newItemForm">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                <div class="flex items-center justify-center py-4 px-10">
                    <form class="w-full">

                        <div class="mb-2">
                            <label for="description" class="mb-1 block text-base font-medium text-[#07074D]">
                                {{ __('Description') }}
                            </label>
                            <textarea wire:model="description" rows="4" name="description" id="description" placeholder="{{ __('Type the item description')}}" class="w-full resize-none rounded-md border border-[#e0e0e0] bg-white py-3 px-6 text-base font-medium text-[#6B7280] outline-none focus:border-indigo-500 focus:shadow-md"></textarea>
                            @error('description') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>

                        <div class="mb-2">
                            <div class="mb-1 flex items-center space-x-2">
                                <i class="fa-solid fa-images text-2xl text-indigo-500"></i>
                                <label for="media" class="mb-1 block text-base font-medium text-[#07074D]">
                                    {{ __('Image URL') }}
                                </label>
                            </div>
                            <input wire:model="media" type="text" id="media" placeholder="{{ __('Enter a valid URL') }}" class="w-full resize-none rounded-md border border-[#e0e0e0] bg-white py-3 px-6 text-base font-medium text-[#6B7280] outline-none focus:border-indigo-500 focus:shadow-md">
                        </div>

                        <div class="flex items-center justify-center w-full mt-1">
                            @if ($media)
                            <img src="{{ $media }}" class="h-60">

                            @else
                            <label class="flex flex-col items-center justify-center w-full h-60 border-2 border-gray-300 border-dashed rounded-lg cursor-pointer bg-gray-50 dark:hover:bg-bray-800 dark:bg-gray-700 hover:bg-gray-100 dark:border-gray-600 dark:hover:border-gray-500 dark:hover:bg-gray-600">
                                <div class="flex flex-col items-center justify-center pt-5 pb-6">
                                    <svg class="w-10 h-10 mb-3 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path>
                                    </svg>
                                    <p class="mb-2 text-sm text-gray-500 dark:text-gray-400"><span class="font-semibold">Please insert link of</span> image or video to share</p>
                                    <p class="text-xs text-gray-500 dark:text-gray-400">PNG, JPG, JPEG, MP4 (MAX. 800x400px)</p>
                                </div>
                            </label>
                            @endif
                        </div>

                        <div class="w-full my-2">
                            <label for="type" class="mb-1 block text-base font-medium text-[#07074D]">
                                {{ __('Select page to post To') }}
                            </label>
                            <select wire:model="page" name="page" id="page" class="w-full rounded-md border border-[#e0e0e0] bg-white py-3 px-6 text-base font-medium text-[#6B7280] outline-none focus:border-indigo-500 focus:shadow-md">
                                <option value="default">Choose a page</option>
                                @foreach( $pages as $page)
                                <option value="{{ $page->id }}">{{ $page->name }}</option>
                                @endforeach
                            </select>
                            @error('page') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>

                        <div class="mt-2 flex justify-start space-x-2">
                            <button wire:click="publishNow" type="button" class="hover:shadow-form rounded-md bg-[#6A64F1] py-2 px-8 text-base font-semibold text-white outline-none">
                                {{ __('Publish now') }}
                            </button>
                            <button wire:click="toggleSchedule" type="button" class="hover:shadow-form rounded-md bg-green-500 py-2 px-8 text-base font-semibold text-white outline-none">
                                {{ __('Schedule') }}
                            </button>
                            <button wire:click="toggleModal" type="button" class="hover:shadow-form rounded-md bg-red-500 py-2 px-8 text-base font-semibold text-white outline-none" id="closeItemForm">
                                {{ __('Cancel') }}
                            </button>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="w-full h-full overflow-scroll overflow-x-hidden absolute top-0 left-0 bg-gray-500/50 py-2 @if(!$schedule) hidden @endif" id="newItemForm">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg py-6 px-4">
                <div class="w-full my-2">
                    <label for="scheduletime" class="mb-1 block text-base font-medium text-[#07074D]">
                        {{ __('Schedule Your Post') }}
                    </label>
                    <input wire:model="scheduleTime" type="datetime-local" value="{{ date('Y-m-d\TH:i', strtotime($scheduleMinTime)) }}" min="{{ date('Y-m-d\TH:i', strtotime($scheduleMinTime)) }}" id="scheduletime" name="scheduletime" class="w-full rounded-md border border-[#e0e0e0] bg-white py-3 px-6 text-base font-medium text-[#6B7280] outline-none focus:border-indigo-500 focus:shadow-md">
                    @error('scheduleTime') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>

                <div class="mt-2 flex justify-start space-x-2">
                    <button wire:click="publishSchedule" type="button" class="hover:shadow-form rounded-md bg-green-500 py-2 px-8 text-base font-semibold text-white outline-none">
                        {{ __('Schedule') }}
                    </button>
                    <button wire:click="toggleSchedule" type="button" class="hover:shadow-form rounded-md bg-red-500 py-2 px-8 text-base font-semibold text-white outline-none" id="closeItemForm">
                        {{ __('Cancel') }}
                    </button>
                </div>
            </div>
        </div>
    </div>

    <div wire:loading wire:target="publishNow, publishSchedule">
        <div class="fixed z-30 top-0 left-0 w-full h-full bg-gray-500/50 flex items-center justify-center">
            <svg aria-hidden="true" class="mr-2 w-8 h-8 text-gray-200 animate-spin dark:text-gray-600 fill-indigo-500" viewBox="0 0 100 101" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M100 50.5908C100 78.2051 77.6142 100.591 50 100.591C22.3858 100.591 0 78.2051 0 50.5908C0 22.9766 22.3858 0.59082 50 0.59082C77.6142 0.59082 100 22.9766 100 50.5908ZM9.08144 50.5908C9.08144 73.1895 27.4013 91.5094 50 91.5094C72.5987 91.5094 90.9186 73.1895 90.9186 50.5908C90.9186 27.9921 72.5987 9.67226 50 9.67226C27.4013 9.67226 9.08144 27.9921 9.08144 50.5908Z" fill="currentColor" />
                <path d="M93.9676 39.0409C96.393 38.4038 97.8624 35.9116 97.0079 33.5539C95.2932 28.8227 92.871 24.3692 89.8167 20.348C85.8452 15.1192 80.8826 10.7238 75.2124 7.41289C69.5422 4.10194 63.2754 1.94025 56.7698 1.05124C51.7666 0.367541 46.6976 0.446843 41.7345 1.27873C39.2613 1.69328 37.813 4.19778 38.4501 6.62326C39.0873 9.04874 41.5694 10.4717 44.0505 10.1071C47.8511 9.54855 51.7191 9.52689 55.5402 10.0491C60.8642 10.7766 65.9928 12.5457 70.6331 15.2552C75.2735 17.9648 79.3347 21.5619 82.5849 25.841C84.9175 28.9121 86.7997 32.2913 88.1811 35.8758C89.083 38.2158 91.5421 39.6781 93.9676 39.0409Z" fill="currentFill" />
            </svg>
        </div>
    </div>
</div>
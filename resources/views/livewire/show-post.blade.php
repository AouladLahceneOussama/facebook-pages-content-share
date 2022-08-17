<div>

    <div class="w-full mb-4 flex justify-end">
        <div class="bg-gray-100 flex justify-start py-1 px-2 rounded-md">
            <div class="border-r border-gray-300 ">
                <button wire:click="chargePostsList('all')" class="px-2" type="button">All</button>
            </div>
            <div class="border-r border-gray-300 px-2">
                <button wire:click="chargePostsList('scheduled')" class="px-2" type="button">Scheduled</button>
            </div>
            <div class=" px-2">
                <button wire:click="chargePostsList('published')" class="px-2" type="button">Published</button>
            </div>
        </div>
    </div>

    @if (session()->has('errorDeletePost'))
    <div class="w-full bg-red-200 font-bold text-sm text-red-500 py-2 px-4 mb-2 rounded-md">
        {{ session('errorDeletePost') }}
    </div>
    @endif

    <div class="inline-block min-w-full shadow rounded-lg overflow-hidden">
        <table class="min-w-full leading-normal">
            <thead>
                <tr>
                    <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-center text-xs font-semibold text-gray-600 uppercase tracking-wider">
                        Page
                    </th>
                    <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-center text-xs font-semibold text-gray-600 uppercase tracking-wider">
                        Media
                    </th>
                    <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-center text-xs font-semibold text-gray-600 uppercase tracking-wider">
                        Description
                    </th>
                    <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-center text-xs font-semibold text-gray-600 uppercase tracking-wider">
                        Shared at
                    </th>
                    <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-center text-xs font-semibold text-gray-600 uppercase tracking-wider">
                        Type
                    </th>
                    <th class=" py-3 border-b-2 border-gray-200 bg-gray-100 text-center text-xs font-semibold text-gray-600 uppercase tracking-wider">
                        Actions
                    </th>
                </tr>
            </thead>
            <tbody>
                @forelse( $posts as $post)
                <tr>
                    <td class="px-5 py-5 border-b text-center border-gray-100 bg-white text-sm">
                        <p class="text-gray-900 whitespace-no-wrap">{{ $post->page->name }}</p>
                    </td>
                    <td class="px-5 py-5 border-b text-center border-gray-100 bg-white text-sm">
                        <div class="flex-shrink-0 w-10 h-10">
                            @if($post->media != '')
                            <img class="w-full h-full ml-4 rounded-full" src="{{ $post->media }}" alt="" />
                            @else
                            <div class="bg-gray-400 flex justify-center items-center rounded-full h-10 w-10 ml-4">
                                <i class="fa-solid fa-ban text-white"></i>
                            </div>
                            @endif
                        </div>
                    </td>
                    <td class="px-5 py-5 border-b text-center border-gray-100 bg-white text-sm">
                        <p class="text-gray-900 whitespace-no-wrap">{{ $post->description}}</p>
                    </td>
                    <td class="px-5 py-5 border-b text-center border-gray-100 bg-white text-sm">
                        <p class="text-gray-900 whitespace-no-wrap">{{ $post->share_date_time->diffForHumans() }}</p>
                    </td>
                    <td class="px-5 py-5 border-b text-center border-gray-100 bg-white text-sm">
                        <p class=" px-2 py-1 rounded-xl text-center @if($post->scheduled == 0) bg-green-300 text-gray-500 @else bg-gray-300 text-white @endif whitespace-no-wrap">{{ $post->scheduled == 0 ? "published" : "scheduled" }}</p>
                    </td>
                    <td class="py-5 border-b text-center  border-gray-100 bg-white text-sm">
                        @if($post->scheduled == 0)
                        <a href="https://www.facebook.com/{{ $post->post_page_id }}" target="_blank"><i class="fa-solid fa-eye text-indigo-500 p-1 cursor-pointer"></i></a>
                        @else
                        <i wire:click="publishNowSchedule({{ $post->id }})" class="fa-solid fa-share-from-square text-indigo-500 p-1 cursor-pointer"></i>
                        @endif
                        <i wire:click="openUpdatePost({{ $post->id }})" class="fa-solid fa-pen text-green-500 p-1 cursor-pointer"></i>
                        <i wire:click="deletePost({{ $post->id }})" class="fa-solid fa-trash text-red-500 p-1 cursor-pointer"></i>

                        @if($post->scheduled == 0)
                        <i wire:click="showComments({{ $post->id }})" class="fa-solid fa-comments text-blue-500 p-1 cursor-pointer"></i>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="text-center py-4">
                        No posts created
                    </td>
                </tr>
                @endforelse


            </tbody>
        </table>

        <!-- Update Form -->
        <div class="w-full h-full flex align-center justify-center fixed top-0 left-0 bg-gray-500/50 py-2 @if(!$open) hidden @endif">
            <div class="max-w-xl  m-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                    <div class="flex items-center justify-center py-4 px-10">

                        <form wire:submit.prevent="createItem" class="w-full">

                            <div class="mb-2">
                                <label for="description" class="mb-1 block text-base font-medium text-[#07074D]">
                                    {{ __('Description') }}
                                </label>
                                <textarea wire:model="description" rows="4" name="description" id="description" placeholder="{{ __('Type the item description')}}" class="w-full resize-none rounded-md border border-[#e0e0e0] bg-white py-3 px-6 text-base font-medium text-[#6B7280] outline-none focus:border-indigo-500 focus:shadow-md"></textarea>
                                @error('description') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>

                            <div class="flex items-center justify-center w-full mt-1">
                                @if ($media)
                                <img src="{{ $media }}" class="w-full h-60">
                                @endif
                            </div>

                            <div class="mt-4 flex justify-start space-x-2">
                                <button wire:click="updatePost" type="button" class="hover:shadow-form rounded-md bg-[#6A64F1] py-2 px-8 text-base font-semibold text-white outline-none">
                                    {{ __('Update') }}
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
        <!-- Update Form -->

        <!-- Comments manage -->
        <div class="w-full h-full flex align-center justify-center overflow-scroll overflow-x-hidden overflow-y-hidden fixed top-0 left-0 bg-gray-500/50 py-2 @if(!$openComments) hidden @endif">
            <div class="w-full m-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg py-4 px-10">
                    <div>
                        <h1 class="text-lg font-bold">Comments</h1>
                    </div>
                    <div>
                        <div class="h-96 overflow-y-auto">

                            @forelse($comments as $comment)
                            <div class="flex justify-between my-4 py-2 border-b border-gray-100" wire:key="'{{ $comment['id'] }}'">
                                <div>
                                    <p>from : {{ $comment['from']['name'] }}</p>
                                    <p>message : {{ $comment['message'] }}</p>
                                    <div class="flex space-x-2 mt-1">
                                        <i class="fa-solid fa-heart rounded-md bg-purple-500 px-2 text-white text-xs leading-10"> {{ $comment['reactions']}}</i>
                                        <button wire:click="replyOnComment('{{ $comment['id'] }}')" class="rounded-md @if($selectedReply == $comment['id']) bg-green-500 @endif bg-indigo-500 px-6 text-base font-semibold text-white outline-none">
                                            Reply
                                        </button>
                                        <button wire:click="deleteComment('{{ $comment['id'] }}')" class="rounded-md bg-red-400 px-6 text-base font-semibold text-white outline-none">
                                            Delete
                                        </button>
                                    </div>

                                </div>
                            </div>
                            @empty
                            <div>
                                No comments on that post
                            </div>
                            @endforelse
                        </div>

                        <div>
                            <label for="reply" class="mb-1 block text-base font-medium text-[#07074D]">Your reply</label>
                            <textarea wire:model="reply" name="" id="reply" cols="80" rows="2" placeholder="{{ __('Type your reply')}}" class="w-full resize-none rounded-md border border-[#e0e0e0] bg-white py-3 px-6 text-base font-medium text-[#6B7280] outline-none focus:border-indigo-500 focus:shadow-md"></textarea>
                            @error('reply') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    <div class="mt-4 flex justify-between align-center space-x-2">
                        <div>
                            <button wire:click="sendComment" class="hover:shadow-form rounded-md bg-indigo-500 py-2 px-8 text-base font-semibold text-white outline-none">
                                {{ __('Send') }}
                            </button>
                            <button wire:click="toggleComments" type="button" class="hover:shadow-form rounded-md bg-red-500 py-2 px-8 text-base font-semibold text-white outline-none">
                                {{ __('Cancel') }}
                            </button>
                        </div>
                        <div>
                            @if (session()->has('message'))
                            <div class="bg-green-300 text-green-500 py-1 px-4 rounded-md">
                                {{ session('message') }}
                            </div>
                            @endif
                        </div>

                    </div>
                </div>

            </div>
        </div>
        <!-- Comments manage -->

        <div wire:loading wire:target="deletePost, publishNowSchedule, chargePostsList, updatePost, showComments, sendComment, deleteComment">
            <div class="fixed z-30 top-0 left-0 w-full h-full bg-gray-500/50 flex items-center justify-center">
                <svg aria-hidden="true" class="mr-2 w-8 h-8 text-gray-200 animate-spin dark:text-gray-600 fill-indigo-500" viewBox="0 0 100 101" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M100 50.5908C100 78.2051 77.6142 100.591 50 100.591C22.3858 100.591 0 78.2051 0 50.5908C0 22.9766 22.3858 0.59082 50 0.59082C77.6142 0.59082 100 22.9766 100 50.5908ZM9.08144 50.5908C9.08144 73.1895 27.4013 91.5094 50 91.5094C72.5987 91.5094 90.9186 73.1895 90.9186 50.5908C90.9186 27.9921 72.5987 9.67226 50 9.67226C27.4013 9.67226 9.08144 27.9921 9.08144 50.5908Z" fill="currentColor" />
                    <path d="M93.9676 39.0409C96.393 38.4038 97.8624 35.9116 97.0079 33.5539C95.2932 28.8227 92.871 24.3692 89.8167 20.348C85.8452 15.1192 80.8826 10.7238 75.2124 7.41289C69.5422 4.10194 63.2754 1.94025 56.7698 1.05124C51.7666 0.367541 46.6976 0.446843 41.7345 1.27873C39.2613 1.69328 37.813 4.19778 38.4501 6.62326C39.0873 9.04874 41.5694 10.4717 44.0505 10.1071C47.8511 9.54855 51.7191 9.52689 55.5402 10.0491C60.8642 10.7766 65.9928 12.5457 70.6331 15.2552C75.2735 17.9648 79.3347 21.5619 82.5849 25.841C84.9175 28.9121 86.7997 32.2913 88.1811 35.8758C89.083 38.2158 91.5421 39.6781 93.9676 39.0409Z" fill="currentFill" />
                </svg>
            </div>
        </div>

    </div>
</div>
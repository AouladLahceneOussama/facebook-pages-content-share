<?php

namespace App\Http\Livewire;

use App\Models\Page;
use App\Models\Post;
use App\Models\User;
use App\Notifications\ShareNotify;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Livewire\Component;

class ShowPost extends Component
{
    protected $listeners = ['postCreated' => 'refreshListPosts'];
    protected $rules = [
        'reply' => 'required'
    ];

    public $posts;
    public $open = false;
    public $openComments = false;

    public $description;
    public $media;
    public $postId;
    public $type;

    public $pageId;
    public $comments = [];
    public $commentToReply = '';
    public $reply;
    public $selectedReply = '';

    public function chargePostsList($type)
    {
        if ($this->type != $type) {
            if ($type == "all") {
                $this->posts = Post::orderBy('share_date_time', 'desc')->get();
            }

            if ($type == "scheduled") {
                $this->posts = Post::where('scheduled', 1)->orderBy('share_date_time', 'desc')->get();
            }

            if ($type == "published") {
                $this->posts = Post::where('scheduled', 0)->orderBy('share_date_time', 'desc')->get();
            }
        }

        $this->type = $type;
    }

    public function updatePost()
    {
        $post = Post::find($this->postId);
        $pageDetail = $post->page;

        $res = Http::post("https://graph.facebook.com/v14.0/$post->post_page_id?message=$this->description&access_token=$pageDetail->access_token")->json();

        if (array_key_exists('success', $res) && $res['success']) {
            $post->update([
                'description' => $this->description,
            ]);

            $this->open = false;
            $this->emit('postCreated');
        }
    }

    public function openUpdatePost($id)
    {
        $this->open = true;

        $postDetail = Post::find($id);

        $this->postId = $id;
        $this->description = $postDetail->description;
        $this->media = $postDetail->media;
        $this->page = $postDetail->page_id;
    }

    public function sendNotification($post_page_id)
    {
        $sharedPost = [
            'url' => 'https://www.facebook.com/' . $post_page_id
        ];

        $user = User::find(Auth::id());
        $user->notify(new ShareNotify($sharedPost));
    }

    public function publishNowSchedule($id)
    {
        $postDetail = Post::find($id);
        $pageDetail = $postDetail->page;

        $res = Http::post("https://graph.facebook.com/$postDetail->post_page_id?is_published=true&access_token=$pageDetail->access_token")->json();

        if (array_key_exists('success', $res) && $res['success']) {
            $postDetail->update([
                'scheduled' => false,
                'share_date_time' => Carbon::now()->format('Y-m-d H:i')
            ]);

            $this->emit('postCreated');
            $this->sendNotification($postDetail->post_page_id);
        }
    }

    public function deletePost($id)
    {
        $postDetail = Post::find($id);
        $access_token = Page::find($postDetail->page_id)->access_token;

        $res = Http::delete("https://graph.facebook.com/$postDetail->post_page_id?access_token=$access_token")->json();

        if (array_key_exists('success', $res) && $res['success']) {
            $postDetail->delete();
            $this->emit('postCreated');
        } else {
            session()->flash('errorDeletePost', 'Error when deleting the post. Probably the post is deleted manually');
        }
    }

    public function showComments($id)
    {
        $this->openComments = true;
        $this->pageId = $id;
        $tmpComments = [];

        $postDetail = Post::find($id);
        $access_token = Page::find($postDetail->page_id)->access_token;

        $res = Http::get("https://graph.facebook.com/$postDetail->post_page_id/comments?access_token=$access_token")->json();
        if (count($res['data']) > 0) $tmpComments = $res['data'];

        for ($i = 0; $i < count($tmpComments); $i++) {
            $cmtId = $tmpComments[$i]['id'];

            $reactions = Http::get("https://graph.facebook.com/$cmtId/reactions?access_token=$access_token")->json();
            if (count($reactions['data']) >= 0) $tmpComments[$i]["reactions"] = count($reactions['data']);
        }

        $this->comments = $tmpComments;
    }

    public function replyOnComment($commentId)
    {
        $this->commentToReply = $commentId;
        $this->selectedReply = $commentId;
    }

    public function sendComment()
    {
        $this->validate();

        $postDetail = Post::find($this->pageId);
        $access_token = Page::find($postDetail->page_id)->access_token;

        if ($this->commentToReply != '')
            $res = Http::post("https://graph.facebook.com/$this->commentToReply/comments?message=$this->reply&access_token=$access_token")->json();
        else
            $res = Http::post("https://graph.facebook.com/$postDetail->post_page_id/comments?message=$this->reply&access_token=$access_token")->json();

        if (array_key_exists('id', $res) && $res['id']) {
            $response = Http::get("https://graph.facebook.com/$postDetail->post_page_id/comments?access_token=$access_token")->json();
            if (count($response['data']) > 0) $this->comments = $response['data'];

            session()->flash('message', 'Your reply is published');
            $this->commentToReply = '';
            $this->reply = '';
            $this->selectedReply = '';
        }
    }

    public function deleteComment($commentId)
    {
        $postDetail = Post::find($this->pageId);
        $access_token = Page::find($postDetail->page_id)->access_token;

        $res = Http::delete("https://graph.facebook.com/$commentId?access_token=$access_token")->json();

        if (array_key_exists('success', $res) && $res['success']) {
            $res = Http::get("https://graph.facebook.com/$postDetail->post_page_id/comments?access_token=$access_token")->json();
            if (count($res['data']) > 0) $this->comments = $res['data'];
        }
    }

    public function toggleComments()
    {
        $this->openComments = $this->openComments == false ? true : false;
        $this->comments = [];
        $this->commentToReply = '';
        $this->reply = '';
        $this->selectedReply = '';
    }

    public function refreshListPosts()
    {
        $this->posts = Post::orderBy('created_at', 'desc')->get();
    }

    public function toggleModal()
    {
        $this->open = $this->open == false ? true : false;
    }

    public function mount()
    {
        $this->type = "all";
        $this->posts = Post::orderBy('created_at', 'desc')->get();
    }

    public function render()
    {
        return view('livewire.show-post');
    }
}

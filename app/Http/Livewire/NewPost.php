<?php

namespace App\Http\Livewire;

use App\Models\Page;
use App\Models\Post;
use App\Models\User;
use App\Notifications\ShareNotify;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Livewire\Component;
use Livewire\WithFileUploads;
use Carbon\Carbon;

class NewPost extends Component
{
    use WithFileUploads;

    public $media = '';
    public $description;
    public $pages;
    public $page;
    public $open = false;
    public $schedule = false;
    public $scheduleTime;
    public $scheduleMinTime;

    protected $rules = [
        'description' => 'required|max:255',
        'page' => 'required'
    ];

    public function toggleModal()
    {
        $this->open = $this->open == false ? true : false;
        if (!$this->open) $this->resetFields();
    }

    public function toggleSchedule()
    {
        if (!$this->schedule) $this->validate();
        $this->schedule = $this->schedule == false ? true : false;

        if ($this->schedule)
            $this->scheduleMinTime = Carbon::now()->add(10, 'minutes');
    }

    public function publishSchedule()
    {
        $this->validate([
            'description' => 'required|max:255',
            'page' => 'required',
            'scheduleTime' => 'required'
        ]);

        $pageDetail = Page::find($this->page);

        if ($this->media != '') {

            $res = Http::post("https://graph.facebook.com/v14.0/$pageDetail->page_id/photos?published=false&url=$this->media&caption=$this->description&access_token=$pageDetail->access_token")->json();
            if (!array_key_exists('error', $res)) {
                $postToCreate = [
                    'user_id' => Auth::id(),
                    'page_id' =>  $pageDetail->id,
                    'post_page_id' => $pageDetail->page_id . "_" . $res['id'],
                    'description' => $this->description,
                    'media' => $this->media,
                    'scheduled' => true,
                    'share_date_time' => $this->scheduleTime
                ];
            }
        } else {

            $res = Http::post("https://graph.facebook.com/v14.0/$pageDetail->page_id/feed?published=false&message=$this->description&access_token=$pageDetail->access_token")->json();
            if (!array_key_exists('error', $res)) {
                $postToCreate = [
                    'user_id' => Auth::id(),
                    'page_id' =>  $pageDetail->id,
                    'post_page_id' => $res['id'],
                    'description' => $this->description,
                    'media' => $this->media,
                    'scheduled' => true,
                    'share_date_time' => $this->scheduleTime
                ];
            }
        }

        if (!array_key_exists('error', $res)) {
            Post::create($postToCreate);
            $this->emit('postCreated');
            $this->open = false;
            $this->schedule = false;
            $this->resetFields();
        } else {
            session()->flash('errorPublishSchedule', 'Error when creating schedule post ' . $res['error']['message']);
        }
    }

    public function sendNotification($post_page_id)
    {
        $sharedPost = [
            'url' => 'https://www.facebook.com/' . $post_page_id
        ];

        $user = User::find(Auth::id());
        $user->notify(new ShareNotify($sharedPost));
    }

    public function publishNow()
    {
        $this->validate();
        $pageDetail = Page::find($this->page);

        $postToCreate = [];
        // create a post without image
        if ($this->media != '') {
            $res = Http::post("https://graph.facebook.com/v14.0/$pageDetail->page_id/photos?url=$this->media&caption=$this->description&access_token=$pageDetail->access_token")->json();
            $postToCreate = [
                'user_id' => Auth::id(),
                'page_id' =>  $pageDetail->id,
                'post_page_id' => $res['post_id'],
                'description' => $this->description,
                'media' => $this->media,
                'scheduled' => false,
                'share_date_time' => Carbon::now()->format('Y-m-d H:i')
            ];
        } else {
            $res = Http::post("https://graph.facebook.com/v14.0/$pageDetail->page_id/feed?message=$this->description&access_token=$pageDetail->access_token")->json();
            $postToCreate = [
                'user_id' => Auth::id(),
                'page_id' =>  $pageDetail->id,
                'post_page_id' => $res['id'],
                'description' => $this->description,
                'media' => $this->media,
                'scheduled' => false,
                'share_date_time' => Carbon::now()->format('Y-m-d H:i')
            ];
        }

        Post::create($postToCreate);
        $this->emit('postCreated');
        $this->open = false;
        $this->resetFields();

        $this->sendNotification($postToCreate['post_page_id']);
    }

    public function resetFields()
    {
        $this->description = '';
        $this->media = '';
        $this->page = 'default';
        $this->scheduleTime = '';
    }

    public function mount()
    {
        $this->pages = User::find(Auth::id())->pages;
    }

    public function render()
    {
        return view('livewire.new-post');
    }
}

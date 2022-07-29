<?php

namespace App\Console;

use App\Models\Post;
use App\Models\User;
use App\Notifications\ShareNotify;
use App\Notifications\WeeklyNotify;
use Carbon\Carbon;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Http;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        // $schedule->command('inspire')->hourly();
        $schedule->call(function () {
            $posts = Post::where([
                ['scheduled', '=', 1],
                ['share_date_time', '=', Carbon::now()->format('Y-m-d H:i') . ':00']
            ])->get();

            if ($posts->count() > 0) {
                foreach ($posts as $post) {
                    $res = Http::post("https://graph.facebook.com/$post->post_page_id?is_published=true&access_token=" . $post->page->access_token)->json();

                    if ($res['success']) {
                        $post->update([
                            'scheduled' => false,
                        ]);

                        $user = User::find($post->user_id);
                        $user->notify(new ShareNotify(['url' => 'https://www.facebook.com/' . $post->post_page_id]));
                    }
                }
            }
        })->everyMinute();

        // send weekly notification
        $schedule->call(function () {
            $users = User::all();
            if ($users->count() > 0) {
                foreach ($users as $user) {
                    Notification::send($user, new WeeklyNotify(['count' => $user->post->count()]));
                }
            }
        })->weekly();
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__ . '/Commands');

        require base_path('routes/console.php');
    }
}

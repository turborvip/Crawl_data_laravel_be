<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
<<<<<<< HEAD

=======
use App\Models\News;
>>>>>>> ac5b240 (update)

class ResetViewDaily extends Command
{
    protected $signature = 'news:reset-view-daily';
    protected $description = 'Reset the views count for all news articles to zero.';

    public function handle(): void
    {
        News::query()->update(['viewDaily' => 0]);
<<<<<<< HEAD
        $this->info('News views count has been reset.');
=======
>>>>>>> ac5b240 (update)
    }
}

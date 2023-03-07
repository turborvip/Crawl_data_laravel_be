<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\News;

class ResetViewHour extends Command
{
    protected $signature = 'news:reset-view-hour';
    protected $description = 'Reset the views count for all news articles to zero.';

    public function handle(): void
    {
        News::query()->update(['viewHour' => 0]);
    }
}

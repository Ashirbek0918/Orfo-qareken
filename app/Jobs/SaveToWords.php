<?php

namespace App\Jobs;

use App\Models\Word;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Contracts\Queue\ShouldBeUnique;

class SaveToWords implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */

    protected $word;
    public function __construct($word)
    {
        $this->word = $word;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $word = $this->word;
        $newWord = new Word();
        $newWord->word = $word;
        $newWord->save();
    }
}

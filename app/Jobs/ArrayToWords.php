<?php

namespace App\Jobs;

use App\Models\Word;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Contracts\Queue\ShouldBeUnique;

class ArrayToWords implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    protected  $words;
    public function __construct()
    {
        $filePath = storage_path('app/pdfs/matn.txt');   
        $this->words = file($filePath, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle($words)
    {
        $badwords = 0;  
        $words = $this->words ;
        foreach ($words as $word) {
            $badwords++;
            if (!empty(trim($word))) { 
                $existingWord = Word::where('word', $word)->first();
                if (!$existingWord) {
                    dispatch( new SaveToWords($word));
                }
            }
        }
    }
}

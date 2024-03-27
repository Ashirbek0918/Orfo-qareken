<?php

namespace App\Http\Controllers;

use App\Models\Word;
use Spatie\PdfToText\Pdf;
use Illuminate\Support\Str;
use Illuminate\Http\Request;

class WordController extends Controller
{
    public function extractPDF(){
        $filePath = storage_path('app/pdfs/matn.txt');   
        $words = file($filePath, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        $result = $this->saveWordsToDatabase($words);
        return $result;
    }
    
    public function saveWordsToDatabase($words){
        $badwords = 0;  
        foreach ($words as $word) {
            $badwords++;
            if (!empty(trim($word))) { 
                $existingWord = Word::where('word', $word)->first();
                $newWord = new Word();
                $newWord->word = $word;
                $newWord->save();
                if (!$existingWord) {
                    $newWord = new Word();
                    $newWord->word = $word;
                    $newWord->save();
                }
            }
        }
        if ($badwords > 0) {
            return response()->json(['message' => "So'zlar bazaga muvaffaqiyatli saqlandi "]);
        } else {
            return response()->json(['message' => "Hozircha hech qanday so'zlar bazaga saqlanmadi"]);
        }
    }
        
}

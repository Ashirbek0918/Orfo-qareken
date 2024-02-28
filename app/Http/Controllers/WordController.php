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
    

    public function count(){
        $filePath = storage_path('app/pdfs/matn.txt');   
        $words = file($filePath, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        $words2 = Word::pluck('word')->toArray();
        $missingElements = array_diff($words, $words2);
        return $missingElements;
        $badwords = 0;  
        $wordscount = 0;
        foreach ($missingElements as $word) {
            if (!empty(trim($word))) { 
                $badwords++;
                $wordscount++;
                $newWord = new Word();
                $newWord->word = $word;
                $newWord->save();
                $existingWord = Word::where('word', $word)->first();
                if (!$existingWord) {
                    if(!$newWord){
                        return $newWord;
                    }else{
                        return "exit";
                    }
                }
            }
        }
    
        if ($badwords > 0) {
            return $badwords;
        } else {
            return response()->json(['message' => "Hozircha hech qanday so'zlar bazaga saqlanmadi"]);
        }
    }

    public function edit(Request $request){
        $text = $request->text; 
        $text = preg_replace('/[^a-z0-9]+/i', ' ',$text);
        $words = explode(" ", $text);
        $word =substr( $words[0],0,-1);
        $allwords = Word::whereIn('word', $words)->pluck('word')->toArray();
        $incorrectWords = array_diff($words, $allwords);
        $incorrectWordscount = count($incorrectWords);
        $similarWords = [];
        foreach ($incorrectWords as $word) {
            $first = $word;
            $second = $word;
            $similar = Word::where('word', 'like', $word .'%')->pluck('word')->toArray();
            if (empty($similar)) {
                $similar = [];
                while (empty($similar) && strlen($word)>2) {
                    $word = substr($word,1);
                    $similar = Word::where('word', 'like','%'. $word .'%')->pluck('word')->toArray();
                }
                if(empty($similar)){
                    while (empty($similar) && strlen($second)>2) {
                        $second = substr($second,0, -1);
                        $similar = Word::where('word', 'like','%'. $second. '%')->pluck('word')->toArray();
                    }
                }
            }
            if (!empty($similar)) {
                $similarWords[$first] = $similar;
            }
        }
        if($incorrectWordscount > 0) {
            return response()->json(["Xato yozilgan so'zlar soni" => $incorrectWordscount,
            "Tavsiya etilgan so'zlar" => $similarWords]);
        }
        return response()->json(['message' =>"Siz barini to'gri yozdingiz!"]);
        
    }



    
}

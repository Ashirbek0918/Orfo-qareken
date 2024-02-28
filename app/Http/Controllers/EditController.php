<?php

namespace App\Http\Controllers;

use App\Models\Word;
use Illuminate\Support\Str;
use Illuminate\Http\Request;

class EditController extends Controller
{
    

    public function edit(Request $request){
        $text = $request->text;
        $text = preg_replace('/[^a-z0-9]+/i', ' ', $text);
        $words = explode(" ", $text);
        $incorrectWords = [];

        foreach ($words as $word) {
            if (!Word::where('word', $word)->exists()) {
                $similarWords = Word::select('word')->get()->map(function ($item) use ($word) {
                    return [
                        'word' => $item->word,
                        'distance' => $this->levenshtein($word, $item->word),
                    ];
                })->sortBy('distance')->pluck('word')->take(5)->toArray();


                $incorrectWords[$word] = $similarWords;
            }
        }

        $incorrectWordsCount = count($incorrectWords);
        if ($incorrectWordsCount > 0) {
            return response()->json([
                "Xato yozilgan so'zlar soni" => $incorrectWordsCount,
                "Tavsiya etilgan so'zlar" => $incorrectWords
            ]);
        }
        return response()->json(['message' => "Siz barini to'g'ri yozdingiz!"]);
    }
    private function levenshtein($str1, $str2){
        $len1 = strlen($str1);
        $len2 = strlen($str2);
        $matrix = [];

        for ($i = 0; $i <= $len1; $i++) {
            $matrix[$i][0] = $i;
        }

        for ($j = 0; $j <= $len2; $j++) {
            $matrix[0][$j] = $j;
        }

        for ($i = 1; $i <= $len1; $i++) {
            for ($j = 1; $j <= $len2; $j++) {
                    $cost = $str1[$i - 1] == $str2[$j - 1] ? 0 : 1;
                    $matrix[$i][$j] = min(
                    $matrix[$i - 1][$j] + 1,
                    $matrix[$i][$j - 1] + 1,
                    $matrix[$i - 1][$j - 1] + $cost
                    );
            }
        }

        return $matrix[$len1][$len2];
    }

}


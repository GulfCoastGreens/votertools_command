<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace GCG\votertools;

/**
 * Description of VoterFormating
 *
 * @author jam
 */
trait VoterFormating {
    
    public static function tidy($text) {
        return \implode(" ", \array_map(function($word) {
            $word = \ucfirst(\strtolower($word));
            //Specials like Mac, Mc etc
            $specials = ["Mac", "Mc", "O'"];
            foreach ($specials as $special) {
                $pos = \stripos($word, $special);
                if (($pos !== false) && ($pos == 0)) {
                    $parts = \explode($special, $word);
                    $word = $special . \ucfirst($parts[1]);
                }
            }
            //...but not for some words that begin with "Mac"
            // (make your own mind up about Macintosh, Maclure & Maclaren)
            $specials = ["macken", "macclesfield", "machynlleth"];
            if (\in_array(strtolower($word), $specials)) {
                $word = \ucfirst(\strtolower($word));
            }
            //Let"s go lower case on some words
            $specials = ["de", "la", "le", "on", "of", "and", "under", "upon"];
            if (\in_array(strtolower($word), $specials)) {
                $word = \strtolower($word);
            }
            //Let's go upper case on some words
            $specials = ['NE', 'NW', 'SW', 'SE'];
            if (\in_array(strtoupper($word), $specials)) {
                $word = \strtoupper($word);
            }
            
            return $word;
        }, \explode(" ", \preg_replace("/\s+/"," ",\trim($text)))));
    }

    public static function titleCase($string) {
        $word_splitters = array(' ', '-', "O'", "L'", "D'", 'St.', 'Mc');
        $lowercase_exceptions = array('the', 'van', 'den', 'von', 'und', 'der', 'de', 'da', 'of', 'and', "l'", "d'");
        $uppercase_exceptions = array('III', 'IV', 'VI', 'VII', 'VIII', 'IX', 'NE', 'NW', 'SW', 'SE');

        $string = \strtolower($string);
        foreach ($word_splitters as $delimiter) { 
            $words = \explode($delimiter, $string); 
            $newwords = array(); 
            foreach ($words as $word)
            { 
                if (\in_array(\strtoupper($word), $uppercase_exceptions))
                    $word = \strtoupper($word);
                else
                if (!\in_array($word, $lowercase_exceptions))
                    $word = \ucfirst($word); 

                $newwords[] = $word;
            }

            if (\in_array(\strtolower($delimiter), $lowercase_exceptions))
                $delimiter = \strtolower($delimiter);

            $string = \join($delimiter, $newwords); 
        } 
        return $string; 
    }
}

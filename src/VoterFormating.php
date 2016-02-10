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
            return $word;
        }, \explode(" ", \preg_replace("/\s+/"," ",\trim($text)))));
    }

}

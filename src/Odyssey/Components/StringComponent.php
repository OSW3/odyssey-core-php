<?php

namespace Odyssey\Components;


class StringComponent
{
    public static function canonical(string $str, $options = [])
    {
        $options = array_merge([
            "uppercase" => false
        ], $options);
        
        $str = self::removeAccent($str);
        $str = strtolower($str);

        if ($options['uppercase']) $str = strtoupper($str);

        return $str;
    }
    
    public static function removeAccent(string $str)
    {
        return strtr($str, [
            'Š'=>'S', 'š'=>'s', 
            'Ž'=>'Z', 'ž'=>'z', 
            'À'=>'A', 'Á'=>'A', 'Â'=>'A', 'Ã'=>'A', 'Ä'=>'A', 'Å'=>'A', 'Æ'=>'A', 'à'=>'a', 'á'=>'a', 'â'=>'a', 'ã'=>'a', 'ä'=>'a', 'å'=>'a', 'æ'=>'a', 
            'Ç'=>'C', 
            'È'=>'E', 'É'=>'E', 'Ê'=>'E', 'Ë'=>'E', 'è'=>'e', 'é'=>'e', 'ê'=>'e', 'ë'=>'e', 
            'Ì'=>'I', 'Í'=>'I', 'Î'=>'I', 'Ï'=>'I', 'ì'=>'i', 'í'=>'i', 'î'=>'i', 'ï'=>'i', 
            'Ñ'=>'N', 'ñ'=>'n', 
            'Ò'=>'O', 'Ó'=>'O', 'Ô'=>'O', 'Õ'=>'O', 'Ö'=>'O', 'Ø'=>'O', 'ð'=>'o', 'ò'=>'o', 'ó'=>'o', 'ô'=>'o', 'õ'=>'o', 'ö'=>'o', 'ø'=>'o', 
            'Ù'=>'U', 'Ú'=>'U', 'Û'=>'U', 'Ü'=>'U', 'ù'=>'u', 'ú'=>'u', 'û'=>'u', 
            'Ý'=>'Y', 'ý'=>'y','ÿ'=>'y',
            'Þ'=>'B', 'þ'=>'b', 
            'ß'=>'Ss', 
            'ç'=>'c', 
            '\''=>' '
        ]);
    }
} 
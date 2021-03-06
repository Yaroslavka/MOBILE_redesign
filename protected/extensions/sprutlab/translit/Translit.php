<?php

final class Translit
{

    /**
     * Укр/Рус символы
     *
     * @var array
     * @access private
     * @static
     */
    static private $cyr=array(
        'Щ','Ш','Ч','Ц','Ю','Я','Ж','А','Б','В','Г','Д','Е','Ё','З','И','Й','К','Л','М','Н','О','П','Р','С','Т','У','Ф','Х','Ь','Ы','Ъ','Э','Є','Ї','І',
        'щ','ш','ч','ц','ю','я','ж','а','б','в','г','д','е','ё','з','и','й','к','л','м','н','о','п','р','с','т','у','ф','х','ь','ы','ъ','э','є','ї','і');

    /**
     * Латинские соответствия
     *
     * @var array
     * @access private
     * @static
     */
    static private $lat=array(
        'shh','sh','ch','c','ju','ja','zh','a','b','v','g','d','je','jo','z','i','j','k','l','m','n','o','p','r','s','t','u','f','kh','y','y','','e','je','ji','i',
        'shh','sh','ch','c','ju','ja','zh','a','b','v','g','d','je','jo','z','i','j','k','l','m','n','o','p','r','s','t','u','f','kh','y','y','','e','je','ji','i');

    /**
     * Приватный конструктор класса
     * не дает создавать объект этого класса
     *
     * @access private
     */
    private function __construct()
    {
        
    }

    /**
     * Статический метод транслитерации
     *
     * @param string
     * @return string
     * @access public
     * @static
     */
    static public function transliterate($string,$wordSeparator='',$clean=false)
    {
        for($i=0; $i<count(self::$cyr); $i++){
            $string=str_replace(self::$cyr[$i],self::$lat[$i],$string);
        }
        $string=preg_replace("/([qwrtpsdfghklzxcvbnmQWRTPSDFGHKLZXCVBNM]+)[jJ]e/","\${1}e",$string);
        $string=preg_replace("/([qwrtpsdfghklzxcvbnmQWRTPSDFGHKLZXCVBNM]+)[jJ]/","\${1}y",$string);
        $string=preg_replace("/([eyuioaEYUIOA]+)[Kk]h/","\${1}h",$string);
        $string=preg_replace("/^kh/","h",$string);
        $string=preg_replace("/^Kh/","H",$string);
        $string=trim($string);
        if($wordSeparator){
            $string=str_replace(' ',$wordSeparator,$string);
            $string=preg_replace('/['.$wordSeparator.']{2,}/','',$string);
        }
        if($clean){
            $string=strtolower($string);
            $string=preg_replace('/[^-_a-z0-9]+/','',$string);
        }
        return $string;
    }

    /**
     * Приведение к УРЛ
     *
     * @return string
     * @access public
     * @static
     */
    static public function asURLSegment($string)
    {
        return strtolower(self::transliterate($string,'-',true));
    }
    
    static public function cutString($string,$maxlen)
    {
        $len=(mb_strlen($string)>$maxlen)?mb_strripos(mb_substr($string,0,$maxlen),' '):$maxlen;
        $cutStr=mb_substr($string,0,$len);
        return (mb_strlen($string)>$maxlen)?''.$cutStr.'...':''.$cutStr.'';
    }
    
}

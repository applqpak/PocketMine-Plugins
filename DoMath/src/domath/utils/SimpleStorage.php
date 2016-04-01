<?php

namespace domath\utils;

class SimpleStorage{
    /** 
     * Stored answers, format: key => answer
     * @var array 
     */
    private static $answers = [];
    /**
     * Stores the input, $key is the name of the input
     * @param mixed $key
     * @param int $input
     */
    public static function store($key, $input){
        self::$answers[strtolower($key)] = $input;
    }
    /**
     * Removes the input from stored answers, if it is stored
     * @param mixed $key
     */
    public static function remove($key){
        if(self::exists($key)){
            unset(self::$answers[strtolower($key)]);
        }
    }
    /**
     * Retrieves the input from stored answers, if it exists, will return string "ERROR" upon failure
     * @param mixed $key
     * @return int|string
     */
    public static function retrieve($key){
        if(self::exists($key)){
            return self::$answers[strtolower($key)];
        }
        return "ERROR";
    }
    /**
     * Returns true if $key exists in stored answers
     * @param mixed $key
     * @return bool
     */
    public static function exists($key){
        return array_key_exists(strtolower($key), self::$answers);
    }
    /**
     * Clears all the answers that are currently stored
     * @return void
     */
    public static function clear(){
        self::$answers = [];
    }
}
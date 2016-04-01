<?php

namespace domath\utils;

class ShapeCalculator{
    /**
     * Calculates the area of a circle
     * @param int $radius
     * @return int
     */
    public static function carea($radius){
        return M_PI * ($radius * $radius);
    }
    /**
     * Calculates the area of a parallelogram
     * @param int $length
     * @param int $width
     * @return int
     */
    public static function parea($length, $width){
        return $length * $width;
    }
    /**
     * Calculates the area of a triangle
     * @param int $base
     * @param int $height
     * @return int
     */
    public static function tarea($base, $height){
        return ($base * $height) / 2;
    }
    /**
     * Calculates the volume of a sphere
     * @param int $radius
     * @return int
     */
    public static function svolume($radius){
        return self::carea($radius) * 4;
    }
    /**
     * Calculates the volume of a rectangular/square prism
     * @param int $length
     * @param int $width
     * @param int $height
     * @return int
     */
    public static function pvolume($length, $width, $height){
        return self::parea($length, $width) * $height;
    }
}
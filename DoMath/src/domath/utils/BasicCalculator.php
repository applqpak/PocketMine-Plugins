<?php

namespace domath\utils;

class BasicCalculator{
    const ADD = 0;
    const SUBTRACT = 1;
    const MULTIPLY = 2;
    const DIVIDE = 3;
    const PERCENT = 4;
    const SQUARE = 5;
    const EXPONENT = 6;
    /**
     * Returns a formatted string with input and answers
     * @param mixed $input
     * @param int $mode
     * @return string
     */
    public static function toString($input, $mode){
        $output = "";
        switch($mode){
            case self::ADD:
                $symbol = "+";
                if(is_array($input)){
                    $answer = self::add($input);
                }
                break;
            case self::SUBTRACT:
                $symbol = "-";
                if(is_array($input)){
                    $answer = self::subtract($input);
                }
                break;
            case self::MULTIPLY:
                $symbol = "*";
                if(is_array($input)){
                    $answer = self::multiply($input);
                }
                break;
            case self::DIVIDE:
                $symbol = "/";
                if(is_array($input)){
                    $answer = self::divide($input);
                }
                break;
            case self::PERCENT:
                $symbol = "%";
                if(is_array($input)){
                    $answer = self::percent($input[0], $input[1]);
                }
                break;
            case self::SQUARE:
                $symbol = "√";
                if(is_string($input)){
                    $answer = self::square($input);
                }
                break;
            case self::EXPONENT:
                $symbol = "^";
                if(is_array($input)){
                    $answer = self::exponent($input[0], $input[1]);
                }
                break;
            default:
                $symbol = "";
                $answer = "ERROR";
                break;
        }
        if(is_array($input)){
            foreach($input as $inputValue){
                $output .= $inputValue.$symbol;
            }
            return substr($output, 0, -1)."=".$answer;
        }
        else{
            return $symbol.$input."=".$answer;
        }
    }
    /**
     * Calculates the sum of all the input values
     * @param int[] $inputs
     * @return int
     */
    public static function add(array $inputs){
        if(is_array($inputs)){
            $output = $inputs[0];
            foreach(array_slice($inputs, 1) as $input){
                $output += $input;
            }
            return $output;
        }
    }
    /**
     * Calculates the difference of all the input values
     * @param int[] $inputs
     * @return int
     */
    public static function subtract(array $inputs){
        if(is_array($inputs)){
            $output = $inputs[0];
            foreach(array_slice($inputs, 1) as $input){
                $output -= $input;
            }
            return $output;
        }
    }
    /**
     * Calculates the total value, multiplying all the values by each other
     * @param int[] $inputs
     * @return int
     */
    public static function multiply(array $inputs){
        if(is_array($inputs)){
            $output = $inputs[0];
            foreach(array_slice($inputs, 1) as $input){
                $output *= $input;
            }
            return $output;
        }
    }
    /**
     * Calculates the quotient of all the input values
     * @param int[] $inputs
     * @return int|string
     */
    public static function divide(array $inputs){
        if(is_array($inputs)){
            $output = $inputs[0];
            foreach(array_slice($inputs, 1) as $input){
                if($input != 0){
                    $output /= $input; //If the value is 0, it won't perform the calculation
                }
            }
            if(in_array(0, array_slice($inputs, 1), 0)){
                return "ERROR"; //There was one or more zeros encountered
            }
            else{
                return $output; //There were no zeros encountered
            }
        }
    }
    /**
     * Calculates the difference between first input and second input, returns percentage
     * @param int $input1
     * @param int $input2
     * @return int
     */
    public static function percent($input1, $input2){
        return ($input1 / $input2) * 100;
    }
    /**
     * Calculates the squared value of input
     * @param int $input
     * @return int
     */
    public static function square($input){
        return sqrt($input);
    }
    /**
     * Calculates the value of input to the power of $exponent
     * @param int $input
     * @param int $exponent
     * @return int
     */
    public static function exponent($input, $exponent){
        return $input ** $exponent;
    }
}
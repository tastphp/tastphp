<?php
namespace TastPHP\Common\Kit;

/**
 * Class Validator
 * @package TastPHP\Common\Kit
 */
class Validator
{
    public static function __callStatic($name, $arguments)
    {
        throw new \Exception("Try to Calling static method '$name' "
            . implode(', ', $arguments). ",But can't find it!\n");
    }

    public static function emailOrName($value)
    {
        $isEmail = self::email($value);
        if (!$isEmail) {
            return self::nickname($value);
        }

        return $isEmail;
    }

    public static function email($value)
    {
        $value = (string)$value;
        $valid = filter_var($value, FILTER_VALIDATE_EMAIL);
        return $valid !== false;
    }

    public static function nickname($value, array $option = array())
    {
        $option = array_merge(
            array('minLength' => 2, 'maxLength' => 18),
            $option
        );

        $len = (strlen($value) + mb_strlen($value, 'utf-8')) / 2;
        if ($len > $option['maxLength'] || $len < $option['minLength']) {
            return false;
        }
        return !!preg_match('/^[\x{4e00}-\x{9fa5}a-zA-z0-9_.]+$/u', $value);
    }

    public static function password($value)
    {
        return !!preg_match('/^[\S]{5,20}$/u', $value);
    }

    public static function integer($value)
    {
        return !!preg_match('/^[+-]?\d{1,9}$/', $value);
    }

    public static function float($value)
    {
        return !!preg_match('/^(([+-]?[1-9]{1}\d*)|([+-]?[0]{1}))(\.(\d){1,2})?$/i', $value);
    }

    public static function date($value)
    {
        return !!preg_match('/^(\d{4}|\d{2})-((0?([1-9]))|(1[0-2]))-((0?[1-9])|([12]([0-9]))|(3[0|1]))$/', $value);
    }
}
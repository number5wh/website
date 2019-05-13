<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016/1/14
 * Time: 15:04
 */
require_once 'Filter.interface.php';
class HtmlSpecialCharFilter implements Filter{
    static $_ins;
    function doFilter(){

        foreach( $_REQUEST as &$val){
            $val = htmlspecialchars($val);
        }
        unset($val);
    }

    static function getFilter()
    {
        // TODO: Implement getFilter() method.
        if(is_null(self::$_ins )){
            self::$_ins = new HtmlSpecialCharFilter();
        }
        return self::$_ins;
    }
}
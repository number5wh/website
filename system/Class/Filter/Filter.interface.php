<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016/1/14
 * Time: 15:53
 */

interface Filter {
    public function doFilter();

    static function getFilter();
}
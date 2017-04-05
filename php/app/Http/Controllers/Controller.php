<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Image;
use Input;

abstract class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;


    /**
     * @param $mobile
     * @return bool
     * 验证手机号码合法性
     */
    public function isMobile($mobile)
    {
        if (!is_numeric($mobile)) {
            return false;
        }
        return preg_match('#^13[\d]{9}$|^14[5,7]{1}\d{8}$|^15[^4]{1}\d{8}$|^17[0,6,7,8]{1}\d{8}$|^18[\d]{9}$#', $mobile) ? true : false;
    }

    /**
     * @param $birthday
     * @return int
     *  根据生日计算年龄
     */
    public function calcAge($birthday) {
        $age = 0;
        if(!empty($birthday)){
            $age = strtotime($birthday);
            if($age === false){
                return 0;
            }

            list($y1,$m1,$d1) = explode("-",date("Y-m-d", $age));

            list($y2,$m2,$d2) = explode("-",date("Y-m-d"), time());

            $age = $y2 - $y1;
            if((int)($m2.$d2) < (int)($m1.$d1)){
                $age -= 1;
            }
        }
        return $age;
    }



    /**
     * sha1加密方法，false为解密
     *
     * 加密微信user_id
     * @package App\Controller
     *
     * @author  kino <735745089@qq.com>
     * @copyright Copyright (c) 2016 lc.top all rights reserved.
     */

    public function dencrypt($string, $isEncrypt = true, $key = KEY_SPACE)
    {


        /*if (!isset($string{0}) || !isset($key{0})) {
            return false;
        }*/

        $dynKey = $isEncrypt ? hash('sha1', microtime(true)) : substr($string, 0, 40);
        $fixedKey = hash('sha1', $key);

        $dynKeyPart1 = substr($dynKey, 0, 20);
        $dynKeyPart2 = substr($dynKey, 20);
        $fixedKeyPart1 = substr($fixedKey, 0, 20);
        $fixedKeyPart2 = substr($fixedKey, 20);
        $key = hash('sha1', $dynKeyPart1 . $fixedKeyPart1 . $dynKeyPart2 . $fixedKeyPart2);

        $string = $isEncrypt ? $fixedKeyPart1 . $string . $dynKeyPart2 : (isset($string{339}) ? gzuncompress(base64_decode(substr($string, 40))) : base64_decode(substr($string, 40)));

        $n = 0;
        $result = '';
        $len = strlen($string);

        for ($n = 0; $n < $len; $n++) {
            $result .= chr(ord($string{$n}) ^ ord($key{$n % 40}));
        }

        return $isEncrypt ? $dynKey . str_replace('=', '', base64_encode($n > 299 ? gzcompress($result) : $result)) : substr($result, 20, -20);
    }


    /**
     * 替换压缩图方法
     *
     * @package App\Controller
     *
     * @author  kino <735745089@qq.com>
     * @copyright Copyright (c) 2016 lc.top all rights reserved.
     */

    public function change_reurl($data,$size)
    {
       foreach ($data as $k => $v){
           $v -> re_img_url = resize_url($v -> img_url,$size);
       }

        return $data;

    }




}

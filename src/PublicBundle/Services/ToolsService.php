<?php

namespace PublicBundle\Services;

class ToolsService
{
    private $function = array(
        'getRandChar'
    );
    private $tools;

    public function __construct($tools)
    {
        $this->tools = $tools;
    }

    /**
     * 获取一个固定长度的随机字符串
     * @param integer $length 字符串长度
     *
     * @return string $str 字符串
     */
    public function getRandChar($length)
    {
        $str = null;
        $strPol = "0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ";
        $max = strlen($strPol) - 1;

        for($i = 0; $i < $length; $i++){
            $str .= $strPol[rand(0, $max)];
        }

        return $str;
    }
}

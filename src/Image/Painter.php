<?php


namespace Jaifire\Brisk\Image;


class Painter
{
    private $paper;

    private $font = 'C:\Users\pc\Desktop\simfang.ttf';

    public function __construct($width, $height)
    {
        $this->paper = imagecreatetruecolor($width, $height);
    }

    public function fillColor($r, $g, $b, $x = 0, $y = 0)
    {
        imagefill(
            $this->paper,
            $x,
            $y,
            imagecolorallocate($this->paper, $r, $g, $b)
        );
        return $this->paper;
    }

    public function draw($logo, $x, $y, $w, $h)
    {
        imagecopyresampled($this->paper, $logo,
            $x,                //logo 在 qr 中的x坐标
            $y,                //logo 在 qr 中的y坐标
            0,                 //logo 平铺时的x坐标，一般为0，否则会被挤开
            0,                 //logo 平铺时的y坐标，一般为0，否则会被挤开
            $w,                //logo 的宽度
            $h,                //logo 的高度
            imagesx($logo),    //载入logo 原图的宽度，小于原图：图像缺失，大于原图：图象有黑块
            imagesy($logo)     //载入logo 原图的高度，小于原图：图像缺失，大于原图：图象有黑块
        );
        return $this->paper;
    }

    public function text($x, $y, $content, $size = 22, $color = 1)
    {
        if (!file_exists($this->font)) {
            trigger_error('字体文件不存在', E_USER_ERROR);
        }
        imagettftext($this->paper,
            $size,        //size
            0,            //倾斜
            $x,           //x坐标
            $y,           //y坐标
            $color,       //color
            $this->font,
            $content
        );
        return $this->paper;
    }

    public function image($type = 'png')
    {
        header("Content-type:image/$type");
        if ('png' === $type) {
            imagepng($this->paper);
        }
        if ('jpg' === $type) {
            imagejpeg($this->paper);
        }
        if ('gif' === $type) {
            imagegif($this->paper);
        }
    }
}
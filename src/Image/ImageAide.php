<?php

namespace Exp\Brisk\Image;
class ImageAide
{
    public $info;
    public $exif;
    public $src;
    public $dst;

    public function __construct($path)
    {
        list($width, $height, $type, $attr) = getimagesize($path);
        $this->info = [
            'width' => $width,
            'height' => $height,
            'type' => image_type_to_extension($type, false),
        ];
        $create = 'imagecreatefrom' . $this->info['type'];
        $this->src = $create($path); // 读取原始图像
        if (function_exists('exif_read_data')) {
            $this->exif = exif_read_data($path);
        }
    }

    public function __destruct()
    {
        imagedestroy($this->dst);
        imagedestroy($this->src);
    }

    public function rotate()
    {
        $orientation = $this->exif['Orientation'];
        $map = [
            3 => 180,
            6 => -90,
            8 => 90,
        ];
        $this->dst = imagerotate($this->src, $map[$orientation], 0);
        return $this;
    }

    public function compress($percent = 1.0, $max = 2440, $way = 1)
    {
        $width = $this->info['width'] * $percent;
        $height = $this->info['height'] * $percent;
        //最大边
        if ($width > $max) {
            $width = $max;
            $height = $height * ($max / $width);
        }
        if ($height > $max) {
            $height = $max;
            $width = $width * ($max / $height);
        }
        // 准备压缩后的图片
        $this->dst = imagecreatetruecolor($width, $height);
        if (1 == $way) {
            //GD 2.x后新增加的函数，是采用插值算法生成更平滑的图像，图片相对清晰，但是速度相对imagecopyresize()函数来说慢一些。
            imagecopyresampled($this->dst, $this->src, 0, 0, 0, 0, $width, $height, $this->info['width'], $this->info['height']);
        } else {
            //在所有GD版本中有效，它所生成的图像比较粗糙，但是速度较快；
            imagecopyresized($this->dst, $this->src, 0, 0, 0, 0, $width, $height, $this->info['width'], $this->info['height']);
        }
        return $this;
    }

    public function output($path = null, $quality = 80)
    {
        $output = 'image' . $this->info['type'];
        // $path 如果为null，图像流将被直接输出
        $output($this->dst, $path, $quality);
    }
}


/*
$t = new ImageAide('2.jpg');
$t->rotate()->output('dst.jpg');
$t->compress(0.1)->output('c_dst.jpg');
 * */
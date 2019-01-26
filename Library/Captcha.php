<?php

namespace Library;


class Captcha
{
    var $characters = '123456789abcdefghjklmnpqrstuvwxyzABCDEFGHJKLMNPQRSTUVWXYZ';
    var $resource_path = '';
    
    function __construct($resource_path='')
    {
        $this->resource_path = $resource_path;
    }

    function generate($len=4)
    {
        $code = '';

        for ($i=0; $i<$len; $i++) {
            $code .= $this->characters[rand(0, strlen($this->characters) - 1)];
        }

        $captcha_encode = $this->characters[rand(0, strlen($this->characters) - 1)] . $this->characters[rand(0, strlen($this->characters) - 1)] . $this->characters[rand(0, strlen($this->characters) - 1)] . base64_encode(strtolower($code));

        $pictures = [];
        $picture_path = "{$this->resource_path}/%s-%s-icon.png";
        for ($i=0; $i<strlen($code); $i++) {
            $char = strtoupper($code[$i]);

            if (is_numeric($char)) {
                $pictures[] = ['source' => sprintf($picture_path, 'Number', $char)];
            } else {
                $pictures[] = ['source' => sprintf($picture_path, 'Letter', $char)];
            }
        }

        if (!empty($pictures)) {
            $captcha_image_width = 0;
            $captcha_image_height = 0;
            foreach ($pictures as $k => $image) {
                list($image_width, $image_height) = getimagesize($image['source']);
                $pictures[$k]['x'] = $captcha_image_width - $pictures[$k]['width'];
                $pictures[$k]['y'] = 0;
                $pictures[$k]['width'] = $image_width;
                $pictures[$k]['height'] = $image_height;
                $pictures[$k]['object'] = imagecreatefrompng($image['source']);

                $captcha_image_width += $image_width;
                $captcha_image_height = ($captcha_image_height < $image_height ? $image_height : $captcha_image_height);
            }

            $captcha_picture = imagecreatetruecolor($captcha_image_width, $captcha_image_height);
            foreach ($pictures as $k => $image) {
                imagecopy($captcha_picture, $pictures[$k]['object'], $pictures[$k]['x'], 0, 0, 0, $pictures[$k]['width'], $pictures[$k]['height']);
            }

            ob_start (); 
            imagecolortransparent($captcha_picture, imagecolorallocate($captcha_picture, 0, 0, 0));
            imagepng ($captcha_picture);
            $captcha_picture = ob_get_contents (); 
            ob_end_clean (); 
        }

        return [
            'captcha_validate' => $captcha_encode, 
            'captcha_picture' => "<img style=\"height: 2.5em;\" src=\"data:image/png;base64,". base64_encode($captcha_picture) .'">'
        ];
    }

    function validate($input='', $captcha_encode='')
    {
        if (empty($input) || empty($captcha_encode) || !is_string($captcha_encode)) {
            return False;
        }

        $input = strtolower($input);
        $captcha_decode = base64_decode(substr($captcha_encode, 3));

        return ($input == $captcha_decode);
    }
}

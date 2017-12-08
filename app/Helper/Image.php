<?php
namespace App\Helper;

class Image {
    private $file;
    private $image;
    private $width;
    private $height;
    private $bits;
    private $mime;

    public function __construct($file) {            
        if (!extension_loaded('gd')) {
            exit('Error: PHP GD is not installed!');
        }
        if (file_exists($file)) {
            $this->file = $file;
            $file_info = pathinfo($file);
            $info = getimagesize($file);

            $this->width  = $info[0];
            $this->height = $info[1];
            $this->bits = isset($info['bits']) ? $info['bits'] : '';
            //$this->mime = isset($info['mime']) ? $info['mime'] : '';
            $this->mime = image_type_to_mime_type(exif_imagetype($file));

            if(isset($info['extension'])) $extension = strtolower($info['extension']);
            else $extension = '';

            switch ($this->mime){
                case $extension == 'gif':
                case 'image/gif':
                    $this->image = imagecreatefromgif($file);
                    break;
                case $extension == 'png':
                case 'image/png':
                case 'image/x-png':
                    $this->image = imagecreatefrompng($file);
                    break;
                case $extension == 'jpg':
                case $extension == 'jpeg':
                case 'image/jpg':
                case 'image/jpeg':
                case 'image/pjpeg':
                    $this->image = imagecreatefromjpeg($file);
                    break;
                case $extension == 'bmp':
                case 'image/bmp':
                case 'image/ms-bmp':
                case 'image/x-bitmap':
                case 'image/x-bmp':
                case 'image/x-ms-bmp':
                case 'image/x-win-bitmap':
                case 'image/x-windows-bmp':
                case 'image/x-xbitmap':
                    $this->image = imagecreatefromwbmp($file);
                    break;
                case $extension == 'webp':
                case 'image/webp':
                case 'image/x-webp':
                    $this->image = imagecreatefromwebp($file);
                    break;
                case 'data-url':
                    //$this->result = $this->processDataUrl();
                    //break;
                case $extension == 'tif':
                case $extension == 'tiff':
                case 'image/tiff':
                case 'image/tif':
                case 'image/x-tif':
                case 'image/x-tiff':
                    //$this->result = $this->processTiff();
                    //break;
                case $extension == 'ico':
                case 'image/x-ico':
                case 'image/x-icon':
                case 'image/vnd.microsoft.icon':
                    //$this->result = $this->processIco();
                    //break;
                case $extension == 'psd':
                case 'image/vnd.adobe.photoshop':
                    //$this->result = $this->processPsd();
                    //break;
                default: $this->image = imagecreatefromstring(file_get_contents($file)); break;
            }
            if(!is_resource($this->image)) throw new Exception('Error: Could not load image ' . $file .', '.$this->mime. '!');
        } else {
            throw new Exception('Error: File not found ' . $file . '!');
        }
    }

    public function getFile() {
        return $this->file;
    }

    public function getImage() {
        return $this->image;
    }

    public function getWidth() {
        return $this->width;
    }

    public function getHeight() {
        return $this->height;
    }

    public function getBits() {
        return $this->bits;
    }

    public function getMime() {
        return $this->mime;
    }

    public function save($quality = 90) {
        $info = pathinfo($this->file);

        if(isset($info['extension'])) $extension = strtolower($info['extension']);
        else $extension = '';

        if (is_resource($this->image)) {
            switch ($this->mime){
                case $extension == 'gif':
                case 'image/gif':
                    imagegif($this->image, $this->file);
                    break;
                case $extension == 'png':
                case 'image/png':
                case 'image/x-png':
                    imagealphablending($this->image, false);
                    imagesavealpha($this->image, true);
                    imagepng($this->image, $this->file, $quality/10);
                    break;
                case $extension == 'jpg':
                case $extension == 'jpeg':
                case 'image/jpg':
                case 'image/jpeg':
                case 'image/pjpeg':
                    imagejpeg($this->image, $this->file, $quality);
                    break;
                case $extension == 'webp':
                case 'image/webp':
                case 'image/x-webp':
                    imagewebp($this->image, $this->file);
                    //break;
                case $extension == 'bmp':
                case 'image/bmp':
                case 'image/ms-bmp':
                case 'image/x-bitmap':
                case 'image/x-bmp':
                case 'image/x-ms-bmp':
                case 'image/x-win-bitmap':
                case 'image/x-windows-bmp':
                case 'image/x-xbitmap':
                    image2wbmp($this->image, $this->file);
                    break;
                case 'data-url':
                    //$this->result = $this->processDataUrl();
                    //break;
                case $extension == 'tif':
                case $extension == 'tiff':
                case 'image/tiff':
                case 'image/tif':
                case 'image/x-tif':
                case 'image/x-tiff':
                    //$this->result = $this->processTiff();
                    //break;
                case $extension == 'ico':
                case 'image/x-ico':
                case 'image/x-icon':
                case 'image/vnd.microsoft.icon':
                    //$this->result = $this->processIco();
                    //break;
                case $extension == 'psd':
                case 'image/vnd.adobe.photoshop':
                    //$this->result = $this->processPsd();
                    //break;
                default: imagepng($this->image, $this->file, $quality/10); break;
            }
            imagedestroy($this->image);
        }
    }

    public function resize($width = 0, $height = 0, $default = '') {
        if (!$this->width || !$this->height) {            
            return $this;
        }

        $xpos = 0;
        $ypos = 0;
        $scale = 1;

        $scale_w = $width / $this->width;
        $scale_h = $height / $this->height;

        if ($default == 'w') {
                $scale = $scale_w;
        } elseif ($default == 'h') {
                $scale = $scale_h;
        } else {
                $scale = max($scale_w, $scale_h);
        }

        if ($scale == 1 && $scale_h == $scale_w && $this->mime != 'image/png') {            
            return $this;
        }

        $new_width = (int)($this->width * $scale);
        $new_height = (int)($this->height * $scale);
        //$xpos = (int)(($width - $new_width) / 2);
        //$ypos = (int)(($height - $new_height) / 2);

        $image_old = $this->image;
        $this->image = imagecreatetruecolor($new_width, $new_height);

        if ($this->mime == 'image/png' ||
            $this->mime == 'image/x-png') {
            imagealphablending($this->image, false);
            imagesavealpha($this->image, true);
            $background = imagecolorallocatealpha($this->image, 255, 255, 255, 127);
            imagecolortransparent($this->image, $background);
        } else {
            $background = imagecolorallocate($this->image, 255, 255, 255);
        }

        imagefilledrectangle($this->image, 0, 0, $new_width, $new_height, $background);

        imagecopyresampled($this->image, $image_old, $xpos, $ypos, 0, 0, $new_width, $new_height, $this->width, $this->height);
        imagedestroy($image_old);

        $this->width = $width;
        $this->height = $height;
        
        return $this;
    }

    public function watermark($watermark, $position = 'bottomright') {
        switch($position) {
            case 'topleft':
                    $watermark_pos_x = 0;
                    $watermark_pos_y = 0;
                    break;
            case 'topcenter':
                    $watermark_pos_x = intval(($this->width - $watermark->getWidth()) / 2);
                    $watermark_pos_y = 0;
                    break;
            case 'topright':
                    $watermark_pos_x = $this->width - $watermark->getWidth();
                    $watermark_pos_y = 0;
                    break;
            case 'middleleft':
                    $watermark_pos_x = 0;
                    $watermark_pos_y = intval(($this->height - $watermark->getHeight()) / 2);
                    break;
            case 'middlecenter':
                    $watermark_pos_x = intval(($this->width - $watermark->getWidth()) / 2);
                    $watermark_pos_y = intval(($this->height - $watermark->getHeight()) / 2);
                    break;
            case 'middleright':
                    $watermark_pos_x = $this->width - $watermark->getWidth();
                    $watermark_pos_y = intval(($this->height - $watermark->getHeight()) / 2);
                    break;
            case 'bottomleft':
                    $watermark_pos_x = 0;
                    $watermark_pos_y = $this->height - $watermark->getHeight();
                    break;
            case 'bottomcenter':
                    $watermark_pos_x = intval(($this->width - $watermark->getWidth()) / 2);
                    $watermark_pos_y = $this->height - $watermark->getHeight();
                    break;
            case 'bottomright':
                    $watermark_pos_x = $this->width - $watermark->getWidth();
                    $watermark_pos_y = $this->height - $watermark->getHeight();
                    break;
        }

        imagealphablending( $this->image, true );
        imagesavealpha( $this->image, true );
        imagecopy($this->image, $watermark->getImage(), $watermark_pos_x, $watermark_pos_y, 0, 0, $watermark->getWidth(), $watermark->getHeight());

        imagedestroy($watermark->getImage());
    }

    public function crop($top_x, $top_y, $bottom_x, $bottom_y) {
        $image_old = $this->image;
        $this->image = imagecreatetruecolor($bottom_x - $top_x, $bottom_y - $top_y);

        imagecopy($this->image, $image_old, 0, 0, $top_x, $top_y, $this->width, $this->height);
        imagedestroy($image_old);

        $this->width = $bottom_x - $top_x;
        $this->height = $bottom_y - $top_y;
        return $this;
    }

    public function rotate($degree, $color = 'FFFFFF') {
        $rgb = $this->html2rgb($color);

        $this->image = imagerotate($this->image, $degree, imagecolorallocate($this->image, $rgb[0], $rgb[1], $rgb[2]));

        $this->width = imagesx($this->image);
        $this->height = imagesy($this->image);
        return $this;
    }

    private function filter() {
        $args = func_get_args();

        call_user_func_array('imagefilter', $args);
        return $this;
    }

    private function text($text, $x = 0, $y = 0, $size = 5, $color = '000000') {
        $rgb = $this->html2rgb($color);
        imagestring($this->image, $size, $x, $y, $text, imagecolorallocate($this->image, $rgb[0], $rgb[1], $rgb[2]));
        return $this;
    }

    private function merge($merge, $x = 0, $y = 0, $opacity = 100) {
        imagecopymerge($this->image, $merge->getImage(), $x, $y, 0, 0, $merge->getWidth(), $merge->getHeight(), $opacity);
        return $this;
    }

    private function html2rgb($color) {
        if ($color[0] == '#') {
                $color = substr($color, 1);
        }

        if (strlen($color) == 6) {
                list($r, $g, $b) = array($color[0] . $color[1], $color[2] . $color[3], $color[4] . $color[5]);
        } elseif (strlen($color) == 3) {
                list($r, $g, $b) = array($color[0] . $color[0], $color[1] . $color[1], $color[2] . $color[2]);
        } else {
                return false;
        }

        $r = hexdec($r);
        $g = hexdec($g);
        $b = hexdec($b);

        return array($r, $g, $b);
    }
}
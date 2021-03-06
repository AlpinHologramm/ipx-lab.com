<?php

class Core_Model_Lib_Image {

    protected $_id;

    protected $_path;

    protected $_color;

    protected $_width;

    protected $_height;

    protected $_crop = false;

    protected $_resources;

    protected $_content;

    protected $_cache_dir;

    public function __construct() {
        $this->_cache_dir = Core_Model_Directory::getImageCacheDirectory(true).'/';
        if(!is_dir($this->_cache_dir)) mkdir($this->_cache_dir, 0777, true);
        return $this;
    }

    public function colorize() {

        $image_name = $this->_id.'.png';
        $cache = $this->_cache_dir;

        if((!file_exists($cache.$image_name) OR !$this->getResources())) {
            try {
                list($width, $height) = getimagesize($this->_path);

                $img = imagecreatefromstring(file_get_contents($this->_path));
                if($this->_color) {
                    $rgb = $this->_toRgb($this->_color);

                    for($x=0; $x<$width; $x++) {
                        for($y=0; $y<$height; $y++) {
                            $colors = imagecolorat($img, $x, $y);
                            $current_rgb = imagecolorsforindex($img, $colors);
                            $color = imagecolorallocatealpha($img, $rgb['red'], $rgb['green'], $rgb['blue'], $current_rgb['alpha']);
                            imagesetpixel($img, $x, $y, $color);
                        }
                    }
                }

                imagesavealpha($img, true);
                imagepng($img, $cache.$image_name);

                $this->_resources = $img;
            } catch(Exception $e) {
                throw new $e;
            }

        }

        return $this;

    }

    public function crop() {

        $image_name = $this->_id.'.png';
        $cache = $this->_cache_dir;

        if($this->_canCrop() AND (!file_exists($cache.$image_name) OR !$this->getResources())) {

            try {
                $newWidth = $this->_width ? $this->_width : $this->_height;
                $newHeight = $this->_height ? $this->_height : $this->_width;
                list($width, $height) = getimagesize($this->_path);
                $img = imagecreatefromstring(file_get_contents($this->_path));
                $newIcon = imagecreatetruecolor($newWidth, $newHeight);
                imagealphablending($newIcon, false);

                imagecopyresampled($newIcon, $img, 0, 0, 0, 0, $newWidth, $newHeight, $width, $height);
                imagesavealpha($newIcon, true);
                imagepng($newIcon, $cache.$image_name);
                $this->_resources = $newIcon;

            } catch(Exception $e) {
                throw new $e;
            }

        }

        return $this;

    }

    public function setId($id) {
        $this->_id = $id;
        return $this;
    }

    public function setPath($path) {
        $this->_path = $path;
        return $this;
    }

    public function getImagePath() {
        return $this->_cache_dir.$this->_id.'.png';
    }

    public function setColor($color) {
        $this->_color = $color;
        return $this;
    }

    public function setWidth($width) {
        $this->_width = $width;
        return $this;
    }

    public function setHeight($height) {
        $this->_height = $height;
        return $this;
    }

    public function setCrop(bool $crop) {
        $this->_crop = $crop;
        return $this;
    }

    public function getResources() {

        if($this->_isValid() AND !$this->_resources) {
            $this->_resources = @imagecreatefrompng($this->getImagePath());
            imagesavealpha($this->_resources, true);
        }
        return $this->_resources;
    }

    public function getUrl($base = false) {
        return $base ? $this->getImagePath() : Core_Model_Directory::getPathTo(str_replace(Core_Model_Directory::getBasePathTo(), '', $this->getImagePath()));
    }

    public function __toString() {

        if($this->_isValid() AND !$this->_content) {
            $this->_content = file_get_contents($this->getImagePath());
        }
        return $this->_content;
    }

    protected function _isValid() {
        return $this->_id && $this->_path && file_exists($this->_path);
    }

    protected function _canCrop() {
        return $this->_width || $this->_height;
    }

    protected function _toRgb($hexStr, $returnAsString = false, $seperator = ','){

        $hexStr = preg_replace("/[^0-9A-Fa-f]/", '', $hexStr);
        $rgbArray = array();

        if (strlen($hexStr) == 6) {
            $colorVal = hexdec($hexStr);
            $rgbArray['red'] = 0xFF & ($colorVal >> 0x10);
            $rgbArray['green'] = 0xFF & ($colorVal >> 0x8);
            $rgbArray['blue'] = 0xFF & $colorVal;
        }
        elseif (strlen($hexStr) == 3) {
            $rgbArray['red'] = hexdec(str_repeat(substr($hexStr, 0, 1), 2));
            $rgbArray['green'] = hexdec(str_repeat(substr($hexStr, 1, 1), 2));
            $rgbArray['blue'] = hexdec(str_repeat(substr($hexStr, 2, 1), 2));
        }
        else {
            return false;
        }

        return $returnAsString ? implode($seperator, $rgbArray) : $rgbArray;
    }

}

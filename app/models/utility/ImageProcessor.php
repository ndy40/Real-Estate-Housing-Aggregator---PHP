<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace models\utility;

use Intervention\Image\Facades\Image;

/**
 * Description of ImageProcessor
 *
 * @author ndy40
 */
class ImageProcessor {
    
    private $original_image;
    
    protected $result;
    
    protected $imageInfo;

    public function __construct($src) {
        $this->original_image = Image::make($src);
        $this->result = $this->original_image;
        if (is_string($src) 
            && (filter_var($src, FILTER_VALIDATE_URL) || is_file($src))
        ){
           $this->imageInfo = pathinfo($src);
        }
    }
    
    public function resize($width, $height = null) 
    {
        if (!$height) {
            $height = $width;
        }
        $this->result->resize($width, $height);
        
        return $this->result;
    }
    
    public function save($path, $quality = 0)
    {
        $this->result->save($path, $quality);
    }
    
    public function crop () {
        throw new \Predis\NotSupportedException("No implementation yet");
    }
    
    public function getOriginal(){
        return $this->original_image;
    }
    
    public function reset()
    {
        return $this->result = $this->original_image;
    }
    
    public function basename() {
        return $this->imageInfo["basename"];
    }
    
    public function extension()
    {
        return $this->imageInfo["extension"];
    }
    
    public function filename(){
        return $this->imageInfo["filename"];
    }
}

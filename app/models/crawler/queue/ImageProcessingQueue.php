<?php
namespace models\crawler\queue;

use models\crawler\abstracts\JobQueue;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Config;
use Intervention\Image\Facades\Image as ImageLib;
use models\entities\Image;
use Illuminate\Support\Facades\Log;

/**
 * Description of ImageProcessingQueue
 *
 * @author ndy40
 */
class ImageProcessingQueue extends JobQueue
{
    protected $propertyRepo;

    protected $imageLibrary;

    protected $dimensions_config;

    protected $filename_template;

    protected $image_dir;

    protected function init()
    {
        $this->propertyRepo = $propertyRepo = App::make("PropertyLogic");
        
        $this->filename_template
            = Config::get("crawler.image_filename_template");
        
        $this->image_dir = array(
            "full"  => Config::get("crawler.image_dir"),
            "thumb" => Config::get("crawler.image_thumb_dir")
        );
        
        $this->dimensions_config["quality"]
            = Config::get("crawler.image_thumb_quality");

        $this->dimensions_config["image_full_quality"]
            = Config::get("crawler.image_full_quality");

        $this->dimensions_config["image_full"]
            = Config::get("crawler.image_full");

        $this->dimensions_config["image_thumb"]
            = Config::get("crawler.image_thumb");
    }

    public function fire($job, $data) 
    {
        $this->init();
        //Get Property ID and Fetch property from DB.
        $property = $this->propertyRepo->findProperty($data["property_id"]);
        
        $action = $data["action"] . "Action";
        $complete = $this->{$action}($property, $data);
        
        if ($complete) {
            $job->delete();
            Log::info("Job {$job->getJobId()} with {$data['action']} action processed successfully");
        } else {
            Log::warning("Job {$job->getJobId()}     with {$data['action']} action didn't complete.\n" . $job["images"]);
            if ($job->attempts() > 3) {
                $job->delete();
            } else {
                $job->release(10);
            }
        }
    }
    
    /**
     * 
     * @param Property $property Instance of Property class.
     * @param mixed[] $data
     * @return boolean True if data saved successfully or false otherwise. 
     */
    public function createAction($property, $data) {
        try {
            $this->savePropertyImages($property, $data["images"]);
        } catch (\Exception $ex) {
            Log::error($ex->getMessage());
        }

        return true;
    }
    
    public function updateAction($property, $data) 
    {
        try{
            $hashString = '';
            $property->images->each(function ($image) use ($hashString) {
                $hashString .= $image->basename;
            });

            $hashStringSrc = "";
            foreach ($data['images'] as $src) {
                $hashStringSrc .= pathinfo($src, PATHINFO_BASENAME);
            }

            if (hash("md5", $hashString) != hash("md5", $hashStringSrc)) {
                $property->images->each(function ($image) {
                    unlink($image->image);
                    unlink($image->thumb);
                    $this->propertyRepo->deleteImage($image->id);
                });
                $this->savePropertyImages($property, $data["images"]);
            }

            return true;
        } catch (\Exception $ex) {
            Log::error($ex->getMessage());
        }
        
        return false;
    }
    
    /**
     * Create image objects from path array.
     * 
     * @param type $property
     * @param array $images
     * @return Image
     */
    protected function processImageSrc($property, array $images) 
    {
        try {
            $image_number = 0;
            $imageObject = array();
            //Loop over images, create image classes Thumbnail etc.
            foreach($images as $src) {
                $image = ImageLib::make($src);
                $basename = pathinfo($src, PATHINFO_BASENAME);
                $filename = sprintf(
                    $this->filename_template,
                    $property->id,
                    ++$image_number,
                    date("d-m-Y")
                );
                $full  = $this->image_dir["full"] . "/" . $filename;

                //create full image
                $image->resize(
                    $this->dimensions_config["image_full"]["width"],
                    $this->dimensions_config["image_full"]["height"]
                );
                $image->save(public_path() . $full, 60);

                //create thumbnail
                $image->backup();
                $image->reset();
                $image->resize(
                    $this->dimensions_config["image_thumb"]["width"],
                    $this->dimensions_config["image_thumb"]["height"]
                );
                $thumb = $this->image_dir["thumb"] . "/" . $filename;
                $image->save(public_path() . $thumb, 20);

                $img = new Image();
                $img->image = Config::get("app.url") . $full;
                $img->thumb = Config::get("app.url") . $thumb;
                $img->enabled = 1;
                $img->basename = $basename;

                $imageObject[] = $img;    
            }
            
            return $imageObject;
            
        } catch (\Exception $ex) {
            echo "   image save exception  ";
            Log::error($ex->getMessage());
        }
        
        return null;
    }
    
    protected function savePropertyImages($property, array $img)
    {
        try {
            $images = $this->processImageSrc($property, $img);
            foreach ($images as $img) {
                $property->images()->save($img);
            }
        } catch (\Exception $ex) {

            Log::error($ex->getMessage());
            return false;
        }

        return true;
    }
    
}

<?php
namespace controllers;

use Intervention\Image\Facades\Image;
use BaseController;

/**
 * Description of ImageTestController
 *
 * @author ndy40
 */
class ImageTestController extends BaseController
{
    public function index()
    {
        $src = "http://roadjournals.viamagazine.com/wp-content/uploads/2011/02/Kelso-dunes-Cool-patterns-in-the-sand-at-sunset1.jpg";
        $image = Image::make($src)->resize(155,103);
        
        return $image->response("jpg");
    }
}

<?php
/**
 * Created by PhpStorm.
 * User: ndy40
 * Date: 05/05/2014
 * Time: 10:33
 */

namespace models\entities\observers;

use Illuminate\Database\Eloquent\ModelNotFoundException;
use models\entities\PropertyChangeLog;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Log;

/**
 * Observer for all property related events.
 * Class PropertyObserver
 * @package models\entities\observers
 */
class PropertyObserver
{
    public function updating ($property)
    {
        $propRepo = App::make("PropertyLogic");
        try {
            $oldProperty = $propRepo->findProperty($property->id);

            if ($property->price != $oldProperty->price) {
                $changeLog = new PropertyChangeLog();
                $changeLog->property()->associate($property);
                $changeLog->old_price = $oldProperty->price;
                $changeLog->new_price = $property->price;
                $propRepo->savePropertyChangeLog($changeLog);

                Log::info(
                    "Property price change " . $property->id
                    . " Old-price:" . $changeLog->old_price
                    . " New-price:" . $changeLog->new_price
                );
            }
        } catch (ModelNotFoundException $ex) {
            Log::warning("Cannot fnd model.");
            return false;
        }

        return true;
    }
}

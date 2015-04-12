<?php
namespace controllers\property;

use BaseController;
use Illuminate\Support\Facades\View;

/**
 * Description of Dashboard
 *
 * @author ndy40
 */
class DashboardController extends BaseController
{
    public function index()
    {
        return View::make("dashboard");
    }
}

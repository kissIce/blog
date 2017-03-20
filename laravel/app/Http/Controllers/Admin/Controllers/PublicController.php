<?php
namespace App\Http\Controllers\Admin\Controllers;

use App\Http\Controllers\Controller;
use Encore\Admin\Facades\Admin;
use Captcha;
class PublicController extends Controller
{
    /*
     * yz_code
     */
    public function code()
    {
        return Captcha::create('flat');
    }

}
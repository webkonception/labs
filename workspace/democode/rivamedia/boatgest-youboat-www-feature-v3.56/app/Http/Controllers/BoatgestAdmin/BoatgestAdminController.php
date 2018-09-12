<?php namespace App\Http\Controllers\boatgestAdmin;

use App\Http\Controllers\Traits\FileUploadTrait;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

abstract class BoatgestAdminController extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests, FileUploadTrait;
}

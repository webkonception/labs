<?php namespace App\Http\Controllers;

    use App\Http\Controllers\Traits\FileUploadTrait;
    use Illuminate\Foundation\Bus\DispatchesJobs;
    use Illuminate\Contracts\Validation\Validator;
    use Illuminate\Routing\Controller as BaseController;
    use Illuminate\Foundation\Validation\ValidatesRequests;
    use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
    //use Illuminate\Foundation\Auth\Access\AuthorizesResources;

    abstract class Controller extends BaseController
    {
        use AuthorizesRequests, DispatchesJobs, ValidatesRequests, FileUploadTrait;
    }

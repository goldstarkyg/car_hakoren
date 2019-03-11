<?php
/**
 * Created by PhpStorm.
 * User: star
 * Date: 2018/05/11
 * Time: 0:30
 */

namespace App\Http\Controllers;


class FileController extends Controller {
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function getFile($filename)
    {
        return response()->download(storage_path().'/licenses/'.$filename, null, [], null);
    }
}
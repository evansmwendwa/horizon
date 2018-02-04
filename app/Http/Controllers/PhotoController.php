<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Photo;
use Illuminate\Support\Facades\DB;

class PhotoController extends Controller
{
  public function index(Request $request)
  {
      $per_page = (int)$request->input('per_page', 30);
      $photos = Photo::paginate($per_page);
      
      return $photos->appends($request->except('page'));
  }
}

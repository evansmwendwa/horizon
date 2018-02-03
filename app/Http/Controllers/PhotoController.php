<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Photo;
use Illuminate\Support\Facades\DB;

class PhotoController extends Controller
{
  public function index()
  {
      $photos = DB::table('photos')->paginate(15);
      return $photos;
  }
}

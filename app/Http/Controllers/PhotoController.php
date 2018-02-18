<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use App\Photo;

class PhotoController extends Controller
{
  public function index(Request $request)
  {
      $per_page = (int)$request->input('per_page', 30);
      $photos = Photo::orderByDesc('creation_date')->paginate($per_page);

      // cleanup unnecessary data
      foreach($photos->items() as $item) {
          $user = clone $item->object->user;
          $item->native_links = $item->object->links;
          unset($user->links, $item->object);
          $item->user = $user;
      }

      return $photos->appends($request->except('page'));
  }

  public function random(Request $request)
  {
      $per_page = (int)$request->input('per_page', 5);

      $photos = Photo::orderBy('likes', 'desc')
        ->take(200)
        ->get()
        ->random($per_page);

      // cleanup unnecessary data
      foreach($photos as $item) {
          $user = clone $item->object->user;
          $item->native_links = $item->object->links;
          unset($user->links, $item->object);
          $item->user = $user;
      }

      return $photos;
  }

  public function search(Request $request)
  {
      $q = $request->input('query');
      $per_page = (int)$request->input('per_page', 30);

      $photos = Photo::search($q)->orderBy('creation_date')->paginate($per_page);

      foreach($photos->items() as $item) {
          $user = clone $item->object->user;
          $item->native_links = $item->object->links;
          unset($user->links, $item->object);
          $item->user = $user;
      }

      return $photos->appends($request->except('page'));
  }
}

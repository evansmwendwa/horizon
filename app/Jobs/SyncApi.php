<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use App\Photo;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;
use Illuminate\Support\Facades\Redis;

class SyncApi implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable;

    protected $page;
    protected $tag;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($tag, $page)
    {
        $this->tag = $tag;
        $this->page = $page;
    }

    /**
     * Get the tags that should be assigned to the job.
     *
     * @return array
     */
    public function tags()
    {
        return ['photo_sync', $this->tag];
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
      Redis::throttle('unsplash')->allow(35)->every(3600)->then(function () {
          $client = new \GuzzleHttp\Client([
              'base_uri' => env("SOURCE_BASE_URL")
          ]);

          $res = $client->request('GET', '/photos', [
              'query' => [
                  'client_id' => env("UNSPLASH_API_KEY"),
                  'per_page' => env("UNSPLASH_PER_PAGE", 30),
                  'order_by' => 'oldest',
                  'page' => $this->page,
              ]
          ]);

          $results = [];

          if ($res->getStatusCode() === 200) {
              $body = (string) $res->getBody();
              $results = json_decode($body);
          }

          foreach($results as $item) {

              $photo = new Photo();

              $photo->tracking_code = $item->id;
              $photo->color = $item->color;
              $photo->creation_date = Carbon::parse($item->created_at);
              $photo->width = $item->width;
              $photo->height = $item->height;
              $photo->likes = $item->likes;
              $photo->description = $item->description;
              $photo->thumbnail = $item->urls->thumb;
              $photo->small = $item->urls->small;
              $photo->regular = $item->urls->regular;
              $photo->full = $item->urls->full;
              $photo->raw = $item->urls->raw;
              $photo->user_id = $item->user->id;
              $photo->object = $item;
              $photo->classified = false;
              $photo->classified_date = Carbon::now();

              $photo->save();

              Log::info($this->tag.' created new record: '. $photo->id);
          }
      }, function () {
          return $this->release(1800);
      });
    }
}

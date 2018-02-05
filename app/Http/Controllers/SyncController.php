<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use GuzzleHttp\Client;
use App\Jobs\SyncApi;
use App\JobTracker;
use Carbon\Carbon;
use Illuminate\Support\Collection;

class SyncController extends Controller
{
    public function sync() {

        $client = new Client([
            'base_uri' => env("SOURCE_BASE_URL")
        ]);

        $res = $client->request('GET', '/photos', [
            'query' => [
                'client_id' => env("UNSPLASH_API_KEY"),
                'per_page' => env("UNSPLASH_PER_PAGE", 30),
                'order_by' => 'oldest'
            ]
        ]);

        if ($res->getStatusCode() === 200) {
            $total = (int)collect($res->getHeader('X-Total'))->first();
            $per_page = (int)collect($res->getHeader('X-Per-Page'))->first();

            $pages = ceil($total/$per_page);

            for ($page = 1; $page <= $pages; $page++) {
                $tag = 'photos_page:'.$page;
                $jobTracking = JobTracker::where('tag', '=', $tag)->first();

                if ($jobTracking === null) {
                    $newJob = new JobTracker();
                    $newJob->tag = $tag;
                    $newJob->save();

                    // dispatch the job here
                    SyncApi::dispatch($tag, $page);
                }
            }
        }
    }
}

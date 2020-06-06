<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Resources\SnapCollection;


class SnapController extends Controller
{
    public function v2_snaps_info(\App\Snap $snap) {
        $publisher = $snap->Publisher()->get()->first();
        $channels = $snap->Channels()->get();
        $channels_sum = 0;
        $response = ([
            "channel-map" => [],
            "default-track" => $snap["default-track"],
            "name" => $snap["name"],
            "snap" => [
                "license" => $snap["license"],
                "name" => $snap["name"],
                "prices" => [],
                "publisher" => [
                    "display-name" => $publisher["display-name"],
                    "id" => $publisher["publisher-id"],
                    "username" => $publisher["username"],
                    "validation" => $publisher["validation"],
                ],
                "snap-id" => $snap["snap-id"],
                "store-url" => $snap["store-url"],
                "summary" => $snap["summary"],
                "title" => $snap["title"],
            ],
            "snap-id" => $snap["snap-id"],
        ]);

        foreach ($channels as $channel)  {
            $download = $channel->Download()->get()->first();
            $response["channel-map"][$channels_sum] = ([
                "channel" => [
                    "architecture" => $channel["architecture"],
                    "name" => $channel["name"],
                    "released-at" => $channel["released-at"],
                    "risk" => $channel["risk"],
                    "track" => $channel["track"],
                ],
                "created-at" => $channel["created-at"],
                "download" => [
                    "deltas" => [],
                    "sha3-384" => $download["sha3-384"],
                    "size" => $download["size"],
                    "url" => $download["url"],
                ],
                "revision" => $channel["revision"],
                "type" => $channel["type"],
                "version" => $channel["version"],
            ]);
            $channels_sum += 1;
        }

        return response()->json($response);
    }
}

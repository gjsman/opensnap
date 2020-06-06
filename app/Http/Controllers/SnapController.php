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

    public function v2_snaps_find(Request $request) {
        $response = [
            "results" => [

            ],
        ];
        $snaps = \App\Snap::where('name', 'like', "%".$request->get('q')."%")->get();
        $snap_iteration = 0;

        foreach ($snaps as $snap) {
            $snap_response = &$response["results"][$snap_iteration];

            $snap_response["name"] = $snap["name"];
            $snap_response["snap"] = [
                "contact" => "",
                "description" => "not implemented",
                "license" => $snap["license"],
                "media" => [],
                "publisher" => [],
                "private" => false,
                "store-url" => $snap["store-url"],
                "summary" => $snap["summary"],
                "title" => $snap["title"],
                "website" => "not implemented",
            ];

            $publisher = $snap->Publisher()->get()->first();
            $snap_response["snap"]["publisher"] = [
                "display-name" => $publisher["display-name"],
                "publisher-id" => $publisher["publisher-id"],
                "username" => $publisher["username"],
                "validation" => $publisher["validation"],
            ];

            $snap_response["snap-id"] = $snap["snap-id"];

            $snap_iteration += 1;
        }

        return response()->json($response);
    }
}

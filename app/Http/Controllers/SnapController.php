<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Resources\SnapCollection;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\URL;
use GuzzleHttp\Exception\ClientException;

class SnapController extends Controller
{
    public function v2_snaps_info(string $name) {
        $snap = \App\Snap::where("name", "=", $name)->get()->first();
        $error = false;
        if (empty($snap)) {
            $client = new Client();
            $url = Url::full();
            $url = str_replace(Url::to('/')."/api", "https://api.snapcraft.io", $url);
            try {
                $request = $client->request("GET", $url, ['headers' => ['Snap-Device-Series' => 16]]);
            } catch (ClientException $e) {
                $request = $e->getResponse();
            }
            /*$passthrough_response = $passthrough->request('GET', $url, [
                "headers" => [
                    "Snap-Device-Series" => "16",
                ],
            ]);*/
            return response()->json(json_decode($request->getBody()->getContents(), true));

        }
        if (!(empty($snap)) || $error == true) {
            $publisher = $snap->Publisher()->get()->first();
            $channels = $snap->Channels()->get();
            $channels_sum = 0;
            $response = ([
                "channel-map" => [],
                "default-track" => $snap["default-track"],
                "name" => $snap["name"],
                "origin" => "opensnap",
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
        }
        

        return response()->json($response);
    }

    public function v2_snaps_find(Request $request) {
        /** 
         * Finds a list of snaps on the opensnap server, followed by snaps on the official Snap Store.
         * If a snap is found on the local server whose name conflicts with the official Snap Store,
         * the OpenSnap server wins and the Snap Store app is not displayed. This behavior may change
         * in a future version.
         */
        $response = [
            "results" => [

            ],
        ];
        $snaps = \App\Snap::where('name', 'like', "%".$request->get('q')."%")->get();
        $snap_iteration = 0;
        $snap_names = array();

        foreach ($snaps as $snap) {
            $snap_names[] = $snap["name"];
            $snap_response = &$response["results"][$snap_iteration];

            $snap_response["name"] = $snap["name"]." [opensnap]";
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
                "origin" => "opensnap",
            ];

            $revision = $snap->Channels()->get()->first();
            $download = $revision->Download()->get()->first();
            $snap_response["revision"] = [
                "base" => null,
                "channel" => $revision["risk"],
                "revision" => $revision["revision"],
                // TODO: Add to DB as option
                "confinement" => "classic",
                "type" => $revision["type"],
                "version" => $revision["version"],
                "download" => [
                    "size" => $download["size"],
                ],
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
        
        $client = new Client();
        $url = Url::full();
        $url = str_replace(Url::to('/')."/api", "https://api.snapcraft.io", $url);
        $request = $client->request("GET", $url, ['headers' => ['Snap-Device-Series' => 16]]);

        $snaps_passthrough = json_decode($request->getBody()->getContents(), true);
        $snap_iteration = count($response) + 1;
        foreach ($snaps_passthrough["results"] as $snap) {
            // The prices field isn't an array, it's an object. Weird, as my PHP tool
            // will automatically convert it to a JSON array for me, but this causes
            // snap to crash. Needs to be an empty object.
            $snap["snap"]["prices"] = (object) null;
            if (!(in_array($snap["name"], $snap_names))) {
                $response["results"][] = $snap;
            }
            $snap_iteration += 1;
        }
        return response()->json($response);
    }
}

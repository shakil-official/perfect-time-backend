<?php

namespace App\Http\Controllers\Event;

use App\Http\Controllers\Controller;
use App\Models\EventBook;
use App\Models\EventManage;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response as ResponseAlias;

class EventController extends Controller
{
    public function __construct()
    {

    }

//    "eventName" => "hello"
//    "contactType" => 1
//    "eventContact" => "32646234638"
//    "eventDescription" => "sdfsfdsfdsfds"
//    "eventSlug" => "fdsf-dsfdf-sdsdfdfd"


    public function createEvent(Request $request)
    {
        $event = EventManage::create([
            "name" => $request['eventName'],
            "contact_type" => $request['contactType'],
            "contact" => $request['eventContact'],
            "description" => $request['eventDescription'],
            "slug" => $request['eventSlug'],
            "user_id" => $request['user_id'],
            "" => '{"hours":[{"value":"Sunday","data":[{"start":9,"end":15}],"available":false},{"value":"Monday","data":[{"start":9,"end":15}],"available":true},{"value":"Tuesday","data":[{"start":9,"end":2}],"available":true},{"value":"Wednesday","data":[{"start":9,"end":15}],"available":true},{"value":"Thursday","data":[{"start":9,"end":15}],"available":true},{"value":"Friday","data":[{"start":9,"end":15}],"available":true},{"value":"Saturday","data":[{"start":9,"end":15}],"available":false}]}'
        ]);

        if ($event) {
            return response()->json([
                'status' => ResponseAlias::HTTP_CREATED,
                'message' => "Event created successfully !"
            ], ResponseAlias::HTTP_CREATED);
        }

        return response()->json([
            'status' => ResponseAlias::HTTP_BAD_REQUEST,
            'message' => "Something is wrong!!"
        ], ResponseAlias::HTTP_BAD_REQUEST);

    }

    public function getEventList(Request $request)
    {
        $event = EventManage::where([
            "user_id" => $request['user_id'],
        ])->orderBy('id', 'DESC')->get();

        if (!is_null($event)) {
            return response()->json([
                'data' => $event,
                'status' => ResponseAlias::HTTP_OK,
                'message' => "Event List"
            ], ResponseAlias::HTTP_OK);
        }

        return response()->json([
            'status' => ResponseAlias::HTTP_BAD_REQUEST,
            'message' => "Something is wrong!!"
        ], ResponseAlias::HTTP_BAD_REQUEST);

    }


    public function getEventScheduleList(Request $request)
    {
        $event = EventBook::where([
            "user_id" => $request['user_id'],
        ])->orderBy('id', 'DESC')->get();

        if (!is_null($event)) {
            return response()->json([
                'data' => $event,
                'status' => ResponseAlias::HTTP_OK,
                'message' => "Event Schedule List"
            ], ResponseAlias::HTTP_OK);
        }

        return response()->json([
            'status' => ResponseAlias::HTTP_BAD_REQUEST,
            'message' => "Something is wrong!!"
        ], ResponseAlias::HTTP_BAD_REQUEST);

    }


}

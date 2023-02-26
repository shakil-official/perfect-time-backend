<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\UserLoginRequest;
use App\Models\EventBook;
use App\Models\EventManage;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Hash;
use Symfony\Component\HttpFoundation\Response as ResponseAlias;

class UserAuthController extends Controller
{
    public function login(UserLoginRequest $request)
    {
        $credentials = request(['email', 'password']);

        if (!$token = auth()->guard('web')->attempt($credentials)) {
            return response()->json([
                'data' => [
                    'error' => 'Unauthorized'
                ],
                'status_code' => 401
            ], 401);
        }

        return response()->json([
            'data' => [
                'access_token' => $token,
                'token_type' => 'bearer',
                'user_info' => auth()->guard('web')->user(),
                'type' => 2,
                'expires_in' => auth()->guard('web')->factory()->getTTL() * 160
            ],
            'status_code' => 200
        ]);
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function refresh(Request $request): JsonResponse
    {
        return response()->json([
            'data' => [
                'access_token' => auth('web')->refresh(),
                'token_type' => 'bearer',
                'expires_in' => auth()->guard('web')->factory()->getTTL() * 160
            ],
            'status_code' => 200
        ]);
    }

    /**
     * @return JsonResponse
     */
    public function logout(): JsonResponse
    {
        auth('student')->logout();

        return response()->json(['message' => 'Successfully logged out']);
    }


    public function signup(Request $request)
    {
        if (trim($request['email']) == '') {
            return response()->json([
                'status' => ResponseAlias::HTTP_UNPROCESSABLE_ENTITY,
                'message' => "Email can not be empty"
            ], ResponseAlias::HTTP_UNPROCESSABLE_ENTITY);
        }

        if (trim($request['password']) == '') {
            return response()->json([
                'status' => ResponseAlias::HTTP_UNPROCESSABLE_ENTITY,
                'message' => "Password can not be empty"
            ], ResponseAlias::HTTP_UNPROCESSABLE_ENTITY);
        }

        $userCheck = User::where([
            'email' => trim($request['email'])
        ])->first();

        if (!is_null($userCheck)) {
            return response()->json([
                'status' => ResponseAlias::HTTP_UNPROCESSABLE_ENTITY,
                'message' => "User already exist"
            ], ResponseAlias::HTTP_UNPROCESSABLE_ENTITY);
        }

        $user = User::create([
            'email' => trim($request['email']),
            'password' => Hash::make(trim($request['password'])),
            'available_time' => User::AVAILABLETIME()
        ]);

        if ($user) {
            return response()->json([
                'status' => ResponseAlias::HTTP_CREATED,
                'message' => "Congratulation you are the part of your perfect time !!"
            ], ResponseAlias::HTTP_CREATED);
        }


        return response()->json([
            'status' => ResponseAlias::HTTP_BAD_REQUEST,
            'message' => "Something is wrong!!"
        ], ResponseAlias::HTTP_BAD_REQUEST);

    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function accountUpdate(Request $request): JsonResponse
    {
        try {
            $user = User::where([
                'id' => trim($request['user_id'])
            ])->update([
                "name" => $request['name'],
                "time_zone" => $request['timeZone'],
                "time_format" => $request['timeFormat'],
                "available_time" => json_encode($request['availableTime']),
            ]);

            if ($user) {
                return response()->json([
                    'status' => ResponseAlias::HTTP_OK,
                    'message' => "Account update successfully !!"
                ], ResponseAlias::HTTP_OK);
            }

            return response()->json([
                'status' => ResponseAlias::HTTP_BAD_REQUEST,
                'message' => "Something is wrong!!"
            ], ResponseAlias::HTTP_BAD_REQUEST);

        } catch (\Exception $exception) {

            return response()->json([
                'status' => ResponseAlias::HTTP_BAD_REQUEST,
                'message' => "Something is wrong!!",
                'data' => $exception->getMessage(),
            ], ResponseAlias::HTTP_BAD_REQUEST);
        }
    }

    public function accountInfo(Request $request): JsonResponse
    {
        try {
            $user = User::select(['id', 'name', 'email', 'time_zone', 'time_format', 'available_time'])->where([
                'id' => trim($request['user_id'])
            ])->first();

            if ($user) {
                return response()->json([
                    'status' => ResponseAlias::HTTP_OK,
                    'message' => "Account Information",
                    'data' => $user
                ], ResponseAlias::HTTP_OK);
            }

            return response()->json([
                'status' => ResponseAlias::HTTP_BAD_REQUEST,
                'message' => "Something is wrong!!"
            ], ResponseAlias::HTTP_BAD_REQUEST);

        } catch (\Exception $exception) {

            return response()->json([
                'status' => ResponseAlias::HTTP_BAD_REQUEST,
                'message' => "Something is wrong!!",
                'data' => $exception->getMessage(),
            ], ResponseAlias::HTTP_BAD_REQUEST);
        }
    }

    public function userSlugInfo(Request $request)
    {
        $event = EventManage::select(['id', 'name', 'contact', 'user_id', 'description'])->where([
            "slug" => trim($request['slug'])

        ])->first();

        if (is_null($event)) {
            return response()->json([
                'data' => [],
                'status' => ResponseAlias::HTTP_BAD_REQUEST,
                'message' => "Something is wrong!!",
            ], ResponseAlias::HTTP_BAD_REQUEST);
        }

        $user = User::select(['id', 'name', 'email', 'time_zone', 'time_format', 'available_time'])->where([
            'id' => $event->user_id
        ])->first();

        $availableTime = json_decode($user->available_time, true);


        $availableDay = [];
        $notAvailableDay = [];
        $availableDayList = [];


        foreach ($availableTime['hours'] as $key => $value) {
            $availableDayList[] = $value;
            if ($value['available']) {
                $availableDay[] = $key;
            } else {
                $notAvailableDay[] = $key;
            }
        }

        return response()->json([
            'data' => [
                'event' => $event,
                'user' => $user,
                'available_day' => $availableDay,
                'not_available_day' => $notAvailableDay,
                'available_day_list' => $availableDayList,
            ],
            'status' => ResponseAlias::HTTP_OK,
            'message' => "Slug information list",
        ], ResponseAlias::HTTP_OK);

    }


    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function slotBooking(Request $request)
    {
        $date = Carbon::parse($request['current_date'])->format('Y-m-d');
        $time = EventBook::TIME_SLOT[$request['select_slot']];
        $dateTime = new Carbon($date . ' ' . $time);
        EventBook::TIME_SLOT[$request['select_slot']];

//        dd($date, Carbon::createFromFormat('Y-m-d H:i:s', $dateTime, $request['time_zone'])->setTimezone('UTC'));

        $booking = EventBook::create([
            'name' => $request['name'],
            'email' => $request['email'],
            'slot' => $request['select_slot'],
            'user_id' => $request['user_id'],
            'booking_date' => Carbon::createFromFormat('Y-m-d H:i:s', $dateTime, $request['time_zone'])->setTimezone('UTC'),
        ]);

        if ($booking) {
            return response()->json([
                'status' => ResponseAlias::HTTP_OK,
                'message' => "Account update successfully !!"
            ], ResponseAlias::HTTP_OK);
        }


        return response()->json([
            'status' => ResponseAlias::HTTP_BAD_REQUEST,
            'message' => "Something is wrong!!",
        ], ResponseAlias::HTTP_BAD_REQUEST);
    }


    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function getUsedSlot(Request $request)
    {
        $date = Carbon::parse($request['date'])->format('Y-m-d');
        $dateTime =  Carbon::createFromFormat('Y-m-d', $date, $request['time_zone'])->setTimezone('UTC');

        $eventBook = EventBook::select(['slot'])->whereDate('booking_date', $dateTime->format('Y-m-d'))->get()->toArray();

        $slot = [];

        foreach ($eventBook as $value){
            $slot[] = $value['slot'];
        }

        return response()->json([
            'status' => ResponseAlias::HTTP_OK,
            'data' => $slot,
            'message' => "Account update successfully !!"
        ], ResponseAlias::HTTP_OK);


    }


}

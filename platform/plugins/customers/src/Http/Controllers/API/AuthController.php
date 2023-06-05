<?php

namespace Botble\Customers\Http\Controllers\API;

use Botble\Base\Http\Controllers\BaseController;
use Botble\Customers\Models\Customers;
use Botble\Checkin\Models\Checkin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;
use Botble\Base\Http\Responses\BaseHttpResponse;
use Symfony\Bridge\PsrHttpMessage\Factory\DiactorosFactory;
use Carbon\Carbon;
use DateTime;

class AuthController extends BaseController
{
    /**
     * Get a JWT via given credentials.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected $user;

    public function __construct()
    {
        $this->middleware('auth:api');
    }


    public function logout(Request $request, BaseHttpResponse $response)
    {
        $request->user()->token()->revoke();

        return $response->setMessage(__('You have been successfully logged out!'));
    }

    public function attendance (Request $request, BaseHttpResponse $response) {
        // $user = JWTAuth::toUser("eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJodHRwOi8vbG9jYWxob3N0OjgwMDAvYXBpL3YxL2N1c3RvbWVyL2xvZ2luIiwiaWF0IjoxNjgyNDQ0MDk2LCJleHAiOjE2OTEwODQwOTYsIm5iZiI6MTY4MjQ0NDA5NiwianRpIjoiSldSTU5iWWFaTWR0MDM3aiIsInN1YiI6IjEyIiwicHJ2IjoiODE2ZGJlY2UwMzY3MDE1ODE0NjQwZmZjMTU5MjhkYzAxOWRlOGZhYyJ9.98SvhRMc79PMlM0yZC2nT5sfnktpYO6OG2xO7TcSrPU");
        $user = auth()->user();
        
        if ($user) {
            $date = Carbon::parse(now(config('app.timezone'))->format('Y-m-d'))->toDateString();
            if ($user->attendance_day === $date) {
                return response()->json([
                    "error" => true,
                    "data" => "This user checked in today",
                ]);
            } else {
                Customers::where('id', $user->id)->update([
                    'attendance_day' => $date,
                    'attendance' => $user->attendance + 1,
                ]);
                Checkin::insert(
                    [
                        'user_id' => $user->id,
                        'date' => $date,
                    ]
                );
                return response()->json([
                    "error" => false,
                    "data" => "Successful attendance",
                ]);
            }
        } else {
            return response()->json([
                "error" => true,
                "data" => "This user does not exist"
            ]);
        }
    }

    public function get_list_attendance (Request $request, BaseHttpResponse $response) {
        $user = auth()->user();
        $checkins = Checkin::where('user_id', $user->id)->get();
        $dates = [];
        foreach ($checkins as $checkin) {
            $dates[] = $checkin->date;
          }
        return response()->json([
            "error" => false,
            "data" => $dates,
        ]);
    }

    public function streak (Request $request, BaseHttpResponse $response) {
        $user = auth()->user();
        $checkins = Checkin::where('user_id', $user->id)->get();
        $dateList = [];
        foreach ($checkins as $checkin) {
            $dateList[] = $checkin->date;
        }

        $date = Carbon::parse(now(config('app.timezone')));
        $yesterday = $date->modify('-1 day')->format('Y-m-d');
        $today = $date->modify('+1 day')->format('Y-m-d');
        if ($yesterday != end($dateList) && $today != end($dateList)) {
            return response()->json([
                "error" => false,
                "streak" => 0,
            ]);
        }

        $closestStreak = 0;
        $currentStreak = 1;

        for ($i = count($dateList) - 1; $i > 0; $i--) {
            $currentDate = new DateTime($dateList[$i]);
            $nextDate = new DateTime($dateList[$i - 1]);
            $diff = $currentDate->diff($nextDate)->days;

            if ($diff == 1) {
                $currentStreak++;
            } else {
                break;
            }

            // if ($currentStreak > $closestStreak) {
            //     $closestStreak = $currentStreak;
            // }
            
        }
        $closestStreak = $currentStreak;
        return response()->json([
            "error" => false,
            "streak" => $closestStreak,
        ]);
    }
}
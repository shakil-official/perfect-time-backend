<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class EventBook extends Model
{
    protected $fillable = [
        'name',
        'email',
        'slot',
        'booking_date',
        'user_id'
    ];


    const TIME_SLOT = [
        "12:00 AM", "1:00 AM",
        "2:00 AM", "3:00 AM",
        "4:00 AM", "5:00 AM",
        "6:00 AM", "7:00 AM",
        "8:00 AM", "9:00 AM",
        "10:00 AM", "11:00 AM",
        "12:00 PM", "1:00 PM",
        "2:00 PM", "3:00 PM",
        "4:00 PM", "5:00 PM",
        "6:00 PM", "7:00 PM",
        "8:00 PM", "9:00 PM",
        "10:00 PM", "11:00 PM",
    ];


    public function getBookingDateAttribute($value)
    {
        $user = User::where(['id' => \request()->get('user_id')])->first();
        $date = Carbon::parse($value)->setTimezone($user->time_zone)->format('Y-m-d');

        return Carbon::createFromFormat('Y-m-d', $date, 'UTC')->setTimezone($user->time_zone)->toString() ;
    }


}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EventManage extends Model
{
    protected $fillable = [
        "name",
        "contact_type",
        "contact",
        "description",
        "slug",
        "user_id"
    ];
}

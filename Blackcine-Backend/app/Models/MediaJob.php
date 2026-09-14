<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MediaJob extends Model
{
    protected $fillable = [
        'job_type', 'payload', 'status', 'attempts', 'error_message', 'ran_at', 'completed_at',
    ];
}

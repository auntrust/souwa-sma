<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Seminar extends Model
{
    /** @use HasFactory<\Database\Factories\SeminarFactory> */
    use HasFactory;

    protected $fillable = ['is_active', 'seminar_type', 'name', 'description', 'seminar_date', 'start_time', 'end_time', 'benefits', 'capacity', 'company_name', 'company_zip', 'company_address', 'company_building', 'company_tel', 'company_url', 'company_email', 'company_speaker_info', 'is_paid', 'paid_fee', 'is_consult', 'timerex_url', 'is_review', 'google_review_url', 'venue_name', 'venue_zip', 'venue_address', 'venue_building', 'venue_tel', 'venue_map_url', 'online_url', 'online_id', 'online_pwd', 'webinar_url', 'webinar_start_at', 'webinar_end_at', 'back_url', 'finish_url', 'is_deleted'];
}

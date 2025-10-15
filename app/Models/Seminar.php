<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Seminar extends Model
{
    /** @use HasFactory<\Database\Factories\SeminarFactory> */
    use HasFactory;

    protected $fillable = ['is_active', 'name', 'description', 'speaker_info', 'benefits', 'detail_url', 'seminar_type', 'onsite_name', 'onsite_zip', 'onsite_address', 'onsite_building', 'onsite_map_url', 'onsite_date', 'onsite_start_time', 'onsite_end_time', 'onsite_capacity', 'online_url', 'online_id', 'online_pwd', 'online_date', 'online_start_time', 'online_end_time', 'online_capacity', 'webinar_url', 'webinar_start_at', 'webinar_end_at', 'organizer_name', 'organizer_tel', 'organizer_email', 'is_paid', 'paid_fee', 'is_consult', 'timerex_url', 'is_review', 'google_review_url'];

    /**
     * セミナー参加者とのリレーション
     */
    public function seminarCustomers()
    {
        return $this->hasMany(SeminarCustomer::class);
    }
}

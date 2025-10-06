<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SeminarCustomer extends Model
{
    /** @use HasFactory<\Database\Factories\SeminarCustomerFactory> */
    use HasFactory;

    protected $fillable = ['seminar_id', 'customer_id', 'entry_date', 'name', 'furigana', 'tel', 'email', 'todofuken', 'co_name', 'co_tel', 'co_busho', 'co_post', 'applicant_count', 'request', 'is_overwrite_customer_info', 'mail_sent_at'];
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class EmailLog extends Model
{
    use HasFactory;

    protected $fillable = ['mail_type', 'recipient_email', 'recipient_name', 'seminar_id', 'customer_id', 'seminar_customer_id', 'status', 'sent_at', 'error_message'];

    protected $casts = [
        'sent_at' => 'datetime',
    ];

    /**
     * セミナーとのリレーション
     */
    public function seminar()
    {
        return $this->belongsTo(Seminar::class);
    }

    /**
     * 顧客とのリレーション
     */
    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    /**
     * セミナー申込とのリレーション
     */
    public function seminarCustomer()
    {
        return $this->belongsTo(SeminarCustomer::class);
    }

    /**
     * メール種別の日本語名を取得
     */
    public function getMailTypeNameAttribute(): string
    {
        $types = [
            'entry' => '受付メール',
            'reminder' => 'リマインダーメール',
            'announcement' => '告知メール',
            'thank_you' => 'お礼メール',
        ];

        return $types[$this->mail_type] ?? $this->mail_type;
    }

    /**
     * 送信状態の日本語名を取得
     */
    public function getStatusNameAttribute(): string
    {
        $statuses = [
            'success' => '送信成功',
            'failed' => '送信失敗',
        ];

        return $statuses[$this->status] ?? $this->status;
    }

    /**
     * 送信成功のスコープ
     */
    public function scopeSuccess($query)
    {
        return $query->where('status', 'success');
    }

    /**
     * 送信失敗のスコープ
     */
    public function scopeFailed($query)
    {
        return $query->where('status', 'failed');
    }

    /**
     * 今日送信されたもののスコープ
     */
    public function scopeToday($query)
    {
        return $query->whereDate('sent_at', Carbon::today());
    }

    /**
     * 特定のメール種別のスコープ
     */
    public function scopeOfType($query, $type)
    {
        return $query->where('mail_type', $type);
    }
}

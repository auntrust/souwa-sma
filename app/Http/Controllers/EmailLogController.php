<?php

namespace App\Http\Controllers;

use App\Models\EmailLog;
use App\Models\Seminar;
use App\Models\Customer;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Carbon\Carbon;

class EmailLogController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = EmailLog::with(['seminar', 'customer', 'seminarCustomer']);

        // 検索条件の処理
        $search_str = $request->input('search_str');
        $mail_type = $request->input('mail_type');
        $status = $request->input('status');
        $date_from = $request->input('date_from');
        $date_to = $request->input('date_to');

        // テキスト検索
        if (!empty($search_str)) {
            $query->where(function ($q) use ($search_str) {
                $q->where('recipient_email', 'LIKE', '%' . $search_str . '%')
                    ->orWhere('recipient_name', 'LIKE', '%' . $search_str . '%')
                    ->orWhereHas('seminar', function ($sq) use ($search_str) {
                        $sq->where('name', 'LIKE', '%' . $search_str . '%');
                    })
                    ->orWhereHas('customer', function ($cq) use ($search_str) {
                        $cq->where('name', 'LIKE', '%' . $search_str . '%')->orWhere('email', 'LIKE', '%' . $search_str . '%');
                    });
            });
        }

        // 送信状態フィルタ
        if (!empty($status)) {
            $query->where('status', $status);
        }

        // 日付範囲フィルタ
        if (!empty($date_from)) {
            $query->whereDate('sent_at', '>=', $date_from);
        }
        if (!empty($date_to)) {
            $query->whereDate('sent_at', '<=', $date_to);
        }

        $emailLogs = $query->orderByDesc('sent_at')->orderByDesc('id')->paginate(20);

        return Inertia::render('EmailLogs/Index', [
            'emailLogs' => $emailLogs,
            'search_str' => $search_str,
            'mail_type' => $mail_type,
            'status' => $status,
            'date_from' => $date_from,
            'date_to' => $date_to,
        ]);
    }

    /**
     * 再送信処理
     */
    public function resend(EmailLog $emailLog)
    {
        try {
            // 元のメールログが失敗の場合のみ再送信を許可
            if ($emailLog->status !== 'failed') {
                return back()->withErrors(['message' => '送信成功済みのメールは再送信できません。']);
            }

            // メール種別に応じた再送信処理
            $jobClass = $this->getJobClassByMailType($emailLog->mail_type);

            if ($jobClass && $emailLog->seminar && $emailLog->seminarCustomer) {
                // ジョブをキューに追加
                dispatch(new $jobClass($emailLog->seminar->id, [$emailLog->seminar_customer_id]));

                return back()->with('success', 'メールの再送信を開始しました。');
            }

            return back()->withErrors(['message' => '再送信に必要な情報が不足しています。']);
        } catch (\Exception $e) {
            return back()->withErrors(['message' => '再送信に失敗しました: ' . $e->getMessage()]);
        }
    }
}

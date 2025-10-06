<?php

namespace App\Http\Controllers;

use App\Models\Seminar;
use App\Models\Customer;
use App\Models\SeminarCustomer;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Http\Requests\SeminarCustomerRequest;
use Inertia\Inertia;
use Carbon\Carbon;
use Illuminate\Support\Facades\Mail;
use App\Mail\SeminarEntryNotification;

class SeminarCustomerController extends Controller
{
    /**
     * Show the form for creating a new resource.
     */
    public function entry(Request $request, $sid, $uid = null)
    {
        $seminar = Seminar::where('unique_key', $sid)->first();
        $customer = Customer::where('unique_key', $uid)->first();

        if (!$seminar) {
            abort(404, 'セミナーが見つかりません');
        }

        return Inertia::render('Entry', [
            'seminar' => $seminar,
            'customer' => $customer,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $seminar = Seminar::where('id', $request->input('seminar_id'))->first();

        // emailを元に新規顧客が登録されているかチェック
        $email = $request->input('email');
        $customer = Customer::where('email', $email)->first();

        // 登録されていない場合
        if (!$customer) {
            // ユニークキーを生成して設定（12桁英数字）
            do {
                $uniqueKey = Str::random(12);
            } while (Customer::where('unique_key', $uniqueKey)->exists());

            // 顧客を登録する
            $customer = Customer::create([
                'unique_key' => $uniqueKey,
                'name' => $request->input('name'),
                'furigana' => $request->input('furigana'),
                'tel' => $request->input('tel'),
                'email' => $email,
                'todofuken' => $request->input('todofuken'),
                'co_name' => $request->input('co_name'),
                'co_tel' => $request->input('co_tel'),
                'co_busho' => $request->input('co_busho'),
                'co_post' => $request->input('co_post'),
                'optin_at' => Carbon::now()->format('Y-m-d H:i'),
                'optin_method' => 'セミナー申し込み時',
            ]);
        } else {
            // 顧客を上書きする
            if ($request->input('is_overwrite_customer_info') == 1) {
                $customer->update([
                    'name' => $request->input('name'),
                    'furigana' => $request->input('furigana'),
                    'tel' => $request->input('tel'),
                    'todofuken' => $request->input('todofuken'),
                    'co_name' => $request->input('co_name'),
                    'co_tel' => $request->input('co_tel'),
                    'co_busho' => $request->input('co_busho'),
                    'co_post' => $request->input('co_post'),
                ]);
            }
        }
        if ($customer) {
            $request->merge(['customer_id' => $customer->id]);
            $request->merge(['entry_date' => Carbon::now()]);
        }

        // メール送信日時をセット
        $request->merge(['mail_sent_at' => Carbon::now()]);

        $seminarCustomer = new SeminarCustomer($request->input());
        $seminarCustomer->save();

        // メール送信
        // Mail::to($customer->email)->send(new SeminarEntryNotification($seminarCustomer, $customer, $seminar));
        // Mail::to(config('mail.admin_address'))->send(new SeminarEntryNotification($seminarCustomer, $customer, $seminar));

        return redirect('/entry_finish');
    }
}

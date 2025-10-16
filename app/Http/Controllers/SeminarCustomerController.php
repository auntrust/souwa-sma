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
use App\Mail\SeminarEntryMail;

class SeminarCustomerController extends Controller
{
    /**
     * Show the form for creating a new resource.
     */
    public function entry(Request $request, $sid, $cid = null)
    {
        $seminar = Seminar::where('unique_key', $sid)->first();
        $customer = Customer::where('unique_key', $cid)->first();

        if (!$seminar) {
            abort(404, '該当のセミナーが存在しません');
        }

        return Inertia::render('Entry', [
            'seminar' => $seminar,
            'customer' => $customer,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function entryStore(Request $request)
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
        $request->merge(['mail_sent_entry_at' => Carbon::now()]);

        $seminarCustomer = new SeminarCustomer($request->input());
        $seminarCustomer->save();

        // メール送信
        Mail::to($customer->email)->send(new SeminarEntryMail('お申し込みありがとうございます', $request, $seminar));
        Mail::to(config('mail.admin_address'))->send(new SeminarEntryMail('お申し込みがありました', $request, $seminar));

        return redirect('/entry_finish');
    }

    /**
     * セミナー評価フォーム
     */
    public function feedback(Request $request, $sid, $cid = null)
    {
        $seminar = Seminar::where('unique_key', $sid)->first();
        $customer = Customer::where('unique_key', $cid)->first();

        if (!$seminar or !$customer or !$seminar->is_review) {
            abort(404, '該当のアンケートが存在しません');
        }

        return Inertia::render('Feedback', [
            'seminar' => $seminar,
            'customer' => $customer,
        ]);
    }

    /**
     * 評価フォーム保存
     */
    public function feedbackStore(Request $request)
    {
        // セミナー情報を取得
        $seminarUniqueKey = $request->input('sid');
        $seminar = Seminar::where('unique_key', $seminarUniqueKey)->first();
        $seminarId = $seminar->id;

        // 顧客情報を取得
        $customerUniqueKey = $request->input('cid');
        $customer = Customer::where('unique_key', $customerUniqueKey)->first();
        $customerId = $customer->id;

        // セミナー顧客情報を取得
        $seminarCustomer = SeminarCustomer::where('seminar_id', $seminarId)->where('customer_id', $customerId)->first();

        // 評価にlowもしくはhighをセット
        $survey_q1 = $request->input('survey_q1');
        if ($survey_q1 <= 4) {
            $request->merge(['survey_rating' => 'low']);
        } elseif ($survey_q1 >= 5) {
            $request->merge(['survey_rating' => 'high']);
        }
        // アンケート回答日時をセット
        $request->merge(['survey_at' => Carbon::now()->format('Y-m-d H:i')]);

        // アンケート評価をセット
        $seminarCustomer->update($request->input());

        // 低評価の場合はフィードバックフォームへ、高評価の場合は完了画面へリダイレクト
        $survey_rating = $request->input('survey_rating');
        if ($survey_rating === 'low') {
            return redirect('/feedback_l_input/' . $seminarUniqueKey . '/' . $customerUniqueKey);
        } else {
            return redirect('/feedback_h_finish/' . $seminarUniqueKey . '/' . $customerUniqueKey);
        }
    }

    /**
     * 評価ページ完了画面（高評価）
     */
    public function feedbackHighFinish(Request $request, $sid, $cid)
    {
        $seminar = Seminar::where('unique_key', $sid)->first();
        $customer = Customer::where('unique_key', $cid)->first();

        if (!$seminar or !$customer or !$seminar->is_review) {
            abort(404, '該当のアンケートが存在しません');
        }

        return Inertia::render('FeedbackHighFinish', [
            'seminar' => $seminar,
            'customer' => $customer,
        ]);
    }

    /**
     * 評価ページ完了画面（低評価入力）
     */
    public function feedbackLowInput($sid, $cid)
    {
        $seminar = Seminar::where('unique_key', $sid)->first();
        $customer = Customer::where('unique_key', $cid)->first();

        if (!$seminar or !$customer or !$seminar->is_review) {
            abort(404, '該当のアンケートが存在しません');
        }

        return Inertia::render('FeedbackLowInput', [
            'seminar' => $seminar,
            'customer' => $customer,
        ]);
    }

    /**
     * 評価ページ完了画面（低評価入力保存）
     */
    public function feedbackLowInputStore(Request $request)
    {
        // セミナー情報を取得
        $seminarUniqueKey = $request->input('sid');
        $seminar = Seminar::where('unique_key', $seminarUniqueKey)->first();
        $seminarId = $seminar->id;

        // 顧客情報を取得
        $customerUniqueKey = $request->input('cid');
        $customer = Customer::where('unique_key', $customerUniqueKey)->first();
        $customerId = $customer->id;

        // セミナー顧客情報を取得
        $seminarCustomer = SeminarCustomer::where('seminar_id', $seminarId)->where('customer_id', $customerId)->first();

        // cid, sidを除いたリクエストデータで更新
        $seminarCustomer->update($request->input());

        // メール送信
        // Mail::to($customer->email)->send(new SeminarEntryNotification('お申し込みありがとうございます', $request, $seminar));
        // Mail::to(config('mail.admin_address'))->send(new SeminarEntryNotification('お申し込みがありました', $request, $seminar));

        return redirect('/feedback_l_finish/' . $seminarUniqueKey . '/' . $customerUniqueKey);
    }

    /**
     * 評価ページ完了画面（低評価完了）
     */
    public function feedbackLowFinish($sid, $cid)
    {
        $seminar = Seminar::where('unique_key', $sid)->first();
        $customer = Customer::where('unique_key', $cid)->first();

        if (!$seminar or !$customer or !$seminar->is_review) {
            abort(404, '該当のアンケートが存在しません');
        }

        return Inertia::render('FeedbackLowFinish', [
            'seminar' => $seminar,
            'customer' => $customer,
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(SeminarCustomer $seminarCustomer)
    {
        $seminarCustomer->delete();
        return redirect('seminars/entry_list/' . $seminarCustomer->seminar_id)->with('success', '参加者情報を削除しました。');
    }
}

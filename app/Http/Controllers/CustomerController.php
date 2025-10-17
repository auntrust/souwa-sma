<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Http\Requests\CustomerRequest;
use Inertia\Inertia;

class CustomerController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if (empty($request->input()['search_str'])) {
            $search_str = null;
            $customers = Customer::orderBy('created_at', 'desc')->paginate(10);
        } else {
            $search_str = $request->input()['search_str'];
            $customers = Customer::where('name', 'LIKE', '%' . $search_str . '%')
                ->orWhere('furigana', 'LIKE', '%' . $search_str . '%')
                ->orWhere('email', 'LIKE', '%' . $search_str . '%')
                ->orWhere('co_name', 'LIKE', '%' . $search_str . '%')
                ->orderBy('created_at', 'desc')
                ->paginate(50);
        }
        return Inertia::render('Customers/Index', [
            'customers' => $customers,
            'search_str' => $search_str,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return Inertia::render('Customers/Create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CustomerRequest $request)
    {
        // dd($request->input());
        $customer = new Customer($request->input());

        // ユニークキーを生成して設定（12桁英数字）
        do {
            $uniqueKey = Str::random(12);
        } while (Customer::where('unique_key', $uniqueKey)->exists());
        $customer->unique_key = $uniqueKey;

        // 配信停止フラグがオンの場合、unsubscribe_atに現在時刻を設定
        if ($request->is_unsubscribe == 1) {
            $customer->unsubscribe_at = now();
        }

        $customer->save();
        return redirect()->route('customers.index')->with('success_str', '登録完了しました');
    }

    /**
     * Display the specified resource.
     */
    public function show(Customer $customer)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Customer $customer)
    {
        return Inertia::render('Customers/Edit', ['customer' => $customer]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(CustomerRequest $request, Customer $customer)
    {
        $data = $request->input();

        // 配信停止の処理
        if ($request->is_unsubscribe == 1) {
            // すでにunsubscribe_atに日付が入っている場合は更新しない
            if (is_null($customer->unsubscribe_at)) {
                $data['unsubscribe_at'] = now();
            }
        } else {
            // 配信再開の場合はunsubscribe_atをnullにする
            $data['unsubscribe_at'] = null;
        }
        $customer->update($data);
        return redirect()->route('customers.index')->with('success_str', '更新完了しました');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Customer $customer)
    {
        $customer->delete();
        return redirect('customers');
    }

    /**
     * ユニークキーで顧客を検索する
     */
    private function findCustomerByUniqueKey($uniqueKey)
    {
        $customer = Customer::where('unique_key', $uniqueKey)->first();

        if (!$customer) {
            abort(404, '指定された顧客が見つかりません。');
        }

        return $customer;
    }

    /**
     * 配信状態を変更する共通メソッド
     */
    private function changeSubscriptionStatus($cid, $isSubscribed, $viewName)
    {
        $customer = $this->findCustomerByUniqueKey($cid);

        $previousStatus = $customer->is_unsubscribe;

        // フラグと日時の整合を取る
        $data = [
            'is_unsubscribe' => $isSubscribed ? 0 : 1,
        ];

        if ($isSubscribed) {
            // 配信再開
            $data['unsubscribe_at'] = null;
        } else {
            // 配信停止（未設定のときのみタイムスタンプを付与）
            if (is_null($customer->unsubscribe_at)) {
                $data['unsubscribe_at'] = now();
            }
        }

        $customer->update($data);

        return Inertia::render($viewName, [
            'customer' => $customer,
            'cid' => $cid,
            'wasAlreadyInTargetState' => $previousStatus === ($isSubscribed ? 0 : 1),
        ]);
    }

    /**
     * メール案内の配信停止処理を行う
     */
    public function unsubscribe($cid)
    {
        return $this->changeSubscriptionStatus($cid, false, 'unsubscribe');
    }

    /**
     * メール案内の配信再開処理を行う
     */
    public function resubscribe($cid)
    {
        return $this->changeSubscriptionStatus($cid, true, 'resubscribe');
    }
}

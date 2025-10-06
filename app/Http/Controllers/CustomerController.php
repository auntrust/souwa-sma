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
            $customers = Customer::paginate(10);
        } else {
            $search_str = $request->input()['search_str'];
            $customers = Customer::where('name', 'LIKE', '%' . $search_str . '%')
                ->orWhere('furigana', 'LIKE', '%' . $search_str . '%')
                ->orWhere('email', 'LIKE', '%' . $search_str . '%')
                ->orWhere('co_name', 'LIKE', '%' . $search_str . '%')
                ->paginate(10);
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
        $customer->update($request->input());
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
}

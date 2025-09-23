<?php

namespace App\Http\Controllers;

use App\Models\Seminar;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Http\Requests\SeminarRequest;
use Inertia\Inertia;

class SeminarController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if (empty($request->input()['search_str'])) {
            $search_str = null;
            // $seminars = Seminar::all();
            $seminars = Seminar::paginate(10);
        } else {
            $search_str = $request->input()['search_str'];
            $seminars = Seminar::where('name', 'LIKE', '%' . $search_str . '%')
                ->orWhere('unique_key', 'LIKE', '%' . $search_str . '%')
                ->paginate(10);
        }
        return Inertia::render('Seminars/Index', [
            'seminars' => $seminars,
            'search_str' => $search_str,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return Inertia::render('Seminars/Create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(SeminarRequest $request)
    {
        // dd($request->input());
        $seminar = new Seminar($request->input());

        // ユニークキーを生成して設定（12桁英数字）
        do {
            $uniqueKey = Str::random(12);
        } while (Seminar::where('unique_key', $uniqueKey)->exists());
        $seminar->unique_key = $uniqueKey;

        $seminar->save();
        return redirect()->route('seminars.index')->with('success_str', '登録完了しました');
    }

    /**
     * Display the specified resource.
     */
    public function show(Seminar $seminar)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Seminar $seminar)
    {
        // dd($seminar);
        return Inertia::render('Seminars/Edit', ['seminar' => $seminar]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(SeminarRequest $request, Seminar $seminar)
    {
        $seminar->update($request->input());
        return redirect()->route('seminars.index')->with('success_str', '更新完了しました');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Seminar $seminar)
    {
        $seminar->delete();
        return redirect('seminars');
    }
}

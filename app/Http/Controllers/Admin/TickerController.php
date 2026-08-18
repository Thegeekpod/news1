<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Ticker;
use Illuminate\Http\Request;

class TickerController extends Controller
{
    public function index()
    {
        $tickers = Ticker::latest()->get();
        return view('admin.tickers.index', compact('tickers'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'text_bn' => 'required|string|max:255',
            'link_url' => 'nullable|string|max:255',
        ]);

        Ticker::create([
            'text_bn' => $request->text_bn,
            'link_url' => $request->link_url,
            'is_active' => true,
        ]);

        return redirect()->route('admin.tickers.index')->with('success', 'Breaking news ticker added successfully.');
    }

    public function update(Request $request, Ticker $ticker)
    {
        $ticker->update([
            'is_active' => !$ticker->is_active
        ]);

        return redirect()->route('admin.tickers.index')->with('success', 'Ticker status updated successfully.');
    }

    public function destroy(Ticker $ticker)
    {
        $ticker->delete();
        return redirect()->route('admin.tickers.index')->with('success', 'Ticker deleted successfully.');
    }
}

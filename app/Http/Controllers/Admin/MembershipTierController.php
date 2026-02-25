<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MembershipTier;
use Illuminate\Http\Request;

class MembershipTierController extends Controller
{
    public function index()
    {
        $tiers = MembershipTier::all();
        return view('admin.membership-tiers.index', compact('tiers'));
    }

    public function create()
    {
        return view('admin.membership-tiers.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'discount_percentage' => 'nullable|numeric|min:0|max:100',
            'discount_weekday' => 'required|numeric|min:0|max:100',
            'discount_weekend' => 'required|numeric|min:0|max:100',
            'duration_days' => 'required|integer|min:1',
            'booking_window_days' => 'required|integer|min:1',
            'description' => 'required|string',
        ]);

        MembershipTier::create($request->all());

        return redirect()->route('admin.membership-tiers.index')->with('success', 'Tier membership berhasil ditambahkan.');
    }

    public function edit(MembershipTier $membershipTier)
    {
        return view('admin.membership-tiers.edit', compact('membershipTier'));
    }

    public function update(Request $request, MembershipTier $membershipTier)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'discount_percentage' => 'nullable|numeric|min:0|max:100',
            'discount_weekday' => 'required|numeric|min:0|max:100',
            'discount_weekend' => 'required|numeric|min:0|max:100',
            'duration_days' => 'required|integer|min:1',
            'booking_window_days' => 'required|integer|min:1',
            'description' => 'required|string',
        ]);

        $membershipTier->update($request->all());

        return redirect()->route('admin.membership-tiers.index')->with('success', 'Tier membership berhasil diperbarui.');
    }

    public function destroy(MembershipTier $membershipTier)
    {
        $membershipTier->delete();
        return redirect()->route('admin.membership-tiers.index')->with('success', 'Tier membership berhasil dihapus.');
    }
}

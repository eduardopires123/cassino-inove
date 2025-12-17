<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Coupon;
use App\Models\CouponRedemption;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class CouponController extends Controller
{
    /**
     * Display a listing of the coupons.
     */
    public function index()
    {
        $coupons = Coupon::where('is_deleted', false)
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        return view('admin.coupons.index', compact('coupons'));
    }

    /**
     * Show the form for creating a new coupon.
     */
    public function create()
    {
        return view('admin.coupons.create');
    }

    /**
     * Store a newly created coupon in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'code' => 'nullable|unique:coupons,code',
            'description' => 'nullable|string|max:255',
            'type' => ['required', Rule::in(['balance', 'bonus'])],
            'amount' => 'required|numeric|min:0.01',
            'valid_from' => 'nullable|date',
            'valid_until' => 'nullable|date|after_or_equal:valid_from',
            'max_usages' => 'required|integer|min:1',
            'is_active' => 'boolean',
        ]);

        // Generate a random code if not provided
        if (!$request->code) {
            $code = Str::upper(Str::random(8));
            while (Coupon::where('code', $code)->exists()) {
                $code = Str::upper(Str::random(8));
            }
        } else {
            $code = $request->code;
        }

        Coupon::create([
            'code' => $code,
            'description' => $request->description,
            'type' => $request->type,
            'amount' => $request->amount,
            'valid_from' => $request->valid_from,
            'valid_until' => $request->valid_until,
            'max_usages' => $request->max_usages,
            'is_active' => $request->has('is_active'),
        ]);

        return redirect()->route('admin.coupons.index')
            ->with('success', 'Cupom criado com sucesso!');
    }

    /**
     * Show the form for editing the specified coupon.
     */
    public function edit(Coupon $coupon)
    {
        return view('admin.coupons.edit', compact('coupon'));
    }

    /**
     * Update the specified coupon in storage.
     */
    public function update(Request $request, Coupon $coupon)
    {
        $request->validate([
            'code' => ['nullable', Rule::unique('coupons')->ignore($coupon->id)],
            'description' => 'nullable|string|max:255',
            'type' => ['required', Rule::in(['balance', 'bonus'])],
            'amount' => 'required|numeric|min:0.01',
            'valid_from' => 'nullable|date',
            'valid_until' => 'nullable|date|after_or_equal:valid_from',
            'max_usages' => 'required|integer|min:1',
        ]);

        $coupon->update([
            'code' => $request->code ?: $coupon->code,
            'description' => $request->description,
            'type' => $request->type,
            'amount' => $request->amount,
            'valid_from' => $request->valid_from,
            'valid_until' => $request->valid_until,
            'max_usages' => $request->max_usages,
            'is_active' => ($request->is_active == "on" ? true : false),
        ]);

        return redirect()->route('admin.coupons.index')
            ->with('success', 'Cupom atualizado com sucesso!');
    }

    /**
     * Soft delete the specified coupon.
     */
    public function destroy(Coupon $coupon)
    {
        $coupon->delete();

        return redirect()->route('admin.coupons.index')
            ->with('success', 'Cupom removido com sucesso!');
    }

    /**
     * Display the redemption history for a coupon.
     */
    public function redemptions(Coupon $coupon)
    {
        $redemptions = $coupon->redemptions()
            ->with('user')
            ->orderBy('redeemed_at', 'desc')
            ->paginate(15);

        return view('admin.coupons.redemptions', compact('coupon', 'redemptions'));
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\Coupon;
use App\Models\CouponRedemption;
use App\Models\Wallet;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class CouponRedemptionController extends Controller
{
    /**
     * Display the coupon redemption form.
     */
    public function showRedemptionForm()
    {
        return view('profile.redeem-coupon');
    }

    /**
     * Process a coupon redemption.
     */
    public function redeemCoupon(Request $request)
    {
        try {
            $request->validate([
                'code' => 'required|string|exists:coupons,code',
            ]);
        } catch (\Exception $e) {
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'error' => 'Código de cupom inválido ou não encontrado.'
                ]);
            }
            return back()->with('error', 'Código de cupom inválido ou não encontrado.');
        }

        $coupon = Coupon::where('code', $request->code)
            ->where('is_active', true)
            ->where('is_deleted', false)
            ->first();

        if (!$coupon) {
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'error' => 'Cupom inválido ou expirado.'
                ]);
            }
            return back()->with('error', 'Cupom inválido ou expirado.');
        }

        if (!$coupon->isValid()) {
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'error' => 'Cupom inválido ou expirado.'
                ]);
            }
            return back()->with('error', 'Cupom inválido ou expirado.');
        }

        $user = Auth::user();

        // Check if max usages is reached
        if ($coupon->max_usages > 0 && $coupon->used_count >= $coupon->max_usages) {
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'error' => 'Este cupom já atingiu o limite máximo de utilizações.'
                ]);
            }
            return back()->with('error', 'Este cupom já atingiu o limite máximo de utilizações.');
        }

        // Check if user has already redeemed this coupon
        if ($coupon->hasBeenRedeemedByUser($user->id)) {
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'error' => 'Você já resgatou este cupom.'
                ]);
            }
            return back()->with('error', 'Você já resgatou este cupom.');
        }

        $Settings = \App\Helpers\Core::getSetting();

        try {
            DB::beginTransaction();

            // Add the amount to the appropriate balance
            $wallet = $user->wallet;

            if ($coupon->type == 'balance') {
                $wallet->balance += $coupon->amount;
            } else { // bonus
                $wallet->balance_bonus += $coupon->amount;

                // If balance_bonus_rollover is empty, set it
                if ($wallet->balance_bonus_rollover === null || $wallet->balance_bonus_rollover == 0) {
                    $wallet->balance_bonus_rollover = $coupon->amount * $Settings->bonus_rollover;
                    $wallet->balance_bonus_rollover_used = 0;
                } else {
                    $wallet->balance_bonus_rollover += $coupon->amount * $Settings->bonus_rollover;
                }

                // Set expiration time to 7 days from now if not set
                if ($wallet->balance_bonus_expire === null) {
                    $wallet->balance_bonus_expire = Carbon::now()->addDays($Settings->bonus_expire_days);
                }
            }

            $wallet->save();

            // Create redemption record
            CouponRedemption::create([
                'coupon_id' => $coupon->id,
                'user_id' => $user->id,
                'amount' => $coupon->amount,
                'redeemed_at' => now(),
            ]);

            // Update coupon usage count
            $coupon->used_count += 1;
            $coupon->save();

            DB::commit();

            $successMessage = 'Cupom resgatado com sucesso! ' .
                ($coupon->type == 'balance' ?
                    'R$ ' . number_format($coupon->amount, 2, ',', '.') . ' adicionado ao seu saldo.' :
                    'R$ ' . number_format($coupon->amount, 2, ',', '.') . ' adicionado ao seu saldo de bônus.');

            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => $successMessage,
                    'amount' => $coupon->amount,
                    'type' => $coupon->type
                ]);
            }

            return back()->with('success', $successMessage);
        } catch (\Exception $e) {
            DB::rollBack();

            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'error' => 'Ocorreu um erro ao resgatar o cupom. Por favor, tente novamente.'
                ]);
            }

            return back()->with('error', 'Ocorreu um erro ao resgatar o cupom. Por favor, tente novamente.');
        }
    }
}

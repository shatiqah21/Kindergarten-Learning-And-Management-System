<?php

namespace App\Http\Controllers\MyParent;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Stripe\Stripe;
use Stripe\Checkout\Session;
use App\Models\Payment;
use App\Helpers\Pay;
use App\Helpers\Qs;

class StripeController extends Controller
{
    /**
     * Create Stripe Checkout Session and redirect to Stripe-hosted page
     */
    public function checkoutPage(Payment $payment)
    {
        // Check minimum amount for Stripe (MYR min = 0.50)
        if ($payment->amount < 0.50) {
            return back()->with('flash_danger', 'Amount must be at least RM0.50 to pay.');
        }

        Stripe::setApiKey(config('services.stripe.secret'));

        // Generate Stripe Checkout Session
        $session = Session::create([
            'payment_method_types' => ['card'],
            'line_items' => [[
                'price_data' => [
                    'currency' => 'myr',
                    'product_data' => [
                        'name' => $payment->title,
                    ],
                    'unit_amount' => intval($payment->amount * 100),
                ],
                'quantity' => 1,
            ]],
            'mode' => 'payment',
            'success_url' => route('parent.payments.stripe.success'),
            'cancel_url' => route('parent.payments.cancel'),
            'metadata' => [
                'payment_id' => $payment->id,
                'parent_id' => auth()->id(),
            ],
        ]);

        // Redirect user to Stripe-hosted checkout page
        return redirect($session->url);
    }

    /**
     * Handle success callback from Stripe
     */
    public function success()
    {
        // Optionally: update all pending payments to 'paid'
        // $payment = Payment::find(...);
        // $payment->update(['status' => 'paid']);

        return view('pages.parent.payments.success');
    }

    /**
     * Handle cancel callback from Stripe
     */
    public function cancel()
    {
        return view('pages.parent.payments.cancel');
    }

    /**
     * Create Payment for multiple children & redirect to Stripe
     */
    public function createPayment(Request $request)
    {
        $totalAmount = collect($request->children)->sum('amount');

        if ($totalAmount < 0.50) {
            return back()->with('flash_danger', 'Total amount must be at least RM0.50.');
        }

        // 1. Create parent-level payment
        $payment = Payment::create([
            'title' => 'Online Payment (Stripe)',
            'amount' => $totalAmount,
            'method' => 'stripe',
            'my_parent_id' => auth()->id(),
            'year' => Qs::getCurrentSession(),
            'ref_no' => Pay::genRefCode(),
            'status' => 'pending',
            'description' => 'Stripe payment for multiple children'
        ]);

        // 2. Save payment details for each child
        foreach ($request->children as $child) {
            $payment->paymentDetails()->create([
                'student_id' => $child['student_id'],
                'amount' => $child['amount']
            ]);
        }

        // 3. Create Stripe Checkout Session
        Stripe::setApiKey(config('services.stripe.secret'));

        $session = Session::create([
            'payment_method_types' => ['card'],
            'line_items' => [[
                'price_data' => [
                    'currency' => 'myr',
                    'product_data' => [
                        'name' => $payment->title,
                    ],
                    'unit_amount' => intval($payment->amount * 100),
                ],
                'quantity' => 1,
            ]],
            'mode' => 'payment',
            'success_url' => route('parent.payments.stripe.success'),
            'cancel_url' => route('parent.payments.cancel'),
            'metadata' => [
                'payment_id' => $payment->id,
                'parent_id' => auth()->id(),
            ],
        ]);

        // 4. Redirect user directly to Stripe-hosted checkout page
        return redirect($session->url);
    }
}

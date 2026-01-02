<?php

namespace App\Http\Controllers\MyParent;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Stripe\Stripe;
use Stripe\PaymentIntent;
use App\Models\Payment;
use App\Helpers\Pay;
use App\Helpers\Qs;

class StripeController extends Controller
{
    public function checkoutPage(Payment $payment)
    {
        // Check minimum amount for Stripe (MYR min = 0.50)
        if ($payment->amount < 0.50) {
            return back()->with('flash_danger', 'Amount must be at least RM0.50 to pay.');
        }

        // Generate Stripe PaymentIntent
        $stripe = new \Stripe\StripeClient(config('services.stripe.secret'));
        $intent = $stripe->paymentIntents->create([
            'amount' => intval(round($payment->amount * 100)), // convert MYR to sen
            'currency' => 'myr',
            'metadata' => [
                'payment_id' => $payment->id,
                'parent_id' => auth()->id(),
            ],
        ]);

        return view('pages.parent.payments.checkout', [
            'payment' => $payment,
            'client_secret' => $intent->client_secret
        ]);
    }


    public function createPayment(Request $request)
    {
        Stripe::setApiKey(config('services.stripe.secret'));

        $totalAmount = collect($request->children)->sum('amount');

        // Ensure amount meets Stripe min
        if ($totalAmount < 0.50) {
            return response()->json(['error' => 'Total amount must be at least RM0.50.'], 400);
        }

        // Create parent-level payment
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

        // Save payment details for each child
        foreach ($request->children as $child) {
            $payment->paymentDetails()->create([
                'student_id' => $child['student_id'],
                'amount' => $child['amount']
            ]);
        }

        // Create Stripe PaymentIntent
        $intent = PaymentIntent::create([
            'amount' => intval($totalAmount * 100),
            'currency' => 'myr',
            'metadata' => [
                'payment_id' => $payment->id,
                'parent_id' => auth()->id(),
            ],
        ]);

        return response()->json([
            'client_secret' => $intent->client_secret,
            'payment_id' => $payment->id
        ]);
    }
}

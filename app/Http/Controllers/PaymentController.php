<?php

namespace App\Http\Controllers;

use App\Contracts\PaymentGatewayInterface;
use App\Http\Requests\PaymentRequest;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PaymentController extends Controller
{
    public function checkout(PaymentRequest $request)
    {
        $gateway = app()->make(PaymentGatewayInterface::class, request()->all());
        $process = $gateway->process($request->all());

        if ($process['status'] === false) {
            $errors = [];
            foreach ($process['errors'] as $error) {
                $errors[$error['code']] = $error['description'];
            }
            return back()->withErrors($errors)->withInput($request->input());
        } else {

            $imageName = '';
            if (empty($process['data']['encodedImage']) === false) {
                $image  = $process['data']['encodedImage'];
                $image = str_replace('data:image/png;base64,', '', $image);
                $image = str_replace(' ', '+', $image);
                $imageName = $process['data']['id'] . '_pix.png';

                Storage::disk('public')->put($imageName, base64_decode($image));
            }

            $payment = new Payment();
            $payment->gateway_order_id = $process['data']['id'];
            $payment->status = $process['data']['status'];
            $payment->billing_type = $process['data']['billingType'];
            $payment->bank_slip_url = $process['data']['bankSlipUrl'] ?? '';
            $payment->encoded_image_pix = $imageName;
            $payment->payload_code_pix = $process['data']['payload'] ?? '';
            $payment->expiration_date_pix = $process['data']['expirationDate'] ?? null;
            $payment->value = $process['data']['value'] ?? 0;
            $payment->user_id = auth()->user()->id;
            $payment->save();

            return redirect(route('thanks', ['id' => $payment->id]))->with('response', $process);
        }

    }

}

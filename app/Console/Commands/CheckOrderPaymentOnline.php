<?php

namespace App\Console\Commands;

use Illuminate\Support\Facades\DB;
use Illuminate\Console\Command;
use App\Libraries\Payments\Payment;
use App\Models\Order;
use App\Models\PaymentMethod;

class CheckOrderPaymentOnline extends Command
{
    protected $signature = 'check_order_payment_online';

    protected $description = 'Check order payment online but has not received result';

    public function handle()
    {
        $time = date('Y-m-d H:i:s', strtotime('- 30 minutes'));

        $orders = Order::with(['paymentMethod', 'orderItems', 'orderTransactions' => function($query) {
            $query->select('order_id', 'type', 'detail')->where('type', Order::PAYMENT_STATUS_PENDING_DB);
        }])->select('order.*')
            ->join('payment_method', 'order.payment_method_id', '=', 'payment_method.id')
            ->where('order.payment_status', Order::PAYMENT_STATUS_PENDING_DB)
            ->whereNull('order.cancelled_at')
            ->where('order.created_at', '<=', $time)
            ->where(function($query) {
                $query->where('payment_method.type', PaymentMethod::PAYMENT_TYPE_ATM_ONLINE_DB)
                    ->orWhere('payment_method.type', PaymentMethod::PAYMENT_TYPE_CREDIT_ONLINE_DB);
            })->get();

        foreach($orders as $order)
        {
            $payment = Payment::getPayments($order->paymentMethod->code);

            try
            {
                DB::beginTransaction();

                list($paid, $params) = $payment->checkOrderPaymentResult($order->paymentMethod, $order);

                if($paid === true)
                    $order->completePayment(null, false, json_encode($params));
                else
                {
                    $order->failPayment(null, json_encode($params));

                    $order->cancelOrder();
                }

                DB::commit();
            }
            catch(\Exception $e)
            {
                DB::rollBack();
            }
        }
    }
}
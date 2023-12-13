<?php

namespace App\Services;

use App\Models\Affiliate;
use App\Models\Merchant;
use App\Models\Order;
use App\Models\User;

class OrderService
{
    public function __construct(
        protected AffiliateService $affiliateService
    ) {
    }

    /**
     * Process an order and log any commissions.
     * This should create a new affiliate if the customer_email is not already associated with one.
     * This method should also ignore duplicates based on order_id.
     *
     * @param  array{order_id: string, subtotal_price: float, merchant_domain: string, discount_code: string, customer_email: string, customer_name: string} $data
     * @return void
     */
    public function processOrder(array $data)
    {
        // TODO: Complete this method
     


    


        if(($data['order_id'] )  != null  ){
            $merchant =  Merchant::where('domain', $data['merchant_domain'])->firstOrFail();
           
            $affilate =   Affiliate::whereHas('user', function ($query) use ($data) {
                            $query->where('email', $data['customer_email']);
                        })
                      ->first();


            if ($affilate == null) {
             
                $affiliate1 = $this->affiliateService->register($merchant, $data['customer_email'], $data['customer_name'], 0.1);

                $order = new Order();
                $order->external_order_id = $data['order_id'];
                $order->subtotal = $data['subtotal_price'];
                $order->affiliate_id = $affiliate1->id;
                $order->merchant_id = $merchant->id;
                $order->commission_owed = $data['subtotal_price'] * $affiliate1->commission_rate;
                $order->save();
                
            }else{
                $order = new Order();
                $order->external_order_id = $data['order_id'];
                $order->subtotal = $data['subtotal_price'];
                $order->affiliate_id = $affilate->id;
                $order->merchant_id = $merchant->id;
                $order->commission_owed = $data['subtotal_price'] * $affilate->commission_rate;
                $order->save();
            }

          
          
        
            

       
        }
    }

   

    public function dublicateOrder($order_id)
    {
        return Order::where('order_id' , $order_id)->first();
    }
}

<?php

namespace App\Observers;

use App\Actionlog;
use App\Order;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class OrderObserver
{
    /**
     * Handle the order "created" event.
     *
     * @param  \App\Order  $order
     * @return void
     */
    public function created(Order $order)
    {
        
        $status = false;
        $result = null;
        try {
            $actionlog = new Actionlog;
            $actionlog->cambio = 'Se creo una orden';
            $actionlog->user_id = $order->id;
            $actionlog->save();

            $status = true;
            DB::commit();
        } catch (\Throwable $th) {
            $result = $th->getMessage();
            DB::rollback();
        }if($status){
            $salida = ['code' => 200, 'data' => $actionlog ];
            return $salida;
        }else{
            $salida = ['code' => 500, 'data' => $result ];
            return $salida;
        }
    }

    /**
     * Handle the order "updated" event.
     *
     * @param  \App\Order  $order
     * @return void
     */
    public function updated(Order $order)
    {
        $status = false;
        $result = null;
        try {
            $actionlog = new Actionlog;
            $actionlog->cambio = 'Se edito la orden';
            $actionlog->user_id = $order->id;
            $actionlog->save();

            $status = true;
            DB::commit();
        } catch (\Throwable $th) {
            $result = $th->getMessage();
            DB::rollback();
        }if($status){
            $salida = ['code' => 200, 'data' => $actionlog ];
            return $salida;
        }else{
            $salida = ['code' => 500, 'data' => $result ];
            return $salida;
        }
    }

    /**
     * Handle the order "deleted" event.
     *
     * @param  \App\Order  $order
     * @return void
     */
    public function deleted(Order $order)
    {
        $status = false;
        $result = null;
        try {
            $actionlog = new Actionlog;
            $actionlog->cambio = 'Se elimino la orden';
            $actionlog->user_id = $order->id;
            $actionlog->save();

            $status = true;
            DB::commit();
        } catch (\Throwable $th) {
            $result = $th->getMessage();
            DB::rollback();
        }if($status){
            $salida = ['code' => 200, 'data' => $actionlog ];
            return $salida;
        }else{
            $salida = ['code' => 500, 'data' => $result ];
            return $salida;
        }
    }

    /**
     * Handle the order "restored" event.
     *
     * @param  \App\Order  $order
     * @return void
     */
    public function restored(Order $order)
    {
        //
    }

    /**
     * Handle the order "force deleted" event.
     *
     * @param  \App\Order  $order
     * @return void
     */
    public function forceDeleted(Order $order)
    {
        //
    }
}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\City;
use App\Mail\OrderMail;
use App\Order;
use App\Product;
use App\ProductsOrder;
use App\Status;
use App\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

class OrderController extends Controller
{
    public function getCities()
    {
        $salida = [
            'code' => 200,
            'data' => City::all()
        ];

        return $salida;
    }

    public function getProduct()
    {
        $salida = [
            'code' => 200,
            'data' => Product::all()
        ];

        return $salida;
    }

    public function getUsers(Request $request)
    {
        $users = User::where(function($q) use ($request){
            $q->where('name', 'LIKE', "%$request->search%");
            $q->where('email', 'LIKE', "%$request->search%");
        })->get();

        $salida = [
            'code' => 200,
            'data' => $users
        ];

        return $salida;
    }

    public function getOrder()
    {
        $salida = ['code' => 200, 'data' => Order::with('user', 'city', 'status')->get()];
        return $salida;
    }

    public function getStatuses()
    {
        $salida = ['code' => 200, 'data' => Status::all()];
        return $salida;
    }

    public function saveOrder(Request $request){
        $status = false;
        $result = null;
        DB::beginTransaction();
        try {
            $order = new Order;
            $order->user_id = $request->user;
            $order->total = $request->total;
            $order->city_id = $request->city;
            $order->status_id = $this->create_code;
            $order->comments = $request->comment;
            $order->iva_id = $request->impuesto;
            $order->subtotal = $request->subtotal;
            $order->save();

            $products = json_decode($request->products);

            foreach($products as $key => $value){
                // return $value;
                $productsOrder = new ProductsOrder;
                $productsOrder->order_id = $order->id;
                $productsOrder->product_id = $value->product->id;
                $productsOrder->save();
            }

            $user = User::where('id', $request->user)->select('email')->get();


            Mail::to($user)->send(new OrderMail($order));

            $status = true;
            DB::commit();
        } catch (\Throwable $th) {
            $result = $th->getMessage();
            DB::rollback();
        }if($status){
            $salida = ['code' => 200, 'data' => $order];
            return $salida;
        }else{
            $salida = ['code' => 500, 'data' => $result];
            return $salida;
        }
    }

    public function deleteOrder(Request $request)
    {
        $status = false;
        $result = null;
        DB::beginTransaction();
        try {
            $result = Order::find($request->id)->delete();

            $status = true;
            DB::commit();
        } catch (\Throwable $th) {
            $result = $th->getMessage();
            DB::rollback();
        }if($status){
            $salida = ['code' => 200, 'data' => $result];
            return $salida;
        }else{
            $salida = ['code' => 500, 'data' => $result];
            return $salida;
        }
    }

    public function editOrder(Request $request)
    {
        $status = false;
        $result = null;
        DB::beginTransaction();
        try {
            $order = Order::find($request->id);
            $order->comments = $request->comment;
            $order->status_id = $request->status;
            $order->city_id = $request->city;
            $order->save();

            $status = true;
            DB::commit();
        } catch (\Throwable $th) {
            $result = $th->getMessage();
            DB::rollback();
        }if($status){
            $salida = ['code' => 200, 'data' => $order];
            return $salida;
        }else{
            $salida = ['code' => 500, 'data' => $result];
            return $salida;
        }
    }
}

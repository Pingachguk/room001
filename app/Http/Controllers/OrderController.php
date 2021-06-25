<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;

class OrderController extends Controller
{
//    Get all orders
    public static function index()
    {
        return Order::all();
    }

//    Find order by id
    public static function findById($orderId)
    {
        $order = Order::where('order_id', $orderId)->get();
        return $order->first();
    }

//    Confirm order
    public static function confirm($orderId)
    {
        $order = self::findById($orderId);
        $order->confirm = true;
        $order->save();
        return $order;
    }

//    Create order
    public static function create($data)
    {
        $order = new Order($data);
        $order->save();
        return $order;
    }
}

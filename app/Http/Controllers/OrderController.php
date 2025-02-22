<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Sellers1;
use App\Models\Sellers2;
use Illuminate\Support\Facades\Auth;


class OrderController extends Controller
{
    public function index(Request $request) {
        $orders = Order::where('user_id',Auth::user()->id)->with('orders')->latest('orders.created_at')->select('orders.*','users.name','users.email');
        $orders = $orders->leftJoin('users','users.id','orders.user_id');

        if ($request->get('keyword')) {
            $orders = $orders->where('users.name','like','%'.$request->keyword.'%');
            $orders = $orders->orWhere('users.email','like','%'.$request->keyword.'%');
            $orders = $orders->orWhere('orders.id','like','%'.$request->keyword.'%');
        }

        $orders = Order::where('user_id', Auth::user()->id)
        ->paginate(10);

        $data = [];
    $data['orders'] = $orders;
    return view('front.orders.list',$data);

        /*return view('front.orders.list',[
            'orders' => $orders
        ]); */
    }

    public function adminIndex(Request $request) {
        $orders = Order::latest('orders.created_at')->select('orders.*','users.name','users.email');
        $orders = $orders->leftJoin('users','users.id','orders.user_id');

        if ($request->get('keyword')) {
            $orders = $orders->where('users.name','like','%'.$request->keyword.'%');
            $orders = $orders->orWhere('users.email','like','%'.$request->keyword.'%');
            $orders = $orders->orWhere('orders.id','like','%'.$request->keyword.'%');
        }

        $orders = $orders->paginate(10);

        return view('admin.orders.list',[
            'orders' => $orders
        ]);
    }


    public function detail($orderId) {

        $order = Order::select('orders.*','county.name as countyName')
                 ->where('orders.id',$orderId)
                  ->leftJoin('county','county.id','orders.county_id')
                 ->first();

        $orderItems = OrderItem::where('order_id',$orderId)->get();

        return view('front.orders.detail',[
            'order' => $order,
            'orderItems' => $orderItems
        ]);
    }

    public function adminDetail($orderId) {

        $order = Order::select('orders.*','county.name as countyName')
                 ->where('orders.id',$orderId)
                  ->leftJoin('county','county.id','orders.county_id')
                 ->first();

        $orderItems = OrderItem::where('order_id',$orderId)->get();

        return view('admin.orders.detail',[
            'order' => $order,
            'orderItems' => $orderItems
        ]);
    }

    public function changeOrderStatusForm(Request $request, $orderId) {
        $order = Order::find($orderId);
        $order->status = $request->status;
        $order->shipped_date = $request->shipped_date;
        $order->save();

        $message = 'Order status updated successfully';

        session()->flash('success', $message);

        return response()->json([
            'status' => true,
            'message' => $message
        ]);
    }

    public function adminChangeOrderStatusForm(Request $request, $orderId) {
        $order = Order::find($orderId);
        $order->status = $request->status;
        $order->shipped_date = $request->shipped_date;
        $order->save();

        $message = 'Order status updated successfully';

        session()->flash('success', $message);

        return response()->json([
            'status' => true,
            'message' => $message
        ]);
    }

    public function sendInvoiceEmail(Request $request, $orderId){
        orderEmail($orderId, $request->userType);

        $message = 'Order email sent successfully';

        
        session()->flash('success', $message);

        return response()->json([
            'status' => true,
            'message' => $message
        ]);

    }

    public function adminSendInvoiceEmail(Request $request, $orderId){
        orderEmail($orderId, $request->userType);

        $message = 'Order email sent successfully';

        
        session()->flash('success', $message);

        return response()->json([
            'status' => true,
            'message' => $message
        ]);

    }
}

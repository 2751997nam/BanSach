<?php

namespace App\Http\Controllers;

use App\Bill;
use App\Book;
use App\Cart;
use App\Order;
use App\Order_item;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;


class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    private $book;
    private $order;
    private $order_item;

    public function __construct(Book $book, Order $order, Order_item $order_item) {
//        $user = Auth::check();
//        $this->user = $user;
        $this->book =$book;
        $this->order = $order;
        $this->order_item = $order_item;
    }

    public function showUserOrder() {
        session()->flash('page', '/user/order');
        $user = Auth::user();
        $orders = Order::where('user_id', '=', $user->id)->orderBy('created_at', 'desc')->paginate(3);
        $page = $orders->currentPage();
        if($page > 1) session()->forget('page');
//        return $orders;
        return view('order.showUserOrder', compact('orders'));
    }

    public function showOptionOrder($status) {
        $user = Auth::user();
        $orders = new Order();
        if($status === "waitting") {
            $orders = Order::where('user_id', '=', $user->id)
                ->whereNotIn('id', Bill::all()->pluck('order_id'))->orderBy('created_at', 'desc')->paginate(3);
        }
        if($status === "shipping") {
            $orders = Order::where('user_id', '=', $user->id)
                ->whereIn('id', Bill::where('was_paid', '=', 0)->pluck('order_id'))->orderBy('created_at', 'desc')->paginate(3);
        }

        if($status === "waspaid") {
            $orders = Order::where('user_id', '=', $user->id)
                ->whereIn('id', Bill::where('was_paid', '=', 1)->pluck('order_id'))->orderBy('created_at', 'desc')->paginate(3);
        }
        return view('order.showOptionOrder', compact('orders'));
    }

    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $user = Auth::user();
        return view('order.create', compact('user'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'address' => 'required|max:500',
            'phone' => 'required|phone|min:10|max:15'
        ]);
        $user = Auth::user();
        $carts = $user->bookCarts;
        $items = array();
        foreach($carts as $key => $value) {
            $items[] = new Order_item([
                'book_code' => $value->book_code,
                'name' => $value->name,
                'price' => $value->price,
                'discount' => $value->discount,
                'quantity' => $value->pivot->quantity,
            ]);
        }
//        DB::transaction(function () use ($request, $user, $items){
//            $order = new Order();
//            $order->user_id = $user->id;
//            $order->name = $user->name;
//            $order->phone = $request->phone;
//            $order->email = $user->email;
//            $order->address = $request->address;
//            $order->save();
//            $order->orderItems()->saveMany($items);
//            foreach ($items as $item) {
//                $book = Book::where('book_code', '=', $item->book_code)->first();
//                $book->quantity -= $item->quantity;
//                $book->save();
//            }
//            Cart::where('user_id', '=', $user->id)->delete();
//        });
        try{
            $order = new Order();
            $order->user_id = $user->id;
            $order->name = $user->name;
            $order->phone = $request->phone;
            $order->email = $user->email;
            $order->address = $request->address;
            $order->save();
            $order->orderItems()->saveMany($items);
            foreach ($items as $item) {
                $book = Book::where('book_code', '=', $item->book_code)->first();
                $book->quantity -= $item->quantity;
                $book->save();
            }
            Cart::where('user_id', '=', $user->id)->delete();
            DB::commit();
        }catch (\Exception $e) {
            DB::rollback();
            return $e;
        }
        session()->flash('message', 'Thêm đơn hàng thành công!');
        return redirect()->route('index');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        try {
            $order = Order::find($request->id);
            $items = $order->orderItems;
            foreach ($items as $item) {
                $book = Book::where('book_code', '=', $item->book_code)->first();
                $book->quantity += $item->quantity;
                $book->save();
                Order_item::destroy($item->id);
            }
            Order::destroy($request->id);
            DB::commit();
//            session()->flash('page', '/user/order');
            return redirect()->route('user.showOrder');
        }catch (\Exception $e) {
            DB::rollback();
            return $e;
        }
    }
}

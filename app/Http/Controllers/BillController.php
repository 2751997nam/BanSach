<?php

namespace App\Http\Controllers;

use App\Bill;
use App\Order_item;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use function PHPSTORM_META\type;

class BillController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $request->session()->put('search', $request
            ->has('search') ? $request->get('search') : ($request->session()
            ->has('search') ? $request->session()->get('search') : ''));

        $request->session()->put('field', $request
            ->has('field') ? $request->get('field') : ($request->session()
            ->has('field') ? $request->session()->get('field') : 'updated_at'));

        $request->session()->put('sort', $request
            ->has('sort') ? $request->get('sort') : ($request->session()
            ->has('sort') ? $request->session()->get('sort') : 'asc'));




        $bills = new Bill();
//        $orders = Order_item::select('order_id', 'SUM(order_items.price * order_items.quantity * (100 - order_items.discount) / 100) as total'
//            )->groupBy('order_id')->get();
//        $orders = DB::query('select `order_id`, SUM( `order_items`.`price` * `order_items`.`quantity` * (100 - `order_items`.`discount`) / 100) as `total`
//                              from `order_items`
//                              group by `order_id`');
//        return $orders;


        $bills = $bills->leftjoin(DB::raw('(select `order_id`, SUM( `order_items`.`price` * `order_items`.`quantity` * (100 - `order_items`.`discount`) / 100) as `total` 
                              from `order_items` 
                              group by `order_id`) as totals'), 'bills.order_id' , '=' , 'totals.order_id')->where('bill_code', 'like', '%'.$request->session()->get('search').'%')
                ->orderBy($request->session()->get('field'), $request->session()->get('sort'))->paginate(5);
//        return $bills;

        $page = $bills->currentPage();

        if($request->ajax()) {
            return view('bill.index', compact('bills', 'page'));
        }else {
            return view('bill.ajax', compact('bills', 'page'));
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
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
    public function destroy($id)
    {
        //
    }
}

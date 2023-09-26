<?php

namespace App\Http\Controllers\Admin;

use DataTables;
use App\Models\User;
use App\Models\Product;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\ProductSelectedUser;
use Illuminate\Support\Facades\Validator;


class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = Product::all();
            return Datatables::of($data)->addIndexColumn()
                ->addColumn('action', function(Product $data){
                    $btn = '<a href="'.route('products.show', $data->id).'" class="btn btn-primary btn-sm btn-rounded">'.trans('translation.view_details').'</a>';
                    return $btn;
                })->editColumn('id', function(Product $data) {             
                    return '#' . $data->id;
                })->editColumn('product_name', function(Product $data) {           
                    return substr($data->product_name,0,20).'...';
                })->editColumn('is_sold', function(Product $data) {

                    if($data->is_sold == 1) {
                       $btn = '<span class="badge badge-pill badge-soft-success font-size-12">'.trans('translation.yes').'</span>';
                    }else {
                        $btn = '<span class="badge badge-pill badge-soft-danger font-size-12">'.trans('translation.not').'</span>'; 
                    }
                    return $btn;
                })->editColumn('status', function(Product $data) {
                                
                    if($data->status == 1) {
                        $btn =  '<div class="form-check form-switch form-switch-lg mb-3 col-md-2" dir="ltr">
                                   <input class="form-check-input update_status" data-id="'.$data->id.'" type="checkbox" id="SwitchCheckSizelg" name="status"  checked>
                                </div>';
                     }else {
                         $btn =  '<div class="form-check form-switch form-switch-lg mb-3 col-md-2" dir="ltr">
                                     <input class="form-check-input update_status" data-id="'.$data->id.'" type="checkbox" id="SwitchCheckSizelg" name="status" >
                                   </div>';
                     }
                     return $btn;
                })
                ->rawColumns(['product_name' ,'is_sold','status' , 'action'])
                ->make(true);
        }

        return view('admin.products.index');
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
        $product  = Product::with(['category' ,'subcategory' , 'country' , 'state' , 'city' , 'user' , 'images'])->findOrFail($id);
        return view('admin.products.show' , compact('product'));
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

    public function updateProductStatus(Request $request) {
        $product = Product::findOrFail($request->product_id);
        ($product->status == 1) ?  $product->status = 0  : $product->status = 1;
        $product->save();
        return response()->json(array('msg'=> 'success'), 200);
    }


    public function getUserBills() {
        $data  = User::has('selectedProducts')->with(['selectedProducts'])->get();
      //  dd($data);
        return view('admin.products.bills',compact('data'));
    }

    public function addUserBill(Request $request) {
        $validator = Validator::make($request->all(), [
            'user_id'              =>   'required|exists:users,id',
            'product_id'           =>   ['required', Rule::exists('products', 'id')],
            'amount'               =>   ['required','numeric'],
        ]);

        if ($validator->fails()) {
            return back()->with('error', $validator->errors()->first());
        }

        $bill                   =  ProductSelectedUser::where(['user_id' => $request->user_id , 'product_id' => $request->product_id ])->first();
        $bill->paid_amount      =  $request->amount;
      // $bill->paid_date        =  date('Y-m-d' ,strtotime($request->paid_date));
        $bill->save();
        flasher( trans('translation.data_updated_successfully'),'success');
        return back();
    }
}

<?php

namespace App\Http\Controllers\Admin;

use DataTables;
use App\Models\User;
use App\Models\Mazdat;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Models\MazadSelectedUser;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class MazdatController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = Mazdat::all();
            return Datatables::of($data)->addIndexColumn()
                ->addColumn('action', function(Mazdat $data){
                    $btn = '<a href="'.route('mazdats.show', $data->id).'" class="btn btn-primary btn-sm btn-rounded">'.trans('translation.view_details').'</a>';
                    return $btn;
                })->editColumn('id', function(Mazdat $data) {             
                    return '#' . $data->id;
                })->editColumn('product_name', function(Mazdat $data) {           
                    return substr($data->product_name,0,20).'...';
                })->editColumn('is_open', function(Mazdat $data) {

                    if($data->is_open == 1) {
                       $btn = '<span class="badge badge-pill badge-soft-success font-size-12">'.trans('translation.yes').'</span>';
                    }else {
                        $btn = '<span class="badge badge-pill badge-soft-danger font-size-12">'.trans('translation.not').'</span>'; 
                    }
                    return $btn;
                })->editColumn('is_closed', function(Mazdat $data) {
                                
                    if($data->is_closed == 1) {
                        $btn = '<span class="badge badge-pill badge-soft-success font-size-12">'.trans('translation.yes').'</span>';
                     }else {
                         $btn = '<span class="badge badge-pill badge-soft-danger font-size-12">'.trans('translation.not').'</span>'; 
                     }
                     return $btn;
                })->editColumn('status', function(Mazdat $data) {
                                
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
                ->rawColumns(['product_name' ,'is_open' ,'is_closed' ,'status' , 'action'])
                ->make(true);
        }

        return view('admin.mazdats.index');
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
        $mazad  = Mazdat::with(['category' ,'subcategory' , 'country' , 'state' , 'city' , 'user' , 'images'])->where('id' , $id)->first();
        return view('admin.mazdats.show' , compact('mazad'));
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

    public function updateMazadStatus(Request $request) {
        $mazad = Mazdat::findOrFail($request->mazad_id);
        ($mazad->status == 1) ?  $mazad->status = 0  : $mazad->status = 1;
        $mazad->save();
        return response()->json(array('msg'=> 'success'), 200);
    }

    public function getUserBills() {
        $data  = User::has('selectedMazdats')->with(['selectedMazdats'])->get();
      //  dd($data);
        return view('admin.mazdats.bills',compact('data'));
    }

    public function addUserBill(Request $request) {
        $validator = Validator::make($request->all(), [
            'user_id'              =>   'required|exists:users,id',
            'mazad_id'             =>   ['required', Rule::exists('mazdats', 'id')],
            'amount'               =>   ['required','numeric'],
        ]);

        if ($validator->fails()) {
            return back()->with('error', $validator->errors()->first());
        }

        $bill                   =  MazadSelectedUser::where(['user_id' => $request->user_id , 'mazdat_id' => $request->mazad_id ])->first();
        $bill->paid_amount      =  $request->amount;
      // $bill->paid_date        =  date('Y-m-d' ,strtotime($request->paid_date));
        $bill->save();
        flasher( trans('translation.data_updated_successfully'),'success');
        return back();
    }


   /* public function showUserBillDetails($user_id) {
        $data    = MazadSelectedUser::where('user_id' , $user_id)->get();
        return view('admin.mazdats.bill_details',compact('data'));
    }*/
}

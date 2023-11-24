<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\DiscountCouponRequest;
use App\Models\DiscountCoupon;
use App\Traits\AdminLogs;
use Illuminate\Http\JsonResponse;
use Yajra\DataTables\DataTables;
use Illuminate\Http\Request;

class DiscountCouponsController extends Controller
{
    use AdminLogs;

    public function index(request $request)
    {
        $discount_coupons = DiscountCoupon::all();
        if ($request->ajax()) {
            return Datatables::of($discount_coupons)
                ->addColumn('action', function ($discount_coupons) {
                    return '
                            <button type="button" data-id="' . $discount_coupons->id . '" class="btn btn-pill btn-info-light editBtn"><i class="fa fa-edit"></i></button>
                            <button class="btn btn-pill btn-danger-light" data-toggle="modal" data-target="#delete_modal"
                                    data-id="' . $discount_coupons->id . '" data-title="' . $discount_coupons->coupon . '">
                                    <i class="fas fa-trash"></i>
                            </button>

                       ';
                })
                ->escapeColumns([])
                ->editColumn('discount_type', function ($discount_coupons) {
                    if($discount_coupons->discount_type == 'per')
                    {
                        return '<td>بالقيمة المئوية</td>';
                    }
                    else
                    {
                        return '<td>بالجنيه المصري</td>';
                    }
                })
                ->editColumn('discount_amount', function ($discount_coupons) {
                    if($discount_coupons->discount_type == 'per') {
                        return '<td>'. $discount_coupons->discount_amount . '%</td>';
                    } else {
                        return '<td>EGP '. $discount_coupons->discount_amount .'</td>';
                    }
                })
                ->editColumn('is_enabled', function ($discount_coupons) {
                    if($discount_coupons->is_enabled == '1')
                    {
                        return '<td>نعم</td>';
                    }
                    else
                    {
                        return '<td>لا</td>';
                    }
                })
                ->make(true);
        } else {
            return view('admin.discount_coupons.index');
        }
    }


     public function create()
     {
         return view('admin.discount_coupons.parts.create');
     }


    public function store(DiscountCouponRequest $request): JsonResponse
    {
        $inputs = $request->all();

        if (DiscountCoupon::create($inputs)) {
            $this->adminLog('تم اضافة كود خصم جديد');
            return response()->json(['status' => 200]);
        } else {
            return response()->json(['status' => 405]);
        }
    }


    public function edit(DiscountCoupon $discount_coupon)
    {
        return view('admin.discount_coupons.parts.edit', compact('discount_coupon'));
    }


    public function update(DiscountCouponRequest $request,DiscountCoupon $discount_coupon): JsonResponse
    {

        if ($discount_coupon->update($request->all())) {
            $this->adminLog('تم تحديث كود خصم');
            return response()->json(['status' => 200]);
        } else {
            return response()->json(['status' => 405]);
        }
    }


    public function destroy(Request $request): JsonResponse
    {
        $discount_coupon = DiscountCoupon::where('id', $request->id)->firstOrFail();
        $discount_coupon->delete();
        $this->adminLog('تم حذف كود خصم');
        return response()->json(['message' => 'تم الحذف بنجاح', 'status' => 200], 200);
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\Bill;
use App\Services\BillServices;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BillController extends Controller
{
    protected $billService;
    public function __construct(BillServices $billServices)
    {
      $this->billService=$billServices;
    }
    public function index()
    {
        return apiSuccess("this all bill",Bill::with('customer')->get(),201);
    }

   
    public function store(): JsonResponse
{
    $bill = $this->billService->checkout();
    if ($bill) {
        return $bill;
    }
    
    $currentBill = Bill::where('customer_id', Auth::user()->customer->id)->latest()->first();

    return apiSuccess("the checkOut is success the bill is stored", $currentBill, 201);
}


    public function show(Bill $bill)
    {
        return apiSuccess("this Bill",$bill->load(['customer','bill_items']),201);
    }

   
    
    public function update(Request $request, string $id)
    {
        //
    }
    public function destroy(Bill $bill){
        $bill->delete();
        return apiSuccess("تم الحذف بنجاح",code:200);
    }
}

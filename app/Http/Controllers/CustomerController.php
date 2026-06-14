<?php

namespace App\Http\Controllers;

use App\Http\Requests\CustomerRequest;
use App\Models\Customer;
use Illuminate\Http\Request;
use App\Services\CustomerService;

class CustomerController extends Controller
{
protected CustomerService $customerService;

public function __construct(CustomerService $customerService){
    $this->customerService=$customerService;
}

    public function index()
    {
       return apiSuccess("تم جلب كل المسنخدمين",Customer::all());
    }





   
    public function show(Customer $customer)
    {
            if($customer){
                return apiSuccess("تم جلب العميل",$customer,201);
            }
            else{
                return apiFail("العميل غير موجود",code:404);
            }
  

    }

   
    public function update(Request $request, string $id)
    {
        //
    }

    public function destroy(string $id)
    {
        //
    }
}

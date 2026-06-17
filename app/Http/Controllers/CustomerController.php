<?php

namespace App\Http\Controllers;

use App\Http\Requests\CustomerRequest;
use App\Http\Requests\CustomerUpdateRequest;
use App\Models\Customer;
use App\Services\CustomerService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CustomerController extends Controller
{
    protected CustomerService $custoemrService;
   
    public function __construct(CustomerService $customerService)
    {
       $this->custoemrService=$customerService;
    }
    public function index():JsonResponse
    {
        $customers=Customer::all();
        if($customers){
          return apiSuccess("All Customers",$customers,201);
        }
        else {
            return apiFail("the customers is not found",code:404);
        }
        
    }

   

  
    public function profile():JsonResponse
    {
        $customer=Auth::user()->customer;
        $user = Auth::user();

        if (!$user) {
             return apiFail("Unauthenticated", code: 401);
                   }

        $customer = $user->customer;
        if($customer){
            return apiSuccess("the customer is exist",$customer,201);
        }
        else{
             return apiFail("the customer is not found",code:404);
        }
    }

    
    public function update(CustomerUpdateRequest $request):JsonResponse
    {
        $data=$request->validated();
       $customer=$this->custoemrService->updateCustomer($data,$request->file('cover'));
       return apiSuccess("the Updated is complete",$customer,201);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Customer $customer):JsonResponse
    {
      if($customer){
         $customer->delete();
         return apiSuccess("is Deleted",code:200);
      }
      else{
        return apiFail("The deleted is faild",400);
      }
       
    }
}

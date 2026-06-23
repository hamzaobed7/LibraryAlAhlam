<?php

namespace App\Http\Controllers;


use App\Http\Requests\CustomerUpdateRequest;
use App\Http\Resources\CustomerInvoice;
use App\Models\Bill;
use App\Models\Book_request;
use App\Models\Customer;
use App\Services\CustomerService;
use Illuminate\Http\JsonResponse;
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
        $customers=Customer::with('user')->get();
        if($customers){
          return apiSuccess("All Customers",$customers,201);
        }
        else {
            return apiFail("the customers is not found",code:404);
        }
        
    }

   public function show(Customer $customer ):JsonResponse
    {

        if (!$customer) {
             return apiFail("Unauthenticated", code: 401);
            }
            $user=$customer->load('user');
         return apiSuccess("the customer is exist",$user,201);
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

    public function showBill(Bill $bill):JsonResponse{
     if($bill->customer_id==Auth::user()->customer->id){
        $user=Auth::user()->customer()->with('bills.bill_items')->get();
     return apiSuccess("this bill",CustomerInvoice::collection($user),201);
     }
     return apiFail("حدث خطأ ما",code:404);
    }

   
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

    function CustomrtCount (){
        return Customer::count();}

   function AllRequest(){
    if (Auth::check() && Auth::user()->customer) {
        
        $customerId = Auth::user()->customer->id;

        
        $req = Book_request::where('customer_id', $customerId)
            ->get();

        return apiSuccess("All Requests", $req, 200); 
    }

  
    return apiFail("Unauthorized or Customer profile not found",code:404);
   }

   function ShowItems(Bill $bill):JsonResponse{
    
     $bill_items=$bill->bill_items();
     $billWithBook=$bill_items->with('book')->get();
     if($billWithBook){
        return apiSuccess("this bill items",$billWithBook,201);
     }
     return apiFail("no Items",code:404);

   }

}

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
        $this->custoemrService = $customerService;
    }
    public function index(): JsonResponse
    {
        $customers = Customer::with('user')->get();
        return apiSuccess("All Customers", $customers, 200);
    }

    public function show(Customer $customer): JsonResponse
    {
        $user = $customer->load('user');
        return apiSuccess("the customer is exist", $user, 200);
    }


    public function profile(): JsonResponse
    {
        $user = Auth::user();
        if (!$user) {
            return apiFail("Unauthenticated", code: 401);
        }
        $customer = $user->customer;
        if (!$customer) {
            return apiFail("Unauthenticated", code: 401);
        }
        if (Auth::user()->can('view', $customer)) {


            return apiSuccess("the customer is exist", $customer, 200);
        }
        return apiFail("لايمكنك مشاهدة البروفايل");
    }


    public function update(CustomerUpdateRequest $request): JsonResponse
    {
        $data = $request->validated();
        $customer = Auth::user()->customer;
        if (Auth::user()->can('update', $customer)) {
            $customer = $this->custoemrService->updateCustomer($data, $request->file('cover'));
            return apiSuccess("the Updated is complete", $customer, 200);
        }
        return apiFail("لا يمكنك تعديل الحساب");
    }



    public function showBill(Bill $bill): JsonResponse
    {
        $customer = Auth::user()->customer;
        if (!$customer || $bill->customer_id !== $customer->id) {
            return apiFail("حدث خطأ ما", code: 404);
        }
        $customer->load([
            'bills' => function ($query) use ($bill) {
                $query->where('id', $bill->id);
            },
            'bills.bill_items.book'
        ]);

        return apiSuccess("this bill", new CustomerInvoice($customer), 200);
    }


    public function showMyBill(): JsonResponse
    {
        $customer = Auth::user()->customer;
        $bills = $customer->bills()->with('bill_items')->get();
        if ($bills) {
            return apiSuccess("All Bills", $bills, 201);
        }
        return apiFail("No Bills Found", code: 404);
    }



    public function destroy(Customer $customer): JsonResponse
    {

        $customer->delete();
        return apiSuccess("is Deleted", code: 200);
    }

    function CustomrtCount()
    {
        return Customer::count();
    }

    function AllRequest()
    {
        if (Auth::check() && Auth::user()->customer) {
            $customerId = Auth::user()->customer->id;
            $req = Book_request::where('customer_id', $customerId)
                ->get();
            return apiSuccess("All Requests", $req, 200);
        }
        return apiFail("Unauthorized or Customer profile not found", code: 404);
    }

    public function ShowItems(Bill $bill): JsonResponse
    {
        $billWithBook = $bill->bill_items()->with('book')->get();
        if ($billWithBook->isNotEmpty()) {
            return apiSuccess("this bill items", $billWithBook, 200);
        }
        return apiFail("no Items", code: 404);
    }
}

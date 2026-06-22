<?php

namespace App\Http\Controllers;

use App\Models\Bill;
use App\Services\BillServices;
use Illuminate\Http\Request;

class BillController extends Controller
{
    protected $billService;
    public function __construct(BillServices $billServices)
    {
      $this->billService=$billServices;
    }
    public function index()
    {
        return Bill::all();
        
    }

   
    public function store()
    {
        $bill=$this->billService->checkout();
        if(!$bill){
            return apiFail("the CheckOut is Fail");
        }
        return apiSuccess("the checkOut is success",$bill,201);

    }


    public function show(string $id)
    {
        
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

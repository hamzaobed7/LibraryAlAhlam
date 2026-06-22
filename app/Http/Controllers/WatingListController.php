<?php

namespace App\Http\Controllers;

use App\Http\Resources\WaitListResource;
use App\Models\WatingList;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class WatingListController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $waiting=WatingList::with(['book','customer'])->get();
        if($waiting){
              return apiSuccess("تم جلب جميع الطلبات",WaitListResource::collection($waiting),201);
        }
        else{
            return apiFail("No requests",code:404);
        }
    }


  public function store(Request $request)
{
   
    $validated = $request->validate([
        'book_id' => 'required|exists:books,id',
    ]);
    $user = Auth::user();
    if (!$user || !$user->customer) {
        return apiFail("حساب العميل غير موجود أو غير مسجل", code: 404);
    }
    $customerId = $user->customer->id;
    $isAlreadyRequested = WatingList::where('customer_id', $customerId)
                                    ->where('book_id', $validated['book_id'])
                                    ->exists();

    if ($isAlreadyRequested) {
        return apiFail("لقد قمت بطلب هذا الكتاب مسبقاً وهو متواجد في قائمة انتظارك", code: 400);
    }

    
    WatingList::create([
        'customer_id' => $customerId,
        'book_id'     => $validated['book_id']
    ]);

    return apiSuccess("تم إضافة الكتاب إلى قائمة الانتظار بنجاح", code: 200);
}
    /**
     * Display the specified resource.
     */
    public function show(WatingList $watingList)
    {
        if(!$watingList){
            return apiFail('the request is not found',404);
        }
        return apiSuccess("تم جلب الطلب",$watingList->load(['book','custoemr'],201));
    }

    
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}

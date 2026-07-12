<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Book;
use App\Models\CartItem;
use App\Models\Rental;
use App\Models\Setting;
use App\Models\WaitingInfo;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{

    public function index()
    {

        $customerId = Auth::user()->customer->id;

        $cartItems = CartItem::with('book')
            ->where('customer_id', $customerId)
            ->get();

        return apiSuccess("All Carts", $cartItems);
    }


    public function store(Request $request)
    {
        $request->validate([
            'book_id' => 'required|exists:books,id'
        ]);
        $customerId = Auth::user()->customer->id;
        $bookId = $request->book_id;
        $book = Book::findOrFail($bookId);
        if ($book->stock <= 0) {
            return apiFail("الكتاب غير متوفر حالياً، هل تريد الانضمام لقائمة الانتظار؟");
        }
        $maxLimitSetting = Setting::where('name', 'max_borrow_limit')->first();
        $maxLimit = $maxLimitSetting ? (int)$maxLimitSetting->value : 3;
        $currentBorrowsCount = Rental::where('customer_id', $customerId)
            ->whereIn('status', ['reserved', 'delivered'])
            ->count();

        $currentCartCount = CartItem::where('customer_id', $customerId)->count();

        if (($currentBorrowsCount + $currentCartCount) >= $maxLimit) {
            return apiFail('عذراً، لقد تجاوزت الحد الأعلى المسموح به لاستعارة الكتب!');
        }


        $exists = CartItem::where('customer_id', $customerId)
            ->where('book_id', $bookId)
            ->exists();

        if ($exists) {
            return apiFail('هذا الكتاب موجود بالفعل في سلة الشراء.');
        }

        $cartItem = CartItem::create([
            'customer_id' => $customerId,
            'book_id' => $bookId,
        ]);

        return apiSuccess('تم إضافة الكتاب إلى السلة بنجاح.', code: 200);
    }

    public function destroy($id)
    {
        $customerId = Auth::user()->customer->id;

        $cartItem = CartItem::where('id', $id)
            ->where('customer_id', $customerId)
            ->firstOrFail();

        $cartItem->delete();

        return apiSuccess('تم إزالة الكتاب من السلة.', code: 200);
    }



    public function CountCart()
    {
        $customer = Auth::user()->customer;
        $count = CartItem::where('customer_id', $customer->id)->count();
        return $count;
    }
}

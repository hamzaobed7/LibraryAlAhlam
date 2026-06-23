<?php

namespace App\Services;

use App\Models\Bill;
use App\Models\BillItem;
use App\Models\Book;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class BillServices 
{
    public function checkout()
    {
        $customer = Auth::user()->customer ?? null;
        if (!$customer) {
            return apiFail("المستخدم غير موجود كزبون في النظام", code: 404);
        }

        $cart = $customer->cart()->with('book')->get();

        if (!$cart || $cart->isEmpty()) {
            return apiFail("السلة فارغة", code: 404);
        }
          
        $oldBill=Bill::where('customer_id', $customer->id)->where('status',"LIKE","%unpaid%")->orWhere('status',"LIKE","%pinding_payment%")->first();
        if($oldBill){
            return apiFail("You Have Old Bill is not finish",code:400);
        }

        return DB::transaction(function () use ($customer, $cart) {
            
            $totalAmount = 0;
            $itemsToCreate = [];

            foreach ($cart as $item) {
                $bookAvailable = Book::available()->where('id', $item->book_id)->exists();

                if (!$bookAvailable) {
                    return apiFail("عذراً، الكتاب '{$item->book->title}' غير متوفر حالياً في المخزن", code: 400);
                }

                $totalAmount += $item->book->deposit + $item->book->rental_price;

                $itemsToCreate[] = [
                    'book_id'        => $item->book_id,
                    'rental_price'   => $item->book->rental_price,
                    'deposit_amount' => $item->book->deposit,
                    'fine_amount'    => 0
                ];                
            }

            $bill = $customer->bills()->create([
                "total_amount" => $totalAmount
            ]);

            foreach ($itemsToCreate as $itemData) {
                $itemData['bill_id'] = $bill->id;
                BillItem::create($itemData);
            }
            $customer->cart()->delete();
        
        });
    }
}
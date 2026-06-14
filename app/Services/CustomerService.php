<?php

namespace App\Services;

use App\Models\Customer;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class CustomerService 
{
    public function addCustomer(array $data, ?UploadedFile $cover = null)
    {
        if ($cover) {
            $fileName = $data['phone'] . "." . $cover->extension();
            $cover->storeAs("customer_images", $fileName);
            $data['cover'] = $fileName;
        } 

        return Customer::create([
            'name'    => $data['name'],
            'gender'  => $data['gender'],
            'DOB'     => $data['DOB'],
            'cover'   => $data['cover'] ?? null,
            'phone'   => $data['phone'],
            'lang'    => $data['lang'],
            'user_id' => $data['user_id']
        ]);
    }

    public function updateCustomer(Customer $customer, array $data, ?UploadedFile $cover = null)
    {
        if ($cover) {
            if ($customer->cover) {
                Storage::delete('customer_images/' . $customer->cover);
            }
            
            $filename = $data['phone'] . "." . $cover->extension();
            $cover->storeAs('customer_images', $filename);
            $data['cover'] = $filename;
        } 
          $customer->update($data);
    }
}
<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
class CustomerService 
{
    
public function addCustomer(array $data, ?UploadedFile $cover = null)
    {
        return DB::transaction(function () use ($data, $cover) {
            if ($cover) {
                $fileName = $data['phone'] . "." . $cover->extension();
                $cover->storeAs("customer_images", $fileName);
                $data['cover'] = $fileName;
            }

            $user = User::create([
                'name'  => $data['name'],
                'email' => $data['email'],
                'email_verified_at' => null,
                'password' => Hash::make($data['password']), 
            ]);

            return $user->customer()->create([
                'name'   => $data['name'],
                'gender' => $data['gender'],
                'DOB'    => $data['DOB'],
                'cover'  => $data['cover'] ?? null,
                'phone'  => $data['phone'],
                'lang'   => $data['lang'],
            ]);
        });
    }
   public function updateCustomer(array $data, ?UploadedFile $cover = null)

    {



        $customer=Auth::user()->customer;

     $user=Auth::user();

        if ($cover) {

            if ($customer->cover) {

                Storage::delete('customer_images/' . $customer->cover);

            }

           

            $filename = $data['phone'] . "." . $cover->extension();

            $cover->storeAs('customer_images', $filename);

            $data['cover'] = $filename;

        }

          $customer->update($data);

           $user->name=$data['name'];

           $user->save();

           return $customer;

    }
}
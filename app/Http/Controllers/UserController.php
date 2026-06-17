<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
   
 public function updateAdmin(UserRequest $request):JsonResponse{
      $data=$request->validated();
      $user=Auth::user();
      if(!$user){
        return apiFail("the user is null",404);
      }
      $user->email=$data['email'];
      $user->name=$data['name'];
      $user->save();
return apiSuccess("the Operation is Success",code:200);
      

 }


}

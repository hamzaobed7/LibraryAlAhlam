<?php

namespace App\Http\Controllers;

use App\Http\Resources\RentalAdminResource;
use App\Http\Resources\RentalResourse;
use App\Http\Resources\RentalResourseForAdmin;
use App\Models\Customer;
use App\Models\Rental;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

class RentalController extends Controller
{

    function index(): JsonResponse
    {
        $rentals = Rental::with('book', 'customer')->get();
        return apiSuccess("All Rentals", RentalAdminResource::collection($rentals), 200);
    }
    

    function CountOfRentals()
    {
        $count = Rental::count();
        return  $count;
    }

    function CountOfUserRentals()
    {
        $count = Customer::has('rentals')->count();
        return  $count;
    }
    function MyRentalsBook(): JsonResponse
    {
        $user = Auth::user()->customer;
        Gate::authorize('view', $user);
        $rentals = Rental::where('customer_id', $user->id)->with('book')->get();
        return apiSuccess("My Book", RentalResourse::collection($rentals), 200);
    }
}

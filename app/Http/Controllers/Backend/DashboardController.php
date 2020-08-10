<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

/**
 * Class DashboardController.
 */
class DashboardController extends Controller
{
    /**
     * @return \Illuminate\View\View
     */
    public function index()
    {
        if (Auth::user()->isAdmin() ){
            return redirect(route('channels.index'));
            return view('backend.dashboard');
        }
        else {
            return view('backend.user-dashboard');
        }
    }
}

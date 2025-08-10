<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AdminController extends Controller
{


    public function index()
    {
        $stats = [
            'total_users' => \App\Models\User::count(),
            'total_biodatas' => \App\Models\Biodata::count(),
            'total_roles' => \App\Models\Role::count(),
            'total_jurusans' => \App\Models\Jurusan::count(),
            'active_members' => \App\Models\Biodata::where('is_active', true)->count(),
            'pending_members' => \App\Models\Biodata::where('is_active', false)->count(),
        ];

        return view('admin.dashboard', compact('stats'));
    }
}

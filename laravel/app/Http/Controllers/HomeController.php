<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\RailwaySystem;
use App\Models\RailwayBooking;
use App\Models\Practical7Student;

class HomeController extends Controller
{
    public function index()
    {
        // Auto sign-in as default student member if guest
        if (!Auth::check()) {
            $defaultUser = User::where('username', 'ridham61')->first() ?: User::first();
            if ($defaultUser) {
                Auth::login($defaultUser);
            }
        }

        $activeSystemCode = session('active_system_code', 'WR-ADI');
        $activeSystem = RailwaySystem::where('code', $activeSystemCode)->first() ?: RailwaySystem::first();
        $allSystems = RailwaySystem::withCount('bookings')->get();
        $allUsers = User::withCount('railwayBookings')->get();

        $stats = [
            'total_users' => User::count(),
            'total_systems' => RailwaySystem::count(),
            'total_bookings' => RailwayBooking::count(),
            'total_revenue' => RailwayBooking::sum('total_fare'),
            'p7_students' => Practical7Student::count(),
            'user_bookings' => Auth::check() ? Auth::user()->railwayBookings()->count() : 0,
        ];

        $teamMembers = [
            [
                'name' => 'Ridham Bambhaniya',
                'enrollment' => '26GMCA61',
                'username' => 'ridham61',
                'role' => 'Team Lead / Layout Architecture',
                'avatar' => 'images/ridham.jpeg',
                'p_assigned' => 'Practical 1 & 2 Layouts, Routing Architecture'
            ],
            [
                'name' => 'Riya Thakkar',
                'enrollment' => '26GMCA33',
                'username' => 'riya33',
                'role' => 'Core Member / Fare Matrix & Portal Logic',
                'avatar' => 'images/riya.jpeg',
                'p_assigned' => 'Practical 3 Ticket Booking Logic & Blade Form'
            ],
            [
                'name' => 'Vaibhav Senjaliya',
                'enrollment' => '26GMCA52',
                'username' => 'vaibhav52',
                'role' => 'Full-Stack Developer / Laravel Conversion',
                'avatar' => 'images/vaibhav.jpeg',
                'p_assigned' => 'Practical 4 Calculator, Laravel Seeders & Pagination'
            ]
        ];

        return view('home', compact('activeSystem', 'allSystems', 'allUsers', 'stats', 'teamMembers'));
    }
}

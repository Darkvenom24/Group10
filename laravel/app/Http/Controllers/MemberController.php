<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

class MemberController extends Controller
{
    protected $members = [
        1 => [
            'id' => 1,
            'name' => 'Ridham Bambhaniya',
            'enrollment' => '26GMCA61',
            'username' => 'ridham61',
            'role' => 'Team Lead / Practical 1 & 2 Lead',
            'email' => 'ridham.26gmca61@gmca.ac.in',
            'phone' => '+91 98250 12345',
            'avatar' => 'images/ridham.jpeg',
            'college' => 'Government MCA College, Maninagar (GMCA)',
            'course' => 'Master of Computer Applications (MCA) — Semester 2',
            'bio' => 'Passionate about web systems architecture, full-stack design patterns, and institutional portals.',
            'skills' => ['HTML5 & CSS3', 'JavaScript', 'Laravel 11/12', 'PHP & MySQL', 'System Architecture'],
            'responsibilities' => [
                'Designed Practical 1 HTML Table-based institutional structure.',
                'Authored Practical 2 CSS Box Model and Indian Tricolor design system.',
                'Configured Laravel multi-system routing and Station Master administrative roles.'
            ]
        ],
        2 => [
            'id' => 2,
            'name' => 'Riya Thakkar',
            'enrollment' => '26GMCA33',
            'username' => 'riya33',
            'role' => 'Practical 3 Ticket Portal & Logic Lead',
            'email' => 'riya.26gmca33@gmca.ac.in',
            'phone' => '+91 98987 11223',
            'avatar' => 'images/riya.jpeg',
            'college' => 'Government MCA College, Maninagar (GMCA)',
            'course' => 'Master of Computer Applications (MCA) — Semester 2',
            'bio' => 'Focuses on dynamic frontend state management, reservation calculations, and user interface workflows.',
            'skills' => ['PHP', 'Laravel Blade', 'UI/UX Design', 'Form Validations', 'CSS Grid & Flexbox'],
            'responsibilities' => [
                'Engineered Practical 3 Indian Railways dynamic ticket reservation workflow.',
                'Implemented real-time fare computation and class/quota pricing matrices.',
                'Structured Laravel Eloquent relationships between users and railway systems.'
            ]
        ],
        3 => [
            'id' => 3,
            'name' => 'Vaibhav Senjaliya',
            'enrollment' => '26GMCA52',
            'username' => 'vaibhav52',
            'role' => 'Practical 4 Calculator & Laravel Integration',
            'email' => 'vaibhav.26gmca52@gmca.ac.in',
            'phone' => '+91 98765 43210',
            'avatar' => 'images/vaibhav.jpeg',
            'college' => 'Government MCA College, Maninagar (GMCA)',
            'course' => 'Master of Computer Applications (MCA) — Semester 2',
            'bio' => 'Specialized in backend APIs, database indexing, mathematical expression parsers, and Laravel Eloquent pagination.',
            'skills' => ['PHP 8.3 & Laravel', 'SQLite & MySQL', 'Blade Components', 'JavaScript ES6+', 'REST APIs'],
            'responsibilities' => [
                'Built Practical 4 interactive keypad calculator with keyboard event listeners.',
                'Engineered Laravel Eloquent pagination engine across 500+ realistic reservation records.',
                'Implemented PHP 9-12 practicals suite, CRUD actions, and phpMyAdmin explorer clone.'
            ]
        ]
    ];

    public function index()
    {
        return view('members.index', ['members' => $this->members]);
    }

    public function show($id)
    {
        // Support lookup by ID or username
        $member = null;
        if (is_numeric($id) && isset($this->members[(int)$id])) {
            $member = $this->members[(int)$id];
        } else {
            foreach ($this->members as $m) {
                if ($m['username'] === $id || $m['enrollment'] === $id) {
                    $member = $m;
                    break;
                }
            }
        }

        if (!$member) {
            abort(404, 'Member not found');
        }

        $user = User::where('username', $member['username'])->first();
        $userBookingsCount = $user ? $user->railwayBookings()->count() : 0;

        return view('members.show', compact('member', 'userBookingsCount'));
    }
}

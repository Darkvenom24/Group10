<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\RailwaySystem;
use App\Models\RailwayBooking;
use App\Models\Practical7Student;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // ---------------------------------------------------------------------
        // 1. CREATE 5 RAILWAY SYSTEMS (Requirement 6)
        // ---------------------------------------------------------------------
        $systemsData = [
            [
                'code' => 'WR-ADI',
                'name' => 'Western Railway — Ahmedabad Division (ADI)',
                'zone' => 'Western Railway (WR)',
                'division' => 'Ahmedabad Division',
                'headquarters' => 'Ahmedabad Junction (Kalupur)',
                'station_master_name' => 'K. P. Rathod',
                'station_master_username' => 'station_master',
                'station_master_email' => 'sm.ahmedabad@railways.gov.in',
                'station_master_phone' => '+91 79 2214 5501',
                'total_trains' => 48,
                'daily_passengers' => 185000,
                'description' => 'Serves key arterial routes connecting Gujarat to Delhi, Mumbai, Saurashtra, and Eastern India.',
                'badge_color' => '#1E3A8A' // Navy
            ],
            [
                'code' => 'NR-NDLS',
                'name' => 'Northern Railway — New Delhi Division (NDLS)',
                'zone' => 'Northern Railway (NR)',
                'division' => 'Delhi Division',
                'headquarters' => 'New Delhi Railway Station (NDLS)',
                'station_master_name' => 'Rajesh Sharma',
                'station_master_username' => 'station_master_ndls',
                'station_master_email' => 'sm.newdelhi@railways.gov.in',
                'station_master_phone' => '+91 11 2334 0000',
                'total_trains' => 96,
                'daily_passengers' => 520000,
                'description' => 'The busiest railway station in India connecting the capital to every state with premier Rajdhani & Vande Bharat trains.',
                'badge_color' => '#0F766E' // Teal
            ],
            [
                'code' => 'CR-CSMT',
                'name' => 'Central Railway — Mumbai CSMT Division (CSMT)',
                'zone' => 'Central Railway (CR)',
                'division' => 'Mumbai Division',
                'headquarters' => 'Chhatrapati Shivaji Maharaj Terminus (CSMT)',
                'station_master_name' => 'Sanjay Deshmukh',
                'station_master_username' => 'station_master_csmt',
                'station_master_email' => 'sm.mumbai@railways.gov.in',
                'station_master_phone' => '+91 22 2262 0123',
                'total_trains' => 74,
                'daily_passengers' => 460000,
                'description' => 'UNESCO World Heritage landmark terminal handling suburban lifelines and major express trains across India.',
                'badge_color' => '#B45309' // Amber
            ],
            [
                'code' => 'SR-MAS',
                'name' => 'Southern Railway — Chennai Central Division (MAS)',
                'zone' => 'Southern Railway (SR)',
                'division' => 'Chennai Division',
                'headquarters' => 'Chennai Central (Puratchi Thalaivar Dr. MGR Central)',
                'station_master_name' => 'M. S. Ramanathan',
                'station_master_username' => 'station_master_mas',
                'station_master_email' => 'sm.chennai@railways.gov.in',
                'station_master_phone' => '+91 44 2535 3545',
                'total_trains' => 62,
                'daily_passengers' => 310000,
                'description' => 'Major transit gateway for South India, connecting Tamil Nadu, Kerala, Karnataka, and North India.',
                'badge_color' => '#15803D' // Green
            ],
            [
                'code' => 'ER-HWH',
                'name' => 'Eastern Railway — Howrah Division (HWH)',
                'zone' => 'Eastern Railway (ER)',
                'division' => 'Howrah Division',
                'headquarters' => 'Howrah Junction, Kolkata',
                'station_master_name' => 'Subhashish Roy',
                'station_master_username' => 'station_master_hwh',
                'station_master_email' => 'sm.howrah@railways.gov.in',
                'station_master_phone' => '+91 33 2660 2581',
                'total_trains' => 82,
                'daily_passengers' => 490000,
                'description' => 'India\'s oldest and largest railway complex with 23 operational platforms linking Bengal and Northeast.',
                'badge_color' => '#C2410C' // Rust Red
            ],
        ];

        $createdSystems = [];
        foreach ($systemsData as $data) {
            $createdSystems[$data['code']] = RailwaySystem::updateOrCreate(
                ['code' => $data['code']],
                $data
            );
        }

        // ---------------------------------------------------------------------
        // 2. CREATE USERS (Requirement 2 & 5)
        // Total 5 primary accounts: 3 Members + 1 Admin (Station Master) + 1 Passenger
        // Plus 4 division station masters for complete multi-system coverage
        // ---------------------------------------------------------------------
        $usersData = [
            // User 1: Member 1 (Team Lead)
            [
                'name' => 'Ridham Bambhaniya',
                'username' => 'ridham61',
                'enrollment' => '26GMCA61',
                'email' => 'ridham.26gmca61@gmca.ac.in',
                'password' => Hash::make('password123'),
                'role' => 'Team Lead / Student Member',
                'is_admin' => false,
                'assigned_system_code' => 'WR-ADI',
                'avatar' => 'images/ridham.jpeg',
                'last_login' => now()->subHours(2),
            ],
            // User 2: Member 2
            [
                'name' => 'Riya Thakkar',
                'username' => 'riya33',
                'enrollment' => '26GMCA33',
                'email' => 'riya.26gmca33@gmca.ac.in',
                'password' => Hash::make('password123'),
                'role' => 'Core Member / Student Member',
                'is_admin' => false,
                'assigned_system_code' => 'CR-CSMT',
                'avatar' => 'images/riya.jpeg',
                'last_login' => now()->subHours(4),
            ],
            // User 3: Member 3
            [
                'name' => 'Vaibhav Senjaliya',
                'username' => 'vaibhav52',
                'enrollment' => '26GMCA52',
                'email' => 'vaibhav.26gmca52@gmca.ac.in',
                'password' => Hash::make('password123'),
                'role' => 'Full-Stack Developer / Student Member',
                'is_admin' => false,
                'assigned_system_code' => 'WR-ADI',
                'avatar' => 'images/vaibhav.jpeg',
                'last_login' => now()->subMinutes(15),
            ],
            // User 4: Admin (Station Master) - Requirement 5 & 2
            [
                'name' => 'K. P. Rathod (Station Master ADI)',
                'username' => 'station_master',
                'enrollment' => 'SM-ADI-01',
                'email' => 'station_master@railways.gov.in',
                'password' => Hash::make('password123'),
                'role' => 'Station Master (Admin)',
                'is_admin' => true,
                'assigned_system_code' => 'WR-ADI',
                'avatar' => 'images/temp.jpeg',
                'last_login' => now()->subMinutes(5),
            ],
            // User 5: Passenger Member - Completes 5 users
            [
                'name' => 'Aarav Patel',
                'username' => 'aarav05',
                'enrollment' => '26GMCA05',
                'email' => 'aarav.patel@gmca.ac.in',
                'password' => Hash::make('password123'),
                'role' => 'Frequent Passenger / Student Member',
                'is_admin' => false,
                'assigned_system_code' => 'NR-NDLS',
                'avatar' => 'images/temp.jpeg',
                'last_login' => now()->subDay(),
            ],
            // Additional System Station Masters for Multi-System Administration
            [
                'name' => 'Rajesh Sharma (Station Master NDLS)',
                'username' => 'station_master_ndls',
                'enrollment' => 'SM-NDLS-02',
                'email' => 'sm.ndls@railways.gov.in',
                'password' => Hash::make('password123'),
                'role' => 'Station Master (Admin)',
                'is_admin' => true,
                'assigned_system_code' => 'NR-NDLS',
                'avatar' => 'images/temp.jpeg',
                'last_login' => now()->subHours(1),
            ],
            [
                'name' => 'Sanjay Deshmukh (Station Master CSMT)',
                'username' => 'station_master_csmt',
                'enrollment' => 'SM-CSMT-03',
                'email' => 'sm.csmt@railways.gov.in',
                'password' => Hash::make('password123'),
                'role' => 'Station Master (Admin)',
                'is_admin' => true,
                'assigned_system_code' => 'CR-CSMT',
                'avatar' => 'images/temp.jpeg',
                'last_login' => now()->subHours(3),
            ],
            [
                'name' => 'M. S. Ramanathan (Station Master MAS)',
                'username' => 'station_master_mas',
                'enrollment' => 'SM-MAS-04',
                'email' => 'sm.mas@railways.gov.in',
                'password' => Hash::make('password123'),
                'role' => 'Station Master (Admin)',
                'is_admin' => true,
                'assigned_system_code' => 'SR-MAS',
                'avatar' => 'images/temp.jpeg',
                'last_login' => now()->subHours(6),
            ],
            [
                'name' => 'Subhashish Roy (Station Master HWH)',
                'username' => 'station_master_hwh',
                'enrollment' => 'SM-HWH-05',
                'email' => 'sm.hwh@railways.gov.in',
                'password' => Hash::make('password123'),
                'role' => 'Station Master (Admin)',
                'is_admin' => true,
                'assigned_system_code' => 'ER-HWH',
                'avatar' => 'images/temp.jpeg',
                'last_login' => now()->subHours(8),
            ],
        ];

        $users = [];
        foreach ($usersData as $u) {
            $users[$u['username']] = User::updateOrCreate(
                ['username' => $u['username']],
                $u
            );
        }

        // ---------------------------------------------------------------------
        // 3. SEED MINIMUM 100 BOOKING ENTRIES PER USER ACCOUNT (Requirement 3)
        // Exactly >= 105 entries for:
        // - ridham61
        // - riya33
        // - vaibhav52
        // - station_master
        // - aarav05
        // ---------------------------------------------------------------------
        $trainsBySystem = [
            'WR-ADI' => [
                ['no' => '12957', 'name' => 'Swarna Jayanti Rajdhani Express', 'src' => 'Ahmedabad Jn (ADI)', 'dst' => 'New Delhi (NDLS)', 'base' => 2400],
                ['no' => '22953', 'name' => 'Gujarat Superfast Express', 'src' => 'Ahmedabad Jn (ADI)', 'dst' => 'Mumbai Central (MMCT)', 'base' => 450],
                ['no' => '12009', 'name' => 'Mumbai Central Vande Bharat', 'src' => 'Ahmedabad Jn (ADI)', 'dst' => 'Mumbai Central (MMCT)', 'base' => 1400],
                ['no' => '19038', 'name' => 'Avadh Express', 'src' => 'Ahmedabad Jn (ADI)', 'dst' => 'Gorakhpur Jn (GKP)', 'base' => 750],
                ['no' => '12901', 'name' => 'Gujarat Mail', 'src' => 'Ahmedabad Jn (ADI)', 'dst' => 'Dadar (DDR)', 'base' => 520],
                ['no' => '12932', 'name' => 'Ahmedabad Double Decker Express', 'src' => 'Ahmedabad Jn (ADI)', 'dst' => 'Mumbai Central (MMCT)', 'base' => 620],
                ['no' => '20901', 'name' => 'Vande Bharat Express', 'src' => 'Ahmedabad Jn (ADI)', 'dst' => 'Jamnagar (JAM)', 'base' => 950],
                ['no' => '12833', 'name' => 'Howrah Ahmedabad Express', 'src' => 'Ahmedabad Jn (ADI)', 'dst' => 'Howrah Jn (HWH)', 'base' => 1100],
            ],
            'NR-NDLS' => [
                ['no' => '12424', 'name' => 'Dibrugarh Rajdhani Express', 'src' => 'New Delhi (NDLS)', 'dst' => 'Dibrugarh (DBRG)', 'base' => 3100],
                ['no' => '22436', 'name' => 'Vande Bharat Express', 'src' => 'New Delhi (NDLS)', 'dst' => 'Varanasi Jn (BSB)', 'base' => 1750],
                ['no' => '12004', 'name' => 'Lucknow Shatabdi Express', 'src' => 'New Delhi (NDLS)', 'dst' => 'Lucknow Jn (LJN)', 'base' => 1150],
                ['no' => '12401', 'name' => 'Nanda Devi AC Express', 'src' => 'New Delhi (NDLS)', 'dst' => 'Dehradun (DDN)', 'base' => 980],
                ['no' => '12011', 'name' => 'Kalka Shatabdi Express', 'src' => 'New Delhi (NDLS)', 'dst' => 'Kalka (KLK)', 'base' => 880],
                ['no' => '12398', 'name' => 'Mahabodhi Express', 'src' => 'New Delhi (NDLS)', 'dst' => 'Gaya Jn (GAYA)', 'base' => 670],
            ],
            'CR-CSMT' => [
                ['no' => '22221', 'name' => 'CSMT Rajdhani Express', 'src' => 'Mumbai CSMT (CSMT)', 'dst' => 'Hazrat Nizamuddin (NZM)', 'base' => 2850],
                ['no' => '12123', 'name' => 'Deccan Queen Superfast', 'src' => 'Mumbai CSMT (CSMT)', 'dst' => 'Pune Jn (PUNE)', 'base' => 390],
                ['no' => '12137', 'name' => 'Punjab Mail', 'src' => 'Mumbai CSMT (CSMT)', 'dst' => 'Firozpur Cantt (FZR)', 'base' => 890],
                ['no' => '12261', 'name' => 'Howrah Duronto Express', 'src' => 'Mumbai CSMT (CSMT)', 'dst' => 'Howrah Jn (HWH)', 'base' => 2450],
                ['no' => '22119', 'name' => 'Tejas Express', 'src' => 'Mumbai CSMT (CSMT)', 'dst' => 'Madgaon Jn (MAO)', 'base' => 1650],
                ['no' => '12111', 'name' => 'Amravati Superfast Express', 'src' => 'Mumbai CSMT (CSMT)', 'dst' => 'Amravati (AMI)', 'base' => 540],
            ],
            'SR-MAS' => [
                ['no' => '12621', 'name' => 'Tamil Nadu Express', 'src' => 'Chennai Central (MAS)', 'dst' => 'New Delhi (NDLS)', 'base' => 2700],
                ['no' => '20643', 'name' => 'Vande Bharat Express', 'src' => 'Chennai Central (MAS)', 'dst' => 'Coimbatore Jn (CBE)', 'base' => 1550],
                ['no' => '12675', 'name' => 'Kovai Superfast Express', 'src' => 'Chennai Central (MAS)', 'dst' => 'Coimbatore Jn (CBE)', 'base' => 480],
                ['no' => '12695', 'name' => 'Trivandrum Superfast Express', 'src' => 'Chennai Central (MAS)', 'dst' => 'Thiruvananthapuram (TVC)', 'base' => 820],
                ['no' => '12601', 'name' => 'Mangalore Mail', 'src' => 'Chennai Central (MAS)', 'dst' => 'Mangaluru Central (MAQ)', 'base' => 690],
                ['no' => '12007', 'name' => 'Mysuru Shatabdi Express', 'src' => 'Chennai Central (MAS)', 'dst' => 'Mysuru Jn (MYS)', 'base' => 1100],
            ],
            'ER-HWH' => [
                ['no' => '12301', 'name' => 'Howrah Rajdhani Express', 'src' => 'Howrah Jn (HWH)', 'dst' => 'New Delhi (NDLS)', 'base' => 2950],
                ['no' => '22301', 'name' => 'Vande Bharat Express', 'src' => 'Howrah Jn (HWH)', 'dst' => 'New Jalpaiguri (NJP)', 'base' => 1600],
                ['no' => '12321', 'name' => 'Mumbai Mail via Chheoki', 'src' => 'Howrah Jn (HWH)', 'dst' => 'Mumbai CSMT (CSMT)', 'base' => 910],
                ['no' => '12303', 'name' => 'Poorva Express', 'src' => 'Howrah Jn (HWH)', 'dst' => 'New Delhi (NDLS)', 'base' => 860],
                ['no' => '12841', 'name' => 'Coromandel Express', 'src' => 'Howrah Jn (HWH)', 'dst' => 'Chennai Central (MAS)', 'base' => 840],
                ['no' => '12313', 'name' => 'Sealdah Rajdhani Express', 'src' => 'Sealdah (SDAH)', 'dst' => 'New Delhi (NDLS)', 'base' => 2900],
            ],
        ];

        $classes = ['1A', '2A', '3A', 'SL', 'CC', 'EC'];
        $classMultipliers = ['1A' => 2.5, '2A' => 1.8, '3A' => 1.2, 'SL' => 0.6, 'CC' => 1.0, 'EC' => 2.0];
        $quotas = ['GN', 'TQ', 'LD', 'SS', 'PT'];
        $statuses = ['Confirmed', 'Confirmed', 'Confirmed', 'RAC', 'Waiting List'];
        
        $passengerNamesPool = [
            'Ridham Bambhaniya', 'Riya Thakkar', 'Vaibhav Senjaliya', 'Aarav Patel', 'Sneha Joshi',
            'Devansh Shah', 'Pooja Mehta', 'Kunal Trivedi', 'Ananya Desai', 'Chirag Parekh',
            'Bhavik Vora', 'Priyanka Dave', 'Hardik Panchal', 'Drashti Shukla', 'Mehul Solanki',
            'Kinjal Bhatt', 'Siddharth Dave', 'Tanvi Somani', 'Nikhil Jani', 'Jhanvi Gandhi',
            'Rohan Kulkarni', 'Aditi Sharma', 'Karthik Raja', 'Meera Nair', 'Amit Sengupta'
        ];

        // The 5 primary user usernames to populate 105+ records each:
        $primaryUsers = ['ridham61', 'riya33', 'vaibhav52', 'station_master', 'aarav05'];

        $bookingRecordsCount = 0;
        $systemKeys = array_keys($createdSystems);

        foreach ($primaryUsers as $userKey) {
            $user = $users[$userKey];
            $userDefaultName = $user->name;

            // Generate exactly 105 records per account
            for ($i = 1; $i <= 105; $i++) {
                // Determine system code (cycle or weighted by user's assigned system)
                $sysCode = ($i % 3 === 0) 
                    ? $systemKeys[($i + strlen($userKey)) % count($systemKeys)]
                    : $user->assigned_system_code;
                
                $system = $createdSystems[$sysCode] ?? $createdSystems['WR-ADI'];
                $trainList = $trainsBySystem[$sysCode] ?? $trainsBySystem['WR-ADI'];
                $train = $trainList[($i - 1) % count($trainList)];

                $travelClass = $classes[$i % count($classes)];
                $quota = $quotas[$i % count($quotas)];
                $paxCount = ($i % 4) + 1; // 1 to 4 pax
                $status = $statuses[$i % count($statuses)];
                
                $multiplier = $classMultipliers[$travelClass] ?? 1.0;
                $fare = round($train['base'] * $multiplier * $paxCount);
                if ($quota === 'TQ' || $quota === 'PT') {
                    $fare += ($paxCount * 250);
                }

                // Deterministic journey dates spreading from past to future
                $daysOffset = ($i - 50); // From -49 days ago to +55 days in future
                $journeyDate = now()->addDays($daysOffset)->format('Y-m-d');
                $bookingDate = now()->addDays($daysOffset - 12)->subHours($i % 24);

                // Passenger name (60% user's own name, 40% realistic fellow travel passengers)
                $paxName = ($i % 5 <= 2) ? $userDefaultName : $passengerNamesPool[$i % count($passengerNamesPool)];
                $paxAge = 20 + ($i % 45);
                $paxGender = ($i % 2 === 0) ? 'Male' : 'Female';
                $paxPhone = '98' . str_pad((string)($i * 7391 + 1029384), 8, '0', STR_PAD_LEFT);

                // Coach seat
                $coach = ($travelClass === '1A') ? 'H1' : (($travelClass === '2A') ? 'A'.(($i%3)+1) : (($travelClass === '3A') ? 'B'.(($i%5)+1) : 'S'.(($i%8)+1)));
                $seat = (($i * 7) % 72) + 1;
                $berth = ($seat % 8 === 1 || $seat % 8 === 4) ? 'LB' : (($seat % 8 === 2 || $seat % 8 === 5) ? 'MB' : (($seat % 8 === 3 || $seat % 8 === 6) ? 'UB' : 'SL'));
                $coachSeat = "$coach-$seat ($berth)";

                // Unique PNR
                $pnrCode = 'PNR' . strtoupper(substr(md5($userKey . '_' . $i . '_' . $sysCode), 0, 7));

                RailwayBooking::updateOrCreate(
                    ['pnr' => $pnrCode],
                    [
                        'user_id' => $user->id,
                        'railway_system_id' => $system->id,
                        'passenger_name' => $paxName,
                        'passenger_age' => $paxAge,
                        'passenger_gender' => $paxGender,
                        'passenger_phone' => $paxPhone,
                        'train_number' => $train['no'],
                        'train_name' => $train['name'],
                        'source_station' => $train['src'],
                        'destination_station' => $train['dst'],
                        'travel_class' => $travelClass,
                        'quota' => $quota,
                        'passengers_count' => $paxCount,
                        'journey_date' => $journeyDate,
                        'total_fare' => $fare,
                        'status' => $status,
                        'coach_seat' => $coachSeat,
                        'remarks' => "E-Ticket booked online via Group 10 IRCTC Laravel Gateway (#$i)",
                        'created_at' => $bookingDate,
                        'updated_at' => $bookingDate,
                    ]
                );
                $bookingRecordsCount++;
            }
        }

        // ---------------------------------------------------------------------
        // 4. CREATE PRACTICAL 7 STUDENT RECORDS (Academic Information)
        // ---------------------------------------------------------------------
        $students = [
            [
                'user_id' => $users['vaibhav52']->id,
                'enrollment' => '26GMCA52',
                'full_name' => 'Vaibhav Senjaliya',
                'email' => 'vaibhav.senjaliya@gmca.ac.in',
                'phone' => '9876543210',
                'department' => 'Master of Computer Applications (MCA)',
                'semester' => 'Semester 2',
                'gender' => 'Male',
                'dob' => '2003-04-12',
                'skills' => 'PHP, Laravel 11/12, MySQL, JavaScript, Blade, CSS3',
                'project_title' => 'Automated Railway Reservation & Multi-Division Portal',
                'project_desc' => 'Converted complete PHP web practicals to Laravel with 5 systems, Station Master role-based admin, and pagination across 500+ records.',
                'address' => 'Nikol, Ahmedabad, Gujarat',
                'status' => 'Approved',
            ],
            [
                'user_id' => $users['ridham61']->id,
                'enrollment' => '26GMCA61',
                'full_name' => 'Ridham Bambhaniya',
                'email' => 'ridham.bambhaniya@gmca.ac.in',
                'phone' => '9825012345',
                'department' => 'Master of Computer Applications (MCA)',
                'semester' => 'Semester 2',
                'gender' => 'Male',
                'dob' => '2002-11-20',
                'skills' => 'PHP, Laravel, HTML5, CSS3, Python, System Architecture',
                'project_title' => 'Multi-System Institutional Portal & Academic Registry',
                'project_desc' => 'Led group practical portfolio conversion into full Laravel MVC architecture with multi-division switching.',
                'address' => 'Maninagar, Ahmedabad, Gujarat',
                'status' => 'Approved',
            ],
            [
                'user_id' => $users['riya33']->id,
                'enrollment' => '26GMCA33',
                'full_name' => 'Riya Thakkar',
                'email' => 'riya.thakkar@gmca.ac.in',
                'phone' => '9898711223',
                'department' => 'Master of Computer Applications (MCA)',
                'semester' => 'Semester 2',
                'gender' => 'Female',
                'dob' => '2003-08-19',
                'skills' => 'PHP, Laravel, JavaScript, CSS Grid, UI/UX Design',
                'project_title' => 'Fare Matrix Engine & Passenger Circulation Manager',
                'project_desc' => 'Engineered dynamic railway ticketing calculation, fare class rules, and responsive Blade UI components.',
                'address' => 'Satellite, Ahmedabad, Gujarat',
                'status' => 'Approved',
            ],
            [
                'user_id' => $users['aarav05']->id,
                'enrollment' => '26GMCA05',
                'full_name' => 'Aarav Patel',
                'email' => 'aarav.patel@gmca.ac.in',
                'phone' => '9712345678',
                'department' => 'Master of Computer Applications (MCA)',
                'semester' => 'Semester 2',
                'gender' => 'Male',
                'dob' => '2003-01-15',
                'skills' => 'Laravel, MySQL, Java, Web Security',
                'project_title' => 'Campus RFID Transit & Passenger Ledger',
                'project_desc' => 'Integrated database CRUD and pagination test harness.',
                'address' => 'Vastrapur, Ahmedabad',
                'status' => 'Approved',
            ],
        ];

        foreach ($students as $stu) {
            Practical7Student::updateOrCreate(
                ['enrollment' => $stu['enrollment']],
                $stu
            );
        }
    }
}

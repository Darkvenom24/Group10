<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Practical7Student;
use App\Models\RailwayBooking;
use App\Models\User;
use App\Models\RailwaySystem;

class PhpPracticalsController extends Controller
{
    public function index()
    {
        return view('practicals.php.index');
    }

    // Practical 9.1: Max of 3 values
    public function p9_1(Request $request)
    {
        $n1 = (float) $request->input('num1', 45);
        $n2 = (float) $request->input('num2', 92);
        $n3 = (float) $request->input('num3', 68);

        // Conditional comparison logic
        if ($n1 >= $n2 && $n1 >= $n3) {
            $maxVal = $n1;
            $step = "$n1 is greater than or equal to both $n2 and $n3";
        } elseif ($n2 >= $n1 && $n2 >= $n3) {
            $maxVal = $n2;
            $step = "$n2 is greater than or equal to both $n1 and $n3";
        } else {
            $maxVal = $n3;
            $step = "$n3 is greater than or equal to both $n1 and $n2";
        }

        return view('practicals.php.p9_1', compact('n1', 'n2', 'n3', 'maxVal', 'step'));
    }

    // Practical 9.2: Print 1 to N
    public function p9_2(Request $request)
    {
        $limit = (int) $request->input('limit', 20);
        if ($limit < 1) $limit = 1;
        if ($limit > 100) $limit = 100;

        $numbers = [];
        $sum = 0;
        $evenCount = 0;
        $oddCount = 0;

        for ($i = 1; $i <= $limit; $i++) {
            $isEven = ($i % 2 === 0);
            $numbers[] = [
                'val' => $i,
                'isEven' => $isEven,
            ];
            $sum += $i;
            if ($isEven) $evenCount++;
            else $oddCount++;
        }

        return view('practicals.php.p9_2', compact('limit', 'numbers', 'sum', 'evenCount', 'oddCount'));
    }

    // Practical 9.3: 3 Types of Pyramids
    public function p9_3(Request $request)
    {
        $rows = (int) $request->input('rows', 5);
        if ($rows < 2) $rows = 2;
        if ($rows > 15) $rows = 15;

        // Pattern 1: Right-Angled Triangle
        $pattern1 = [];
        for ($i = 1; $i <= $rows; $i++) {
            $pattern1[] = str_repeat('* ', $i);
        }

        // Pattern 2: Equilateral Centered Pyramid
        $pattern2 = [];
        for ($i = 1; $i <= $rows; $i++) {
            $spaces = str_repeat(' ', ($rows - $i) * 2);
            $stars = str_repeat('* ', (2 * $i - 1));
            $pattern2[] = $spaces . $stars;
        }

        // Pattern 3: Inverted Pyramid
        $pattern3 = [];
        for ($i = $rows; $i >= 1; $i--) {
            $spaces = str_repeat(' ', ($rows - $i) * 2);
            $stars = str_repeat('* ', (2 * $i - 1));
            $pattern3[] = $spaces . $stars;
        }

        return view('practicals.php.p9_3', compact('rows', 'pattern1', 'pattern2', 'pattern3'));
    }

    // Practical 10.1: Array Operations
    public function p10_1(Request $request)
    {
        $arr1Str = $request->input('array1', '12, 45, 7, 89, 23, 56');
        $arr2Str = $request->input('array2', '34, 90, 11, 67, 4');

        $arr1 = array_map('trim', explode(',', $arr1Str));
        $arr1 = array_filter($arr1, 'is_numeric');
        $arr1 = array_map('floatval', $arr1);
        if (empty($arr1)) $arr1 = [12, 45, 7, 89, 23, 56];

        $arr2 = array_map('trim', explode(',', $arr2Str));
        $arr2 = array_filter($arr2, 'is_numeric');
        $arr2 = array_map('floatval', $arr2);
        if (empty($arr2)) $arr2 = [34, 90, 11, 67, 4];

        $reversed = array_reverse($arr1);
        $merged = array_merge($arr1, $arr2);
        sort($merged);
        $sum = array_sum($arr1);

        return view('practicals.php.p10_1', compact('arr1Str', 'arr2Str', 'arr1', 'arr2', 'reversed', 'merged', 'sum'));
    }

    // Practical 10.2: String Name & Length passed as argument
    public function p10_2(Request $request)
    {
        $name = $request->input('name', 'Vaibhav Senjaliya');
        $length = strlen($name);
        $mbLength = mb_strlen($name);
        $words = str_word_count($name);

        return view('practicals.php.p10_2', compact('name', 'length', 'mbLength', 'words'));
    }

    // Practical 11.1: Max and Min
    public function p11_1(Request $request)
    {
        $a = (float) $request->input('a', 58);
        $b = (float) $request->input('b', 84);
        $c = (float) $request->input('c', 29);

        // If-Else
        if ($a >= $b && $a >= $c) $max = $a;
        elseif ($b >= $a && $b >= $c) $max = $b;
        else $max = $c;

        if ($a <= $b && $a <= $c) $min = $a;
        elseif ($b <= $a && $b <= $c) $min = $b;
        else $min = $c;

        // Ternary
        $ternaryMax = ($a > $b) ? (($a > $c) ? $a : $c) : (($b > $c) ? $b : $c);
        $ternaryMin = ($a < $b) ? (($a < $c) ? $a : $c) : (($b < $c) ? $b : $c);

        return view('practicals.php.p11_1', compact('a', 'b', 'c', 'max', 'min', 'ternaryMax', 'ternaryMin'));
    }

    // Practical 11.2: Date/Time & Greeting
    public function p11_2(Request $request)
    {
        $hour = $request->has('hour') ? (int) $request->input('hour') : (int) date('H');
        
        if ($hour >= 5 && $hour < 12) {
            $greeting = 'Good Morning! 🌅';
            $theme = '#F59E0B';
        } elseif ($hour >= 12 && $hour < 17) {
            $greeting = 'Good Afternoon! ☀️';
            $theme = '#3B82F6';
        } elseif ($hour >= 17 && $hour < 21) {
            $greeting = 'Good Evening! 🌇';
            $theme = '#8B5CF6';
        } else {
            $greeting = 'Good Night! 🌙';
            $theme = '#1E3A8A';
        }

        return view('practicals.php.p11_2', compact('hour', 'greeting', 'theme'));
    }

    // Practical 11.3: User Profile Card
    public function p11_3(Request $request)
    {
        $name = $request->input('name', 'Ridham Bambhaniya');
        $enrollment = $request->input('enrollment', '26GMCA61');
        $email = $request->input('email', 'ridham.26gmca61@gmca.ac.in');
        $course = $request->input('course', 'Master of Computer Applications');
        $bio = $request->input('bio', 'Team lead of Group 10 developing multi-system Indian Railways Laravel portal.');

        return view('practicals.php.p11_3', compact('name', 'enrollment', 'email', 'course', 'bio'));
    }

    // Practical 11.4: 5 PHP String Functions
    public function p11_4(Request $request)
    {
        $str1 = $request->input('str1', 'Government MCA College, Maninagar');
        $str2 = $request->input('str2', 'Web Technology Practicals');
        $search = $request->input('search', 'College');

        $len = strlen($str1);
        $concat = $str1 . ' — ' . $str2;
        $upper = strtoupper($str1);
        $lower = strtolower($str1);
        $ucwords = ucwords(strtolower($str1));
        $pos = strpos($str1, $search);

        return view('practicals.php.p11_4', compact('str1', 'str2', 'search', 'len', 'concat', 'upper', 'lower', 'ucwords', 'pos'));
    }

    // Practical 12: Database CRUD GUI
    public function p12(Request $request)
    {
        $students = Practical7Student::latest()->paginate(10);
        return view('practicals.php.p12', compact('students'));
    }

    // Interactive phpMyAdmin-style Database Explorer
    public function dbExplorer(Request $request)
    {
        $table = $request->query('table', 'railway_bookings');
        $validTables = ['railway_bookings', 'railway_systems', 'users', 'practical7_students'];
        if (!in_array($table, $validTables)) {
            $table = 'railway_bookings';
        }

        $tablesInfo = [];
        foreach ($validTables as $t) {
            $count = DB::table($t)->count();
            $tablesInfo[$t] = $count;
        }

        $records = DB::table($table)->latest('id')->paginate(15);
        $columns = DB::getSchemaBuilder()->getColumnListing($table);

        return view('practicals.php.db_explorer', compact('table', 'tablesInfo', 'records', 'columns'));
    }
}

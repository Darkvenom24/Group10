# Group 10 — Web Technology Practicals & Web Portal

**Government MCA College, Maninagar (GMCA)**  
*Master of Computer Applications (MCA) — Academic Year 2025–2026*  
**Course:** Web Technology Practical (WTP)

---

## 👥 Project Team (Group 10)

| Candidate Name | Enrollment No. | Academic Role | Profile Link |
| :--- | :--- | :--- | :--- |
| **Ridham Bambhaniya** | `26GMCA61` | Team Lead / Practical 1 & 2 Layouts | [member1.html](member1.html) |
| **Riya Thakkar** | `26GMCA33` | Practical 3 Ticket Portal & Logic | [member2.html](member2.html) |
| **Vaibhav Senjaliya** | `26GMCA52` | Practical 4 Calculator & Integration | [member3.html](member3.html) |

---

## 📂 Repository & Practical Filing Structure

```text
Group10-1/
├── index.html                               # Main Portal & Hub (Auth, User Dashboard, Student Data, Practicals)
├── about.html                               # Group 10 Team Overview & Academic Credentials
├── member1.html                             # Ridham Bambhaniya's Full Profile (26GMCA61)
├── member2.html                             # Riya Thakkar's Full Profile (26GMCA33)
├── member3.html                             # Vaibhav Senjaliya's Full Profile (26GMCA52)
│
├── practical-1.html                         # Practical 1: Institutional HTML Webpage
├── Practical-1 (26GMCA61...).html           # Practical 1: Original Lab Submission File
│
├── practical 2/                             # Practical 2: HTML & CSS Webpage & Profiles
│   ├── index.html                           # Practical 2 Webpage
│   ├── practical-2(26GMCA61).html           # Practical 2 Submission File
│   ├── about.html                           # Practical 2 Team Showcase
│   ├── member1.html, member2, member3       # Practical 2 Member Profiles
│   └── style.css                            # Practical 2 Stylesheet
│
├── railway.html                             # Practical 3: Indian Railways Booking Portal (Root Web App)
├── data.html                                # Practical 3: Saved Bookings Dataset & Analytics
├── Practical 3/                             # Practical 3: Dedicated Lab Directory
│   ├── index.html                           # Booking Portal Interface
│   ├── data.html                            # Booking Records Table
│   ├── about.html                           # About Us Page
│   ├── member1, member2, member3            # Member Profiles
│   ├── script.js                            # Form validation, fare computation & storage
│   └── style.css                            # UI styling & responsive stacking
│
├── calculator.html                          # Practical 4: Interactive Calculator (Themed Web App)
├── Practical-4.html                         # Practical 4: Standalone Lab Calculator (Dark Theme)
│
├── practical-7.html                         # Practical 7: Student Academic & Project Registration Form
├── practical-7-data.html                    # Practical 7 Data & Practical 12 CRUD Records Dashboard
│
├── php-practicals.html                      # All-in-One Interactive PHP Practicals Hub
├── practical-9-1.html                       # Practical 9.1: Max of 3 Values
├── practical-9-2.html                       # Practical 9.2: Print 1 to N Numbers
├── practical-9-3.html                       # Practical 9.3: 3 Types of Pyramid Patterns
├── practical-10-1.html                      # Practical 10.1: Array Operations (Print, Reverse, Merge, Sum)
├── practical-10-2.html                      # Practical 10.2: String Name & Length Passed as Argument
├── practical-11-1.html                      # Practical 11.1: Max & Min Numbers (If-Else & Ternary)
├── practical-11-2.html                      # Practical 11.2: Date/Time & Greeting by Time
├── practical-11-3.html                      # Practical 11.3: User Profile Web Page & PHP Form Handler
├── practical-11-4.html                      # Practical 11.4: 5 PHP String Functions
├── practical-12.html                        # Practical 12: MySQL Database Connection & Full CRUD Guide
│
├── php/                                     # Raw PHP Source Files & SQL Schema (for Apache / XAMPP / Lab Manual)
│   ├── config.php                           # MySQL Database Connection (mysqli & PDO)
│   ├── schema.sql                           # MySQL Database Table DDL & Group 10 Seed Data
│   ├── practical9_1.php                     # 9.1 Max of 3 values
│   ├── practical9_2.php                     # 9.2 1 to N numbers
│   ├── practical9_3.php                     # 9.3 3 pyramid patterns
│   ├── practical10_1.php                    # 10.1 Array operations
│   ├── practical10_2.php                    # 10.2 Name & string length
│   ├── practical11_1.php                    # 11.1 Max and min
│   ├── practical11_2.php                    # 11.2 Date/time greeting
│   ├── practical11_3.php                    # 11.3 Profile form POST processor
│   ├── practical11_4.php                    # 11.4 String operations
│   └── practical12_crud.php                 # 12.0 Complete MySQL CRUD API & Web GUI
│
├── php_engine.js                            # Client-Side PHP Simulation & Relational Storage Engine for Vercel
├── style.css                                # Master Stylesheet (Tricolor theme, nested dropdowns, PHP terminals)
├── script.js                                # Master JavaScript for Railway Portal & Visit Counter
│
├── ridham.jpeg                              # Ridham Bambhaniya's Profile Photo
├── riya.jpeg                                # Riya Thakkar's Profile Photo
├── vaibhav.jpeg                             # Vaibhav Senjaliya's Profile Photo
├── temp.jpeg                                # Vaibhav Senjaliya's Profile Photo Alias
│
├── vercel.json                              # Vercel Deployment Configuration
└── README.md                                # Comprehensive Project Documentation
```

---

## 🎯 Practical Objectives & Concepts Demonstrated

### Practical 1: Basic HTML Webpage
- **File**: `practical-1.html` / `Practical-1 (26GMCA61 - Web Page).html`
- **Concepts**: Pure HTML tags, table-based multi-column layout (`<table>`, `<tr>`, `<td>`, `<th>`), text formatting, HTML anchors/hyperlinks, form elements, and image embedding without external CSS frameworks.
- **Theme**: Institutional webpage for Government MCA College, Maninagar.

### Practical 2: HTML & CSS Styling
- **Folder**: `practical 2/`
- **Concepts**: Separation of concerns (HTML structure + external CSS), CSS box model, custom color palettes (Indian Flag saffron/navy/green theme), hover effects, multi-page site navigation, and structured profile cards.
- **Theme**: Group 10 member profiles and academic credentials.

### Practical 3: Dynamic Web Application (Indian Railways Portal)
- **Files**: `railway.html`, `data.html`, `script.js`, `Practical 3/`
- **Concepts**:
  1. **Dynamic DOM Manipulation**: Real-time fare estimation based on train selection, travel class multiplier (SL ₹500, 3A ₹1200, 2A ₹1800, 1A ₹3000), quota rules, and passenger count.
  2. **Client-Side Form Validation**: Regular expression validation for 10-digit mobile number, full name verification, journey date checking, and inline error message display.
  3. **Data Persistence**: `localStorage` JSON serialization to save bookings, delete records, calculate cumulative revenue, and filter records by passenger name or quota.
  4. **Visit Counter**: Tracks and increments unique page visits using browser storage.

### Practical 4: Interactive Keyboard Calculator
- **Files**: `calculator.html` (Themed Web App) & `Practical-4.html` (Standalone Lab View)
- **Concepts**:
  1. **Event Listeners**: Dual input handling — tactile button clicks and physical keyboard events (`keydown`).
  2. **Expression Evaluation**: Arithmetic parsing (`+`, `-`, `*`, `/`), parenthesis handling, decimal precision, backspace, and error trapping (e.g. division by zero).
  3. **CSS Grid**: 4-column responsive keypad layout with distinct action/operator color tokens.

### Practical 7: Student Academic & Project Information Form
- **Files**: `practical-7.html`, `practical-7-data.html`
- **Concepts**:
  - Comprehensive registration form collecting Student Enrollment, Full Name, Email, Contact Number, Academic Division, Roll Number, Chosen Project Title, Elective Technology Stack, GitHub Repository URL, and Academic Semester.
  - Required form validation, clear reset action, and submission storage linking directly into Practical 12's MySQL student database.

---

### Practical of PHP (9, 10, 11, 12)

#### 9. PHP Basics
- **9.1 Max Value of 3 Numbers** (`practical-9-1.html`, `php/practical9_1.php`)
  - Finds the maximum of 3 numbers entered by the user using conditional comparison logic and PHP `max()` function.
- **9.2 Print 1 to N Numbers** (`practical-9-2.html`, `php/practical9_2.php`)
  - Loops from 1 to `N`, displaying each number with badges, cumulative sum, and odd/even statistics.
- **9.3 3 Types of Pyramid Patterns** (`practical-9-3.html`, `php/practical9_3.php`)
  - Generates 3 customizable pyramid patterns for user-specified rows:
    1. *Right-Angled Triangle*
    2. *Centered Equilateral Pyramid*
    3. *Inverted Pyramid*

#### 10. PHP Array
- **10.1 Array Operations** (`practical-10-1.html`, `php/practical10_1.php`)
  - Performs 4 operations on user-entered comma-separated arrays:
    - `10.1.1` Print array values with index mapping
    - `10.1.2` Reverse array using `array_reverse()`
    - `10.1.3` Merge two arrays in sorted order using `array_merge()` and `sort()`
    - `10.1.4` Calculate sum of all array elements using `array_sum()`
- **10.2 String Name & Size Passed as Argument** (`practical-10-2.html`, `php/practical10_2.php`)
  - `10.2.1` Prints author/student name.
  - `10.2.2` Computes string size (`strlen()`, `mb_strlen()`, word count) passed as argument to a PHP function.

#### 11. PHP Control Structures
- **11.1 Maximum & Minimum Number** (`practical-11-1.html`, `php/practical11_1.php`)
  - Evaluates both maximum and minimum from user values using `if-else` and ternary conditional logic.
- **11.2 Date/Time & Greeting Message** (`practical-11-2.html`, `php/practical11_2.php`)
  - Reads system date/time (`date('H')`) and outputs a contextual greeting (*Good Morning* 05:00-11:59, *Good Afternoon* 12:00-16:59, *Good Evening* 17:00-21:59, *Good Night* 22:00-04:59). Features an interactive hour-slider simulation.
- **11.3 User Profile Web Page & PHP Form Display** (`practical-11-3.html`, `php/practical11_3.php`)
  - Full-featured user profile creation web page. Submits profile data and renders an official identification card and parameter summary in PHP.
- **11.4 5 PHP String Operations** (`practical-11-4.html`, `php/practical11_4.php`)
  - Encapsulated PHP functions for:
    - `11.4.1` Print name
    - `11.4.2` Print string size
    - `11.4.3` Concatenate two strings
    - `11.4.4` Convert case (`strtoupper`, `strtolower`, `ucwords`, `ucfirst`)
    - `11.4.5` Find substring position (`strpos`, `stripos`)

#### 12. Database Connection with PHP & CRUD Operations
- **Files**: `index.html` (Auth + User Record), `practical-7.html` (Form), `practical-7-data.html` (CRUD Data Dashboard), `practical-12.html` (Architecture Guide), `php/config.php`, `php/schema.sql`, `php/practical12_crud.php`
- **Workflow Implemented**:
  1. **User Registration & Login on Index Page**:
     - Modern tabbed authentication widget on `index.html` (`#authSection`).
     - Supports 1-click quick-fill credentials for Group 10 team leads (`ridham61`, `riya33`, `vaibhav52`).
  2. **Enforced Practical 7 Submission**:
     - After logging in, the user fills their Practical 7 Academic Form (`practical-7.html`).
     - Seamless pre-filling of logged-in user credentials.
  3. **Dedicated Records Display Page (`practical-7-data.html`)**:
     - Displays all submitted student data in a structured, sortable, and searchable table.
     - Provides complete **CRUD** capabilities:
       - **Create**: Add new student record.
       - **Read**: View student dossier modal with full academic breakdown.
       - **Update**: Edit existing record with live recalculation.
       - **Delete**: Soft/hard delete with confirmation prompt.
       - **Export**: Export database records to CSV or JSON.
  4. **Synced User Record Display on Index Page**:
     - Once submitted, the logged-in user's Practical 7 submission details appear on the `index.html` dashboard, along with recent submissions from all registered students.

---

## 🌐 Live Vercel Compatibility Architecture

Because Vercel is a serverless frontend hosting platform that does not run background Apache, MySQL, or phpMyAdmin daemons, this project uses a **dual architecture**:

1. **Client-Side PHP Execution Engine (`php_engine.js`)**:
   - Accurately parses and simulates all PHP logic in real-time in the browser.
   - Stores user accounts and Practical 7 records persistently in `localStorage`.
   - Logs simulated MySQL queries (`INSERT INTO users...`, `SELECT * FROM practical7_students...`) in real-time terminal views.
   - Allows professors, evaluators, and visitors to test the entire application on the live Vercel URL with zero setup.

2. **Authentic PHP Source Code & MySQL Database Files (`php/`)**:
   - Ready-to-deploy `.php` scripts and `schema.sql` for real XAMPP/Apache/MySQL environments.
   - Clean, procedural and PDO object-oriented code, with prepared statements to prevent SQL injection.
   - Students can copy code directly from the web GUI or download files for lab manual submissions.

---

## 🚀 How to Run Locally

### Option A: Static Web Server (Direct Browser / Python)
1. Clone or open the repository folder:
   ```bash
   cd Group10-1
   ```
2. Start a lightweight server:
   ```bash
   python -m http.server 3000
   ```
3. Open `http://localhost:3000` in your web browser.

### Option B: Local PHP & MySQL (XAMPP / WAMP / Apache)
1. Copy the `Group10-1` directory to your `xampp/htdocs/` folder.
2. Start **Apache** and **MySQL** in XAMPP Control Panel.
3. Open `http://localhost/phpmyadmin` and import `php/schema.sql`.
4. Open `http://localhost/Group10-1/` in your browser.
5. Direct PHP scripts can be executed at `http://localhost/Group10-1/php/practical12_crud.php`.

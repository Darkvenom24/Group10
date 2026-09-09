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
├── index.html                           # Main Landing Page (Simple HTML table structure & central menu)
├── about.html                           # Group 10 Team Overview & Academic Details
├── member1.html                         # Ridham Bambhaniya's Full Profile
├── member2.html                         # Riya Thakkar's Full Profile
├── member3.html                         # Vaibhav Senjaliya's Full Profile
│
├── practical-1.html                     # Practical 1: Institutional HTML Webpage
├── Practical-1 (26GMCA61...).html       # Practical 1: Original Submission File
│
├── practical 2/                         # Practical 2: HTML & CSS Webpage & Profiles
│   ├── index.html                       # Practical 2 Webpage
│   ├── practical-2(26GMCA61).html       # Practical 2 Submission File
│   ├── about.html                       # Practical 2 Team Showcase
│   ├── member1.html, member2, member3   # Practical 2 Member Profiles
│   └── style.css                        # Practical 2 Stylesheet
│
├── railway.html                         # Practical 3: Indian Railways Booking Portal (Root Web App)
├── data.html                            # Practical 3: Saved Bookings Dataset & Analytics
├── Practical 3/                         # Practical 3: Dedicated Lab Directory
│   ├── index.html                       # Booking Portal Interface
│   ├── data.html                        # Booking Records Table
│   ├── about.html                       # About Us Page
│   ├── member1, member2, member3        # Member Profiles
│   ├── script.js                        # Form validation, fare computation & storage
│   └── style.css                        # UI styling & responsive stacking
│
├── calculator.html                      # Practical 4: Interactive Calculator (Themed Web App)
├── Practical-4.html                     # Practical 4: Standalone Lab Calculator (Dark Theme)
│
├── style.css                            # Master Stylesheet (Tricolor palette, flexbox, cards, tables)
├── script.js                            # Master JavaScript (Validation, pricing, localStorage, counter)
│
├── ridham.jpeg                          # Ridham Bambhaniya's Profile Photo (1440x1440)
├── riya.jpeg                            # Riya Thakkar's Profile Photo (800x800)
├── vaibhav.jpeg                         # Vaibhav Senjaliya's Profile Photo (1024x1024)
├── temp.jpeg                            # Compatibility image alias for Vaibhav
│
├── vercel.json                          # Vercel Production Deployment Configuration
└── README.md                            # Comprehensive Project Documentation
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

---

## 🚀 How to Run Locally

1. Clone or open the repository folder in VS Code or any text editor:
   ```bash
   cd Group10-1
   ```
2. Open `index.html` in any modern web browser (Google Chrome, Microsoft Edge, Firefox):
   - You will land on the **Simple HTML Home Page**.
   - Use the top navigation bar to access any practical or member profile.
   - All pages feature a `← Home` link to return to the landing page at any time.
3. Alternatively, run a lightweight local server:
   ```bash
   python -m http.server 3000
   ```
   Then open `http://localhost:3000` in your browser.

window.addEventListener('DOMContentLoaded', () => {
    // 1. Track & Display Page Visit Counter
    updateVisitCounter();

    // 3. Set min date to today for train booking
    const travelDate = document.getElementById('travelDate');
    if (travelDate) {
        travelDate.min = new Date().toISOString().split('T')[0];
    }

    // 4. Seed initial mock dataset if empty
    seedInitialData();

    // 5. Render booked data on data.html
    const dataContainer = document.getElementById('dataContainer');
    if (dataContainer) {
        renderSubmittedData();
    }

    // 6. Initial Price Calculation
    calculateRailwayTotal();

    // 7. Attach Real-Time Event Listeners for Validation
    attachRealTimeValidation();
});

// Increment and render visit counts
function updateVisitCounter() {
    let totalVisits = parseInt(localStorage.getItem('totalPageVisits')) || 0;
    totalVisits++;
    localStorage.setItem('totalPageVisits', totalVisits);

    const pageKey = 'visits_' + window.location.pathname;
    let pageVisits = parseInt(localStorage.getItem(pageKey)) || 0;
    pageVisits++;
    localStorage.setItem(pageKey, pageVisits);

    const visitDisplay = document.getElementById('visitCounter');
    if (visitDisplay) {
        visitDisplay.innerText = totalVisits;
    }
}

// Dynamic Ticket Price Calculation
function calculateRailwayTotal() {
    const classSelect = document.getElementById('travelClass');
    const qtyInput = document.getElementById('passengerQty');
    if (!classSelect || !qtyInput) return;

    const baseFare = parseFloat(classSelect.value) || 0;
    const qty = parseInt(qtyInput.value) || 0;

    const total = baseFare * qty;
    const totalFareSpan = document.getElementById('totalFare');
    if (totalFareSpan) {
        totalFareSpan.innerText = total;
    }
}

// Seed mock records for testing/demonstration
function seedInitialData() {
    if (!localStorage.getItem('railwayBookingDataList')) {
        const initialData = [
            {
                pnr: "8421093847",
                name: "Rahul Sharma",
                phone: "9876543210",
                train: "20902 | VANDE BHARAT EXP (Ahmedabad to Mumbai)",
                classCategory: "Executive / 1st AC (1A) - ₹3000",
                passengers: 2,
                quota: "General",
                date: "2026-09-10",
                fareNum: 6000,
                status: "CONFIRMED"
            },
            {
                pnr: "6124598012",
                name: "Priya Patel",
                phone: "9825012345",
                train: "12958 | ADI SJ RAJDHANI (Ahmedabad to Delhi)",
                classCategory: "AC 3 Tier (3A) - ₹1200",
                passengers: 1,
                quota: "Tatkal",
                date: "2026-09-05",
                fareNum: 1200,
                status: "CONFIRMED"
            }
        ];
        localStorage.setItem('railwayBookingDataList', JSON.stringify(initialData));
    }
}

// --- Inline Error Helpers & Individual Field Validations ---

function setError(inputElem, errorElem, message) {
    if (!inputElem || !errorElem) return;
    errorElem.innerText = message;
    if (message) {
        inputElem.classList.add('input-error');
        inputElem.classList.remove('input-valid');
    } else {
        inputElem.classList.remove('input-error');
        inputElem.classList.add('input-valid');
    }
}

function validateName() {
    const nameInput = document.getElementById('passengerName');
    const nameError = document.getElementById('nameError');
    if (!nameInput || !nameError) return true;

    const val = nameInput.value.trim();
    const nameRegex = /^[A-Z][A-Za-z\s]{2,49}$/;

    if (!val) {
        setError(nameInput, nameError, "Full Name is required.");
        return false;
    } else if (!nameRegex.test(val)) {
        setError(nameInput, nameError, "Must start with a capital letter, contain no numbers (3-50 chars).");
        return false;
    } else {
        setError(nameInput, nameError, "");
        return true;
    }
}

function validatePhone() {
    const phoneInput = document.getElementById('phone');
    const phoneError = document.getElementById('phoneError');
    if (!phoneInput || !phoneError) return true;

    const val = phoneInput.value.trim();
    const phoneRegex = /^[6-9]\d{9}$/;

    if (!val) {
        setError(phoneInput, phoneError, "Mobile number is required.");
        return false;
    } else if (!phoneRegex.test(val)) {
        setError(phoneInput, phoneError, "Enter a valid 10-digit Indian number starting with 6-9.");
        return false;
    } else {
        setError(phoneInput, phoneError, "");
        return true;
    }
}

function validateTrain() {
    const select = document.getElementById('trainSelect');
    const err = document.getElementById('trainError');
    if (!select || !err) return true;

    if (!select.value) {
        setError(select, err, "Please select a train route.");
        return false;
    } else {
        setError(select, err, "");
        return true;
    }
}

function validateClass() {
    const select = document.getElementById('travelClass');
    const err = document.getElementById('classError');
    if (!select || !err) return true;

    if (!select.value) {
        setError(select, err, "Please select a travel class.");
        return false;
    } else {
        setError(select, err, "");
        return true;
    }
}

function validateQuota() {
    const select = document.getElementById('quota');
    const err = document.getElementById('quotaError');
    if (!select || !err) return true;

    if (!select.value) {
        setError(select, err, "Please select a booking quota.");
        return false;
    } else {
        setError(select, err, "");
        return true;
    }
}

function validateQty() {
    const input = document.getElementById('passengerQty');
    const err = document.getElementById('qtyError');
    if (!input || !err) return true;

    const qty = parseInt(input.value, 10);
    if (isNaN(qty) || qty < 1 || qty > 6) {
        setError(input, err, "Passengers count must be between 1 and 6.");
        return false;
    } else {
        setError(input, err, "");
        return true;
    }
}

function validateDate() {
    const input = document.getElementById('travelDate');
    const err = document.getElementById('dateError');
    if (!input || !err) return true;

    if (!input.value) {
        setError(input, err, "Please select a travel date.");
        return false;
    }

    const selectedDate = new Date(input.value);
    const today = new Date();
    today.setHours(0, 0, 0, 0);

    if (selectedDate < today) {
        setError(input, err, "Travel date cannot be in the past.");
        return false;
    } else {
        setError(input, err, "");
        return true;
    }
}

// Bind live listeners for real-time error checking
function attachRealTimeValidation() {
    const nameInput = document.getElementById('passengerName');
    const phoneInput = document.getElementById('phone');
    const trainSelect = document.getElementById('trainSelect');
    const classSelect = document.getElementById('travelClass');
    const quotaSelect = document.getElementById('quota');
    const qtyInput = document.getElementById('passengerQty');
    const dateInput = document.getElementById('travelDate');

    if (nameInput) nameInput.addEventListener('input', validateName);
    if (phoneInput) phoneInput.addEventListener('input', validatePhone);
    if (trainSelect) trainSelect.addEventListener('change', validateTrain);
    if (classSelect) classSelect.addEventListener('change', validateClass);
    if (quotaSelect) quotaSelect.addEventListener('change', validateQuota);
    if (qtyInput) qtyInput.addEventListener('input', validateQty);
    if (dateInput) dateInput.addEventListener('change', validateDate);
}

// Form Submit Handler
function handleRailwayBooking(event) {
    event.preventDefault();

    // Trigger all individual field validations on submit
    const isNameValid = validateName();
    const isPhoneValid = validatePhone();
    const isTrainValid = validateTrain();
    const isClassValid = validateClass();
    const isQuotaValid = validateQuota();
    const isQtyValid = validateQty();
    const isDateValid = validateDate();

    // Halt if any field is invalid
    if (!isNameValid || !isPhoneValid || !isTrainValid || !isClassValid || !isQuotaValid || !isQtyValid || !isDateValid) {
        return false;
    }

    // Extract DOM Input Values
    const passengerName = document.getElementById('passengerName').value.trim();
    const phone = document.getElementById('phone').value.trim();
    const trainSelect = document.getElementById('trainSelect');
    const classSelect = document.getElementById('travelClass');
    const passengerQty = parseInt(document.getElementById('passengerQty').value, 10);
    const travelDate = document.getElementById('travelDate').value;
    const quota = document.getElementById('quota').value;

    const totalFare = parseFloat(document.getElementById('totalFare').innerText) || 0;
    const className = classSelect.options[classSelect.selectedIndex].text;
    const trainName = trainSelect.options[trainSelect.selectedIndex].text;
    const pnrNumber = Math.floor(1000000000 + Math.random() * 9000000000).toString();

    const bookingRecord = {
        pnr: pnrNumber,
        name: passengerName,
        phone: phone,
        train: trainName,
        classCategory: className,
        passengers: passengerQty,
        quota: quota,
        date: travelDate,
        fareNum: totalFare,
        status: "CONFIRMED"
    };

    // Save into LocalStorage Array
    const existingData = JSON.parse(localStorage.getItem('railwayBookingDataList')) || [];
    existingData.unshift(bookingRecord);
    localStorage.setItem('railwayBookingDataList', JSON.stringify(existingData));

    alert(`Booking Confirmed Successfully!\n\nPNR: ${pnrNumber}\nPassenger: ${passengerName}\nTotal Fare: ₹${totalFare}`);

    // Reset Form & Clear validation styling states
    document.getElementById('railwayForm').reset();
    document.querySelectorAll('.error-msg').forEach(el => el.innerText = '');
    document.querySelectorAll('.input-error, .input-valid').forEach(el => {
        el.classList.remove('input-error', 'input-valid');
    });

    calculateRailwayTotal();
    return true;
}

// Render Saved Tickets Array into Data Table
function renderSubmittedData() {
    const container = document.getElementById('dataContainer');
    const records = JSON.parse(localStorage.getItem('railwayBookingDataList')) || [];

    updateDashboardMetrics(records);

    if (records.length === 0) {
        container.innerHTML = `
            <div style="background: white; padding: 30px; border-radius: 8px;">
                <h2>No Booking Records Found</h2>
                <p>No active reservations registered yet. <a href="index.html">Book a Ticket</a></p>
            </div>`;
        return;
    }

    let tableHTML = `
        <table class="data-table">
            <thead>
                <tr>
                    <th>PNR</th>
                    <th>Passenger Name</th>
                    <th>Mobile</th>
                    <th>Train Route</th>
                    <th>Quota</th>
                    <th>Passengers</th>
                    <th>Date</th>
                    <th>Fare</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody id="tableBody">`;

    records.forEach((item, index) => {
        tableHTML += `
            <tr>
                <td><b>${item.pnr}</b></td>
                <td>${item.name}</td>
                <td>${item.phone}</td>
                <td>${item.train}</td>
                <td><span class="badge quota-badge">${item.quota}</span></td>
                <td>${item.passengers}</td>
                <td>${item.date}</td>
                <td>₹${item.fareNum}</td>
                <td><span class="badge status-badge">${item.status}</span></td>
                <td><button onclick="cancelBooking(${index})" class="btn-danger">Cancel</button></td>
            </tr>`;
    });

    tableHTML += `</tbody></table>`;
    container.innerHTML = tableHTML;
}

// Update Dashboard Metric Widgets
function updateDashboardMetrics(records) {
    const totalCount = document.getElementById('totalBookingsCount');
    const totalRev = document.getElementById('totalRevenueAmount');
    const activeRoutes = document.getElementById('activeRoutesCount');

    if (!totalCount || !totalRev || !activeRoutes) return;

    const revenue = records.reduce((sum, r) => sum + r.fareNum, 0);
    const routesSet = new Set(records.map(r => r.train));

    totalCount.innerText = records.length;
    totalRev.innerText = '₹' + revenue.toLocaleString('en-IN');
    activeRoutes.innerText = routesSet.size;
}

// Dynamic Filter Bar Handler
function filterBookingData() {
    const searchVal = document.getElementById('searchInput').value.toLowerCase();
    const quotaVal = document.getElementById('quotaFilter').value;
    const records = JSON.parse(localStorage.getItem('railwayBookingDataList')) || [];

    const filtered = records.filter(r => {
        const matchesSearch = r.name.toLowerCase().includes(searchVal) || r.pnr.includes(searchVal);
        const matchesQuota = (quotaVal === 'ALL') || (r.quota === quotaVal);
        return matchesSearch && matchesQuota;
    });

    const tbody = document.getElementById('tableBody');
    if (!tbody) return;

    tbody.innerHTML = filtered.map((item, index) => `
        <tr>
            <td><b>${item.pnr}</b></td>
            <td>${item.name}</td>
            <td>${item.phone}</td>
            <td>${item.train}</td>
            <td><span class="badge quota-badge">${item.quota}</span></td>
            <td>${item.passengers}</td>
            <td>${item.date}</td>
            <td>₹${item.fareNum}</td>
            <td><span class="badge status-badge">${item.status}</span></td>
            <td><button onclick="cancelBooking(${index})" class="btn-danger">Cancel</button></td>
        </tr>
    `).join('');
}

// Cancellation Handler
function cancelBooking(index) {
    const records = JSON.parse(localStorage.getItem('railwayBookingDataList')) || [];
    if (confirm(`Cancel ticket with PNR: ${records[index].pnr}?`)) {
        records.splice(index, 1);
        localStorage.setItem('railwayBookingDataList', JSON.stringify(records));
        renderSubmittedData();
    }
}
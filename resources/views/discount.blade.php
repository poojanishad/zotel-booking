<!DOCTYPE html>
<html>
<head>
    <title>Discount Config</title>

<style>
body {
    font-family: Arial;
    background: #f5f7fa;
    padding: 40px;
}

/* MENU BUTTON */
.menu-btn {
    position: fixed;
    top: 20px;
    right: 20px;
    font-size: 20px;
    background: #000;
    color: white;
    border: none;
    padding: 10px;
    border-radius: 5px;
    cursor: pointer;
}

/*SIDEBAR */
.sidebar {
    position: fixed;
    right: -300px;
    top: 0;
    width: 250px;
    height: 100%;
    background: white;
    box-shadow: -2px 0 10px rgba(0,0,0,0.1);
    padding: 20px;
    transition: 0.3s;
}

.sidebar a {
    display: block;
    padding: 10px;
    margin: 10px 0;
    text-decoration: none;
    color: black;
    border-radius: 5px;
}

.sidebar a:hover {
    background: #f1f1f1;
}

h2 { text-align: center; }

.card {
    background: #fff;
    padding: 20px;
    border-radius: 12px;
    margin: 20px auto;
    max-width: 600px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
}

.row {
    display: flex;
    gap: 10px;
    align-items: center;
    margin-top: 10px;
}

input {
    padding: 8px;
    border-radius: 8px;
    border: 1px solid #ccc;
    width: 120px;
}

button {
    background: black;
    color: white;
    border: none;
    padding: 8px 12px;
    border-radius: 8px;
    cursor: pointer;
}

.tag {
    background: #e6f4ea;
    color: green;
    padding: 5px 10px;
    border-radius: 20px;
    margin-right: 10px;
}

.edit-btn {
    cursor: pointer;
    margin-left: 10px;
    color: #555;
}

.toast {
    position: fixed;
    top: 20px;
    right: 20px;
    color: #fff;
    padding: 12px 20px;
    border-radius: 8px;
    opacity: 0;
    transition: 0.3s;
}
.toast.show { opacity: 1; }
</style>
</head>

<body>

<button onclick="toggleSidebar()" class="menu-btn">☰</button>

<div id="sidebar" class="sidebar">
    <h3>Zotel Demo</h3>

    <a href="/">🔍 Booking</a>
    <a href="/inventory-view">📊 Inventory</a>
    <a href="/discounts">⚙ Discounts</a>
</div>

<h2>Discount Configuration</h2>

<div id="toast" class="toast"></div>

<div class="card">
    <h4>LONG STAY DISCOUNTS</h4>

    <div class="row">
        <input type="number" id="min_nights" placeholder="Min nights">
        <input type="number" id="long_value" placeholder="%">
        <button onclick="saveLong()">✔</button>
    </div>

    <div id="longList"></div>
</div>

<div class="card">
    <h4>LAST MINUTE DISCOUNTS</h4>

    <div class="row">
        <input type="number" id="days" placeholder="Days ahead">
        <input type="number" id="last_value" placeholder="%">
        <button onclick="saveLast()">✔</button>
    </div>

    <div id="lastList"></div>
</div>

<script>

//  SIDEBAR TOGGLE
function toggleSidebar() {
    let sidebar = document.getElementById('sidebar');

    if (sidebar.style.right === '0px') {
        sidebar.style.right = '-300px';
    } else {
        sidebar.style.right = '0px';
    }
}

//  Toast
function showToast(msg, success = true) {
    const t = document.getElementById('toast');
    t.innerText = msg;
    t.style.background = success ? 'green' : 'red';
    t.classList.add('show');
    setTimeout(() => t.classList.remove('show'), 2000);
}

// Load
function loadDiscounts() {
    fetch('/api/discounts')
    .then(res => res.json())
    .then(data => {

        let longHTML = '';
        let lastHTML = '';

        data.forEach(d => {

            if (d.type === 'long_stay') {
                longHTML += `
                <p>
                    <span class="tag">${d.value}%</span>
                    ${d.min_nights}+ nights
                    <span class="edit-btn" onclick="editLong(${d.min_nights}, ${d.value})">✏</span>
                </p>`;
            }

            if (d.type === 'last_minute') {
                lastHTML += `
                <p>
                    <span class="tag">${d.value}%</span>
                    within ${d.days_before_checkin} days
                    <span class="edit-btn" onclick="editLast(${d.days_before_checkin}, ${d.value})">✏</span>
                </p>`;
            }
        });

        document.getElementById('longList').innerHTML = longHTML;
        document.getElementById('lastList').innerHTML = lastHTML;
    });
}

//  EDIT
function editLong(min, val) {
    document.getElementById('min_nights').value = min;
    document.getElementById('long_value').value = val;
}

function editLast(days, val) {
    document.getElementById('days').value = days;
    document.getElementById('last_value').value = val;
}

// SAVE LONG
function saveLong() {
    let min = document.getElementById('min_nights').value;
    let val = document.getElementById('long_value').value;

    if (!min || !val) {
        showToast('Fill all fields ❌', false);
        return;
    }

    fetch('/api/discounts', {
        method: 'POST',
        headers: {'Content-Type': 'application/json'},
        body: JSON.stringify({
            type: 'long_stay',
            min_nights: min,
            value: val
        })
    })
    .then(() => {
        showToast('Saved Long');
        loadDiscounts();
    });
}

// SAVE LAST
function saveLast() {
    let days = document.getElementById('days').value;
    let val = document.getElementById('last_value').value;

    if (!days || !val) {
        showToast('Fill all fields ❌', false);
        return;
    }

    fetch('/api/discounts', {
        method: 'POST',
        headers: {'Content-Type': 'application/json'},
        body: JSON.stringify({
            type: 'last_minute',
            days_before_checkin: days,
            value: val
        })
    })
    .then(() => {
        showToast('Saved  Last');
        loadDiscounts();
    });
}

// INIT
loadDiscounts();

</script>

</body>
</html>
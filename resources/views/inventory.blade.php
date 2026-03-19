<!DOCTYPE html>
<html>
<head>
    <title>Inventory & Pricing</title>

    <style>
        * {
            box-sizing: border-box;
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto;
        }

        body {
            margin: 0;
            background: #f4f6f9;
        }

        h2 {
            text-align: center;
            margin-top: 30px;
            font-weight: 600;
        }

        .container {
            max-width: 1100px;
            margin: auto;
            padding: 20px;
        }

        .tabs {
            display: flex;
            justify-content: center;
            gap: 10px;
            margin: 20px 0;
        }

        .tab {
            padding: 10px 22px;
            border-radius: 25px;
            background: #e0e0e0;
            cursor: pointer;
            font-weight: 500;
            transition: 0.2s;
        }

        .tab.active {
            background: #000;
            color: #fff;
        }

        .card {
            background: #fff;
            border-radius: 12px;
            padding: 10px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.05);
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th {
            text-align: left;
            padding: 14px;
            font-size: 14px;
            color: #666;
            border-bottom: 1px solid #eee;
        }

        td {
            padding: 14px;
            border-bottom: 1px solid #f1f1f1;
            font-size: 14px;
        }

        tr:hover {
            background: #fafafa;
        }

        .editable {
            cursor: pointer;
            font-weight: 500;
        }

        .editable:hover {
            background: #f1f3f6;
            border-radius: 6px;
        }

        .inline-input {
            width: 80px;
            padding: 5px;
            text-align: center;
            border-radius: 6px;
            border: 1px solid #ccc;
        }

        .update-msg {
            font-size: 10px;
            color: green;
        }

        .menu-btn {
            position: fixed;
            top: 20px;
            right: 20px;
            background: black;
            color: white;
            border: none;
            padding: 10px 14px;
            border-radius: 6px;
            cursor: pointer;
        }

        .sidebar {
            position: fixed;
            right: -260px;
            top: 0;
            width: 250px;
            height: 100%;
            background: white;
            padding: 20px;
            box-shadow: -2px 0 10px rgba(0,0,0,0.2);
            transition: 0.3s;
        }

        .sidebar.active {
            right: 0;
        }

        .sidebar a {
            display: block;
            padding: 10px;
            margin: 10px 0;
            text-decoration: none;
            color: black;
        }

        .overlay {
            position: fixed;
            width: 100%;
            height: 100%;
            background: rgba(0,0,0,0.3);
            display: none;
            top: 0;
            left: 0;
        }

        .overlay.active {
            display: block;
        }
    </style>
</head>

<body>

<button class="menu-btn" onclick="toggleSidebar()">☰</button>

<div id="overlay" class="overlay" onclick="toggleSidebar()"></div>

<div id="sidebar" class="sidebar">
    <h3>Zotel Demo</h3>
    <a href="/">🔍 Booking</a>
    <a href="/inventory-view">📊 Inventory</a>
    <a href="/discounts">⚙ Discounts</a>
</div>


<h2>Inventory & Pricing</h2>

<div class="container">

    <div class="tabs">
        <div class="tab active" onclick="loadInventory(1, this)">Standard</div>
        <div class="tab" onclick="loadInventory(2, this)">Deluxe</div>
    </div>

    <div class="card">
        <table>
            <thead>
                <tr>
                    <th>Room</th>
                    <th>Date</th>
                    <th>Avail</th>
                    <th>1 Person</th>
                    <th>2 Persons</th>
                    <th>3 Persons</th>
                    <th>Breakfast</th>
                </tr>
            </thead>
            <tbody id="tableBody"></tbody>
        </table>
    </div>

</div>

<script>

function toggleSidebar() {
    document.getElementById('sidebar').classList.toggle('active');
    document.getElementById('overlay').classList.toggle('active');
}

function loadInventory(roomTypeId, el=null) {

    if (el) {
        document.querySelectorAll('.tab').forEach(t => t.classList.remove('active'));
        el.classList.add('active');
    }

    fetch(`http://127.0.0.1:8000/api/inventory?room_type_id=${roomTypeId}`)
    .then(res => res.json())
    .then(data => {

        let html = '';

        data.forEach(row => {

            let date = new Date(row.date).toLocaleDateString('en-GB');

            html += `
            <tr>
                <td>${roomTypeId==1?'Standard':'Deluxe'}</td>
                <td>${date}</td>

                <td class="editable" data-field="available" data-id="${row.id}" onclick="editCell(this)">
                    ${row.available}
                </td>

                <td class="editable" data-field="price_1" data-id="${row.id}" onclick="editCell(this)">
                    ₹${parseInt(row.price_1)}
                </td>

                <td class="editable" data-field="price_2" data-id="${row.id}" onclick="editCell(this)">
                    ₹${parseInt(row.price_2)}
                </td>

                <td class="editable" data-field="price_3" data-id="${row.id}" onclick="editCell(this)">
                    ₹${parseInt(row.price_3)}
                </td>

                <td class="editable" data-field="breakfast" data-id="${row.id}" onclick="editCell(this)">
                    ₹${parseInt(row.breakfast)}
                </td>
            </tr>`;
        });

        document.getElementById('tableBody').innerHTML = html;
    });
}

function editCell(cell) {

    if (cell.querySelector('input')) return;

    let field = cell.dataset.field;
    let oldValue = parseInt(cell.innerText.replace(/[₹,\s]/g, ''));

    let input = document.createElement('input');
    input.type = "number";
    input.value = oldValue;
    input.className = "inline-input";

    cell.innerHTML = "";
    cell.appendChild(input);
    input.focus();

    function save() {

        let newValue = input.value;

        if (newValue === "" || isNaN(newValue)) {
            alert("Only number allowed");
            input.focus();
            return;
        }

        newValue = parseInt(newValue);

        cell.innerHTML = field === 'available' ? newValue : "₹" + newValue;

        let msg = document.createElement('div');
        msg.className = "update-msg";
        msg.innerText = "✔";
        cell.appendChild(msg);

        setTimeout(() => msg.remove(), 1000);

        fetch('http://127.0.0.1:8000/api/update-inventory', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({
                id: cell.dataset.id,
                field: field,
                value: newValue
            })
        });
    }

    input.addEventListener('blur', save);
    input.addEventListener('keydown', e => {
        if (e.key === 'Enter') save();
    });
}

loadInventory(1);

</script>

</body>
</html>
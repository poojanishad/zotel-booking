<!DOCTYPE html>
<html>
<head>
    <title>Inventory & Pricing</title>

    <style>
        body {
            font-family: Arial;
            background: #f5f7fa;
            padding: 20px;
        }

        h2 {
            text-align: center;
        }

        /*  MENU BUTTON */
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

        .sidebar h3 {
            margin-bottom: 20px;
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

        table {
            width: 100%;
            border-collapse: collapse;
            background: white;
            margin-top: 20px;
        }

        th, td {
            padding: 12px;
            border-bottom: 1px solid #ddd;
            text-align: center;
        }

        th {
            background: #f1f1f1;
        }

        .tabs {
            display: flex;
            justify-content: center;
            margin-bottom: 10px;
        }

        .tab {
            padding: 10px 20px;
            cursor: pointer;
            background: #ddd;
            margin: 5px;
            border-radius: 20px;
            transition: 0.3s;
        }

        .tab.active {
            background: #000;
            color: white;
        }
    </style>
</head>

<body>


<button onclick="toggleSidebar()" class="menu-btn">☰</button>

<div id="sidebar" class="sidebar">
    <h3>Zotel temp</h3>

    

    <a href="{{ url('/') }}">🔍 Booking</a>
    <a href="/inventory-view">📊 Inventory</a>
    <a href="/discounts">⚙ Discounts</a>

</div>

<h2>Inventory & Pricing</h2>

<div class="tabs">
    <div class="tab active" onclick="loadInventory(1, this)">Standard Room</div>
    <div class="tab" onclick="loadInventory(2, this)">Deluxe Room</div>
</div>

<table>
    <thead>
        <tr>
            <th>Room Type</th>
            <th>Date</th>
            <th>Avail.</th>
            <th>1 Person</th>
            <th>2 Persons</th>
            <th>3 Persons</th>
            <th>Breakfast</th>
        </tr>
    </thead>

    <tbody id="tableBody"></tbody>
</table>

<script>

// SIDEBAR TOGGLE
function toggleSidebar() {
    let sidebar = document.getElementById('sidebar');

    if (sidebar.style.right === '0px') {
        sidebar.style.right = '-300px';
    } else {
        sidebar.style.right = '0px';
    }
}

// INVENTORY LOAD
function loadInventory(roomTypeId, el = null) {

    if (el) {
        document.querySelectorAll('.tab').forEach(t => t.classList.remove('active'));
        el.classList.add('active');
    }

    fetch(`http://127.0.0.1:8000/api/inventory?room_type_id=${roomTypeId}`)
        .then(res => res.json())
        .then(data => {

            let html = '';

            data.forEach(row => {

                let date = new Date(row.date).toLocaleDateString('en-GB', {
                    day: '2-digit',
                    month: 'short',
                    year: 'numeric'
                });

                html += `
                    <tr>
                        <td>${roomTypeId == 1 ? 'Standard' : 'Deluxe'}</td>
                        <td>${date}</td>
                        <td>${row.available}</td>
                        <td>₹${row.price_1}</td>
                        <td>₹${row.price_2}</td>
                        <td>₹${row.price_3}</td>
                        <td>₹${row.breakfast}</td>
                    </tr>
                `;
            });

            document.getElementById('tableBody').innerHTML = html;
        });
}

//  Default load
loadInventory(1);

</script>

</body>
</html>
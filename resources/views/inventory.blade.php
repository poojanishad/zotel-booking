<!DOCTYPE html>
<html>
<head>
    <title>Inventory & Pricing</title>

    <style>
        body { font-family: Arial; background: #f5f7fa; padding: 20px; }
        h2 { text-align: center; }

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

        th { background: #f1f1f1; }

        .editable { cursor: pointer; }

        .inline-input {
            width: 80px;
            text-align: center;
        }

        .update-msg {
            color: green;
            font-size: 11px;
        }

        .tab {
            padding: 10px 20px;
            cursor: pointer;
            background: #ddd;
            margin: 5px;
            border-radius: 20px;
        }

        .tab.active {
            background: #000;
            color: white;
        }

        .tabs {
            display: flex;
            justify-content: center;
        }
    </style>
</head>

<body>

<h2>Inventory & Pricing</h2>

<div class="tabs">
    <div class="tab active" onclick="loadInventory(1, this)">Standard</div>
    <div class="tab" onclick="loadInventory(2, this)">Deluxe</div>
</div>

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

<script>

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

            let date = new Date(row.date).toLocaleDateString('en-GB', {
                day:'2-digit', month:'short', year:'numeric'
            });

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
    input.step = "1";
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

        if (field === 'available') {
            cell.innerHTML = newValue;
        } else {
            cell.innerHTML = "₹" + newValue;
        }

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
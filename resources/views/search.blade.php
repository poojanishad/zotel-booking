<!DOCTYPE html>
<html>
<head>
    <title>Hotel Booking</title>

    <style>
        body {
            font-family: Arial;
            background: #f5f7fa;
            margin: 0;
            padding: 20px;
        }

        h2 {
            text-align: center;
        }

  
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

        form {
            background: #fff;
            padding: 15px;
            border-radius: 10px;
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
            justify-content: center;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }

        input, select, button {
            padding: 8px;
            border-radius: 5px;
            border: 1px solid #ccc;
        }

        button {
            background: #007bff;
            color: #fff;
            cursor: pointer;
        }

        button:hover {
            background: #0056b3;
        }

        #results {
            margin-top: 20px;
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            gap: 20px;
        }

        .card {
            background: #fff;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
            transition: 0.3s;
        }

        .card:hover {
            transform: translateY(-5px);
        }

        .card img {
            width: 100%;
            height: 180px;
            object-fit: cover;
        }

        .card-body {
            padding: 15px;
        }

        .price {
            color: green;
            font-size: 20px;
            font-weight: bold;
        }

        .old-price {
            text-decoration: line-through;
            color: #999;
        }

        .sold {
            color: red;
            font-weight: bold;
        }
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

<h2>🏨 Hotel Booking Search</h2>

<form id="searchForm">
    <input type="date" name="check_in" value="2026-03-20" required>
    <input type="date" name="check_out" value="2026-03-23" required>
    <input type="number" name="guests" min="1" value="1" max="3" placeholder="Adults" required>

    <select name="meal_plan">
        <option value="">No Meal</option>
        <option value="room_only">Room Only</option>
        <option value="breakfast">Breakfast</option>
    </select>

    <button type="submit">Search</button>
</form>

<div id="results"></div>

<script>

// 🔥 SIDEBAR TOGGLE
function toggleSidebar() {
    let sidebar = document.getElementById('sidebar');

    if (sidebar.style.right === '0px') {
        sidebar.style.right = '-300px';
    } else {
        sidebar.style.right = '0px';
    }
}

document.addEventListener("DOMContentLoaded", function () {

    const form = document.getElementById('searchForm');
    const resultsDiv = document.getElementById('results');

    // 🔥 Auto search
    document.querySelectorAll('input, select').forEach(el => {
        el.addEventListener('change', () => {
            form.dispatchEvent(new Event('submit'));
        });
    });

    form.onsubmit = function(e) {
        e.preventDefault();

        const formData = new FormData(form);
        const query = new URLSearchParams(formData).toString();

        resultsDiv.innerHTML = "Loading...";

        fetch('http://127.0.0.1:8000/api/search?' + query)
            .then(res => res.json())
            .then(data => {

                let html = '';

                if (!data.length) {
                    resultsDiv.innerHTML = "<p>No rooms available</p>";
                    return;
                }

                data.forEach(room => {

                    let image = room.room_type === "Deluxe"
                        ? "https://images.unsplash.com/photo-1566073771259-6a8506099945"
                        : "https://images.unsplash.com/photo-1551882547-ff40c63fe5fa";

                    html += `
                        <div class="card">
                            <img src="${image}" alt="hotel">

                            <div class="card-body">
                                <h3>${room.room_type}</h3>

                                <p>👥 Adults: <b>${formData.get('guests')}</b></p>

                                ${room.sold_out
                                    ? '<p class="sold">❌ Sold Out</p>'
                                    : `<p>✅ Available Rooms: ${room.available_rooms}</p>`
                                }

                                <p class="old-price">₹${room.pricing.total}</p>
                                <p class="price">₹${room.pricing.final}</p>
                                <p>Discount: ₹${room.pricing.discount}</p>
                            </div>
                        </div>
                    `;
                });

                resultsDiv.innerHTML = html;
            })
            .catch(err => {
                console.error(err);
                resultsDiv.innerHTML = "<p style='color:red'>Error loading data</p>";
            });
    };

});
</script>

</body>
</html>
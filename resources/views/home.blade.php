<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>

    <style>
        body {
            margin: 0;
            font-family: Arial, sans-serif;
            background-color: #f4f6f9;
        }

        .sidebar {
            width: 220px;
            height: 100vh;
            background: #1f2937;
            color: white;
            position: fixed;
            padding: 20px;
        }

        .sidebar h2 {
            margin-bottom: 30px;
        }

        .sidebar a {
            display: block;
            color: #cbd5e1;
            text-decoration: none;
            margin: 15px 0;
        }

        .sidebar a:hover {
            color: white;
        }

        .main {
            margin-left: 240px;
            padding: 30px;
        }

        .cards {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
            gap: 20px;
            margin-top: 20px;
        }

        .card {
            background: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.05);
        }

        .card h3 {
            margin: 0 0 10px;
        }

        .card p {
            font-size: 22px;
            font-weight: bold;
        }

        .topbar {
            background: white;
            padding: 15px 30px;
            box-shadow: 0 2px 6px rgba(0,0,0,0.05);
            margin-left: 240px;
        }
    </style>
</head>
<body>

    <div class="sidebar">
        <h2>Dashboard</h2>
        <a href="#">Home</a>
        <a href="#">Users</a>
        <a href="#">Reports</a>
        <a href="#">Settings</a>
    </div>

    <div class="topbar">
        <strong>Welcome back, Admin</strong>
    </div>

    <div class="main">
        <h1>Overview</h1>

        <div class="cards">
            <div class="card">
                <h3>Total Users</h3>
                <p>1,245</p>
            </div>

            <div class="card">
                <h3>New Orders</h3>
                <p>87</p>
            </div>

            <div class="card">
                <h3>Revenue</h3>
                <p>$12,340</p>
            </div>

            <div class="card">
                <h3>Pending Tickets</h3>
                <p>14</p>
            </div>
        </div>
    </div>

</body>
</html>
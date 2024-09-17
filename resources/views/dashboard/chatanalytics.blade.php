@extends('layout.app')

@section('content')
<style>
    body {
        font-family: Arial, sans-serif;
        background-color: #f4f4f4;
        margin: 0;
        padding: 20px;
    }

    .container {
        background: white;
        border-radius: 8px;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        padding: 20px;
    }

    .header {
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .date-picker input,
    .date-picker button {
        padding: 8px;
        margin-left: 5px;
    }

    .tab {
        display: flex;
    }

    .tab button {
        background: none;
        border: none;
        padding: 10px;
        margin-left: 5px;
        cursor: pointer;
    }

    .tab button.active {
        border-bottom: 2px solid blue;
    }

    .chart {
        margin-top: 20px;
    }

    .stats {
        display: flex;
        justify-content: space-between;
        margin-top: 20px;
    }

    .stat-item {
        background: #e3e4e6;
        padding: 15px;
        border-radius: 8px;
        text-align: center;
    }

    .stat-item h2 {
        color: #333;
    }

    .stat-item p {
        font-size: 24px;
        margin-top: 5px;
    }
</style>
<div class="container">
    <header class="header">
        <h1>Analytics</h1>
        <div class="date-picker">
            <input type="date">
            <input type="date">
            <button>Show</button>
        </div>
        <div class="tab">
            <button class="tablinks active">Weekly</button>
            <button class="tablinks">Monthly</button>
        </div>
    </header>
    <div class="chart">
        <canvas id="line-chart"></canvas>
    </div>
    <div class="stats">
        <div class="stat-item">
            <h2>Total Chats</h2>
            <p>01</p>
        </div>
        <div class="stat-item">
            <h2>Total Messages</h2>
            <p>100</p>
        </div>
        <div class="stat-item">
            <h2>Total Feedbacks</h2>
            <p>00</p>
        </div>
        <div class="stat-item">
            <h2>Total Visitors</h2>
            <p>200</p>
        </div>
    </div>
</div>
@endsection

@section('java_scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
    // Chart.js configuration
    const ctx = document.getElementById('line-chart').getContext('2d');
    const lineChart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul"],

            datasets: [{
                label: "My First dataset",
                data: [65, 59, 80, 81, 56, 55, 40],
                backgroundColor: 'rgba(255, 99, 132, 0.2)',
                borderColor: 'rgba(255, 99, 132, 1)',
                borderWidth: 2,
                fill: true,
                tension: 0.4
            },
            {
                label: "My Second dataset",
                data: [28, 48, 40, 19, 86, 27, 90],
                backgroundColor: 'rgba(54, 162, 235, 0.2)',
                borderColor: 'rgba(54, 162, 235, 1)',
                borderWidth: 2,
                fill: true,
                tension: 0.4
            }]
        },
        options: {
            responsive: true,
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });
</script>
@endsection

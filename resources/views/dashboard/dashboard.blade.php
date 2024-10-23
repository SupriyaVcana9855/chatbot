@extends('layout.app')

@section('content')

<div class="dashboard">
  <div class="row">
    <div class="col-lg-12 set-das-heading">
      <h2>Dashboard</h2>
    </div>
    
    <!-- Dashboard Stat Cards -->
    <div class="col-lg-3 col-md-6 mt-3 mb-3">
      <div class="set-total">
        <div class="set-inner-content">
          <h6>TOTAL BOTS</h6>
          <p>{{ $getBotCount }}</p>
        </div>
        <div class="">
          <img src="{{ asset('/assets/images/BOTS.png') }}" alt="Bots">
        </div>
      </div>
    </div>
    <div class="col-lg-3 col-md-6 mt-3 mb-3">
      <div class="set-total">
        <div class="set-inner-content">
          <h6>TOTAL CHATS</h6>
          <p>{{ $getChatCount }}</p>
        </div>
        <div class="">
          <img src="{{ asset('/assets/images/TOTAL MESSAGES.png') }}" alt="Chats">
        </div>
      </div>
    </div>
    <div class="col-lg-3 col-md-6 mt-3 mb-3">
      <div class="set-total">
        <div class="set-inner-content">
          <h6>TOTAL USERS</h6>
          <p>{{ $getUserCount }}</p>
        </div>
        <div class="">
          <img src="{{ asset('/assets/images/TOTAL FEEDBACKS.png') }}" alt="Users">
        </div>
      </div>
    </div>
    <div class="col-lg-3 col-md-6 mt-3 mb-3">
      <div class="set-total">
        <div class="set-inner-content">
          <h6>TOTAL BOTS USERS</h6>
          <p>{{ $getBotUserCount }}</p>
        </div>
        <div class="">
          <img src="{{ asset('/assets/images/TOTAL VISITORS.png') }}" alt="Bot Users">
        </div>
      </div>
    </div>

    <!-- Chart Section -->
    <div class="container col-lg-12">
      <div id="filter" style="text-align: center; margin-bottom: 20px;">
        <select id="time-filter" class="form-control col-md-6 form-select">
          <option value="week">Last Week</option>
          <option value="month">Last Month</option>
          <option value="year">Last Year</option>
          <option value="5years">Last 5 Years</option>
        </select>
      </div>
      <div id="apexcharts-area"></div>
    </div>
  </div>
</div>

@endsection

@section('java_scripts')
<script>
$(document).ready(function() {
    // Parse the JSON data passed from PHP
    var chartData = JSON.parse('<?php echo $chartDataJson; ?>');

    // Chart options
    var options = {
        chart: {
            height: 350,
            type: "area",
        },
        dataLabels: {
            enabled: false
        },
        stroke: {
            curve: "smooth"
        },
        series: [], // This will be updated dynamically
        xaxis: {
            type: "datetime", // Dates on x-axis
            categories: [], // This will be dynamically populated
        },
        tooltip: {
            x: {
                format: "dd/MM/yy"
            },
        },
    };

    var chart = new ApexCharts(
        document.querySelector("#apexcharts-area"),
        options
    );

    // Function to update chart with dynamic data
    function updateChart(range) {
        // Extract data for the selected range
        var usersData = Object.values(chartData[range].users);
        var botsData = Object.values(chartData[range].bots);
        var botUsersData = Object.values(chartData[range].bot_users);
        var dates = Object.keys(chartData[range].users); // Extract dates for x-axis

        // Update the chart series and categories
        options.series = [{
            name: "Users",
            data: usersData
        }, {
            name: "Bots",
            data: botsData
        }, {
            name: "Bot Users",
            data: botUsersData
        }];

        options.xaxis.categories = dates;

        // Update the chart with new data
        chart.updateOptions(options);
    }

    // Initial render
    chart.render().then(() => {
        updateChart('week'); // Load week data by default
    });

    // Event listener for filter change
    document.getElementById('time-filter').addEventListener('change', function () {
        var selectedRange = this.value;
        updateChart(selectedRange); // Update chart based on the selected time frame
    });
});
</script>
@endsection

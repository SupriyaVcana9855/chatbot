@extends('layout.app')
@section('content')

<div class="boxpadding">
    <div class="row searchi-section mt-4">
        <div class="col-2 set-boat-heading">
            <h6>Chart Analytics</h6>
        </div>
    </div>
   <!-- Chart Section -->
   <div class="col-lg-12">
      <div id="filter" style="text-align: center; margin-bottom: 20px;">
        <select id="time-filter" class="form-control col-md-6 form-select">
          <option value="week">Last Week</option>
          <option value="month">Last Month</option>
          <option value="year">Last Year</option>
          <option value="5years">Last 5 Years</option>
        </select>
      </div>
      <div id="apexcharts-area-bot"></div>
    </div>
</div>
@endsection

@section('java_scripts')
<script>
    // Pass the chart data from PHP to JavaScript
    var chartData = JSON.parse('<?php echo $chartDataJson; ?>');

    // Initialize the chart with default options
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
        series: [{
            name: "Bot Users", // The name for the series (label in the legend)
            data: chartData['week'].counts // Initial data (week)
        }],
        xaxis: {
            type: "datetime",
            categories: chartData['week'].categories // Initial categories (week)
        },
        tooltip: {
            x: {
                format: "dd/MM/yy"
            }
        },
        // Add the legend to display the name
        legend: {
            show: true,
            position: 'top', // You can adjust the position as needed
        }
    };

    // Render the chart
    var chart = new ApexCharts(document.querySelector("#apexcharts-area-bot"), options);
    chart.render();

    // Listen for filter changes
    document.querySelector("#time-filter").addEventListener("change", function() {
        var selectedValue = this.value;

        // Update chart with the new data based on the selected filter
        chart.updateOptions({
            series: [{
                name: "Bot Users", // Ensure this label persists
                data: chartData[selectedValue].counts
            }],
            xaxis: {
                categories: chartData[selectedValue].categories
            }
        });
    });
</script>
@endsection

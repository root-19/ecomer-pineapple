<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Visitor and User Chart</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        canvas {
            max-width: 600px;
            margin: 20px auto;
        }
        .count-display {
            text-align: center;
            font-size: 24px;
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <h1>Visitor and User Statistics</h1>
    <canvas id="myChart"></canvas>
    <div class="count-display" id="countDisplay"></div> <!-- Section to display counts -->
    
    <script>
        async function fetchData() {
            const response = await fetch('user_visitor.php'); // Adjust the path if necessary
            const data = await response.json();
            return data;
        }

        fetchData().then(data => {
            // Prepare the chart data
            const chartData = {
                labels: ['Users', 'Visitors'],
                datasets: [{
                    label: 'User vs Visitor',
                    data: [data.user_count, data.visitor_count], // Using the data from the backend
                    backgroundColor: [
                        'rgb(54, 162, 235)', // Color for Users
                        'rgb(255, 99, 132)'  // Color for Visitors
                    ],
                    hoverOffset: 4
                }]
            };

            // Create the doughnut chart
            const ctx = document.getElementById('myChart').getContext('2d');
            const myChart = new Chart(ctx, {
                type: 'doughnut', // Doughnut chart
                data: chartData,
                options: {
                    responsive: true,
                    plugins: {
                        legend: {
                            position: 'top',
                        },
                        title: {
                            display: true,
                            text: 'User and Visitor Counts'
                        }
                    }
                }
            });

            // Update the count display
            const countDisplay = document.getElementById('countDisplay');
            countDisplay.innerHTML = `Users: ${data.user_count} <br> Visitors: ${data.visitor_count}`;
        }).catch(error => {
            console.error('Error fetching data:', error);
        });
    </script>
</body>
</html>

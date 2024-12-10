<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<div>
    <canvas id="monthlyBookingsChart" width="400" height="200"></canvas>
</div>

<script>
// Get data from PHP
const bookingsPerMonth = <?php echo json_encode(array_values($bookingsPerMonth)); ?>;

const ctx = document.getElementById('monthlyBookingsChart').getContext('2d');
const monthlyBookingsChart = new Chart(ctx, {
    type: 'line',
    data: {
        labels: [
            'January', 'February', 'March', 'April', 'May', 'June', 
            'July', 'August', 'September', 'October', 'November', 'December'
        ],
        datasets: [{
            label: 'Bookings per Month (<?php echo $currentYear; ?>)',
            data: bookingsPerMonth,
            backgroundColor: 'rgba(75, 192, 192, 0.2)',
            borderColor: 'rgba(75, 192, 192, 1)',
            borderWidth: 2,
            fill: true
        }]
    },
    options: {
        responsive: true,
        scales: {
            y: {
                beginAtZero: true,
                title: {
                    display: true,
                    text: 'Number of Bookings'
                }
            },
            x: {
                title: {
                    display: true,
                    text: 'Month'
                }
            }
        }
    }
});
</script>

</body>
</html>

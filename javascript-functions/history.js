$(document).ready(function() {
    const ctx = document.getElementById('historicalChart').getContext('2d');
    
    // Generate dates for last 20 days
    const dates = Array.from({length: 20}, (_, i) => {
        const date = new Date();
        date.setDate(date.getDate() - (19 - i));
        return date.toLocaleDateString('en-US', {
            month: 'numeric',
            day: 'numeric',
            year: 'numeric'
        });
    });
    
    // Generate random data and calculate averages
    const data1 = Array.from({length: 20}, () => Math.floor(Math.random() * 100));
    const data2 = Array.from({length: 20}, () => Math.floor(Math.random() * 100));
    const averageData = data1.map((val, idx) => (val + data2[idx]) / 2);

    const historicalChart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: dates,
            datasets: [{
                label: 'Average Daily Readings',
                data: averageData,
                backgroundColor: 'rgba(75, 192, 192, 0.8)',
                borderColor: 'rgba(75, 192, 192, 1)',
                borderWidth: 1,
                barPercentage: 1.0,
                categoryPercentage: 1.0
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    position: 'top',
                },
                title: {
                    display: true,
                    text: 'Average Daily Readings Histogram'
                }
            },
            scales: {
                x: {
                    display: true,
                    title: {
                        display: true,
                        text: 'Date'
                    }
                },
                y: {
                    beginAtZero: true,
                    title: {
                        display: true,
                        text: 'Average Reading'
                    }
                }
            }
        }
    });
    
    
    // Date range filter
    $('#dateRangeForm').on('submit', function(e) {
        e.preventDefault();
        const startDate = $('#startDate').val();
        const endDate = $('#endDate').val();
        
        // Add your filter logic here
        // You can update the chart data based on date range
        console.log('Date Range:', startDate, endDate);
    });
});
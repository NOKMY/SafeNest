function printChart() {
    // Create a promise that resolves when both charts are rendered
    const chartsRendered = new Promise((resolve) => {
        const checkChartsRendered = setInterval(() => {
            if (window.pastHourChartRendered && window.waterLevelChartRendered) {
                clearInterval(checkChartsRendered);
                resolve();
            }
        }, 100);
    });

    chartsRendered.then(() => {
        // Get the chart data
        const pastHourChartData = window.pastHourChartData;
        const waterLevelChartData = window.waterLevelChartData;

        // Create a new window for printing
        const printWindow = window.open('', '_blank');
        printWindow.document.write('<html><head><title>Print Chart</title>');
        printWindow.document.write('<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">');
        printWindow.document.write('</head><body>');
        printWindow.document.write('<div class="container">');
        printWindow.document.write('<h4>Real-Time Water Level Data</h4>');
        printWindow.document.write('<img src="' + waterLevelChartData + '"/>');
        printWindow.document.write('<h4>Real-Time Water Level Data (Past 1 Hour)</h4>');
        printWindow.document.write('<img src="' + pastHourChartData + '"/>');
        printWindow.document.write('</div>');
        printWindow.document.write('</body></html>');
        printWindow.document.close();
        printWindow.focus();
        printWindow.print();
        printWindow.close();
    });
}
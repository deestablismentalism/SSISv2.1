document.addEventListener('DOMContentLoaded', function() {
    const statCards = document.querySelectorAll('.stat-card');
    
    statCards.forEach(card => {
        card.addEventListener('mouseenter', function() {
            this.style.cursor = 'pointer';
        });
    });

    // Realtime clock functionality
    const hoursElement = document.getElementById('Hours');
    const minutesElement = document.getElementById('Minutes');
    const secondsElement = document.getElementById('Seconds');
    const dateElement = document.getElementById('date');

    // Display current date
    const currentDate = new Date();
    dateElement.innerHTML = currentDate.toLocaleDateString();

    // Update clock every second
    setInterval(() => {
        const now = new Date();
        hoursElement.innerHTML = String(now.getHours()).padStart(2, '0');
        minutesElement.innerHTML = String(now.getMinutes()).padStart(2, '0');
        secondsElement.innerHTML = String(now.getSeconds()).padStart(2, '0');
    }, 1000);

    // Initialize clock immediately
    const now = new Date();
    hoursElement.innerHTML = String(now.getHours()).padStart(2, '0');
    minutesElement.innerHTML = String(now.getMinutes()).padStart(2, '0');
    secondsElement.innerHTML = String(now.getSeconds()).padStart(2, '0');

    // Chart elements
    const chart1Canvas = document.getElementById('students-bio-sex-chart');
    const chart2Canvas = document.getElementById('enrollees-grade-level-chart');
    const chart3Canvas = document.getElementById('enrollees-bio-sex-chart');

    // Loading elements
    const chart1Loading = document.getElementById('students-bio-sex-loading');
    const chart2Loading = document.getElementById('enrollees-grade-level-loading');
    const chart3Loading = document.getElementById('enrollees-bio-sex-loading');

    // Containers
    const chart1Container = document.getElementById('students-bio-sex-container');
    const chart2Container = document.getElementById('enrollees-grade-level-container');
    const chart3Container = document.getElementById('enrollees-bio-sex-container');

    // Hide canvases initially
    chart1Canvas.style.display = 'none';
    chart2Canvas.style.display = 'none';
    chart3Canvas.style.display = 'none';

    // Fetch and render charts
    fetch('../../../BackEnd/api/teacher/fetchDashboardChart.php')
        .then(response => {
            if(!response.ok) {
                throw new Error('Network response was not ok');
            }
            return response.json();
        })
        .then(data => {
            console.log('Chart data received:', data);
            if(data.success) {
                // Chart 1: Students Biological Sex (Pie Chart)
                if(data.data.chart1 && data.data.chart1.success) {
                    console.log('Chart 1 data:', data.data.chart1.data);
                    chart1Loading.style.display = 'none';
                    chart1Canvas.style.display = 'block';
                    renderBiologicalSexPieChart(data.data.chart1.data, chart1Canvas, 'Students Biological Sex Distribution');
                } else {
                    console.log('Chart 1 failed:', data.data.chart1);
                    chart1Loading.textContent = data.data.chart1 ? data.data.chart1.message : 'No data available';
                }

                // Chart 2: Enrollees Grade Level (Bar Chart)
                if(data.data.chart3 && data.data.chart3.success) {
                    console.log('Chart 2 data:', data.data.chart3.data);
                    chart2Loading.style.display = 'none';
                    chart2Canvas.style.display = 'block';
                    renderGradeLevelBarChart(data.data.chart3.data, chart2Canvas, 'Enrollees Grade Level Distribution');
                } else {
                    console.log('Chart 2 failed:', data.data.chart3);
                    chart2Loading.textContent = data.data.chart3 ? data.data.chart3.message : 'No data available';
                }

                // Chart 3: Enrollees Biological Sex (Pie Chart)
                if(data.data.chart4 && data.data.chart4.success) {
                    console.log('Chart 3 data:', data.data.chart4.data);
                    chart3Loading.style.display = 'none';
                    chart3Canvas.style.display = 'block';
                    renderBiologicalSexPieChart(data.data.chart4.data, chart3Canvas, 'Enrollees Biological Sex Distribution');
                } else {
                    console.log('Chart 3 failed:', data.data.chart4);
                    chart3Loading.textContent = data.data.chart4 ? data.data.chart4.message : 'No data available';
                }
            } else {
                console.error('Failed to fetch chart data:', data.message);
                [chart1Loading, chart2Loading, chart3Loading].forEach(loading => {
                    loading.textContent = 'Failed to load data: ' + (data.message || 'Unknown error');
                });
            }
        })
        .catch(error => {
            console.error('Error fetching chart data:', error);
            [chart1Loading, chart2Loading, chart3Loading].forEach(loading => {
                loading.textContent = 'Error loading data';
            });
        });
});

// Chart rendering functions
function renderBiologicalSexPieChart(data, canvas, title) {
    const ctx = canvas.getContext('2d');
    const labels = data.map(item => item.label);
    const values = data.map(item => parseInt(item.value));
    const total = values.reduce((sum, val) => sum + val, 0);

    new Chart(ctx, {
        type: 'pie',
        data: {
            labels: labels,
            datasets: [{
                data: values,
                backgroundColor: [
                    '#36A2EB',
                    '#FF6384'
                ]
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            layout: {
                padding: {
                    top: 0,
                    left: 50,
                    right: 50,
                    bottom: 50
                }
            },
            plugins: {
                title: {
                    display: true,
                    text: title,
                    position: 'top',
                    padding: {
                        top: 10,
                        bottom: 10
                    },
                    font: {
                        size: 16,
                        weight: 'bold'
                    }
                },
                legend: {
                    position: 'top',
                    align: 'center',
                    labels: {
                        padding: 12,
                        font: {
                            size: 11
                        },
                        boxWidth: 12,
                        boxHeight: 12
                    }
                },
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            const label = context.label || '';
                            const value = context.parsed || 0;
                            const percentage = total > 0 ? ((value / total) * 100).toFixed(1) : 0;
                            return `${label}: ${value} (${percentage}%)`;
                        }
                    }
                },
                datalabels: {
                    color: '#ffffff',
                    backgroundColor: function(context) {
                        return context.dataset.backgroundColor[context.dataIndex];
                    },
                    borderColor: '#ffffff',
                    borderWidth: 1.5,
                    borderRadius: 3,
                    padding: 4,
                    font: {
                        weight: 'bold',
                        size: 10
                    },
                    anchor: 'end',
                    align: 'end',
                    offset: 6,
                    formatter: (value, context) => {
                        const percentage = total > 0 ? ((value / total) * 100).toFixed(1) : 0;
                        return `${value}\n(${percentage}%)`;
                    }
                }
            }
        },
        plugins: [ChartDataLabels]
    });
}

function renderGradeLevelBarChart(data, canvas, title) {
    const ctx = canvas.getContext('2d');
    
    // Define all grade levels in order
    const allGradeLevels = ['Kinder I', 'Kinder II', 'Grade 1', 'Grade 2', 'Grade 3', 'Grade 4', 'Grade 5', 'Grade 6'];
    
    // Create a map from existing data
    const dataMap = {};
    data.forEach(item => {
        dataMap[item.label] = parseInt(item.value);
    });
    
    // Fill in all grade levels with 0 if not present
    const labels = allGradeLevels;
    const values = allGradeLevels.map(level => dataMap[level] || 0);
    const total = values.reduce((sum, val) => sum + val, 0);

    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: labels,
            datasets: [{
                label: 'Count',
                data: values,
                backgroundColor: '#36A2EB',
                borderColor: '#36A2EB',
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            indexAxis: 'y',
            scales: {
                x: {
                    beginAtZero: true,
                    ticks: {
                        stepSize: 1
                    }
                },
                y: {
                    grid: {
                        display: false
                    }
                }
            },
            plugins: {
                title: {
                    display: true,
                    text: title,
                    position: 'top',
                    padding: {
                        top: 10,
                        bottom: 10
                    },
                    font: {
                        size: 16,
                        weight: 'bold'
                    }
                },
                legend: {
                    display: false
                },
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            const value = context.parsed.x || 0;
                            const percentage = total > 0 ? ((value / total) * 100).toFixed(1) : 0;
                            return `Count: ${value} (${percentage}%)`;
                        }
                    }
                },
                datalabels: {
                    color: '#ffffff',
                    font: {
                        weight: 'bold',
                        size: 12
                    },
                    anchor: 'center',
                    align: 'center',
                    formatter: (value, context) => {
                        if (value === 0) return '';
                        const percentage = total > 0 ? ((value / total) * 100).toFixed(1) : 0;
                        return `${value} (${percentage}%)`;
                    }
                }
            }
        },
        plugins: [ChartDataLabels]
    });
}


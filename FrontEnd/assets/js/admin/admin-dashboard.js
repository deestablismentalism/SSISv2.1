import {loadingText} from '../utils.js';
document.addEventListener('DOMContentLoaded', function(){
    const canva = document.getElementById('enrollee-pie-chart');
    const canva2 = document.getElementById('enrollee-grade-level-distribution');
    const canva3 = document.getElementById('enrollee-biological-sex');
    const canva4 = document.getElementById('student-pie-chart');
    const canva5 = document.getElementById('student-grade-level-distribution');
    const canva6 = document.getElementById('student-biological-sex');
    
    // Loading elements
    const enrolleeLoading = document.getElementById('enrollee-pie-chart-loading');
    const GradeLevelDistributionLoading = document.getElementById('enrollee-grade-level-distribution-loading');
    const BiologicalSexLoading = document.getElementById('enrollee-biological-sex-loading');
    const studentPieChartLoading = document.getElementById('student-pie-chart-loading');
    const studentGradeLevelDistributionLoading = document.getElementById('student-grade-level-distribution-loading');
    const studentBiologicalSexLoading = document.getElementById('student-biological-sex-loading');
    
    // Containers
    const pieChartContainer = document.getElementById('pie-chart-container');
    const gradeLevelDistributionContainer = document.getElementById('grade-level-distribution-container');
    const biologicalSexContainer = document.getElementById('biological-sex-container');
    const studentPieChartContainer = document.getElementById('student-pie-chart-container');
    const studentGradeLevelDistributionContainer = document.getElementById('student-grade-level-distribution-container');
    const studentBiologicalSexContainer = document.getElementById('student-biological-sex-container');

    // Initialize counters and hide containers
    pieChartContainer.style.display = 'none';
    gradeLevelDistributionContainer.style.display = 'none';
    biologicalSexContainer.style.display = 'none';
    studentPieChartContainer.style.display = 'none';
    studentGradeLevelDistributionContainer.style.display = 'none';
    studentBiologicalSexContainer.style.display = 'none';

    const loaders = document.querySelectorAll('.chart-loading');
    console.log(loaders);
    fetch('../../../BackEnd/api/admin/fetchDashboardChart.php')
    .then(response => {
        if(!response.ok) {
            console.error(`Failed to load Status: ${data.httpcode}`);
            console.error(`Error: ${data.message}`);
        }
        return response.json();
    })
    .then(data=> {
        loaders.forEach(load=>{load.innerHTML = loadingText});
        if(!data.success) {
            alert(data.message);
        }
        if (data.failed && data.failed > 0) {
            data.failed.forEach(message =>{
                console.error(`failed fetches ${message[0]}`);
            })
        }
        else {
            const {chart1, chart2, chart3, chart4, chart5, chart6} = data.data;
            // Update counters
            loaders.forEach(load=>{load.style.display = 'none'});
            // Display enrollment charts
            if (canva && chart1.success) {
                enrolleeLoading.style.display = 'none';
                pieChartContainer.style.display = 'block';
                EnrollmentsPieChart(chart1.data, canva);
            }
            
            if (canva2 && chart2.success) {
                GradeLevelDistributionLoading.style.display = 'none';
                gradeLevelDistributionContainer.style.display = 'block';
                barGraph(chart2.data, canva2); 
            }
            
            if (canva3 && chart3.success) {
                BiologicalSexLoading.style.display = 'none';
                biologicalSexContainer.style.display = 'block';
                BiologicalSexPieGraph(chart3.data, canva3);
            }
            
            // Display student charts
            if (canva4 && chart4.success) {
                studentPieChartLoading.style.display = 'none';
                studentPieChartContainer.style.display = 'block';
                StudentsPieChart(chart4.data, canva4);
            }
            
            if (canva5 && chart5.success) {
                studentGradeLevelDistributionLoading.style.display = 'none';
                studentGradeLevelDistributionContainer.style.display = 'block';
                StudentGradeLevelDistribution(chart5.data, canva5);
            }
            
            if (canva6 && chart6.success) {
                studentBiologicalSexLoading.style.display = 'none';
                studentBiologicalSexContainer.style.display = 'block';
                StudentBiologicalSexPieGraph(chart6.data, canva6);
            }
        }
    })
    .catch(error=>{
        console.error("Error fetching dashboard data:", error);
        // Set loading elements to show "No data found" message
        const loadingElements = [enrolleeLoading, GradeLevelDistributionLoading, BiologicalSexLoading, 
            studentPieChartLoading, studentGradeLevelDistributionLoading, studentBiologicalSexLoading];
        
        loadingElements.forEach(element => {
            if (element) element.innerHTML = "<p>No data found</p>";
        });
    });
    //dashboard clock
    let hours = document.getElementById('Hours');
    let mins = document.getElementById('Minutes');
    let secs = document.getElementById('Seconds');
    let date = document.getElementById('date');

    const currentDate = new Date();
    date.innerHTML = currentDate.toLocaleDateString();
    setInterval(()=> {
        let currentTime = new Date();
        hours.innerHTML = (currentTime.getHours()<10 ? '0' : '') + currentTime.getHours();
        mins.innerHTML = (currentTime.getMinutes()<10 ? '0' : '') + currentTime.getMinutes();
        secs.innerHTML = (currentTime.getSeconds()<10 ? '0' : '') + currentTime.getSeconds();
    }, 1000);

    //initial enrollee by day 
    const enrolleeByDayCanva = document.getElementById('enrollee-by-day');
    //change depending on button input
    const radio = document.querySelectorAll('input[type="radio"]');
    async function updateEnrolleeByDay() {
        const currentSelected = document.querySelector("input[name='days-filter']:checked").value;
        const data = await enrolleeByDay(currentSelected);
        if (data) EnrolleeByDaybarGraph(data, enrolleeByDayCanva);
    }
    updateEnrolleeByDay(); // initial load
    radio.forEach(el => el.addEventListener('change', updateEnrolleeByDay));
});
//outside DOM event listener
function StudentsPieChart(data, title) {
    const ctx = title.getContext('2d');
    const labels = data.map(item=> item.label);
    const values = data.map(item=> parseInt(item.value));
    const total = values.reduce((sum, val) => sum + val, 0);
    
    new Chart(ctx, {
        type: 'pie',
        data: {
            labels: labels,
            datasets: [{
                data: values,
                backgroundColor: [
                    '#36A2EB',
                    '#FFCE56',
                    '#FF6384',
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
                    text: 'Student Status Distribution',
                    position: 'top',
                    padding: {
                        top: 10,
                        bottom: 5
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
                            const value = context.parsed;
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
                        if (total === 0) return '0 (0%)';
                        const percentage = ((value / total) * 100).toFixed(1);
                        const label = context.chart.data.labels[context.dataIndex];
                        return `${label}\n${value} (${percentage}%)`;
                    }
                }
            }
        },
        plugins: [ChartDataLabels]
    });
}
async function enrolleeByDay(day) {
    try {
        day = parseInt(day);

        let response = await fetch('../../../BackEnd/api/admin/fetchAdminEnrolleesByDay.php', {
            method: 'POST',
            headers : {'Content-type' : 'application/json'},
            body: JSON.stringify({day})
        });
        let data = await response.json();

        if(!response.ok) {
            console.error(`Failed to load Status: ${data.httpcode}`);
            console.error(`Error ${data.message}`);
            return null;
        }
        if(!data.success) {
            alert(data.message || 'Something went wrong');
            return null;
        }
        
        return data.data;
    }
    catch(error) {
        console.error(error);
        return null;
    }
}
function EnrollmentsPieChart(data, title) {
    const ctx = title.getContext('2d');
    const gradient = ctx.createLinearGradient(0, 0, 0, 400);
    gradient.addColorStop(0, 'rgba(255, 99, 132, 0.5)');
    gradient.addColorStop(1, 'rgba(54, 162, 235, 0.5)');
    const labels = data.map(item=> item.label);
    const values = data.map(item=> parseInt(item.value));
    const total = values.reduce((sum, val) => sum + val, 0);
    
    new Chart(ctx, {
        type: 'pie',
        data: {
            labels: labels,
            datasets: [{
                data: values,
                backgroundColor: [
                    '#6581a3',
                    '#00142f',
                    '#005c66',
                    '#000866'
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
                    text: 'Enrollment Status Distribution',
                    position: 'top',
                    padding: {
                        top: 10,
                        bottom: 5
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
                            const value = context.parsed;
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
                        if (total === 0) return '0 (0%)';
                        const percentage = ((value / total) * 100).toFixed(1);
                        const label = context.chart.data.labels[context.dataIndex];
                        return `${label}\n${value} (${percentage}%)`;
                    }
                }
            }
        },
        plugins: [ChartDataLabels]
    });
}
function barGraph(data, title) {
    const ctx = title.getContext('2d');
    
    // Define all grade levels in your school
    const allGradeLevels = ['Kinder I', 'Kinder II', 'Grade 1', 'Grade 2', 'Grade 3', 'Grade 4', 'Grade 5', 'Grade 6'];
    
    // Create a map from existing data
    const dataMap = {};
    data.forEach(item => {
        dataMap[item.label] = item.value;
    });
    
    // Fill in all grade levels with 0 if not present
    const labels = allGradeLevels;
    const values = allGradeLevels.map(level => dataMap[level] || 0);
    
    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: labels,
            datasets: [{
                label: 'Enrollee Count',
                data: values,
                backgroundColor: '#36A2EB'
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            indexAxis: 'y',
            plugins: {
                legend: {
                    position: 'top'
                },
                title: {
                    display: true,
                    text: 'Enrollee Grade Level Distribution',
                    font: {
                        size: 16
                    }
                }
            },
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
            }
        }
    });
}
function BiologicalSexPieGraph(data, title) {
    const ctx = title.getContext('2d');
    const labels = data.map(item=> item.label);
    const values = data.map(item=> parseInt(item.value));
    const total = values.reduce((sum, val) => sum + val, 0);
    
    new Chart(ctx, {
        type: 'pie',
        data: {
            labels: labels,
            datasets: [{
                data: values,
                backgroundColor: [
                    '#36A2EB',
                    '#FFCE56',
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
                    text: 'Enrollee Biological Sex Distribution',
                    position: 'top',
                    padding: {
                        top: 10,
                        bottom: 5
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
                            const value = context.parsed;
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
                        if (total === 0) return '0 (0%)';
                        const percentage = ((value / total) * 100).toFixed(1);
                        const label = context.chart.data.labels[context.dataIndex];
                        return `${label}\n${value} (${percentage}%)`;
                    }
                }
            }
        },
        plugins: [ChartDataLabels]
    });
}
function StudentGradeLevelDistribution(data, title) {
    const ctx = title.getContext('2d');
    
    // Define all grade levels in your school
    const allGradeLevels = ['Kinder I', 'Kinder II', 'Grade 1', 'Grade 2', 'Grade 3', 'Grade 4', 'Grade 5', 'Grade 6'];
    
    // Create a map from existing data
    const dataMap = {};
    data.forEach(item => {
        dataMap[item.label] = item.value;
    });
    
    // Fill in all grade levels with 0 if not present
    const labels = allGradeLevels;
    const values = allGradeLevels.map(level => dataMap[level] || 0);
    
    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: labels,
            datasets: [{
                label: 'Student Count',
                data: values,
                backgroundColor: '#FF6384'
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            indexAxis: 'y',
            plugins: {
                legend: {
                    position: 'top'
                },
                title: {
                    display: true,
                    text: 'Student Grade Level Distribution',
                    font: {
                        size: 16
                    }
                }
            },
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
            }
        }
    });
}
function StudentBiologicalSexPieGraph(data, title) {
    const ctx = title.getContext('2d');
    const labels = data.map(item=> item.label);
    const values = data.map(item=> parseInt(item.value));
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
                    text: 'Student Biological Sex Distribution',
                    position: 'top',
                    padding: {
                        top: 10,
                        bottom: 5
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
                            const value = context.parsed;
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
                        if (total === 0) return '0 (0%)';
                        const percentage = ((value / total) * 100).toFixed(1);
                        const label = context.chart.data.labels[context.dataIndex];
                        return `${label}\n${value} (${percentage}%)`;
                    }
                }
            }
        },
        plugins: [ChartDataLabels]
    });
}
let Enrollmentchart = null
function EnrolleeByDaybarGraph(data, title) {
    const ctx = title.getContext('2d');
    const labels = data.map(item=> item.label);
    const values = data.map(item=> item.value);
    if (Enrollmentchart) {
        Enrollmentchart.destroy();
    }
    Enrollmentchart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: labels,
            datasets: [{
                label: 'Submitted Enrollment Forms',
                data: values,
                backgroundColor: '#31c1ffff'
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    position: 'top'
                },
                title: {
                    display: true,
                    text: 'Submitted Enrollment Forms Distribution',
                    font: {
                        size: 20
                    }
                }
            }
        }
    });
}
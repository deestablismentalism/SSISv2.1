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

    fetch('../../../BackEnd/api/admin/fetchDashboardChart.php')
    .then(response => response.json())
    .then(data=> {
        console.log(data);
        
        if(data.success === false) {
            alert(data.message);
        }
        else {
            // Update counters
            
            // Display enrollment charts
            if (canva && data.chart1) {
                enrolleeLoading.style.display = 'none';
                pieChartContainer.style.display = 'block';
                EnrollmentsPieChart(data.chart1);
            }
            
            if (canva2 && data.chart2) {
                GradeLevelDistributionLoading.style.display = 'none';
                gradeLevelDistributionContainer.style.display = 'block';
                barGraph(data.chart2); 
            }
            
            if (canva3 && data.chart3) {
                BiologicalSexLoading.style.display = 'none';
                biologicalSexContainer.style.display = 'block';
                BiologicalSexPieGraph(data.chart3);
            }
            
            // Display student charts
            if (canva4 && data.chart4) {
                studentPieChartLoading.style.display = 'none';
                studentPieChartContainer.style.display = 'block';
                StudentsPieChart(data.chart4);
            }
            
            if (canva5 && data.chart5) {
                studentGradeLevelDistributionLoading.style.display = 'none';
                studentGradeLevelDistributionContainer.style.display = 'block';
                StudentGradeLevelDistribution(data.chart5);
            }
            
            if (canva6 && data.chart6) {
                studentBiologicalSexLoading.style.display = 'none';
                studentBiologicalSexContainer.style.display = 'block';
                StudentBiologicalSexPieGraph(data.chart6);
            }
        }
    })
    .catch(error=>{
        console.log("Error fetching dashboard data:", error);
        
        // Set loading elements to show "No data found" message
        const loadingElements = [enrolleeLoading, GradeLevelDistributionLoading, BiologicalSexLoading, 
            studentPieChartLoading, studentGradeLevelDistributionLoading, studentBiologicalSexLoading];
        
        loadingElements.forEach(element => {
            if (element) element.innerHTML = "<p>No data found</p>";
        });
    });

    function StudentsPieChart(data) {
        const ctx = canva4.getContext('2d');
        const labels = data.map(item=> item.label);
        const values = data.map(item=> item.value);
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
                plugins: {
                    legend: {
                        position: 'top'
                    },
                    title: {
                        display: true,
                        text: 'Student Status Distribution',
                        font: {
                            size: 16
                        }
                    }
                }
            }
        });
    }
    
    function EnrollmentsPieChart(data) {
        const ctx = canva.getContext('2d');
        const gradient = ctx.createLinearGradient(0, 0, 0, 400);
        gradient.addColorStop(0, 'rgba(255, 99, 132, 0.5)');
        gradient.addColorStop(1, 'rgba(54, 162, 235, 0.5)');
        const labels = data.map(item=> item.label);
        const values = data.map(item=> item.value);
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
                plugins: {
                    legend: {
                        position: 'top'
                    },
                    title: {
                        display: true,
                        text: 'Enrollment Status Distribution',
                        font: {
                            size: 16
                        }
                    }
                }
            }
        });
    }
    
    function barGraph(data) {
        const ctx = canva2.getContext('2d');
        const labels = data.map(item=> item.label);
        const values = data.map(item=> item.value);
        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: labels,
                datasets: [{
                    label: 'Grade Level Count',
                    data: values,
                    backgroundColor: '#36A2EB'
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
                        text: 'Enrollee Grade Level Distribution',
                        font: {
                            size: 16
                        }
                    }
                }
            }
        });
    } 
    
    function BiologicalSexPieGraph(data) {
        const ctx = canva3.getContext('2d');
        const labels = data.map(item=> item.label);
        const values = data.map(item=> item.value);
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
                plugins: {
                    legend: {
                        position: 'top'
                    },
                    title: {
                        display: true,
                        text: 'Enrollee Biological Sex Distribution',
                        font: {
                            size: 16
                        }
                    }
                }
            }
        });
    }
    
    // New functions for student charts
    function StudentGradeLevelDistribution(data) {
        const ctx = canva5.getContext('2d');
        const labels = data.map(item=> item.label);
        const values = data.map(item=> item.value);
        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: labels,
                datasets: [{
                    label: 'Student Grade Level Count',
                    data: values,
                    backgroundColor: '#FF6384'
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
                        text: 'Student Grade Level Distribution',
                        font: {
                            size: 16
                        }
                    }
                }
            }
        });
    }
    
    function StudentBiologicalSexPieGraph(data) {
        const ctx = canva6.getContext('2d');
        const labels = data.map(item=> item.label);
        const values = data.map(item=> item.value);
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
                plugins: {
                    legend: {
                        position: 'top'
                    },
                    title: {
                        display: true,
                        text: 'Student Biological Sex Distribution',
                        font: {
                            size: 16
                        }
                    }
                }
            }
        });
    }

    
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

    //enrollee counts by day
    const radio = document.querySelectorAll("input[name='days-filter']");
    const currentSelected = document.querySelector("input[name='days-filter']:checked").value;
    fetch('../../../BackEnd/api/admin/fetchAdminEnrolleesByDay.php', {
        method: 'POST',
        header: {'Content-type' : 'application/json'},
        body: JSON.stringify({day: currentSelected})
    })
    .then(response => response.json())
    .then(data=> {
        if(data.success === false) {
            alert(data.message);
        }
        else {
            EnrolleeByDaybarGraph(data);
        }
    })
    .catch(error => {
        console.error(error);
    })
    radio.forEach(element=> {
        element.addEventListener('change', function() {
           const currentSelected = document.querySelector("input[name=days-filter]:checked").value;
           console.log(currentSelected);
           fetch('../../../BackEnd/api/admin/fetchAdminEnrolleesByDay.php', {
                method: 'POST',
                header: {'Content-type' : 'application/json'},
                body: JSON.stringify({day: currentSelected})
            })
            .then(response => response.json())
            .then(data=> {
                if(data.success === false) {
                    alert(data.message);
                }
                else {
                    EnrolleeByDaybarGraph(data);
                }
            })
            .catch(error => {
                console.error(error);
            })
            });
    });

    const enrolleeByDayCanva = document.getElementById('enrollee-by-day');
    let Enrollmentchart = null;
    function EnrolleeByDaybarGraph(data) {
        const ctx = enrolleeByDayCanva.getContext('2d');
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
                    backgroundColor: '#000d23'
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
});
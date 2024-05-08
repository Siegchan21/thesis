<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Generate</title>
    <link href="https://cdn.lineicons.com/4.0/lineicons.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-KK94CHFLLe+nY2dmCWGMq91rCGa5gtU4mk92HdvYe+M/SXH301p5ILy+dN9+nJOZ" crossorigin="anonymous">
    <link rel="stylesheet" href="assets/css/style.css">
</head>

<body>
    <div class="wrapper">
        <aside id="sidebar">
            <div class="d-flex">
                <button class="toggle-btn" type="button">
                    <i class="lni lni-menu"></i>
                </button>
                <div class="sidebar-logo">
                    <a href="#"></a>
                </div>
            </div>
            <ul class="sidebar-nav">
                <li class="sidebar-item">
                    <a href="main.html" class="sidebar-link">
                        <i class="lni lni-dashboard"></i>
                        <span>Dashboard</span>
                    </a>
                </li>
                <li class="sidebar-item">
                    <a href="Gen Page.html" class="sidebar-link">
                        <i class="lni lni-calendar"></i>
                        <span>Generate</span>
                    </a>
                </li>
                <li class="sidebar-item dropdown">
                    <a class="sidebar-link dropdown-toggle" href="#" role="button" id="navbarDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="lni lni-graph"></i>
                        <span>Schedule</span>
                    </a>
                    <div class="sub-menu">
                    <ul class="sub-menu" aria-labelledby="navbarDropdown">
                        <li class="sidebar-item"><a class="dropdown-item sidebar-link text-light" href="subject.php">Subject</a></li>
                        <li class="sidebar-item"><a class="dropdown-item sidebar-link text-light" href="room.php">Room</a></li>
                        <li class="sidebar-item"><a class="dropdown-item sidebar-link text-light" href="faculty.php">Faculty</a></li>
                        <li class="sidebar-link"><a class="dropdown-item sidebar-link text-light" href="sections.php">Sections</a></li>
                    </ul>
                    </div>
                </li>
                <li class="sidebar-item">
                    <a class="sidebar-link" href="Request.html">
                        <i class="lni lni-cog"></i>
                        <span>Request</span>
                    </a>
                </li>
            </ul>
            <div class="sidebar-footer">
                <a href="assets/php/index.php" class="sidebar-link">
                    <i class="lni lni-exit"></i>
                    <span>Logout</span>
                </a>
            </div>
        </aside>
        <div class="main pt-5">
            <div class="container mt-5 d-flex justify-content-around">
                    <table id="table1">
                        <thead class="head">
                            <tr>
                                <th>Grade and Section</th>
                            </tr>
                        </thead>
                        <tbody id="tableBody1"></tbody>
                    </table>
                    <table id="table2">
                        <thead class="head">
                            <tr>
                                <th>Subjects</th>
                            </tr>
                        </thead>
                        <tbody id="tableBody2"></tbody>
                    </table>
                    <table id="table3">
                        <thead class="head">
                            <tr>
                                <th>Rooms</th>
                            </tr>
                        </thead>
                        <tbody id="tableBody3"></tbody>
                    </table>
                    <table id="table4">
                        <thead class="head">
                            <tr>
                                <th>instructors</th>
                            </tr>
                        </thead>
                        <tbody id="tableBody4"></tbody>
                    </table>
                <div class="bg-light p-5 m-5 rounded-6 w-25 h-25 dropdown-container2" name = "container2" style="float: right;">
                    <h3>SCHOOL YEAR:</h3>
                        <select class="dropdown" id="dropdown1">
                        <option value="option1">2024-2025</option>
                        <option value="option2">2025-2026</option>
                        <option value="option3">2026-2027</option>
                    </select>
                    <p></p>
                    <h3>SEMESTER:</h3>
                        <select class="dropdown" id="dropdown2">
                        <option value="option1">1st</option>
                        <option value="option2">2nd</option>
                    </select>
                    <p></p>
                    <h3>COURSE:</h3>
                        <select id="filterDropdown">
                            <option value="none">----------</option>
                            <option value="CCS">BSCS, BSIT, BSIS</option>
                            <option value="CAFA">CAFA</option>
                            <option value="Auto">AUTOMOTIVE</option>
                            <!-- Add more options as needed -->
                        </select>
                    
                    <!-- Container to display filtered data -->
                    <div id="filteredDataContainer"></div>
                    <button id="generateBtn" class="btn btn-primary mb-1 ml-3" style="float:right; margin: 60px; background-color: blue;">Generate</button>
                </div>
            </div>
            </div>
        </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-ENjdO4Dr2bkBIFxQpeoTz1HIcje39Wm4jDKdf19U8gI4ddQ3GYNS7NTKfAdVQSZe"
        crossorigin="anonymous"></script>
    <script src="assets/js/script.js"></script>
    <script>
        // JavaScript to make table cells editable
        document.addEventListener("DOMContentLoaded", function () {
            var cells = document.querySelectorAll("#editableTable td[contenteditable=true]");
            cells.forEach(function (cell) {
                cell.addEventListener("input", function () {
                    // Do something when content is edited
                    console.log("Cell content changed: ", this.textContent);
                });
            });
        });
    </script>

<script>
async function fetchDataAndDisplay() {
    try {
        const selectedValue = document.getElementById('filterDropdown').value;
        let courseID = '';

        // Clear tables if selectedValue is "none"
        if (selectedValue === 'none') {
            clearTables();
            return; // Exit the function
        }

        // Set courseID based on the selected value
        switch (selectedValue) {
            case 'CCS':
                courseID = 5;
                break;
            case 'CAFA':
                courseID = 3;
                break;
            case 'Auto':
                courseID = 4;
                break;
            // Add cases for other options as needed
            default:
                courseID = ''; // Set default value
                break;
        }

        // Fetch sections data
        const responseSections = await fetch(`filterSection.php?courseID=${courseID}`);
        const sections = await responseSections.json();
        const tableBody1 = document.getElementById('tableBody1');
        // Clear existing table rows
        tableBody1.innerHTML = '';
        // Populate table with sections data
        sections.forEach(section => {
            const row = document.createElement('tr');
            row.innerHTML = `<td>${section.sectionName}</td>`;
            tableBody1.appendChild(row);
        });

        // Fetch subjects data
        const responseSubjects = await fetch(`filterSubject.php?courseID=${courseID}`);
        const subjects = await responseSubjects.json();
        const tableBody2 = document.getElementById('tableBody2');
        // Clear existing table rows
        tableBody2.innerHTML = '';
        // Populate table with subjects data
        subjects.forEach(subject => {
            const row = document.createElement('tr');
            row.innerHTML = `<td>${subject.subjectName}</td>`;
            tableBody2.appendChild(row);
        });

        // Fetch rooms data
        const responseRooms = await fetch(`filterRoom.php?courseID=${courseID}`);
        const rooms = await responseRooms.json();
        const tableBody3 = document.getElementById('tableBody3');
        // Clear existing table rows
        tableBody3.innerHTML = '';
        // Populate table with rooms data
        rooms.forEach(room => {
            const row = document.createElement('tr');
            row.innerHTML = `<td>${room.roomName}</td>`;
            tableBody3.appendChild(row);
        });

        // Fetch instructors data
        const responseInstructors = await fetch(`filterInstructor.php?courseID=${courseID}`);
        const instructors = await responseInstructors.json();
        const tableBody4 = document.getElementById('tableBody4');
        // Clear existing table rows
        tableBody4.innerHTML = '';
        // Populate table with instructors data
        instructors.forEach(instructor => {
            const row = document.createElement('tr');
            row.innerHTML = `<td>${instructor.instructorName}</td>`;
            tableBody4.appendChild(row);
        });
    } catch (error) {
        console.error('Error fetching data:', error);
    }
}

// Function to clear all tables
function clearTables() {
    document.getElementById('tableBody1').innerHTML = '';
    document.getElementById('tableBody2').innerHTML = '';
    document.getElementById('tableBody3').innerHTML = '';
    document.getElementById('tableBody4').innerHTML = '';
}

// Call the function to fetch and display data when the page loads
window.onload = fetchDataAndDisplay;

// Call the function when the dropdown selection changes
document.getElementById('filterDropdown').addEventListener('change', fetchDataAndDisplay);

</script>
</body>

</html>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Subjects</title>
    <link href="https://cdn.lineicons.com/4.0/lineicons.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-KK94CHFLLe+nY2dmCWGMq91rCGa5gtU4mk92HdvYe+M/SXH301p5ILy+dN9+nJOZ" crossorigin="anonymous">
    <link rel="stylesheet" href="../css/styleSub.css">
</head>

<body>
    <!-- Line for the Sidebar and its contents -->
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
                    <a href="/ThesisWebDev/main.html" class="sidebar-link">
                        <i class="lni lni-dashboard"></i>
                        <span>Dashboard</span>
                    </a>
                </li>
                <li class="sidebar-item">
                    <a href="genPage.php" class="sidebar-link">
                        <i class="lni lni-calendar"></i>
                        <span>Generate</span>
                    </a>
                </li>
                <li class="sidebar-item dropdown">
                    <a href="#" class="sidebar-link collapsed has-dropdown" data-bs-toggle="collapse" data-bs-target="#auth" aria-expanded="false" aria-controls="auth">
                        <i class="lni lni-graph"></i>
                        <span>Schedule</span>
                    </a>
                    <ul id="auth" class="sidebar-dropdown list-unstyled collapse" data-bs-parent="#sidebar">
                        <li class="sidebar-item">
                            <a href="subject.php" class="sidebar-link">Subject</a>
                        </li>
                        <li class="sidebar-item">
                            <a href="room.php" class="sidebar-link">Room</a>
                        </li>
                        <li class="sidebar-item">
                            <a href="faculty.php" class="sidebar-link">Faculty</a>
                        </li>
                        <li class="sidebar-item">
                            <a href="sections.php" class="sidebar-link">Sections</a>
                        </li>
                    </ul>
                </li>
                <li class="sidebar-item">
                    <a class="sidebar-link" href="/ThesisWebDev/Request.html">
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
        </div>
        </div>

        <div class="background-image"></div> <!-- Background image container -->
            <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"
            integrity="sha384-ENjdO4Dr2bkBIFxQpeoTz1HIcje39Wm4jDKdf19U8gI4ddQ3GYNS7NTKfAdVQSZe"
            crossorigin="anonymous">
            </script>

<!-- This line check if the table is empty before saving it into the database -->
<script>
    function validateForm() {
        const inputFields = document.querySelectorAll('input[type="text"]');
        let isValid = true;

        inputFields.forEach(function(field) {
            if (!field.value.trim()) {
                isValid = false;
                return;
            }
        });

        if (!isValid) {
            alert("Please fill in all fields before submitting.");
        }

        return isValid;
    }
</script>

<!-- This is the Line for the table to enter/save the data -->
<form id="facultyForm" action="facultySave.php" method="post">
    <div class="container">
        <div class="row justify-content-around mt-5">
            <div class="col">
                <table id="courseTable">
                    <thead class="head">
                        <tr>
                            <th>Instructor</th>
                            <th>Role/Position</th>
                            <th>Course</th>
                            <th>Actions</th> <!-- New column for action buttons -->
                        </tr>
                    </thead>
                    <tbody id="tableBody">
                        <!-- Table rows will be dynamically added here -->
                        <tr>
                            <td><input type="text" name="instructorName[]" placeholder="Enter instructor name"></td>
                            <td><input type="text" name="instructorRole[]" placeholder="Enter instructor role"></td>
                            <td>
                            <select name="courseName[]">
                                <!-- PHP code to populate the dropdown -->
                                <?php include("retrieveCourse.php"); ?>
                            </select>
                            </td>
                            <td><button class="removeRowButton" onclick="deleteRow(this)"><i class="lni lni-trash-can"></i></button></td> <!-- Remove row button -->
                        </tr>
                    </tbody>
                </table>
                <button type="button" id="addRowButton">Add Row</button> <!-- Specify type="button" to prevent form submission -->
            </div>
            <div class="col-md-auto">
                <div class="flex flex-column align-items-center ">
                    <button class="mb-3" type="button" onclick="submitForm()" id="saveButton">Save</button>
                    <button id="backButton" onclick="navigateToPage()">Back</button> 
                </div>
            </div>
        </div>
    </div>
</form>

<script>
    function submitForm() {
        if (validateForm()) {
            document.getElementById("facultyForm").submit();
        }
    }
</script>

<script>
    function deleteRow(button) {
        const row = button.closest('tr');
        row.remove();
    }
</script>

<!-- This line is for adding more rows to the input table -->        
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"
    integrity="sha384-ENjdO4Dr2bkBIFxQpeoTz1HIcje39Wm4jDKdf19U8gI4ddQ3GYNS7NTKfAdVQSZe"
    crossorigin="anonymous"></script>
<script src="assets/js/script.js"></script>
<script>
    function navigateToPage() {
        window.location.href = "main.html"; // Replace "your-page-url.html" with the actual URL
    }

    document.addEventListener("DOMContentLoaded", function() {
        const maxLength = 20;
        const editableFields = document.querySelectorAll("[contenteditable=true]");
        const addRowButton = document.getElementById("addRowButton");
        const tableBody = document.getElementById("tableBody");
    
            editableFields.forEach(function(field) {
                field.addEventListener("input", function() {
                    if (this.textContent.length > maxLength) {
                        this.textContent = this.textContent.slice(0, maxLength);
                    }
                });
            });
    
            addRowButton.addEventListener("click", function() {
                const newRow = document.createElement("tr");
                newRow.innerHTML = `
                    <td><input type="text" name="instructorName[]" placeholder="Enter instructor name"></td>
                    <td><input type="text" name="instructorRole[]" placeholder="Enter instructor role"></td>
                    <td><select name="courseName[]">
                                <?php include("retrieveCourse.php"); ?>
                        </select>
                    </td>
                    <td><button class="removeRowButton" onclick="deleteRow(this)"><i class="lni lni-trash-can"></i></button></td> <!-- Remove row button -->
                `;
                tableBody.appendChild(newRow);
            });
    
            tableBody.addEventListener("click", function(event) {
                if (event.target.classList.contains("removeRowButton")) {
                    const row = event.target.closest("tr");
                    tableBody.removeChild(row);
                }
            });
        });
    </script>



<div class="container2">
        <div class="row justify-content-around mt-5">
            <div class="col">
                <table id="courseTable2">
                    <thead class="head">
                        <tr>
                            <th>Instructor</th>
                            <th>Role/Position</th>
                            <th>Course</th>
                            <th>Actions</th> <!-- New column for action buttons -->
                        </tr>
                    </thead>
                    <tbody id="tableBody2"></tbody>
                </table>
            </div>
        </div>
    </div>



<script>
    // Function to fetch and display data
    async function fetchDataAndDisplay() {
        try {
            const response = await fetch('fetchFaculty.php');
            const data = await response.json();
            const tableBody = document.getElementById('tableBody2');

            // Clear existing table rows
            tableBody.innerHTML = '';

            // Iterate through data and create table rows
            data.forEach(item => {
                const row = document.createElement('tr');
                row.innerHTML = `
                    <td>${item.name}</td>
                    <td>${item.instructorRole}</td>
                    <td>${item.course}</td>
                    <td>
                    <button class="deleteButton" data-id="${item.subjectID}">Delete</button>
                    </td>
                `;
                tableBody.appendChild(row);
            });
        } catch (error) {
            console.error('Error fetching data:', error);
        }
    }

    // Call the function to fetch and display data when the page loads
    window.onload = fetchDataAndDisplay;
</script>

</body>
</html> 
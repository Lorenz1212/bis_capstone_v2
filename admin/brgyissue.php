<?php
include 'user_navbar.php';
?>
    <div class="container">
        <div class="dashboard-container">
            <!-- Your dashboard content -->
        </div>

        <div class="nav-container">
            <div class="header">
                <button class="add-new" onclick="openAddNewPopup()">+ Add New</button>
                <input type="text" class="search-bar" id="myInput" onkeyup="searchTable()" placeholder="Search...">
            </div>
            <div class="table-container">
                <table id="myTable">
                    <?php
                    // SQL query to retrieve blotter data
                    $sql = "SELECT * FROM blotter";
                    $result = $conn->query($sql);

                    // Display table if there are results
                    if ($result->num_rows > 0) {
                        echo "<tr>
                            <th>File No.</th>
                            <th>Barangay</th>
                            <th>Purok</th>
                            <th>Incident</th>
                            <th>Place of Incident</th>
                            <th>Date</th>
                            <th>Time</th>
                            <th>Complainant</th>
                            <th>Witness</th>
                            <th>Narrative</th>
                            <th>Action</th>
                        </tr>";
                        while ($row = $result->fetch_assoc()) {
                            echo "<tr>";
                            echo "<td>" . $row["File_No"] . "</td>";
                            echo "<td>" . $row["Barangay"] . "</td>";
                            echo "<td>" . $row["Purok"] . "</td>";
                            echo "<td>" . $row["Incident"] . "</td>";
                            echo "<td>" . $row["Place_of_Incident"] . "</td>";
                            echo "<td>" . $row["Date"] . "</td>";
                            echo "<td>" . $row["Time"] . "</td>";
                            echo "<td>" . $row["Complainant"] . "</td>";
                            echo "<td>" . $row["Witness"] . "</td>";
                            echo "<td>" . $row["Narrative"] . "</td>";

                            // Add "View" and "Edit" buttons to each row
                            echo "<td class='action-icons'>
                                <a href='javascript:void(0)' onclick='openViewPopup(\"" . $row['File_No'] . "\", \"" . $row['Barangay'] . "\", \"" . $row['Purok'] . "\", \"" . $row['Incident'] . "\", \"" . $row['Place_of_Incident'] . "\", \"" . $row['Date'] . "\", \"" . $row['Time'] . "\", \"" . $row['Complainant'] . "\", \"" . $row['Witness'] . "\", \"" . $row['Narrative'] . "\")'><i class='fas fa-eye'></i></a>
                                <a href='javascript:void(0)' onclick='openEditPopup(\"" . $row['File_No'] . "\", \"" . $row['Barangay'] . "\", \"" . $row['Purok'] . "\", \"" . $row['Incident'] . "\", \"" . $row['Place_of_Incident'] . "\", \"" . $row['Date'] . "\", \"" . $row['Time'] . "\", \"" . $row['Complainant'] . "\", \"" . $row['Witness'] . "\", \"" . $row['Narrative'] . "\")'><i class='fas fa-edit'></i></a>
                              </td>";
                            echo "</tr>";
                        }
                    } else {
                        echo "<tr><td colspan='12'>No records found</td></tr>";
                    }
                    $conn->close();
                    ?>
                </table>
            </div>

            <!-- Popup modal for adding new entry -->
            <div id="addNewModal" class="popup">
                <div class="popupform-container">
                    <div class="popup-content">
                        <div class="popup-contitle">
                        <span class="close-button" onclick="closeAddNewPopup('popupForm')">&times;</span>
                        <form id="addNewForm">
                            <!-- Display field for automatically generated file number -->
                            <label for="fileNo">File No:</label>
                            <input type="text" class="form-control"  id="fileNo" name="fileNo" readonly>

                            <!-- Input fields for other columns in the table -->
                            <input type="text" class="form-control"  id="barangay" name="barangay" placeholder="Barangay">
                            <input type="text" class="form-control"  id="purok" name="purok" placeholder="Purok">
                            <input type="text" class="form-control"  id="incident" name="incident" placeholder="Incident">
                            <input type="text" class="form-control"  id="placeOfIncident" name="placeOfIncident" placeholder="Place of Incident">
                            <label for="date">Date:</label>
                            <input type="date" class="form-control"  id="date" name="date" placeholder="Date" readonly>
                            <input type="time" class="form-control"  id="time" name="time" placeholder="Time">

                            <input type="text" class="form-control"  id="complainant" name="complainant" placeholder="Complainant">
                            <input type="text" class="form-control"  id="witness" name="witness" placeholder="Witness">
                            <textarea id="narrative" class="form-control" name="narrative" rows="3" placeholder="Narrative"></textarea>

                            <!-- Submit button to add the new entry -->
                            <button type="button" class="btn-success" onclick="addNewEntry()">Add Entry</button>
                        </form>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Popup modal for viewing entry -->
            <div id="viewModal" class="modal">
                <div class="modal-container">
                    <div class="modal-content">
                        <span class="close" onclick="closeViewPopup()">&times;</span>
                        <div class="modal-header">
                            <img src="../image/logo.png" alt="brgy marinig logo">
                            <div class="brgy-info">
                                <p>Republic of the Philippines</p>
                                <p>Province of Laguna</p>
                                <p>Municipality of Cabuyao</p>
                                <h3>Barangay Marinig</h3>
                            </div>
                            <img src="../image/cablogo.png" alt="cabuyao logo">
                        </div>
                        <div id="viewEntryDetails" class="letter-style"></div>
                        <button onclick="printModal('viewModal')">Print</button>
                    </div>
                </div>
            </div>

            <!-- Popup modal for editing entry -->
            <div id="editModal" class="popup">
                <div class="popupform-container">
                        <div class="popup-content">
                            <div class="popup-contitle">
                                <span class="close-button" onclick="closeEditPopup()">&times;</span>
                                <form id="editForm">
                                    <!-- Display field for file number -->
                                    <label for="editFileNo">File No:</label>
                                    <input type="text" class="form-control" id="editFileNo" name="editFileNo" readonly>

                                    <!-- Input fields for other columns in the table -->
                                    <input type="text" class="form-control" id="editBarangay" name="editBarangay" placeholder="Barangay">
                                    <input type="text" class="form-control" id="editPurok" name="editPurok" placeholder="Purok">
                                    <input type="text" class="form-control" id="editIncident" name="editIncident" placeholder="Incident">
                                    <input type="text" class="form-control" id="editPlaceOfIncident" name="editPlaceOfIncident" placeholder="Place of Incident">
                                    <label for="editDate">Date:</label>
                                    <input type="date" class="form-control" id="editDate" name="editDate" placeholder="Date">
                                    <input type="time" class="form-control" id="editTime" name="editTime" placeholder="Time">

                                    <input type="text" class="form-control" id="editComplainant" name="editComplainant" placeholder="Complainant">
                                    <input type="text" class="form-control" id="editWitness" name="editWitness" placeholder="Witness">
                                    <textarea id="editNarrative"  class="form-control"  name="editNarrative" rows="3" placeholder="Narrative"></textarea>

                                    <!-- Submit button to update the entry -->
                                    <button type="button" class="btn-success" onclick="updateEntry()">Update Entry</button>
                                </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <script>
            // Set the value of the date input field to today's date
            document.getElementById('date').value = new Date().toISOString().split('T')[0];

            // Function to perform live search for residents
            function searchTable() {
                // Declare variables
                var input, filter, table, tr, td, i, txtValue, found;
                input = document.getElementById("myInput");
                filter = input.value.toUpperCase();
                table = document.getElementById("myTable");
                tr = table.getElementsByTagName("tr");
                found = false; // Variable to track if any matching rows were found

                // Remove any existing message rows
                var existingMessageRows = table.getElementsByClassName("no-records-found");
                for (var k = 0; k < existingMessageRows.length; k++) {
                    table.removeChild(existingMessageRows[k]);
                }

                // Loop through all table rows, and hide those that don't match the search query
                for (i = 0; i < tr.length; i++) {
                    td = tr[i].getElementsByTagName("td");
                    for (var j = 0; j < td.length; j++) {
                        if (td[j]) {
                            txtValue = td[j].textContent || td[j].innerText;
                            if (txtValue.toUpperCase().indexOf(filter) > -1) {
                                tr[i].style.display = "";
                                found = true; // Set found to true if a match is found
                                break;
                            } else {
                                tr[i].style.display = "none";
                            }
                        }
                    }
                }

                // Display message if no records found
                if (!found) {
                    var noRecordsRow = document.createElement("tr");
                    noRecordsRow.className = "no-records-found"; // Add a class to identify message rows
                    var noRecordsCell = document.createElement("td");
                    noRecordsCell.setAttribute("colspan", "11");
                    noRecordsCell.textContent = "No records found";
                    noRecordsRow.appendChild(noRecordsCell);
                    table.appendChild(noRecordsRow);
                }
            }

            // Function to open the add new entry modal
            function openAddNewPopup() {
                var modal = document.getElementById("addNewModal");
                modal.style.display = "block";
                // Function to generate a random 6-digit number
                function generateRandomNumber() {
                    // Generate a random number between 100000 and 999999
                    return Math.floor(Math.random() * 900000) + 100000;
                }

                // Automatically generate the file number
                var fileNo = "CASE-" + generateRandomNumber();
                document.getElementById("fileNo").value = fileNo;
            }

            // Function to close the add new entry modal
            function closeAddNewPopup() {
                var modal = document.getElementById("addNewModal");
                modal.style.display = "none";
            }

            // Function to add a new entry to the table
            function addNewEntry() {
                // Retrieve values from the form
                var fileNo = document.getElementById("fileNo").value;
                var barangay = document.getElementById("barangay").value;
                var purok = document.getElementById("purok").value;
                var incident = document.getElementById("incident").value;
                var placeOfIncident = document.getElementById("placeOfIncident").value;
                var date = document.getElementById("date").value;
                var time = document.getElementById("time").value;
                var complainant = document.getElementById("complainant").value;
                var witness = document.getElementById("witness").value;
                var narrative = document.getElementById("narrative").value;

                // Construct a data object to send to the server
                var formData = new FormData();
                formData.append('fileNo', fileNo);
                formData.append('barangay', barangay);
                formData.append('purok', purok);
                formData.append('incident', incident);
                formData.append('placeOfIncident', placeOfIncident);
                formData.append('date', date);
                formData.append('time', time);
                formData.append('complainant', complainant);
                formData.append('witness', witness);
                formData.append('narrative', narrative);

                // Make an AJAX request to insert the data into the database
                fetch('insert_into_database.php', {
                        method: 'POST',
                        body: formData
                    })
                    .then(response => response.json())
                    .then(data => {
                        // Handle the response from the server
                        console.log(data);

                        if (data.success) {
                            // Reload the page to reflect the changes
                            location.reload();
                        } else {
                            // If insertion failed, display an error message
                            alert('Failed to insert data into the database');
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        // Display an error message if the request fails
                        alert('Failed to connect to the server');
                    });
            }

            // Function to open the view popup and populate it with data
            function openViewPopup(fileNo, barangay, purok, incident, placeOfIncident, date, time, complainant, witness, narrative) {
                // Debugging: Log the values of the variables passed to this function
                console.log('File No:', fileNo);
                console.log('Barangay:', barangay);
                console.log('Purok:', purok);
                console.log('Incident:', incident);
                console.log('Place of Incident:', placeOfIncident);
                console.log('Date:', date);
                console.log('Time:', time);
                console.log('Complainant:', complainant);
                console.log('Witness:', witness);
                console.log('Narrative:', narrative);

                var modal = document.getElementById("viewModal");
                modal.style.display = "block";

                // Populate the view popup with data
                var viewEntryDetails = document.getElementById("viewEntryDetails");
                viewEntryDetails.innerHTML = `
                    <p><strong>File No:</strong> ${fileNo}</p>
                    <p><strong>Barangay:</strong> ${barangay}</p>
                    <p><strong>Purok:</strong> ${purok}</p>
                    <p><strong>Incident:</strong> ${incident}</p>
                    <p><strong>Place of Incident:</strong> ${placeOfIncident}</p>
                    <p><strong>Date:</strong> ${date}</p>
                    <p><strong>Time:</strong> ${time}</p>
                    <p><strong>Complainant:</strong> ${complainant}</p>
                    <p><strong>Witness:</strong> ${witness}</p>
                    <p><strong>Narrative:</strong> ${narrative}</p>
                `;
            }

            // Function to close the view popup
            function closeViewPopup() {
                var modal = document.getElementById("viewModal");
                modal.style.display = "none";
            }

            // Function to open the edit popup and populate it with data
            function openEditPopup(fileNo, barangay, purok, incident, placeOfIncident, date, time, complainant, witness, narrative) {
                var modal = document.getElementById("editModal");
                modal.style.display = "block";

                // Populate the edit popup with data
                document.getElementById("editFileNo").value = fileNo;
                document.getElementById("editBarangay").value = barangay;
                document.getElementById("editPurok").value = purok;
                document.getElementById("editIncident").value = incident;
                document.getElementById("editPlaceOfIncident").value = placeOfIncident;
                document.getElementById("editDate").value = date;
                document.getElementById("editTime").value = time;
                document.getElementById("editComplainant").value = complainant;
                document.getElementById("editWitness").value = witness;
                document.getElementById("editNarrative").value = narrative;
            }

            // Function to close the edit popup
            function closeEditPopup() {
                var modal = document.getElementById("editModal");
                modal.style.display = "none";
            }

            // Function to update the entry
            function updateEntry() {
                // Retrieve values from the form
                var fileNo = document.getElementById("editFileNo").value;
                var barangay = document.getElementById("editBarangay").value;
                var purok = document.getElementById("editPurok").value;
                var incident = document.getElementById("editIncident").value;
                var placeOfIncident = document.getElementById("editPlaceOfIncident").value;
                var date = document.getElementById("editDate").value;
                var time = document.getElementById("editTime").value;
                var complainant = document.getElementById("editComplainant").value;
                var witness = document.getElementById("editWitness").value;
                var narrative = document.getElementById("editNarrative").value;

                // Construct a data object to send to the server
                var formData = new FormData();
                formData.append('fileNo', fileNo);
                formData.append('barangay', barangay);
                formData.append('purok', purok);
                formData.append('incident', incident);
                formData.append('placeOfIncident', placeOfIncident);
                formData.append('date', date);
                formData.append('time', time);
                formData.append('complainant', complainant);
                formData.append('witness', witness);
                formData.append('narrative', narrative);

                // Make an AJAX request to update the data in the database
                fetch('update_database.php', {
                        method: 'POST',
                        body: formData
                    })
                    .then(response => response.json())
                    .then(data => {
                        // Handle the response from the server
                        console.log(data);

                        if (data.success) {
                            // Reload the page to reflect the changes
                            location.reload();
                        } else {
                            // If update failed, display an error message
                            alert('Failed to update data in the database');
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);

                        // Display an error message if the request fails
                        alert('Failed to connect to the server');
                    });
            }

            // Function to print the view modal content
            function printModal(modalId) {
                window.print();
            }
            ini_set('display_errors', 1);
            error_reporting(E_ALL);
        </script>
    </div>
</body>

</html>
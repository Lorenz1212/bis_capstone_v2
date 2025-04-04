<?php
include 'user_navbar.php';
?>
<div class="container mt-4">
    <h2 class="mb-3">Blotter</h2>
        <div class="nav-container">
            <div class="search">
                <div class="input-group mb-3">
                    <input type="text" id="myInput" class="form-control" placeholder="Search..." oninput="searchTable()">
                    <button class="btn btn-outline-secondary"><i class="fa fa-search"></i></button>
                </div>
            </div>
            <div class="d-flex justify-content-end mb-3">
                <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addNewModal">+ Add New</button>
            </div>
            <div class="table-responsive" style="max-height: 600px; overflow-y: auto;">
                 <table class="table table-bordered table-hover" id="myTable">
                    <?php
                    // SQL query to retrieve blotter data
                    $sql = "SELECT * FROM blotter";
                    $result = $conn->query($sql);

                    // Display table if there are results
                    if ($result->num_rows > 0) {
                        echo "<tr class='table-dark'>
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
                                <a href='#' onclick='openViewModal(".htmlspecialchars(json_encode($row), ENT_QUOTES, 'UTF-8').")'><i class='fas fa-eye'></i></a>
                                <a href='javascript:void(0)' onclick='openEditModal(\"" . $row['File_No'] . "\", \"" . $row['Barangay'] . "\", \"" . $row['Purok'] . "\", \"" . $row['Incident'] . "\", \"" . $row['Place_of_Incident'] . "\", \"" . $row['Date'] . "\", \"" . $row['Time'] . "\", \"" . $row['Complainant'] . "\", \"" . $row['Witness'] . "\", \"" . $row['Narrative'] . "\")'><i class='fas fa-edit'></i></a>
                              </td>";
                            echo "</tr>";
                        }
                    } else {
                        echo "<tr><td colspan='12'>No records found</td></tr>";
                    }
          
                    ?>
                </table>
            </div>

        <div class="modal fade" id="addNewModal" tabindex="-1" aria-labelledby="addNewModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="addNewModalLabel">Add New Entry</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form id="addNewForm">
                            <div class="row mb-3">
                                <div class="col-md-6">
                                <?php
                                // Get current year
                                $currentYear = date('Y');
                                
                                // Get the count of records for this year
                                $stmt = $conn->prepare("SELECT COUNT(*) as count FROM blotter WHERE YEAR(Date) = ?");
                                $stmt->bind_param("s", $currentYear);
                                $stmt->execute();
                                $result = $stmt->get_result();
                                $row = $result->fetch_assoc();
                                $recordCount = $row['count'] + 1; // Add 1 for the new record
                                
                                // Format file number (e.g., BLT-2023-001)
                                $fileNo = "CASE-" . $currentYear . "-" . str_pad($recordCount, 3, '0', STR_PAD_LEFT);
                                
                                // Set current date
                                $currentDate = date('Y-m-d');
                                ?>
                                    <label for="fileNo" class="form-label">File No:</label>
                                    <input type="text" class="form-control" id="fileNo" name="fileNo" value="<?php echo $fileNo; ?>" readonly>
                                </div>
                                <div class="col-md-6">
                                    <label for="date" class="form-label">Date:</label>
                                    <input type="date" class="form-control" id="date" name="date" readonly>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label for="barangay" class="form-label">Barangay:</label>
                                    <input type="text" class="form-control" id="barangay" name="barangay" placeholder="Barangay">
                                </div>
                                <div class="col-md-6">
                                    <label for="purok" class="form-label">Purok:</label>
                                    <input type="text" class="form-control" id="purok" name="purok" placeholder="Purok">
                                </div>
                            </div>

                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label for="incident" class="form-label">Incident:</label>
                                    <input type="text" class="form-control" id="incident" name="incident" placeholder="Incident">
                                </div>
                                <div class="col-md-6">
                                    <label for="placeOfIncident" class="form-label">Place of Incident:</label>
                                    <input type="text" class="form-control" id="placeOfIncident" name="placeOfIncident" placeholder="Place of Incident">
                                </div>
                            </div>

                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label for="time" class="form-label">Time:</label>
                                    <input type="time" class="form-control" id="time" name="time">
                                </div>
                                <div class="col-md-6">
                                    <label for="complainant" class="form-label">Complainant:</label>
                                    <input type="text" class="form-control" id="complainant" name="complainant" placeholder="Complainant">
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="witness" class="form-label">Witness:</label>
                                <input type="text" class="form-control" id="witness" name="witness" placeholder="Witness">
                            </div>

                            <div class="mb-3">
                                <label for="narrative" class="form-label">Narrative:</label>
                                <textarea class="form-control" id="narrative" name="narrative" rows="4" placeholder="Narrative"></textarea>
                            </div>

                            <div class="mb-3">
                                <label for="file_id">Upload Evidence</label>
                                <input type="file" class="form-control" name="file"  accept="image/*">
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="button" class="btn btn-primary" onclick="addNewEntry()">Add Entry</button>
                    </div>
                </div>
            </div>
        </div>

       <!-- View Blotter Modal -->
        <div class="modal fade" id="viewModal" tabindex="-1" aria-labelledby="viewModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content border-0" id="printThis">
                    <!-- Header with Official Barangay Seal -->
                    <div class="modal-header bg-primary text-white">
                        <div class="text-center w-100">
                            <img src="../image/logo.png" alt="Barangay Seal" width="80" class="mb-2">
                            <h4 class="modal-title fw-bold mb-0" id="viewModalLabel">BARANGAY BLOTTER REPORT</h4>
                            <small class="d-block">Republic of the Philippines • Province of Laguna • Barangay Mamatid, Cabuyao City</small>
                        </div>
                    </div>
                    
                    <!-- Body with Blotter Details -->
                    <div class="modal-body">
                        <div class="blotter-details">
                            <!-- Case Reference -->
                            <div class="blotter-section border-bottom pb-2 mb-3">
                                <h6 class="fw-bold text-primary">CASE REFERENCE NO: <span id="viewFileNo" class="text-dark"></span></h6>
                                <p class="mb-1"><strong>Date Recorded:</strong> <span id="viewDate"></span></p>
                                <p><strong>Time Recorded:</strong> <span id="viewTime"></span></p>
                            </div>
                            
                            <!-- Incident Details -->
                            <div class="blotter-section border-bottom pb-3 mb-3">
                                <h6 class="fw-bold text-primary">INCIDENT DETAILS</h6>
                                <p class="mb-1"><strong>Type of Incident:</strong> <span id="viewIncident"></span></p>
                                <p class="mb-1"><strong>Location:</strong> <span id="viewPlaceOfIncident"></span></p>
                                <p><strong>Barangay/Purok:</strong> <span id="viewBarangay"></span>, Purok <span id="viewPurok"></span></p>
                            </div>
                            
                            <!-- Involved Parties -->
                            <div class="blotter-section border-bottom pb-3 mb-3">
                                <h6 class="fw-bold text-primary">INVOLVED PARTIES</h6>
                                <p class="mb-1"><strong>Complainant:</strong> <span id="viewComplainant"></span></p>
                                <p><strong>Witness(es):</strong> <span id="viewWitness"></span></p>
                            </div>
                            
                            <!-- Narrative Report -->
                            <div class="blotter-section">
                                <h6 class="fw-bold text-primary">NARRATIVE REPORT</h6>
                                <div class="narrative-box p-3 bg-light rounded">
                                    <p id="viewNarrative" class="mb-0"></p>
                                </div>
                            </div>
                            <div class="blotter-section">
                                <h6 class="fw-bold text-primary">EVIDENCE PICTURE</h6>
                                <div class="narrative-box p-3 bg-light rounded">
                                    <a href="" target="_blank" id="evidence_href">
                                        <img src="" id="evidence" class="img-fluid w-30 mx-auto d-block mw-100">
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Footer with Signature Line -->
                    <div class="modal-footer bg-light">
                        <div class="w-100 text-center">
                            <div class="signature-line mt-4 mb-2 mx-auto" style="width: 300px; border-top: 1px solid #000;"></div>
                            <h6 class="fw-bold mb-0">HON. PETER GUEVARRA</h6>
                            <small class="text-muted">Punong Barangay</small>
                        </div>
                        <button type="button" class="btn btn-primary" onclick="printBlotter()">
                            <i class="fas fa-print me-2"></i>Print Blotter
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="editModalLabel">Edit Entry</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form id="editForm" enctype="multipart/form-data">
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label for="editFileNo" class="form-label">File No:</label>
                                    <input type="text" class="form-control" id="editFileNo" name="fileno" readonly>
                                </div>
                                <div class="col-md-6">
                                    <label for="editDate" class="form-label">Date:</label>
                                    <input type="date" class="form-control" id="editDate" name="date">
                                </div>
                            </div>
                            
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label for="editBarangay" class="form-label">Barangay:</label>
                                    <input type="text" class="form-control" id="editBarangay" name="barangay" placeholder="Barangay">
                                </div>
                                <div class="col-md-6">
                                    <label for="editPurok" class="form-label">Purok:</label>
                                    <input type="text" class="form-control" id="editPurok" name="purok" placeholder="Purok">
                                </div>
                            </div>
                            
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label for="editIncident" class="form-label">Incident:</label>
                                    <input type="text" class="form-control" id="editIncident" name="incident" placeholder="Incident">
                                </div>
                                <div class="col-md-6">
                                    <label for="editPlaceOfIncident" class="form-label">Place of Incident:</label>
                                    <input type="text" class="form-control" id="editPlaceOfIncident" name="placeofincident" placeholder="Place of Incident">
                                </div>
                            </div>
                            
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label for="editTime" class="form-label">Time:</label>
                                    <input type="time" class="form-control" id="editTime" name="time">
                                </div>
                                <div class="col-md-6">
                                    <label for="editComplainant" class="form-label">Complainant:</label>
                                    <input type="text" class="form-control" id="editComplainant" name="complainant" placeholder="Complainant">
                                </div>
                            </div>
                            
                            <div class="mb-3">
                                <label for="editWitness" class="form-label">Witness:</label>
                                <input type="text" class="form-control" id="editWitness" name="witness" placeholder="Witness">
                            </div>
                            
                            <div class="mb-3">
                                <label for="editNarrative" class="form-label">Narrative:</label>
                                <textarea class="form-control" id="editNarrative" name="narrative" rows="4" placeholder="Narrative"></textarea>
                            </div>

                            <div class="mb-3">
                                <label for="file_id">Upload Evidence</label>
                                <input type="file" class="form-control" name="file" id="file_id" accept="image/*">
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="button" class="btn btn-primary" onclick="saveChanges()">Update Entry</button>
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
            async function addNewEntry() {
                try {
                    // Create FormData and automatically append all form fields
                    const form = document.getElementById('addNewForm');
                    const formData = new FormData(form);
                    
                    // Add any additional fields not in the form
                    formData.append('fileNo', document.getElementById('fileNo').value);
                    
                    // Send data to server
                    const response = await fetch('save_blotter.php', {
                        method: 'POST',
                        body: formData
                    });
                    
                    const result = await response.json();
                    
                    if (result.success) {
                        // Success - show message and reset form
                        alert('Entry added successfully!');
                        location.reload();
                    } else {
                        throw new Error(result.message || 'Failed to insert data');
                    }
                } catch (error) {
                    console.error('Error:', error);
                    alert(`Error: ${error.message}`);
                }
            }

            // Function to open the view popup and populate it with data
            function openViewModal(data) {
                const modal = new bootstrap.Modal(document.getElementById('viewModal'));

                // Populate data
                document.getElementById('viewFileNo').textContent = data.File_No;
                document.getElementById('viewDate').textContent = data.Date;
                document.getElementById('viewTime').textContent = data.Time;
                document.getElementById('viewIncident').textContent = data.Incident;
                document.getElementById('viewPlaceOfIncident').textContent = data.Place_of_Incident;
                document.getElementById('viewBarangay').textContent = data.Barangay;
                document.getElementById('viewPurok').textContent = data.Purok;
                document.getElementById('viewComplainant').textContent = data.Complainant;
                document.getElementById('viewWitness').textContent = data.Witness;
                document.getElementById('viewNarrative').textContent = data.Narrative;

                
                document.getElementById('evidence').src = '../uploads/evidences/'+data.file;
                document.getElementById('evidence_href').href = '../uploads/evidences/'+data.file;
                
                modal.show();
            }

            function printBlotter() {
                const printContent = document.querySelector('#printThis').cloneNode(true);
                const printWindow = window.open('', '_blank');
                
                printWindow.document.write(`
                    <!DOCTYPE html>
                    <html>
                    <head>
                        <title>Blotter Report Print</title>
                        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
                        <style>
                            body { padding: 20px; }
                            .blotter-section { margin-bottom: 15px; }
                            .narrative-box { min-height: 150px; }
                            @media print {
                                .no-print { display: none !important; }
                                body { font-size: 12pt; }
                                button { display: none !important; }
                                .img-fluid { width: 100px !important; }
                            }
                        </style>
                    </head>
                    <body>
                        ${printContent.innerHTML}
                        <script>
                            window.onload = function() {
                                setTimeout(function() {
                                    window.print();
                                    
                                    // Close window after printing (works in most browsers)
                                    window.onafterprint = function() {
                                        window.close();
                                    };
                                    
                                    // Fallback in case onafterprint doesn't work
                                    setTimeout(function() {
                                        window.close();
                                    }, 1000);
                                }, 200);
                            };
                            
                            // Close if user cancels print
                            window.onbeforeunload = function() {
                                window.close();
                            };
                        <\/script>
                    </body>
                    </html>
                `);
                printWindow.document.close();
            }

            // Function to open the edit popup and populate it with data
            function openEditModal(fileNo, barangay, purok, incident, placeOfIncident, date, time, complainant, witness, narrative) {
                const modal = new bootstrap.Modal(document.getElementById('editModal'));
                
                // Populate the form fields
                document.getElementById('editFileNo').value = fileNo;
                document.getElementById('editBarangay').value = barangay;
                document.getElementById('editPurok').value = purok;
                document.getElementById('editIncident').value = incident;
                document.getElementById('editPlaceOfIncident').value = placeOfIncident;
                document.getElementById('editDate').value = date;
                document.getElementById('editTime').value = time;
                document.getElementById('editComplainant').value = complainant;
                document.getElementById('editWitness').value = witness;
                document.getElementById('editNarrative').value = narrative;

                modal.show();
            }


            // Function to update the entry
            async function saveChanges() {
                const form = document.getElementById('editForm');
                const formData = new FormData(form);

                try {
                    const response = await fetch('update_blotter.php', {
                    method: 'POST',
                    body: formData
                    });
                    
                    const result = await response.json();
                    
                    if (result.success) {
                        alert(result.message);
                    location.reload(); // Refresh to show changes
                    } else {
                    alert(result.message || 'Update failed');
                    }
                } catch (error) {
                    console.error('Error:', error);
                    alert('Failed to connect to server');
                }
                }
        </script>
    </div>
</body>

</html>
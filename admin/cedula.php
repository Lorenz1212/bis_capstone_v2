<?php
include '../connection/connect.php';
include 'user_navbar.php';    
?>
<div class="container">
      
        <div class="search">
            <input type="text" id="searchInput" onkeyup="searchTable()" placeholder="Search for names..">
        </div>
        <div class="table-payment">
            <table id="paymentTable">
                <tr>
                    <th>Reference No</th>
                    <th>Name of Person</th>
                    <th>Date</th>
                    <th>Purpose</th>
                    <th>Action</th> <!-- New column for the action -->
                </tr>
        </div>

        <script>
            function printClearance() {
                window.print();
            }
        </script>
        <?php
        // Include your database connection file
  

        // Fetch data from the payment table
        $paymentQuery = "SELECT *, 
                            CONCAT(
                                firstName, ' ', 
                                IFNULL(CONCAT(UPPER(LEFT(middleName, 1)), '. '), ''), 
                                lastName
                            ) AS Name,
                            civil_status,
                            address
                        FROM payment A
                        LEFT JOIN resident_list B ON A.resident_id = B.accountID ORDER BY A.created_at DESC";
        $paymentResult = mysqli_query($conn, $paymentQuery);

        // Check if data exists
        if (mysqli_num_rows($paymentResult) > 0) {
            // Output data of each row
            while ($row = mysqli_fetch_assoc($paymentResult)) {
                // Output table row with edit and delete buttons
                echo "<tr>";
                echo "<td>" . $row['RefNo'] . "</td>";
                echo "<td>" . $row['Name'] . "</td>";
                echo "<td>" . $row['PaymentDate'] . "</td>";
                echo "<td>" . $row['Purpose'] . "</td>";
                echo "<td class='action-buttons'>";
                echo "<a href='javascript:void(0)' onclick='openLetterModal(".json_encode($row, JSON_HEX_APOS | JSON_HEX_QUOT).", \"indigency\")'><i class='fas fa-edit'></i></a>";
                echo "</td>";
                echo "</tr>";
            }
        } else {
            echo "<tr><td colspan='5'>No payments found</td></tr>";
        }
        ?>

        <!-- No records found message -->
        <div id="noRecordsMessage" style=" color: red; display: none;">No records found..</div>

    </div>

    <!-- Barangay Clearance Modal -->
    <div id="letterModal" class="modal">
        <div class="modal-content">
            <span class="close" onclick="closeLetterModal('letterModal')">&times;</span>
            <div class="modal-header">
                <img src="../image/logo.png" alt="Logo" class="logo">
                <div class="head">
                    <p>Republic of the Philippines</p>
                    <p>Province of Laguna</p>
                    <p>Municipality of Cabuyao</p>
                    <h3>Barangay Marinig</h3>
                </div>
                <img src="../image/cablogo.png" alt="Logo" class="logo">
            </div>
            <div class="underline"></div>
            <div class="contentbrgy">
                <h1>CERTIFICATE OF INDIGENCY</h1>
            </div>

            <div class="contents">
                <h2>To Whom It May Concern:</h2>
                <p style="text-align: justify;">This is to certify that <strong id="name"></strong>, of legal age, <span id="civil_status"></span>, and a resident of <strong id="address"></strong> in <strong>Barangay Marinig, Cabuyao City</strong>, is known to be indigent.</p>
                <br>
                <p style="text-align: justify;">Based on available records and verification conducted, the aforementioned person belongs to a low-income household and has limited financial resources.</p>
                <br>
                <p style="text-align: justify;">This certification is issued upon the request of <strong id="name"></strong> for whatever legal purpose it may serve.</p>

                <p>Issued this <strong id="pDateIssued"></strong> at <strong>Barangay Marinig, Cabuyao City</strong>.</p>

                <span id="pCaptain"></span>

                <div class="punongbrgy">

                    <div class="punong-brgy">
                        <p>Hon. Conrado B. Hain Jr.</p>
                        <p>Punong Barangay</p>
                    </div>

                </div>

            </div>
            <button onclick="printClearance()">Print</button>
        </div>
    </div>


    <script>
        function openLetterModal(data,type) {
            // Set details based on the type of modal
            switch (type) {
                case "indigency":
                    document.getElementById("name").innerHTML = data.Name;
                    document.getElementById("civil_status").innerHTML = data.civil_status;
                    document.getElementById("address").innerHTML = data.address;
                    document.getElementById("pDateIssued").innerHTML = getCurrentDate();
                    document.getElementById("letterModal").style.display = "block";
                    break;
            }
        }

        function getCurrentDate() {
            var currentDate = new Date();
            return currentDate.toLocaleDateString('en-US', {
                weekday: 'long',
                year: 'numeric',
                month: 'long',
                day: 'numeric'
            });
        }

        function closeLetterModal(modalId) {
            document.getElementById(modalId).style.display = "none";
        }

        // Function to close the pop-up letter modal
        function closeLetterModal() {
            // Hide all modals when closing
            document.getElementById("letterModal").style.display = "none";
            document.getElementById("businessModal").style.display = "none";
            document.getElementById("buildingModal").style.display = "none";
        }

        // Function to perform live search
        function searchTable() {
            // Declare variables
            var input, filter, table, tr, td, i, txtValue, found;
            input = document.getElementById("searchInput");
            filter = input.value.toUpperCase();
            table = document.getElementById("paymentTable");
            tr = table.getElementsByTagName("tr");
            found = false; // Variable to track if any matching rows were found

            // Loop through all table rows, and hide those that don't match the search query
            for (i = 0; i < tr.length; i++) {
                td = tr[i].getElementsByTagName("td")[1]; // Index 1 for Name column
                if (td) {
                    txtValue = td.textContent || td.innerText;
                    if (txtValue.toUpperCase().indexOf(filter) > -1) {
                        tr[i].style.display = "";
                        found = true; // Set found to true if a match is found
                    } else {
                        tr[i].style.display = "none";
                    }
                }
            }

            // Display or hide message if no records found
            var noRecordsMessage = document.getElementById("noRecordsMessage");
            if (found) {
                // Hide message if records are found
                noRecordsMessage.style.display = "none";
            } else {
                // Display message if no records found
                noRecordsMessage.style.display = "block";
            }
        }
    </script>

</body>

</html>
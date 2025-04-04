<?php
include '../connection/connect.php';
include 'user_navbar.php';    
?>
 <style>
        .cedula-container {
            width: 100%;
            max-width: 750px;
            background: white;
            padding: 20px;
            border: 2px solid black;
            text-align: center;
            box-shadow: 3px 3px 10px rgba(0, 0, 0, 0.2);
        }
        .header {
            font-size: 20px;
            font-weight: bold;
        }
        .info {
            text-align: left;
            margin-top: 20px;
        }
        .info p {
            margin: 5px 0;
        }
        .signature {
            margin-top: 30px;
            text-align: right;
        }
        .signature p {
            margin: 5px 0;
            font-weight: bold;
        }
        .seal {
            margin-top: 20px;
            font-size: 12px;
            font-style: italic;
        }
        .signature p:first-child {
            display: inline-block;
            border-bottom: 2px solid black; /* Adjust thickness & color */
            padding-bottom: 2px; /* Adjust spacing */
        }
        @media print {
            .cedula-container, .contentbrgy, .contents {
                width: 100%; /* Fit to page */
                max-width: 730px; /* Reduce width */
                padding: 10px; /* Reduce padding */
                font-size: 14px; /* Adjust text size */
            }

            .header {
                font-size: 18px; /* Reduce title size */
            }

            .signature {
                margin-top: 20px; /* Reduce spacing */
                text-align: center; /* Center signature */
            }

            .signature p {
                font-size: 14px; /* Smaller font */
            }

            .seal {
                font-size: 10px; /* Adjust seal text */
            }

            /* Remove box-shadow and border if necessary */
            .cedula-container {
                box-shadow: none;
                border: 1px solid black;
            }

            /* Ensure proper page breaks */
            .contents {
                page-break-before: auto;
                page-break-inside: avoid;
            }
        }

    </style>
<div class="container">
      
        <div class="search">
            <input type="text" id="searchInput" onkeyup="searchTable()" placeholder="Search for names..">
        </div>
        <div class="table-payment">
            <table id="paymentTable">
                <tr>
                    <th>Reference No</th>
                    <th>Name of Person</th>
                    <th>Type</th>
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
                            email,
                            address
                        FROM payment A
                        LEFT JOIN resident_list B ON A.resident_id = B.accountID ORDER BY A.created_at DESC";
        $paymentResult = mysqli_query($conn, $paymentQuery);

        // Check if data exists
        if (mysqli_num_rows($paymentResult) > 0) {
            // Output data of each row
            while ($row = mysqli_fetch_assoc($paymentResult)) {
                // Output table row with edit and delete buttons
                $row['total_amount'] = number_format($row['Amount'],2);
                echo "<tr>";
                echo "<td>" . $row['RefNo'] . "</td>";
                echo "<td>" . $row['Name'] . "</td>";
                echo "<td>" . $row['Type'] . "</td>";
                echo "<td>" . $row['PaymentDate'] . "</td>";
                echo "<td>" . $row['Purpose'] . "</td>";
                echo "<td class='action-buttons'>";
                echo "<a href='javascript:void(0)' onclick='openLetterModal(".json_encode($row, JSON_HEX_APOS | JSON_HEX_QUOT).")'><i class='fas fa-edit'></i></a>";
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

    <div id="clearanceModal" class="modal">
    <div class="modal-content">
        <span class="close" onclick="closeLetterModal()">&times;</span>
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
        <div id="clearanceText"></div>
        <button onclick="printClearance()">Print</button>
    </div>
</div>

    <script>
        function barangayClearance(data){
            return `<div class="contentbrgy">
                        <h1 id="clearanceTitle">BARANGAY CLEARANCE</h1>
                    </div>

                    <div class="contents" id="clearanceText">
                        <h2>To Whom It May Concern:</h2>
                        <p style="text-align: justify;">This is to certify that <strong>${data.Name}</strong>, of legal age, ${data.civil_status}, and a resident of <strong>${data.address}</strong> in <strong>Barangay Marinig, Cabuyao City</strong>, has been verified as a bonafide resident. This certification is issued upon request for the purpose of <strong>${data.Purpose}</strong>.
                        </p>
                        <br>
                        <p>Issued this <strong id="pDateIssued"></strong> at <strong>Barangay Marinig, Cabuyao City</strong>.</p>

                        <div class="punongbrgy">
                            <div class="punong-brgy">
                                <p>Hon. Conrado B. Hain Jr.</p>
                                <p>Punong Barangay</p>
                            </div>
                        </div>
                    </div>`;
        }
        function businessClearance(data){
            return `<div class="contentbrgy">
                        <h1>BUSINESS CLEARANCE</h1>
                    </div>

                    <div class="contents" >
                        <h2>To Whom It May Concern:</h2>
                        <p style="text-align: justify;">
                        This is to certify that <strong>${data.Name}</strong> is cleared to operate a business within this jurisdiction, having met all the necessary requirements. This certification is issued upon request for the purpose of <strong>${data.Purpose}</strong>.
                        </p>
                        <br>
                        <p>Issued this <strong id="pDateIssued"></strong> at <strong>Barangay Marinig, Cabuyao City</strong>.</p>

                        <div class="punongbrgy">
                            <div class="punong-brgy">
                                <p>Hon. Conrado B. Hain Jr.</p>
                                <p>Punong Barangay</p>
                            </div>
                        </div>
                    </div>`;
        }
        function buildingClearance(data){
            return `<div class="contentbrgy">
                        <h1>BUILDING CLEARANCE</h1>
                    </div>

                    <div class="contents">
                        <h2>To Whom It May Concern:</h2>
                        <p style="text-align: justify;">
                        This is to certify that <strong>${data.Name}</strong> is authorized to construct a building in this barangay, having obtained all necessary permits and approvals. This certification is issued upon request for the purpose of <strong>${data.Purpose}</strong>.
                        </p>
                        <br>
                        <p>Issued this <strong id="pDateIssued"></strong> at <strong>Barangay Marinig, Cabuyao City</strong>.</p>

                        <div class="punongbrgy">
                            <div class="punong-brgy">
                                <p>Hon. Conrado B. Hain Jr.</p>
                                <p>Punong Barangay</p>
                            </div>
                        </div>
                    </div>`;
        }
        function certificateIndigency(data){
            return `<div class="contentbrgy">
                        <h1>CERTIFICATE OF INDIGENCY</h1>
                    </div>
                    <div class="contents">
                        <h2>To Whom It May Concern:</h2>
                        <p style="text-align: justify;">
                       This is to certify that <strong>${data.Name}</strong>, ${data.age} years old, is a bonofide resident of <strong>${data.address}, BRGY MARINIG, CITY OF CABUYAO, LAGUNA</strong>.He/She is one of those who belong to a low-income family earners and has no permanent source of income.<br> This certification is issued upon request of the aforementioned for the PURPOSES (<strong>${data.Purpose}</strong>).
                        </p>
                        <br>
                        <p>Issued this <strong id="pDateIssued"></strong> at <strong>Barangay Marinig, Cabuyao City</strong>.</p>

                        <div class="punongbrgy">
                            <div class="punong-brgy">
                                <p>Hon. Conrado B. Hain Jr.</p>
                                <p>Punong Barangay</p>
                            </div>
                        </div>
                    </div>`;
        }
        function barangayCertificate(data){
            return `<div class="contentbrgy">
                        <h1>CERTIFICATE OF INDIGENCY</h1>
                    </div>
                    <div class="contents">
                        <h2>To Whom It May Concern:</h2>
                        <p style="text-align: justify;">
                     This is to certify that <strong>${data.Name}</strong>, of legal age, ${data.civil_status}, and a resident of <strong>${data.address}</strong>, is recognized as a lawful resident of Barangay Marinig. This certification is issued upon request for the purpose of <strong>${data.Purpose}</strong>.
                        </p>
                        <br>
                        <p>Issued this <strong id="pDateIssued"></strong> at <strong>Barangay Marinig, Cabuyao City</strong>.</p>

                        <div class="punongbrgy">
                            <div class="punong-brgy">
                                <p>Hon. Conrado B. Hain Jr.</p>
                                <p>Punong Barangay</p>
                            </div>
                        </div>
                    </div>`;
        }
        function Cedula(data) {
            return `<div class="cedula-container">
                        <h2>Community Tax Certificate (Cedula)</h2>
                        <div class="info">
                            <p><strong>CTC No.:</strong> ${data.RefNo || "____________"}</p>
                            <p><strong>Issued to:</strong> ${data.Name}</p>
                            <p><strong>Address:</strong> ${data.address}, Barangay Marinig, Cabuyao City</p>
                            <p><strong>Purpose:</strong> ${data.Purpose}</p>
                            <p><strong>Date Issued:</strong> <span id="pDateIssued">${getCurrentDate()}</span></p>
                            <p><strong>Amount Paid:</strong> Php ${data.total_amount || "___"}</p>
                        </div>
                        <div class="signature">
                            <p>Hon. Conrado B. Hain Jr.</p>
                            <p>Barangay Captain</p>
                        </div>
                        <div class="seal">(This is a system-generated document and does not require a physical signature.)</div>
                    </div>`;
        }
        function openLetterModal(data) {
    const templates = {
        "Brgy clearance": barangayClearance(data),
        "Business Clearance": businessClearance(data),
        "Building Clearance": buildingClearance(data),
        "Certificate of Indigency": certificateIndigency(data),
        "Barangay Certificate": barangayCertificate(data),
        "Cedula": Cedula(data)
    };

    const modal = document.getElementById("clearanceModal");
    const clearanceText = document.getElementById("clearanceText");

    // Ensure the template exists
    if (templates[data.Type]) {
        clearanceText.innerHTML = templates[data.Type];  // Only update the content
    } else {
        clearanceText.innerHTML = "<p>No data available.</p>";
    }

    // Wait until the content is inserted, then set the date
    setTimeout(() => {
        const dateElement = document.getElementById("pDateIssued");
        if (dateElement) {
            dateElement.innerHTML = getCurrentDate();
        }
    }, 100);

    // Show modal
    modal.style.display = "block";
}

        function closeLetterModal() {
            document.getElementById("clearanceModal").style.display = "none";
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
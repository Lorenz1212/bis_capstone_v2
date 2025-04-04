   // Function to open the popup and display resident information
function viewResidentInfo(row) {
    let actionButtons = "";
    if (row.registration_status === 'REQUEST') {
        actionButtons += `
            <div class="d-flex justify-content-center mt-3">
                <button type="button" class="btn btn-success mx-2 approval" data-status="APPROVED" data-id="${row.accountID}">Approve</button>
                <button type="button" class="btn btn-danger mx-2 approval" data-status="DISAPPROVED" data-id="${row.accountID}">Reject</button>
            </div>
        `;
    }

    let filePath = `./../uploads/${row.file}`;

    let residentInfoHtml = `
        <div class="row">
            <div class="col-md-4 text-center">
                <img src="../image/person.png" alt="Profile Image" class="img-fluid rounded-circle mb-3" style="max-width: 150px;">
            </div>
            <div class="col-md-4">
                <p><strong>Account ID:</strong> ${row.accountID}</p>
                <p><strong>National ID:</strong> ${row.national_id}</p>
                <p><strong>Last Name:</strong> ${row.lastName}</p>
                <p><strong>First Name:</strong> ${row.firstName}</p>
                <p><strong>Middle Name:</strong> ${row.middleName}</p>
                <p><strong>Suffix:</strong> ${row.Suffix ?? ''}</p>
            </div>
            <div class="col-md-4">
                <p><strong>Address:</strong> ${row.address}</p>
                <p><strong>House No:</strong> ${row.house_no}</p>
                <p><strong>Birthdate:</strong> ${row.birthdate}</p>
                <p><strong>Age:</strong> ${row.age}</p>
                <p><strong>Gender:</strong> ${row.gender}</p>
                <p><strong>Civil Status:</strong> ${row.civil_status}</p>
            </div>
        </div>

        <hr>

        <div class="row">
            <div class="col-md-6">
                <p><strong>Birthplace:</strong> ${row.birthplace}</p>
                <p><strong>Religion:</strong> ${row.religion}</p>
                <p><strong>Email:</strong> ${row.email}</p>
                <p><strong>Contact Number:</strong> ${row.contact_number}</p>
                <p><strong>Voter Status:</strong> ${row.voter_status}</p>
            </div>
            <div class="col-md-6">
                <p><strong>Educational Attainment:</strong> ${row.education_attainment}</p>
                <p><strong>Occupation:</strong> ${row.occupation}</p>
                <p><strong>Disability:</strong> ${row.disability}</p>
                <p><strong>Vaccinated:</strong> ${row.vaccination_status}</p>
                <p><strong>ID:</strong> <a href="${filePath}" target="_blank">Click to View</a></p>
            </div>
        </div>

        ${actionButtons}
    `;

    document.getElementById("residentInfo").innerHTML = residentInfoHtml;
    
    // Show modal
    let residentModal = new bootstrap.Modal(document.getElementById("residentInfoModal"));
    residentModal.show();

    // Add event listeners for approval buttons
    document.querySelectorAll(".approval").forEach(button => {
        button.addEventListener("click", function (e) {
            e.preventDefault();
            
            if (!confirm("Are you sure you want to proceed?")) return;

            let status = this.getAttribute("data-status");
            let account_id = this.getAttribute("data-id");

            let formData = new FormData();
            formData.append("registration_status", status);
            formData.append("accountID", account_id);

            fetch("../controller/resident_approval.php", {
                method: "POST",
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                alert(data.message);
                if (data.status === "success") {
                    location.reload();
                }
            })
            .catch(error => {
                console.error("Error:", error);
                alert("An error occurred. Please try again.");
            });
        });
    });
}

  // Function to open the view modal and display vaccination details
function viewVaccinationDetails(button) {
    if (!button) return; // Ensure button is provided

    var row = button.closest("tr"); // Get the closest table row
    let modal = new bootstrap.Modal(document.getElementById("viewModal"));
    modal.show();

    // Retrieve data from the row and trim to avoid extra spaces
    var accountID = row.cells[0].textContent.trim();
    var nationalId = row.cells[1].textContent.trim();
    var lastName = row.cells[2].textContent.trim();
    var firstName = row.cells[3].textContent.trim();
    var age = row.cells[4].textContent.trim();
    var gender = row.cells[5].textContent.trim();
    var birthdate = row.cells[6].textContent.trim();
    var vaccinationStatus = row.cells[7].textContent.trim();
    var vaccine = row.cells[8].textContent.trim();
    var vaccinationType = row.cells[9].textContent.trim();
    var vaccinationDate = row.cells[10].textContent.trim();

    // Set the values in the view modal
    document.getElementById('accountIDView').textContent = accountID;
    document.getElementById('nationalIdView').textContent = nationalId;
    document.getElementById('lastNameView').textContent = lastName;
    document.getElementById('firstNameView').textContent = firstName;
    document.getElementById('ageView').textContent = age;
    document.getElementById('genderView').textContent = gender;
    document.getElementById('birthdateView').textContent = birthdate;
    document.getElementById('vaccinationStatusView').textContent = vaccinationStatus;
    document.getElementById('vaccinationDateView').textContent = vaccinationDate;
    document.getElementById('vaccineView').textContent = vaccine;
    document.getElementById('vaccinationTypeView').textContent = vaccinationType;
}

function printVaccinationDetails() {
    let modalContent = document.querySelector("#viewModal .modal-body").innerHTML; // Get modal content only

    let newWindow = window.open("", "_blank"); // Open a new blank tab
    newWindow.document.write(`
        <html>
        <head>
            <title>Print Vaccination Record</title>
            <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
            <style>
                body { font-family: Arial, sans-serif; padding: 20px; }
                .table { width: 100%; border-collapse: collapse; margin-top: 20px; }
                .table th, .table td { border: 1px solid black; padding: 8px; text-align: center; }
                .table th { background-color: #f8f9fa; }
            </style>
        </head>
        <body>
            <div class="text-center">
                <img src="../image/logo.png" alt="Logo" width="100">
                <h4 class="fw-bold">Vaccination Record</h4>
            </div>
            ${modalContent} <!-- Insert modal content -->
            <script>
                window.onload = function() {
                    window.print();
                    window.close();
                };
            </script>
        </body>
        </html>
    `);

    newWindow.document.close(); // Close writing stream
}

// Function to perform live search for residents
function searchResidents(element,searchElement) {
    var input, filter, table, tr, td, i, txtValue;
    input = document.getElementById(searchElement);
    filter = input.value.toUpperCase();
    table = document.getElementById(element);
    tr = table.getElementsByTagName("tr");

    var found = false; // Flag to track if any results are found

    // Start from index 1 to skip the header row
    for (i = 1; i < tr.length; i++) {
        td = tr[i].getElementsByTagName("td");
        var rowFound = false; // Flag to track if a row matches the search criteria
        for (var j = 0; j < td.length; j++) {
            var cell = td[j];
            if (cell) {
                txtValue = cell.textContent || cell.innerText;
                if (txtValue.toUpperCase().indexOf(filter) > -1) {
                    rowFound = true;
                    found = true;
                    break; // If a match is found in any cell of the row, break the loop
                }
            }
        }
        if (rowFound) {
            tr[i].style.display = ""; // Show the row if a match is found
        } else {
            tr[i].style.display = "none"; // Hide the row if no match is found
        }
    }

    // Create or remove message row based on search result
    if (!found) {
        // If no results are found, create the message row
        var noResultsRow = document.getElementById("noResultsRow");
        if (!noResultsRow) {
            noResultsRow = table.insertRow(-1); // Insert at the end of the table
            noResultsRow.id = "noResultsRow"; // Set the id for easy identification
            var noResultsCell = noResultsRow.insertCell(0);
            noResultsCell.colSpan = table.rows[0].cells.length; // Set colspan to span all columns
            noResultsCell.textContent = "No results found"; // Set the message text
        }
    } else {
        // If results are found, remove the message row if it exists
        var noResultsRow = document.getElementById("noResultsRow");
        if (noResultsRow) {
            noResultsRow.parentNode.removeChild(noResultsRow); // Remove the message row
        }
    }
}

document.addEventListener('DOMContentLoaded', function() {
    // Add event listener for filter change
    document.getElementById('vaccinationFilter').addEventListener('change', filterTable);
}); 

function filterTable() {
    var filterValue = document.getElementById('vaccinationFilter').value.toUpperCase();
    var searchKeyword = document.getElementById('searchInput').value.toUpperCase();
    var rows = document.querySelectorAll('#vaccinationTable tbody tr');

    rows.forEach(function(row) {
        var statusCell = row.querySelector('td:nth-child(8)').textContent.toUpperCase();
        var nationalIdCell = row.querySelector('td:nth-child(2)').textContent.toUpperCase();
        var lastNameCell = row.querySelector('td:nth-child(3)').textContent.toUpperCase();
        var firstNameCell = row.querySelector('td:nth-child(4)').textContent.toUpperCase();
        
        if ((filterValue === '' || statusCell === filterValue.toUpperCase()) &&
            (searchKeyword === '' || nationalIdCell.includes(searchKeyword) ||
            lastNameCell.includes(searchKeyword) ||
            firstNameCell.includes(searchKeyword))) {
            row.style.display = '';
        } else {
            row.style.display = 'none';
        }
    });
}

function openEditPopupFormEdit(row) {  
    // Show the Bootstrap modal
    let modal = new bootstrap.Modal(document.getElementById("editModal"));
    modal.show();

    // Use a more specific query to ensure the elements are found correctly
    const form = document.querySelector("#edit_resident");
    if (form) {
        console.log(row);
        form.querySelector("#editAccountID").value = row.accountID;
        form.querySelector("#national_id").value = row.national_id;
        form.querySelector("#lastName").value = row.lastName;
        form.querySelector("#firstName").value = row.firstName;
        form.querySelector("#middleName").value = row.middleName;
        form.querySelector("#Suffix").value = row.Suffix;
        form.querySelector("#address").value = row.address;
        form.querySelector("#civil_status").value = row.civil_status;
        form.querySelector("#gender").value = row.gender;
        form.querySelector("#house_no").value = row.house_no;
        form.querySelector("#birthdate").value = row.birthdate;
        form.querySelector("#age").value = row.age;
        form.querySelector("#birthplace").value = row.birthplace;
        form.querySelector("#email").value = row.email;
        form.querySelector("#contact_number").value = row.contact_number;
        form.querySelector("#occupation").value = row.occupation;
        form.querySelector("#vaccination_date").value = row.vaccination_date;
        form.querySelector("#religion").value = row.religion;

        form.querySelector("#education_attainment").value = row.education_attainment;
        form.querySelector("#voter_status").value = row.voter_status;
        form.querySelector("#disability").value = row.disability;
        form.querySelector("#vaccination_status").value = row.vaccination_status;
        form.querySelector("#vaccination_type").value = row.vaccination_type;
        form.querySelector("#vaccine").value = row.vaccine;

        if (row.file !== null && row.file !== "") {
            let filePath = "./../uploads/" + row.file; // Adjust the path as needed
            form.querySelector('#file_id_show').style.display = 'block';
            form.querySelector('#id_link').setAttribute('href', filePath);
        } else {
            form.querySelector('#file_id_show').style.display = 'none';
            form.querySelector('#file_id').setAttribute('required', true);
        }
    } else {
        console.error("Edit modal form not found!");
    }
}

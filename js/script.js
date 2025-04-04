   // Function to open the popup and display resident information
   function viewResidentInfo(row) {

    $action ="";
    if(row.registration_status == 'REQUEST'){
        $action += `<div class="button-group">
                        <button type="button" class="btn-success approval" data-status="APPROVED" data-id="${row.accountID}">Approve</button>
                        <button type="button" class="btn-danger approval" data-status="DISAPPROVED" data-id="${row.accountID}">Reject</button>
                    </div>
                    `;
    }
    let filePath = "./../uploads/" + row.file; // Adjust the path as needed
    var residentInfoHtml = `
    <div class="res-infos">
    <div class="profile-image" style="margin-right:100px">
        <img src="../image/icon.jpg" alt="Profile Image">
    </div>
    <div class="info1">
        <p><strong>Account ID:</strong> ${row.accountID}</p>
        <p><strong>National ID:</strong> ${row.national_id}</p>
        <p><strong>Last Name:</strong> ${row.lastName}</p>
        <p><strong>First Name:</strong> ${row.firstName}</p>
        <p><strong>Middle Name:</strong> ${row.middleName}</p>
        <p><strong>Suffix:</strong> ${row.Suffix??''}</p>
        <p><strong>Address:</strong> ${row.address}</p>
        <p><strong>House No:</strong> ${row.house_no}</p>
        <p><strong>Birthdate:</strong> ${row.birthdate}</p>
        <p><strong>Age:</strong> ${row.age}</p>
        <p><strong>Gender:</strong> ${row.gender}</p>
        <p><strong>Civil Status:</strong> ${row.civil_status}</p>
        </div>
        <div class="info2">
        <p><strong>Birthplace:</strong> ${row.birthplace}</p>
        <p><strong>Religion:</strong> ${row.religion}</p>
        <p><strong>Email:</strong> ${row.email}</p>
        <p><strong>Contact Number:</strong> ${row.contact_number}</p>
        <p><strong>Voter Status:</strong> ${row.voter_status}</p>
        <p><strong>Educational Attainment:</strong> ${row.education_attainment}</p>
        <p><strong>Occupation:</strong> ${row.occupation}</p>
        <p><strong>Disability:</strong> ${row.disability}</p>
        <p><strong>Vaccinated:</strong> ${row.vaccination_status}</p>
        <p><a href="${filePath}" target="_blank">Click to view ID</a><p>
        <br>
        <p>
            ${$action}
        </p>
        </div>
    </div>
    `;
    
    document.getElementById("residentInfo").innerHTML = residentInfoHtml;
    document.getElementById("viewResidentPopup").style.display = "block";

    document.querySelectorAll(".approval").forEach(button => {
        button.addEventListener("click", function (e) {
            e.preventDefault();
            e.stopImmediatePropagation();
        
            // Show confirmation alert before proceeding
            let confirmAction = confirm("Are you sure you want to proceed with this approval?");
            if (!confirmAction) return; // Stop execution if the user cancels
    
            // Retrieve data attributes
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
                    location.reload(); // Reload only on success
                }
            })
            .catch(error => {
                console.error("Error:", error);
                alert("An error occurred. Please try again.");
            });
        });
    });
}

// Function to close the popup
function closeViewPopup() {
    document.getElementById("viewResidentPopup").style.display = "none";
}

function printVaccinationDetails(){
    window.print();
}
  // Function to open the view modal and display vaccination details
function viewVaccinationDetails(button) {
    var row = button.parentNode.parentNode; // Get the row that contains the clicked button
    
    // Retrieve data from the row

    var accountID = row.cells[0].textContent;
    var nationalId = row.cells[1].textContent;
    var lastName = row.cells[2].textContent;
    var firstName = row.cells[3].textContent;
    var age = row.cells[4].textContent;
    var gender = row.cells[5].textContent;
    var birthdate = row.cells[6].textContent;
    var vaccinationStatus = row.cells[7].textContent;
    var vaccine = row.cells[8].textContent;
    var vaccinationType = row.cells[9].textContent;
    var vaccinationDate = row.cells[10].textContent;
    
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



    
    // Display the view modal
    document.getElementById('viewModal').style.display = 'block';
}

// Function to close the view modal
function closeViewModal() {
    document.getElementById('viewModal').style.display = 'none';
}
// Function to open popup form
function openPopupForm() {
    document.getElementById("popupForm").style.display = "block";
}

// Function to close popup form
function closePopupForm(element) {
    document.getElementById(element).style.display = "none";
}

// Function to open a specific tab
function openTab(evt, tabName) {
    var i, tabcontent, tablinks;

    // Hide all tab content
    tabcontent = document.getElementsByClassName("tabcontent");
    for (i = 0; i < tabcontent.length; i++) {
        tabcontent[i].style.display = "none";
    }

    // Remove the 'active' class from all tab links
    tablinks = document.getElementsByClassName("tablinks");
    for (i = 0; i < tablinks.length; i++) {
        tablinks[i].className = tablinks[i].className.replace(" active", "");
    }

    // Show the specific tab content and set the button as active
    document.getElementById(tabName).style.display = "block";
    evt.currentTarget.className += " active";
}
// Function to set the active tab ID in local storage
function setActiveTab(tabId) {
    localStorage.setItem('activeTab', tabId);
}

// Function to get the active tab ID from local storage
function getActiveTab() {
    return localStorage.getItem('activeTab');
}

// Function to open the default active tab when the page loads
window.onload = function() {
    var activeTabId = getActiveTab();
    if (activeTabId) {
        openTab(null, activeTabId);
    } else {
        document.getElementById('defaultOpen').click();
    }
};

// Function to open a specific tab
function openTab(evt, tabName) {
    var i, tabcontent, tablinks;

    // Hide all tab content
    tabcontent = document.getElementsByClassName("tabcontent");
    for (i = 0; i < tabcontent.length; i++) {
        tabcontent[i].style.display = "none";
    }

    // Remove the 'active' class from all tab links
    tablinks = document.getElementsByClassName("tablinks");
    for (i = 0; i < tablinks.length; i++) {
        tablinks[i].className = tablinks[i].className.replace(" active", "");
    }

    // Show the specific tab content and set the button as active
    document.getElementById(tabName).style.display = "block";
    if (evt) {
        evt.currentTarget.className += " active";
    } else {
        document.getElementById(tabName).className += " active";
    }
    

    // Function to set the active tab ID in local storage
    function setActiveTab(tabId) {
        localStorage.setItem('activeTab', tabId);
    }

    // Function to get the active tab ID from local storage
    function getActiveTab() {
        return localStorage.getItem('activeTab');
    }

    // Function to zoom in the "Add New" button on hover
    document.querySelector('.left-button').addEventListener('mouseover', function() {
        this.style.transform = 'scale(1.1)';
    });

    // Function to zoom out the "Add New" button when not hovered
    document.querySelector('.left-button').addEventListener('mouseout', function() {
        this.style.transform = 'scale(1)';
    });

    // Function to open the default active tab when the page loads
    window.onload = function() {
        var activeTabId = getActiveTab();
        if (activeTabId) {
            openTab(null, activeTabId);
        } else {
            document.getElementById('defaultOpen').click();
        }
    };
}
    // Check if the URL contains a warning parameter
    const urlParams = new URLSearchParams(window.location.search);
    const warning = urlParams.get('warning');
    // Check if the warning has been shown in this session
    const warningShown = sessionStorage.getItem('warningShown');
    // If there is a warning parameter and it hasn't been shown in this session, display an alert with the message
    if (warning && !warningShown) {
        alert(warning);
        // Mark the warning as shown in this session
        sessionStorage.setItem('warningShown', 'true');
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

  // Function to open the popup form for editing resident information
 // Function to open the popup form for editing resident information
function openEditPopupFormEdit(row) {
    // Show the popup form
    document.getElementById("editPopupFormEdit").style.display = "block";

    // Use a more specific query to ensure the elements are found correctly
    const form = document.querySelector("#edit_resident");
    if (form) {
        form.querySelector("#editAccountID").value = row.accountID;
        form.querySelector("#national_id").value = row.national_id;
        form.querySelector("#lastName").value = row.lastName;
        form.querySelector("#firstName").value = row.firstName;
        form.querySelector("#middleName").value = row.middleName;
        form.querySelector("#Suffix").value = row.Suffix;
        form.querySelector("#address").value = row.address;
        form.querySelector("#house_no").value = row.house_no;
        form.querySelector("#birthdate").value = row.birthdate;
        form.querySelector("#age").value = row.age;
        form.querySelector("#birthplace").value = row.birthplace;
        form.querySelector("#email").value = row.email;
        form.querySelector("#contact_number").value = row.contact_number;
        form.querySelector("#occupation").value = row.occupation;
        form.querySelector("#vaccination_date").value = row.vaccination_date;

        if (row.file !== null && row.file !== "") {
            let filePath = "./../uploads/" + row.file; // Adjust the path as needed
            form.querySelector('#file_id_show').style.display = 'block';
            form.querySelector('#id_link').setAttribute('href', filePath);
        } else {
            form.querySelector('#file_id_show').style.display = 'none';
            form.querySelector('#file_id').setAttribute('required');
        }
        

        const genderSelect = form.querySelector("#gender");
        if ([...genderSelect.options].some(option => option.value === row.gender)) {
            genderSelect.value = row.gender;
        }

        const civil_status = form.querySelector("#civil_status");
        if ([...civil_status.options].some(option => option.value === row.civil_status)) {
            civil_status.value = row.civil_status;
        }

        const religionSelect = form.querySelector("#religion");
        if ([...religionSelect.options].some(option => option.value === row.religion)) {
            religionSelect.value = row.religion;
        }

        const educationattainmentSelect = form.querySelector("#education_attainment");
        if ([...educationattainmentSelect.options].some(option => option.value === row.education_attainment)) {
            educationattainmentSelect.value = row.education_attainment;
        }

        const voterStatus = form.querySelector("#voter_status");
        if ([...voterStatus.options].some(option => option.value === row.voter_status)) {
            voterStatus.value = row.voter_status;
        }

        const disabilitySelect = form.querySelector("#disability");
        if ([...disabilitySelect.options].some(option => option.value === row.disability)) {
            disabilitySelect.value = row.disability;
        }

        const vaccinationstatusSelect = form.querySelector("#vaccination_status");
        if ([...vaccinationstatusSelect.options].some(option => option.value === row.vaccination_status)) {
            vaccinationstatusSelect.value = row.vaccination_status;
        }

        const vaccinationtpeSelect = form.querySelector("#vaccination_type");
        if ([...vaccinationtpeSelect.options].some(option => option.value === row.vaccination_type)) {
            vaccinationtpeSelect.value = row.vaccination_type;
        }

        const vaccineSelect = form.querySelector("#vaccine");
        if ([...vaccineSelect.options].some(option => option.value === row.vaccine)) {
            vaccineSelect.value = row.vaccine;
        }
      
    } else {
        console.error("Edit popup form not found!");
    }
}

// Function to close the popup form for editing resident information
function closeEditPopupForm() {
    document.getElementById("editPopupFormEdit").style.display = "none";
}
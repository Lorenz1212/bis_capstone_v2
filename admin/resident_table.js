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

// Function to open the popup form for adding a new resident
function openPopupForm() {
    document.getElementById("addResidentForm").style.display = "block";
}

// Function to close the popup form
function closePopupForm() {
    document.getElementById("addResidentForm").style.display = "none";
}

// Function to add a new resident
function addResident() {
    // Retrieve the input values
    var accountID = document.getElementById("accountID").value;
    var nationalID = document.getElementById("national_id").value;
    var lastName = document.getElementById("lastName").value;
    var firstName = document.getElementById("firstName").value;
    var middleName = document.getElementById("middleName").value;
    var alias = document.getElementById("alias").value;
    var address = document.getElementById("address").value;
    var houseNo = document.getElementById("houseNo").value;
    var birthdate = document.getElementById("birthdate").value;
    var age = document.getElementById("age").value;
    var gender = document.getElementById("gender").value;
    var civilStatus = document.getElementById("civilStatus").value;

    // Check if any field is empty
    if (!accountID || !nationalID || !lastName || !firstName || !middleName || !alias || !address || !houseNo || !birthdate || !age || !gender || !civilStatus) {
        // If any field is empty, display an error message
        console.error("All fields are required.");
        return; // Exit the function early
    }

    // Prepare the data to be sent to the server
    var data = {
        accountID: accountID,
        national_id: nationalID,
        lastName: lastName,
        firstName: firstName,
        middleName: middleName,
        Alias: alias,
        address: address,
        house_no: houseNo,
        birthdate: birthdate,
        age: age,
        gender: gender,
        civil_status: civilStatus
    };

    // Send an HTTP POST request to the server
    fetch("insert_resident.php", {
        method: "POST",
        body: JSON.stringify(data),
        headers: {
            "Content-Type": "application/json"
        }
    })
    .then(response => response.json())
    .then(data => {
        // Check if the insertion was successful
        if (data.status === "success") {
            // If successful, you can display a success message or perform any other action
            console.log("Resident added successfully");
        } else {
            // If unsuccessful, handle the error
            console.error("Failed to add resident:", data.message);
        }
    })
    .catch(error => {
        // Handle network errors or other exceptions
        console.error("Error:", error);
    });
}

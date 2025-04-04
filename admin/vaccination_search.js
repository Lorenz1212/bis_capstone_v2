function filterVaccinationTable() {
  var searchInput = document.getElementById("searchInputVaccination");
  var filter = searchInput.value.toUpperCase();
  var table = document.getElementById("vaccinationTable");
  var rows = table.getElementsByTagName("tr");

  var found = false; // Flag to track if any results are found

  // Skip the first row (header row), start iteration from index 1
  for (var i = 1; i < rows.length; i++) {
    var cells = rows[i].getElementsByTagName("td");
    var rowFound = false;
    for (var j = 0; j < cells.length; j++) {
      var cell = cells[j];
      if (cell) {
        var textValue = cell.textContent || cell.innerText;
        if (textValue.toUpperCase().indexOf(filter) > -1) {
          rowFound = true;
          found = true;
          break;
        }
      }
    }
    if (rowFound) {
      rows[i].style.display = ""; // Show the row
    } else {
      rows[i].style.display = "none"; // Hide the row
    }
  }

  // Create or remove message row based on search result
  var noResultsRow = document.getElementById("noResultsRowVaccination");
  if (!found) {
    // If no results are found, create the message row
    if (!noResultsRow) {
      noResultsRow = table.insertRow(-1); // Insert at the end of the table
      noResultsRow.id = "noResultsRowVaccination"; // Set the id for easy identification
      var noResultsCell = noResultsRow.insertCell(0);
      noResultsCell.colSpan = table.rows[0].cells.length; // Set colspan to span all columns
      noResultsCell.textContent = "No results found"; // Set the message text
    }
  } else {
    // If results are found, remove the message row if it exists
    if (noResultsRow) {
      noResultsRow.parentNode.removeChild(noResultsRow); // Remove the message row
    }
  }
}

// Add event listener to the search input
// document
//   .getElementById("searchInputVaccination")
//   .addEventListener("input", filterVaccinationTable);

// Function to handle editing a row
// Function to handle editing a row
function editRow(button) {
  // Get the row that contains the clicked button
  var row = button.parentNode.parentNode;

  // Get the data from the row
  var nationalId = row.cells[0].textContent;
  var lastName = row.cells[2].textContent;
  var firstName = row.cells[3].textContent;
  var vaccinationStatus = row.cells[4].textContent;

  // Set the values in the edit modal
  document.getElementById("nationalIdEdit").textContent = nationalId;
  document.getElementById("lastNameEdit").textContent = lastName;
  document.getElementById("firstNameEdit").textContent = firstName;
  document.getElementById("vaccinationStatusEdit").value = vaccinationStatus;

  // Display the edit modal
  document.getElementById("editModal").style.display = "block";
}

// Function to close the modal
function closeModal() {
  var modal = document.getElementById("editModal");
  modal.style.display = "none";
}
function saveChanges() {
  // Retrieve updated values from the edit modal
  var accountID = document.getElementById("nationalIdEdit").textContent; // Assuming nationalIdEdit corresponds to accountID
  var vaccinationStatus = document.getElementById(
    "vaccinationStatusEdit"
  ).value;
  var vaccine = document.getElementById("vaccineEdit").value;
  var vaccinationType = document.getElementById("vaccinationTypeEdit").value;
  var vaccinationDate = document.getElementById("vaccinationDateEdit").value;

  // Send asynchronous POST request to update_resident.php
  var formData = new FormData();
  formData.append("accountID", accountID);
  formData.append("vaccinationStatus", vaccinationStatus);
  formData.append("vaccine", vaccine);
  formData.append("vaccinationType", vaccinationType);
  formData.append("vaccinationDate", vaccinationDate);

  fetch("update_resident.php", {
    method: "POST",
    body: formData,
  })
    .then((response) => response.json())
    .then((data) => {
      alert(data.message);
      if (data.status === "success") {
        location.reload();
        closeModal(); // Fix this function call
      }
    })
    .catch((error) => {
      console.error("Error:", error);
    });
}

// Function to load content of the vaccination table asynchronously
function loadVaccinationTable() {
  var xhr = new XMLHttpRequest();
  xhr.open("GET", "vaccinated_individuals.php", true);
  xhr.onload = function () {
    if (xhr.status === 200) {
      // Replace the content of the vaccination table with the response
      document.getElementById("vaccinationTable").innerHTML = xhr.responseText;
    } else {
      // Show an error message if the AJAX request failed
      alert("Error: " + xhr.statusText);
    }
  };
  xhr.onerror = function () {
    // Show an error message if the AJAX request encountered an error
    alert("Error: Network request failed");
  };
  xhr.send();
}

// Function to automatically close the popup card after a specified duration
function autoClosePopup(duration) {
  // Set a timeout to close the popup after the specified duration
  setTimeout(function () {
    // Close the modal
    closeModal();
  }, duration);
}

<?php 
$query1 = "SELECT * FROM purok ORDER BY `name`";
$result1 = $conn->query($query1);
$purok = array();
while($row = $result1->fetch_assoc()){
    $purok[] = $row; 
}
?>

<div class="modal fade" id="editVacinationtModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editModalLabel"><i class="fa-solid fa-edit"></i> Edit Vaccination Details</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="editForm">
                    <input type="hidden" id="nationalIdEdit" name="nationalIdEdit">
                    
                    <div class="mb-3">
                        <label for="vaccinationStatusEdit" class="form-label">Vaccination Status:</label>
                        <select id="vaccinationStatusEdit" name="vaccinationStatusEdit" class="form-select">
                            <option value="" disabled selected>--Select Vaccination--</option>
                            <option value="Yes">Yes</option>
                            <option value="No">No</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="vaccineEdit" class="form-label">Vaccine:</label>
                        <select id="vaccineEdit" name="vaccineEdit" class="form-select">
                            <option value="None">None</option>
                            <option value="Johnson & Johnson">Johnson & Johnson</option>
                            <option value="AstraZeneca">AstraZeneca</option>
                            <option value="Pfizer">Pfizer</option>
                            <option value="Moderna">Moderna</option>
                            <option value="Sputnik">Sputnik</option>
                            <option value="Sinovac">Sinovac</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="vaccinationTypeEdit" class="form-label">Vaccination Type:</label>
                        <select id="vaccinationTypeEdit" name="vaccinationTypeEdit" class="form-select">
                            <option value="None">None</option>
                            <option value="First Dose">1st dose</option>
                            <option value="Second Dose">2nd dose</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="vaccinationDateEdit" class="form-label">Vaccination Date:</label>
                        <input type="date" id="vaccinationDateEdit" name="vaccinationDateEdit" class="form-control" required>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" onclick="saveChanges()">Save Changes</button>
            </div>
        </div>
    </div>
</div>

<!-- View Vaccination Modal -->
<div class="modal fade" id="viewModal" tabindex="-1" aria-labelledby="viewModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="viewModalLabel">
                    <i class="fa-solid fa-id-card"></i> Vaccination Record Card
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            
            <div class="modal-body">
                <div class="text-center">
                    <img src="../image/logo.png" alt="Logo" class="mb-3" width="100">
                    <h4 class="fw-bold">Vaccination Details</h4>
                </div>

                <div class="mb-3 border p-3 rounded bg-light">
                    <p><strong>Account ID:</strong> <span id="accountIDView"></span></p>
                    <p><strong>Valid ID #:</strong> <span id="nationalIdView"></span></p>
                    <p><strong>Name:</strong> <span id="lastNameView"></span>, <span id="firstNameView"></span></p>
                    <p><strong>Age:</strong> <span id="ageView"></span></p>
                    <p><strong>Gender:</strong> <span id="genderView"></span></p>
                    <p><strong>Birthdate:</strong> <span id="birthdateView"></span></p>
                </div>

                <table class="table table-bordered text-center">
                    <thead class="table-dark">
                        <tr>
                            <th>Vaccination Status</th>
                            <th>Vaccination Date</th>
                            <th>Vaccine Name</th>
                            <th>Vaccination Type</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td id="vaccinationStatusView"></td>
                            <td id="vaccinationDateView"></td>
                            <td id="vaccineView"></td>
                            <td id="vaccinationTypeView"></td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-outline-primary" onclick="printVaccinationDetails()">
                    <i class="fa-solid fa-print"></i> Print
                </button>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal Structure -->
<div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editModalLabel">Edit Resident</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="edit_resident" enctype="multipart/form-data">
                    <input type="hidden" id="editAccountID" name="accountID">

                    <div class="row mb-3">
                        <div class="col-md-5 mb-2">
                            <input type="text" class="form-control" id="national_id" name="national_id" placeholder="Enter Valid ID #" 
                                required pattern="[0-9]{6,20}" title="Valid ID No. should be 6-20 digits long and contain only numbers." 
                                inputmode="numeric" oninput="this.value = this.value.replace(/\D/g, '')">
                        </div>
                        <div class="col-md-3 mb-2">
                            <input type="text" class="form-control" name="house_no" id="house_no" placeholder="House No" required>
                        </div>
                        <div class="col-md-4">
                            <select id="address" name="address" class="form-control" required>
                                <option value="" disabled selected>--Select Purok--</option>
                                <?php foreach($purok as $row):?>
                                    <option value="<?= ucwords($row['name']) ?>"><?= $row['name'] ?></option>
                                <?php endforeach ?>
                            </select>
                        </div>
                    </div>

                    <!-- Aligned Name Fields -->
                    <div class="row mb-3">
                        <div class="col-md-3">
                            <input type="text" class="form-control" id="lastName" name="lastName" placeholder="Last Name" required oninput="this.value = this.value.toUpperCase()">
                        </div>
                        <div class="col-md-3">
                            <input type="text" class="form-control" id="firstName" name="firstName" placeholder="First Name" required oninput="this.value = this.value.toUpperCase()">
                        </div>
                        <div class="col-md-3">
                            <input type="text" class="form-control" id="middleName" name="middleName" placeholder="Middle Name" oninput="this.value = this.value.toUpperCase()">
                        </div>
                        <div class="col-md-3">
                            <select class="form-select" id="Suffix" name="Suffix">
                                <option value="">--Suffix--</option>
                                <option value="JR.">JR.</option>
                                <option value="SR.">SR.</option>
                                <option value="I">I</option>
                                <option value="II">II</option>
                                <option value="III">III</option>
                                <option value="IV">IV</option>
                            </select>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <select class="form-select" id="gender" name="gender" required>
                                <option value="" disabled selected>--Select Gender--</option>
                                <option value="Male">Male</option>
                                <option value="Female">Female</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <select class="form-select" id="civil_status" name="civil_status" required>
                                <option value="" disabled selected>--Select Civil Status--</option>
                                <option value="Single">Single</option>
                                <option value="Married">Married</option>
                                <option value="Legally Separated">Legally Separated</option>
                                <option value="Widow/Widower">Widow/Widower</option>
                            </select>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <input type="text" class="form-control" id="birthplace" name="birthplace" placeholder="Birthplace">
                        </div>
                        <div class="col-md-6">
                            <input type="tel" class="form-control" id="contact_number" name="contact_number" placeholder="09XXXXXXXXX" required pattern="09[0-9]{9}" maxlength="11" title="Enter a valid 11-digit contact number starting with 09" inputmode="numeric" oninput="this.value = this.value.replace(/[^0-9]/g, '')">
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="birthdate">Birth Date:</label>
                            <input type="date" class="form-control " id="birthdate" name="birthdate" onchange="updateAgeEdit()" required>
                        </div>
                        <div class="col-md-6">
                            <label for="age">Age:</label>
                            <input type="text" class="form-control" id="age" name="age" placeholder="Age" readonly>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <input type="text" class="form-control" id="occupation" name="occupation" placeholder="Occupation">
                        </div>
                        <div class="col-md-6">
                            <input type="email" class="form-control" id="email" name="email" placeholder="jollyhotdog@gmail.com" required pattern="[a-zA-Z0-9._%+-]+@gmail\.com" title="Please enter a valid Gmail address">
                        </div>
                    </div>

                    <div class="row g-3 mt-3">
                        <div class="col-md-12">                    
                            <div class="mb-3">
                                <label for="disability" class="form-label">Disability</label>
                                <select class="form-select"  id="disability" name="disability" required>
                                    <option value="" disabled selected>--Select Beneficiary ID--</option>
                                    <option value="Person with Disability">Person with Disability</option>
                                    <option value="Senior Citizen">Senior Citizen</option>
                                    <option value="Solo Parent">Solo Parent</option>
                                    <option value="N/A">N/A</option>
                                </select>
                            </div>
                        </div>
                    </div>


                    <div class="mb-3">
                        <select class="form-select" id="religion" name="religion" required>
                            <option value="" disabled selected>--Select Religion--</option>
                            <option value="Roman Catholic">Roman Catholic</option>
                            <option value="Iglesia ni Cristo">Iglesia ni Cristo</option>
                            <option value="Islam">Islam</option>
                            <option value="Protestant">Protestant</option>
                            <option value="Buddhist">Buddhist</option>
                            <option value="Hindu">Hindu</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <select class="form-select" id="education_attainment" name="education_attainment" required>
                            <option value="">--Select Highest Educational Attainment--</option>
                            <option value="Elementary Graduate">Elementary Graduate</option>
                            <option value="High School Graduate">High School Graduate</option>
                            <option value="College Graduate">College Graduate</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <select class="form-select" id="voter_status" name="voter_status" required>
                            <option value="" disabled selected>--Are you a voter in Marinig?--</option>
                            <option value="Yes">Yes</option>
                            <option value="No">No</option>
                        </select>
                    </div>

                    <!-- Vaccination Details -->
                    <div class="row g-3 mt-3 mb-3">
                        <div class="col-md-6">
                            <label for="vaccination_status" class="form-label">Vaccination Status</label>
                            <select id="vaccination_status" name="vaccination_status" class="form-select" required>
                                <option value="" disabled selected>--Select Status--</option>
                                <option value="Yes">Vaccinated</option>
                                <option value="No">Not Vaccinated</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label for="vaccination_date" class="form-label">Vaccination Date</label>
                            <input type="date" id="vaccination_date" name="vaccination_date" class="form-control">
                        </div>
                    </div>

                    <div class="row g-3 mt-3 mb-3">
                        <div class="col-md-6">
                            <label for="vaccination_status" class="form-label">Vaccine</label>
                            <select id="vaccine" name="vaccine"  class="form-select"  required>
                                <option value="None">--Select Vaccine--</option>
                                <option value="Johnson & Johnson">Johnson & Johnson</option>
                                <option value="AstraZeneca">AstraZeneca</option>
                                <option value="Pfizer">Pfizer</option>
                                <option value="Moderna">Moderna</option>
                                <option value="Sputnik">Sputnik</option>
                                <option value="Sinovac">Sinovac</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label for="vaccination_date" class="form-label">Vaccination Type</label>
                            <select id="vaccination_type" name="vaccination_type" class="form-select"  required>
                                <option value="None">--Select Vaccination Type--</option>
                                <option value="First Dose">1st dose</option>
                                <option value="Second Dose">2nd dose</option>
                            </select>
                        </div>
                    </div>


                    <div class="mb-3">
                        <label for="file_id">Upload Your ID 
                            <span style="color:red">(Note: Include your address and date of birth)</span>
                        </label>
                        <input type="file" class="form-control" name="file" id="file_id" accept="image/*">
                    </div>

                    <button type="submit" class="btn btn-primary w-100">Submit</button>
                </form>
            </div>
        </div>
    </div>
</div>


<!-- Bootstrap 5 Modal -->
<div class="modal fade" id="addResidentModal" tabindex="-1" aria-labelledby="addResidentModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addResidentModalLabel"><i class="fa-solid fa-user-plus"></i> Add New Resident</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="add_new_resident" enctype="multipart/form-data">
                    
                    <!-- Name Inputs -->
                    <div class="row g-3">
                        <div class="col-md-4">
                            <label for="lastName" class="form-label">Last Name</label>
                            <input type="text" id="lastName" name="lastName" class="form-control" required oninput="this.value = this.value.toUpperCase()">
                        </div>
                        <div class="col-md-4">
                            <label for="firstName" class="form-label">First Name</label>
                            <input type="text" id="firstName" name="firstName" class="form-control" required oninput="this.value = this.value.toUpperCase()">
                        </div>
                        <div class="col-md-4">
                            <label for="middleName" class="form-label">Middle Name</label>
                            <input type="text" id="middleName" name="middleName" class="form-control" oninput="this.value = this.value.toUpperCase()">
                        </div>
                    </div>

                    <!-- Address & Contact -->
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label for="address" class="form-label">Purok</label>
                            <select id="address" name="address" class="form-select" required>
                                <option value="" disabled selected>--Select Purok--</option>
                                <?php foreach($purok as $row):?>
                                    <option value="<?= ucwords($row['name']) ?>"><?= $row['name'] ?></option>
                                <?php endforeach ?>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label for="house_no" class="form-label">House No</label>
                            <input type="text" id="house_no" name="house_no" class="form-control" required>
                        </div>
                    </div>

                    <!-- Personal Info -->
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label for="birthdate" class="form-label">Birth Date</label>
                            <input type="date" id="Addbirthdate" name="birthdate" class="form-control" required onchange="updateAge()">
                        </div>
                        <div class="col-md-6">
                            <label for="age" class="form-label">Age</label>
                            <input type="text" id="Addage" name="age" class="form-control" readonly>
                        </div>
                    </div>

                    <!-- Gender & Civil Status -->
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label for="gender" class="form-label">Gender</label>
                            <select id="gender" name="gender" class="form-select" required>
                                <option value="" disabled selected>--Select Gender--</option>
                                <option value="Male">Male</option>
                                <option value="Female">Female</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label for="civil_status" class="form-label">Civil Status</label>
                            <select id="civil_status" name="civil_status" class="form-select" required>
                                <option value="" disabled selected>--Select Civil Status--</option>
                                <option value="Single">Single</option>
                                <option value="Married">Married</option>
                            </select>
                        </div>
                    </div>

                    <!-- Contact Details -->
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label for="contact_number" class="form-label">Contact Number</label>
                            <input type="tel" id="contact_number" name="contact_number" class="form-control" required pattern="09[0-9]{9}" maxlength="11" title="Enter a valid 11-digit contact number starting with 09" inputmode="numeric">
                        </div>
                        <div class="col-md-6">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" id="email" name="email" class="form-control" required pattern="[a-zA-Z0-9._%+-]+@gmail\.com" title="Please enter a valid Gmail address">
                        </div>
                    </div>

                    <div class="row g-3 mt-3">
                        <div class="col-md-12">                    
                            <div class="mb-3">
                                <label for="disability" class="form-label">Disability</label>
                                <select class="form-select"  name="disability" required>
                                    <option value="" disabled selected>--Select Beneficiary ID--</option>
                                    <option value="Person with Disability">Person with Disability</option>
                                    <option value="Senior Citizen">Senior Citizen</option>
                                    <option value="Solo Parent">Solo Parent</option>
                                    <option value="N/A">N/A</option>
                                </select>
                            </div>
                        </div>
                    </div>


                                    
                    <div class="row g-3 mt-3">
                        <div class="col-md-12">                    
                            <div class="mb-3">
                                <label for="religion" class="form-label">Religion</label>
                                <select class="form-select" name="religion" required>
                                    <option value="" disabled selected>--Select Religion--</option>
                                    <option value="Roman Catholic">Roman Catholic</option>
                                    <option value="Iglesia ni Cristo">Iglesia ni Cristo</option>
                                    <option value="Islam">Islam</option>
                                    <option value="Protestant">Protestant</option>
                                    <option value="Buddhist">Buddhist</option>
                                    <option value="Hindu">Hindu</option>
                                </select>
                            </div>
                        </div>
                    </div>


                    <div class="row g-3 mt-3">
                        <div class="col-md-12">                
                            <div class="mb-2">
                                <label for="education_attainment" class="form-label">Highest Educational Attainment</label>
                                <select class="form-select" name="education_attainment" required>
                                    <option value="">--Select Highest Educational Attainment--</option>
                                    <option value="Elementary Graduate">Elementary Graduate</option>
                                    <option value="High School Graduate">High School Graduate</option>
                                    <option value="College Graduate">College Graduate</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row g-3">
                        <div class="col-md-12">       
                            <div class="mb-2">
                                <label for="voter_status" class="form-label">Voter in Mamatid</label>
                                <select class="form-select"  name="voter_status" required>
                                    <option value="" disabled selected>--Are you a voter in Marinig?--</option>
                                    <option value="Yes">Yes</option>
                                    <option value="No">No</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <!-- Vaccination Details -->
                    <div class="row g-3 mt-3">
                        <div class="col-md-6">
                            <label for="vaccination_status" class="form-label">Vaccination Status</label>
                            <select  name="vaccination_status" class="form-select" required>
                                <option value="" disabled selected>--Select Status--</option>
                                <option value="Yes">Vaccinated</option>
                                <option value="No">Not Vaccinated</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label for="vaccination_date" class="form-label">Vaccination Date</label>
                            <input type="date" name="vaccination_date" class="form-control">
                        </div>
                    </div>

                    <div class="row g-3 mt-3 mb-3">
                        <div class="col-md-6">
                            <label for="vaccination_status" class="form-label">Vaccine</label>
                            <select name="vaccine"  class="form-select"  required>
                                <option value="None">--Select Vaccine--</option>
                                <option value="Johnson & Johnson">Johnson & Johnson</option>
                                <option value="AstraZeneca">AstraZeneca</option>
                                <option value="Pfizer">Pfizer</option>
                                <option value="Moderna">Moderna</option>
                                <option value="Sputnik">Sputnik</option>
                                <option value="Sinovac">Sinovac</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label for="vaccination_date" class="form-label">Vaccination Type</label>
                            <select  name="vaccination_type" class="form-select"  required>
                                <option value="None">--Select Vaccination Type--</option>
                                <option value="First Dose">1st dose</option>
                                <option value="Second Dose">2nd dose</option>
                            </select>
                        </div>
                    </div>

                    <!-- Upload ID -->
                    <div class="row g-3">
                        <div class="col-md-12">
                            <label for="file" class="form-label">Upload Your ID <span class="text-danger">(Must show address & birth date)</span></label>
                            <input type="file" name="file" class="form-control" accept="image/*" required>
                        </div>
                    </div>

                    <!-- Submit Button -->
                    <div class="d-flex justify-content-end mt-4">
                        <button type="submit" class="btn btn-primary"><i class="fa-solid fa-save"></i> Submit</button>
                        <button type="button" class="btn btn-secondary ms-2" data-bs-dismiss="modal">Close</button>
                    </div>

                </form>
            </div>
        </div>
    </div>
</div>


<!-- Resident Info Modal -->
<div class="modal fade" id="residentInfoModal" tabindex="-1" aria-labelledby="residentInfoModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="residentInfoModalLabel">Resident Information</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div id="residentInfo"></div> <!-- Resident details will be loaded here -->
            </div>
        </div>
    </div>
</div>


<script>
    // Set default date when page loads
function setDefaultDate() {
    let today = new Date();
    let birthYear = today.getFullYear() - 16;
    let month = String(today.getMonth() + 1).padStart(2, '0'); 
    let day = String(today.getDate()).padStart(2, '0'); 
    let defaultDate = `${birthYear}-${month}-${day}`;

    document.getElementById("Addbirthdate").value = defaultDate;
    document.getElementById("birthdate").value = defaultDate;
    // Calculate age for the default date
    updateAge();
}

// Calculate age from birthdate
function calculateAge(birthdate) {
    if (!birthdate) return "";
    
    const today = new Date();
    const birthDate = new Date(birthdate);
    
    // Check if birthdate is in the future
    if (birthDate > today) {
        alert("Birth date cannot be in the future.");
        document.getElementById('birthdate').value = "";
        document.getElementById('Addbirthdate').value = "";
        return "";
    }
    
    let age = today.getFullYear() - birthDate.getFullYear();
    const monthDifference = today.getMonth() - birthDate.getMonth();
    
    if (monthDifference < 0 || (monthDifference === 0 && today.getDate() < birthDate.getDate())) {
        age--;
    }
    
    if (age < 16) {
        alert("Age must be 16 or above.");
        document.getElementById('birthdate').value = "";
        document.getElementById('Addbirthdate').value = "";
        return "";
    }
    
    return age;
}

// Update age field based on birthdate
function updateAge() {
    const birthdateInput = document.getElementById('Addbirthdate').value;
    const ageInput = document.getElementById('Addage');
    if (birthdateInput) {
        const age = calculateAge(birthdateInput);
        ageInput.value = age !== "" ? age : "";
    } else {
        ageInput.value = "";
    }
}
function updateAgeEdit() {
    const birthdateInput = document.getElementById('birthdate').value;
    const ageInput = document.getElementById('age');
    if (birthdateInput) {
        const age = calculateAge(birthdateInput);
        ageInput.value = age !== "" ? age : "";
    } else {
        ageInput.value = "";
    }
}
setDefaultDate();
// Initialize when page loads
document.addEventListener('DOMContentLoaded', function() {
    setDefaultDate();
    
    // Add event listener for birthdate changes
    document.getElementById('Addbirthdate').addEventListener('change', updateAge);
    document.getElementById('birthdate').addEventListener('change', updateAgeEdit);
});

</script>
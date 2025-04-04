

<?php 
  include 'user_navbar.php'; 
  include 'dashboard_data.php'; ?>

  <div class="tabs">
        <a href="javascript:void(0)" class="tablinks" onclick="openTab(event, 'resident-request')" id="defaultOpen">Request For Approval (Resident)</a>

        <a href="javascript:void(0)" class="tablinks" onclick="openTab(event, 'resident-list')" id="defaultOpen">Resident List (Approved)</a>

        <a href="javascript:void(0)" class="tablinks" onclick="openTab(event, 'resident-rejected')" id="defaultOpen">Resident List (Rejected)</a>
        
        <a href="javascript:void(0)" class="tablinks" onclick="openTab(event, 'vaccination-list')">Vaccination List</a>

        <button href="javascript:void(0)" class="left-button" onclick="openPopupForm()">+ Add New Resident</button>
    </div>
<div class="container">
        
        <div id="resident-request" class="tabcontent" style="display: block;">
            <div class="tab-header">
                <div class="search-container">
                    <input type="text" id="searchInputListRequest" placeholder="Search..." oninput="searchResidents('residentRequestTableList','searchInputListRequest')">
                    <button class="search-icon"><i class="fa fa-search"></i></button>
                </div>
            </div>
            <?php include 'resident_request.php'; ?>
        </div>

            <div id="resident-list" class="tabcontent">
                <div class="tab-header">
                    <div class="search-container">
                        <input type="text" id="searchInputList" placeholder="Search..." oninput="searchResidents('residentTableList','searchInputList')">
                        <button class="search-icon"><i class="fa fa-search"></i></button>
                    </div>
                </div>
                <?php include 'resident_list.php'; ?>
            </div>

            <div id="resident-rejected" class="tabcontent">
                <div class="tab-header">
                    <div class="search-container">
                        <input type="text" id="searchInputRejected" placeholder="Search..." oninput="searchResidents('residentTableRejected','searchInputRejected')">
                        <button class="search-icon"><i class="fa fa-search"></i></button>
                    </div>
                </div>
                <?php include 'resident_rejected.php'; ?>
            </div>

            <div id="vaccination-list" class="tabcontent">
                <div class="filter-container">
                    <label for="vaccinationFilter">Filter by Vaccination Status:</label>
                    <select id="vaccinationFilter">
                        <option value="">All</option>
                        <option value="Yes">Vaccinated</option>
                        <option value="No">Not Vaccinated</option>
                    </select>
                </div>
                <div class="search-container">
                    <label for="searchInputVaccination">Search:</label>
                    <input type="text" id="searchInput" placeholder="Enter search keyword">
                </div>
            <div>
            <table id="vaccinationTable">
                <thead>
                <tr>
                <th>Account ID</th>
                    <th>National ID</th>
                    <th>Last Name</th>
                    <th>First Name</th>
                    <th>Age</th>
                    <th>Gender</th>
                    <th>Birthdate</th>
                    <th>Vaccination Status</th>
                    <th>Vaccine Name</th>
                    <th>Vaccine Type</th>
                    <th>VaccinationDate</th>
                    <th>Action</th>
                </tr>
                </thead>
                <tbody>
                    <!-- Include vaccinated_individuals.php here -->
                    <?php include 'vaccinated_individuals.php'; ?>
                </tbody>
            </table>
        </div>
        <div id="editModal" class="modal">
            <div class="modal-content">
                <div class="modal-title">
                <span class="close" onclick="closeModal()">&times;</span>
                <h2>Edit Vaccination Details</h2>
                </div>
                <div class="name">
            <span id="nationalIdEdit" style="display: none;"></span>
                <span id="lastNameEdit"></span>
                <span id="firstNameEdit"></span>
                </div>
                <div class="editform-table">
                <form id="editForm">
                    <label for="vaccinationStatusEdit">Vaccination Status:</label>
                    <select id="vaccinationStatusEdit" name="vaccinationStatusEdit">
                        <option value="" disabled selected>--Select Vaccination--</option>
                        <option value="Yes">Yes</option>
                        <option value="No">No</option>
                    </select><br><br>
                    <label for="vaccineEdit">Vaccine:</label>
                    <select id="vaccineEdit" name="vaccineEdit">
                    <option value="None">None</option>
                        <option value="Johnson & Johnson">Johnson & Johnson</option>
                        <option value="AstraZeneca">AstraZeneca</option>
                        <option value="Pfizer">Pfizer</option>
                        <option value="Moderna">Moderna</option>
                        <option value="Sputnik">Sputnik</option>
                        <option value="Sinovac">Sinovac</option>
                    </select><br><br>
                    <!-- Add vaccination type dropdown for first dose and second dose -->
                    <label for="vaccinationTypeEdit">Vaccination Type:</label>
                    <select id="vaccinationTypeEdit" name="vaccinationTypeEdit">
                    <option value="None">None</option>
                        <option value="First Dose">1st dose</option>
                        <option value="Second Dose">2nd dose</option>
                    </select><br><br>
                    <label for="vaccinationDateEdit">Vaccination Date:</label>
            <input type="date" id="vaccinationDateEdit" name="vaccinationDateEdit" required><br><br>
            <div class="edit-button">
                    <button type="button" onclick="saveChanges()">Save</button>
                    </div>
                </form>
                </div>
            </div>
        </div>  
        <!-- View Vaccination Details Popup -->
        <div id="viewModal" class="modals">
                <div class="modal-content-view">
                    <span class="close-button" onclick="closeViewModal()">&times;</span>
                    <div class="title-con">
                    <img src="../image/logo.png" alt="Logo" class="logo" >
                        <h2>Vaccination Record Card</h2>
                    </div>  
                    <div class="table-con">
                        <p>Account ID: <span id="accountIDView"></span></p>
                        <p>Valid ID #: <span id="nationalIdView"></span></p>
                        <p>Last Name: <span id="lastNameView"></span></p>
                        <p>First Name: <span id="firstNameView"></span></p>
                        <p>Age: <span id="ageView"></span></p>
                        <p>Gender: <span id="genderView"></span></p>
                        <p>Birthdate: <span id="birthdateView"></span></p>
                        </div>
                        <div class="tablevac">
                        <table>
                            <tr>
                                <th>Vaccination Status:</th>
                                <th>VaccinationDate </th>
                                <th>Vaccine Name: </th>
                                <th>Vaccination Type: </th>
                            </tr>
                        <td id="vaccinationStatusView"></td></p>
                        <td id="vaccinationDateView"></td>
                        <td id="vaccineView"></td></p>
                        <td id="vaccinationTypeView"></td></p>
                        </table>
                        </div>
                        <button onclick="printVaccinationDetails()">Print</button>
                </div>
            </div>
        </div>
        <div id="popupForm" class="popup">
            <div class="popupform-container">
                <div class="popup-content">
                    <div class="popup-contitle">
                    <span class="close-button" onclick="closePopupForm('popupForm')">&times;</span>
                    <h2>Add New Resident</h2>
                        <form id="add_new_resident" enctype="multipart/form-data">
                        <div class="grid-row">
                        <input type="text" id="national_id" name="national_id" placeholder="Enter Valid ID #" 
                        required pattern="[0-9]{6,20}" title="National ID should be 6-20 digits long and contain only numbers." 
                        inputmode="numeric" oninput="this.value = this.value.replace(/\D/g, '')">
                        <input type="text" name="house_no" id="house_no" placeholder="House No" required>
                        </div>

                        <div class="grid-row">
                            <input type="text" id="lastName" name="lastName" placeholder="Last Name" required oninput="this.value = this.value.toUpperCase()">
                            <select id="disability" name="disability" required>
                                <option value="" disabled selected>--Select Beneficiary ID--</option>
                                <option value="Person with Disability">Person with Disability</option>
                                <option value="Senior Citizen">Senior Citizen</option>
                                <option value="Solo Parent">Solo Parent</option>
                                <option value="N/A">N/A</option>
                            </select>
                        </div>
                        
                        <div class="grid-row">
                            <input type="text" id="firstName" name="firstName" placeholder="First Name" required oninput="this.value = this.value.toUpperCase()">
                            <select id="address" name="address" required>
                                <option value="" disabled selected>--Select Address--</option>
                                <option value="Purok 1">Purok 1</option>
                                <option value="Purok 2">Purok 2</option>
                                <option value="Purok 3">Purok 3</option>
                                <option value="Purok 4">Purok 4</option>
                                <option value="Purok 5">Purok 5</option>
                                <option value="Purok 6">Purok 6</option>
                                <option value="Purok 7">Purok 7</option>
                                <option value="St Joseph 7 Village">St. Joseph 7 Village</option>
                                <option value="Celestine Homes">Celestine Homes</option>
                                <option value="Maripaz">Maripaz</option>
                                <option value="Lynville">Lynville</option>
                            </select>
                        </div>
                        
                        <div class="grid-row">
                            <input type="text" id="middleName" name="middleName" placeholder="Middle Name" oninput="this.value = this.value.toUpperCase()">
                            <select id="religion" name="religion" required>
                            <option value="" disabled selected>--Select Religion--</option>
                            <option value="Roman Catholic">Roman Catholic</option>
                            <option value="Iglesia ni Cristo">Iglesia ni Cristo</option>
                            <option value="Islam">Islam</option>
                            <option value="Protestant">Protestant</option>
                            <option value="Buddhist">Buddhist</option>
                            <option value="Hindu">Hindu</option>
                            </select>
                        </div>

                        <div class="grid-row">
                            <select id="Suffix" name= "Suffix" placeholder="suffix">
                                <option value="">--Select Suffix--</option>
                                <option value="JR.">JR.</option>
                                <option value="SR.">SR.</option>
                                <option value="I">I</option>
                                <option value="II">II</option>
                                <option value="III">III</option>
                                <option value="IV">IV</option>
                                <option value="V">V</option>
                                <option value="VI">VI</option>
                                <option value="VII">VII</option>
                                <option value="VIII">VIII</option>
                                <option value="IX">IX</option>
                                <option value="X">X</option>
                                <option value="XI">XI</option>
                                <option value="XII">XII</option>
                                <option value="XIII">XIII</option>
                                <option value="XIV">XIV</option>
                                <option value="XV">XV</option>
                                <option value="XVI">XVI</option>
                                <option value="XVII">XVII</option>
                                <option value="XVIII">XVIII</option>
                                <option value="XIX">XIX</option>
                                <option value="XX">XX</option>
                            </select>
                        </div>


                        <div class="grid-row">
                            <select id="gender" name="gender" required>
                                <option value="" disabled selected>--Select Gender--</option>
                                <option value="Male">Male</option>
                                <option value="Female">Female</option>
                            </select>

                            <select id="civil_status" name="civil_status" required>
                                <option value="" disabled selected>--Select Civil Status--</option>
                                <option value="Single">Single</option>
                                <option value="Married">Married</option>
                                <option value="Legally Seperated">Legally Seperated</option>
                                <option value="Widow/Widower">Widow/Widower</option>
                            </select>
                        </div>

                        <div class="grid-row">
                        <input type="text" id="birthplace" name="birthplace" placeholder="Birthplace">
                        <input type="tel" id="contact_number" name="contact_number" placeholder="09XXXXXXXXX" required pattern="09[0-9]{9}" maxlength="11" title="Enter a valid 11-digit contact number starting with 09" inputmode="numeric" oninput="this.value = this.value.replace(/[^0-9]/g, '')">
                        </div>

                        <div class="grid-row" style="margin-bottom: 1px;">
                            <label for="national_id">Birth Date:</label>
                        </div>
                        <div class="grid-row">
                            <input type="date" id="birthdate" name="birthdate"  onchange="updateAge('#add_new_resident')" required>
                            <input type="text" id="age" name="age" placeholder="age" readonly>
                        </div>

                        <div class="grid-row">
                            <input type="text" id="occupation" name="occupation" placeholder="Occupation">
                            <input type="text" id="email" name="email" placeholder="jollyhotdog@gmail.com" required pattern="[a-zA-Z0-9._%+-]+@gmail\.com" title="Please enter a valid Gmail address">
                        </div>

                        <div class="grid-row">
                            <select id="education_attainment" name="education_attainment" required>
                                <option value="--Highest Educational Attainment--">--Select Highest Educational Attainment--</option>
                                <option value="Elementary Graduate">Elementary Graduate</option>
                                <option value="Firstyear Highschool">First year Highschool</option>
                                <option value="Second year Highschool">Second year Highschool</option>
                                <option value="Third year Highschool">Third year Highschool</option>
                                <option value="Fourth year Highschool">Fourth year Highschool</option>
                                <option value="First year College">First year College</option>
                                <option value="Second year College">Second year College</option>
                                <option value="Third year College">Third year College</option>
                                <option value="Fourth year College">Fourth year College</option>
                                <option value="College Graduate">College Graduate</option>
                            </select>
                        </div>

                        <div class="grid-row">
                            <select id="voter_status" name="voter_status" required>
                                <option value="" disabled selected>--Are you a voter in Marinig?--</option>
                                <option value="Yes">Yes</option>
                                <option value="No">No</option>
                            </select>
                        </div>
                        

                        <div class="grid-row">
                            <select id="vaccination_status" name="vaccination_status" required>
                                <option value="" disabled selected>--Select Vaccination Status--</option>
                                <option value="Yes">Vaccinated</option>
                                <option value="No">Not Vaccinated</option>
                            </select>
                        </div>

                        <div class="grid-row">
                            <select id="vaccine" name="vaccine" required>
                                <option value="None">--Select Vaccine--</option>
                                <option value="Johnson & Johnson">Johnson & Johnson</option>
                                <option value="AstraZeneca">AstraZeneca</option>
                                <option value="Pfizer">Pfizer</option>
                                <option value="Moderna">Moderna</option>
                                <option value="Sputnik">Sputnik</option>
                                <option value="Sinovac">Sinovac</option>
                            </select>
                        </div>

                        <div class="grid-row">
                            <select id="vaccination_type" name="vaccination_type" required>
                                <option value="None">--Select Vaccination Type--</option>
                                <option value="First Dose">1st dose</option>
                                <option value="Second Dose">2nd dose</option>
                            </select>
                        </div>

                        <div class="grid-row" style="margin-bottom: 1px;">
                            <label for="national_id">Vaccination Date:</label>
                        </div>
                        <div class="grid-row">
                            <input type="date" id="vaccination_date" name="vaccination_date">
                        </div>
                        <div>
                            <label for="national_id">Upload Your ID <span style="color:red">(Note: Include your address and date of birth)</span></label>
                            <br>
                            <input type="file" name="file"  accept="image/*" required>
                        </div>
                        <br>
                        <button type="submit" class="btn-submit">Submit</button>
                    </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

    <div id="viewResidentPopup" class="popup-view">
        <div class="popup-content-view">
            <div class="navss"> 
                <span class="close-button" onclick="closeViewPopup()">&times;</span>
                <h2>Resident Information</h2>
            </div>
            <div id="residentInfo" class="resident-info">
                <!-- Resident information will be displayed here -->
            </div>
        </div>
    </div>
   
    <div id="editPopupFormEdit" class="popup">
        <div class="popupform-container">
            <div class="popup-content">
                <div class="popup-contitle">
                <span class="close-button" onclick="closePopupForm('editPopupFormEdit')">&times;</span>
                <h2>Edit Resident</h2>
                    <form  id="edit_resident" enctype="multipart/form-data">
                    <input type="hidden" id="editAccountID" name="accountID">
                    <div class="grid-row">
                        <input type="text" id="national_id" name="national_id" placeholder="Enter Valid ID #" 
                        required pattern="[0-9]{6,20}" title="National ID should be 6-20 digits long and contain only numbers." 
                        inputmode="numeric" oninput="this.value = this.value.replace(/\D/g, '')">
                        <input type="text" name="house_no" id="house_no" placeholder="House No" required>
                    </div>

                    <div class="grid-row">
                        <input type="text" id="lastName" name="lastName" placeholder="Last Name" required oninput="this.value = this.value.toUpperCase()">
                        <select id="disability" name="disability" required>
                            <option value="" disabled selected>--Select Beneficiary ID--</option>
                            <option value="Person with Disability">Person with Disability</option>
                            <option value="Senior Citizen">Senior Citizen</option>
                            <option value="Solo Parent">Solo Parent</option>
                            <option value="N/A">N/A</option>
                        </select>
                    </div>
                    
                    <div class="grid-row">
                        <input type="text" id="firstName" name="firstName" placeholder="First Name" required oninput="this.value = this.value.toUpperCase()">
                        <select id="address" name="address" required>
                            <option value="" disabled selected>--Select Address--</option>
                            <option value="Purok 1">Purok 1</option>
                            <option value="Purok 2">Purok 2</option>
                            <option value="Purok 3">Purok 3</option>
                            <option value="Purok 4">Purok 4</option>
                            <option value="Purok 5">Purok 5</option>
                            <option value="Purok 6">Purok 6</option>
                            <option value="Purok 7">Purok 7</option>
                            <option value="St Joseph 7 Village">St. Joseph 7 Village</option>
                            <option value="Celestine Homes">Celestine Homes</option>
                            <option value="Maripaz">Maripaz</option>
                            <option value="Lynville">Lynville</option>
                        </select>
                    </div>
                    
                    <div class="grid-row">
                        <input type="text" id="middleName" name="middleName" placeholder="Middle Name" oninput="this.value = this.value.toUpperCase()">
                        <select id="religion" name="religion" required>
                        <option value="" disabled selected>--Select Religion--</option>
                        <option value="Roman Catholic">Roman Catholic</option>
                        <option value="Iglesia ni Cristo">Iglesia ni Cristo</option>
                        <option value="Islam">Islam</option>
                        <option value="Protestant">Protestant</option>
                        <option value="Buddhist">Buddhist</option>
                        <option value="Hindu">Hindu</option>
                        </select>
                    </div>

                    <div class="grid-row">
                        <select id="Suffix" name="Suffix" placeholder="suffix">
                            <option value="">--Select Suffix--</option>
                            <option value="JR.">JR.</option>
                            <option value="SR.">SR.</option>
                            <option value="I">I</option>
                            <option value="II">II</option>
                            <option value="III">III</option>
                            <option value="IV">IV</option>
                            <option value="V">V</option>
                            <option value="VI">VI</option>
                            <option value="VII">VII</option>
                            <option value="VIII">VIII</option>
                            <option value="IX">IX</option>
                            <option value="X">X</option>
                            <option value="XI">XI</option>
                            <option value="XII">XII</option>
                            <option value="XIII">XIII</option>
                            <option value="XIV">XIV</option>
                            <option value="XV">XV</option>
                            <option value="XVI">XVI</option>
                            <option value="XVII">XVII</option>
                            <option value="XVIII">XVIII</option>
                            <option value="XIX">XIX</option>
                            <option value="XX">XX</option>
                        </select>
                    </div>


                    <div class="grid-row">
                        <select id="gender" name="gender" required>
                            <option value="" disabled selected>--Select Gender--</option>
                            <option value="Male">Male</option>
                            <option value="Female">Female</option>
                        </select>

                        <select id="civil_status" name="civil_status" required>
                            <option value="" disabled selected>--Select Civil Status--</option>
                            <option value="Single">Single</option>
                            <option value="Married">Married</option>
                            <option value="Legally Seperated">Legally Seperated</option>
                            <option value="Widow/Widower">Widow/Widower</option>
                        </select>
                    </div>

                    <div class="grid-row">
                    <input type="text" id="birthplace" name="birthplace"  placeholder="Birthplace">
                    <input type="tel" id="contact_number" name="contact_number" placeholder="09XXXXXXXXX" required pattern="09[0-9]{9}" maxlength="11" title="Enter a valid 11-digit contact number starting with 09" inputmode="numeric" oninput="this.value = this.value.replace(/[^0-9]/g, '')">
                    </div>

                    <div class="grid-row" style="margin-bottom: 1px;">
                        <label for="national_id">Birth Date:</label>
                    </div>
                    <div class="grid-row">
                        <input type="date" id="birthdate" name="birthdate" class="birthdate"  onchange="updateAgeEdit('#edit_resident')" required>
                        <input type="text" id="age" name="age" class="age" placeholder="age" readonly>
                    </div>

                    <div class="grid-row">
                        <input type="text" id="occupation" name="occupation" placeholder="Occupation">
                        <input type="text" id="email" name="email" placeholder="jollyhotdog@gmail.com" required pattern="[a-zA-Z0-9._%+-]+@gmail\.com" title="Please enter a valid Gmail address">
                    </div>

                    <div class="grid-row">
                        <select id="education_attainment" name="education_attainment" required>
                            <option value="--Highest Educational Attainment--">--Select Highest Educational Attainment--</option>
                            <option value="Elementary Graduate">Elementary Graduate</option>
                            <option value="Firstyear Highschool">First year Highschool</option>
                            <option value="Second year Highschool">Second year Highschool</option>
                            <option value="Third year Highschool">Third year Highschool</option>
                            <option value="Fourth year Highschool">Fourth year Highschool</option>
                            <option value="First year College">First year College</option>
                            <option value="Second year College">Second year College</option>
                            <option value="Third year College">Third year College</option>
                            <option value="Fourth year College">Fourth year College</option>
                            <option value="College Graduate">College Graduate</option>
                        </select>
                    </div>

                    <div class="grid-row">
                        <select id="voter_status" name="voter_status" required>
                            <option value="" disabled selected>--Are you a voter in Marinig?--</option>
                            <option value="Yes">Yes</option>
                            <option value="No">No</option>
                        </select>
                    </div>
                    
                    <div class="grid-row">
                        <select id="vaccination_status" name="vaccination_status" required>
                            <option value="" disabled selected>--Select Vaccination Status--</option>
                            <option value="Yes">Vaccinated</option>
                            <option value="No">Not Vaccinated</option>
                        </select>
                    </div>

                    <div class="grid-row">
                        <select id="vaccine" name="vaccine" required>
                            <option value="None">--Select Vaccine--</option>
                            <option value="Johnson & Johnson">Johnson & Johnson</option>
                            <option value="AstraZeneca">AstraZeneca</option>
                            <option value="Pfizer">Pfizer</option>
                            <option value="Moderna">Moderna</option>
                            <option value="Sputnik">Sputnik</option>
                            <option value="Sinovac">Sinovac</option>
                        </select>
                    </div>

                    <div class="grid-row">
                        <select id="vaccination_type" name="vaccination_type" required>
                            <option value="None">--Select Vaccination Type--</option>
                            <option value="First Dose">1st dose</option>
                            <option value="Second Dose">2nd dose</option>
                        </select>
                    </div>

                    <div class="grid-row" style="margin-bottom: 1px;">
                        <label for="national_id">Vaccination Date:</label>
                    </div>
                    <div class="grid-row">
                        <input type="date" id="vaccination_date" name="vaccination_date">
                    </div>
                    <div>
                        <label for="national_id">Upload Your ID 
                            <span style="color:red">(Note: Include your address and date of birth)</span>
                            <div id="file_id_show"><a href="" id="id_link" target="_blank">(View ID)</a></div>
                        </label>
                        <input type="file" name="file" id="file_id" accept="image/*">
                    </div>
                    <br>
                    <button type="submit" class="btn-submit">Submit</button>
                </form>
                </div>
            </div>
        </div>
    </div>
    <script src="../js/script.js"></script>
    <script src="vaccination_search.js"></script>
    <script src="../js/resident.js" defer></script>
</body>
</html>

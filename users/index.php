<?php include 'header.php'; ?>
<?php include 'nav.php'; ?>

<?php

$accountID = $_SESSION['accountID'];
$sql = "SELECT * FROM resident_list WHERE accountID = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $accountID);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

// Handle form submission for editing account
if ($_SERVER["REQUEST_METHOD"] == "POST") {
 
        // Update user's information
        $updateSql = "UPDATE resident_list SET 
            lastName = ?, 
            firstName = ?, 
            middleName = ?, 
            Suffix = ?, 
            address = ?, 
            house_no = ?, 
            birthdate = ?, 
            age = ?, 
            gender = ?, 
            civil_status = ?, 
            birthplace = ?, 
            religion = ?, 
            email = ?, 
            contact_number = ?, 
            voter_status = ?, 
            education_attainment = ?, 
            occupation = ?, 
            disability = ?, 
            vaccination_status = ?, 
            vaccine = ?, 
            vaccination_type = ?, 
            vaccination_date = ?, 
            national_id = ?
            WHERE accountID = ?";
        $updateStmt = $conn->prepare($updateSql);
        $updateStmt->bind_param(
            "ssssssssssssssssssssssss",
            $_POST['lastName'],
            $_POST['firstName'],
            $_POST['middleName'],
            $_POST['Suffix'],
            $_POST['address'],
            $_POST['house_no'],
            $_POST['birthdate'],
            $_POST['age'],
            $_POST['gender'],
            $_POST['civil_status'],
            $_POST['birthplace'],
            $_POST['religion'],
            $_POST['email'],
            $_POST['contact_number'],
            $_POST['voter_status'],
            $_POST['education_attainment'],
            $_POST['occupation'],
            $_POST['disability'],
            $_POST['vaccination_status'],
            $_POST['vaccine'],
            $_POST['vaccination_type'],
            $_POST['vaccination_date'],
            $_POST['national_id'],
            $accountID
        );
        if ($updateStmt->execute()) {
            // Update session with new username
            $_SESSION['success'] = "Account updated successfully!";
            header("Location: index.php");
            exit();
        } else {
            $error = "Error updating account: " . $conn->error;
        }
}
?>

<div class="content">
    <div class="container-profile">
        <div class="icon-container">
            <i class="fa fa-user-circle"></i>
        </div>
        <h2>User Profile</h2>
        <?php if (isset($error)) : ?>
            <div class="error"><?php echo $error; ?></div>
        <?php endif; ?>
        <?php if (isset($success)) : ?>
            <div class="success"><?php echo $success; ?></div>
        <?php endif; ?>
        <div class="profile-info">
            <form action="index.php" method="post">

                <button type="button" onclick="openEditProfileModal()">See Information</button>
                <button type="button" onclick="openChangePasswordModal()">Change Password</button>
            </form>

        </div>
    </div>
</div>

    <!-- Edit Profile Modal -->
    <div id="edit-profile-modal" class="modal">
        <div class="modal-content">
            <span class="close" onclick="closeEditProfileModal()">&times;</span>
            <form id="edit-profile-form" action="index.php" method="post">
                <div class="input-container">
                    <div class="left">

                        <div class="inputs">

                            <label for="lastName">Last Name:</label>
                            <input type="text" id="lastName" name="lastName" placeholder="Last Name" required oninput="this.value = this.value.toUpperCase()" value="<?php echo htmlspecialchars($user['lastName']); ?> " readonly>
                        
                        </div>

                        <div class="inputs">

                            <label for="firstName">First Name:</label>
                            <input type="text" id="firstName" name="firstName" placeholder="First Name" required oninput="this.value = this.value.toUpperCase()" value="<?php echo htmlspecialchars($user['firstName']); ?>"readonly>
                        
                        </div>

                        <div class="inputs">

                            <label for="middleName">Middle Name:</label>
                            <input type="text" id="middleName" name="middleName" placeholder="Middle Name" required oninput="this.value = this.value.toUpperCase()" value="<?php echo htmlspecialchars($user['middleName']); ?>"readonly>

                            <!-- Repeat this for all other columns -->
                        </div>
                        
                        <div class="inputs">

                            <label for="Suffix">Suffix:</label>
                            <select id="Suffix" name="Suffix" disabled>
                                <option value="<?php echo htmlspecialchars($user['Suffix']); ?>"><?php echo htmlspecialchars($user['Suffix']); ?></option>
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

                        <div class="inputs">

                            <label for="address">Address:</label>
                            <select id="address" name="address" disabled>
                                <option value="<?php echo htmlspecialchars($user['address']); ?>"><?php echo htmlspecialchars($user['address']); ?></option>
                                <option value="Purok 1">Purok 1</option>
                                <option value="Purok 2">Purok 2</option>
                                <option value="Purok 3">Purok 3</option>
                                <option value="Purok 4">Purok 4</option>
                                <option value="Purok 5">Purok 5</option>
                                <option value="Purok 6">Purok 6</option>
                                <option value="Purok 7">Purok 7</option>
                                <option value="St Joseph 7 Village">St Joseph 7 Village</option>
                                <option value="Celestine Homes">Celestine Homes</option>
                                <option value="Maripaz">Maripaz</option>
                                <option value="Lynville">Lynville</option>
                            </select>
                        
                        </div>

                        <div class="inputs">

                            <label for="house_no">House Number:</label>
                            <input type="text" id="house_no" name="house_no" value="<?php echo htmlspecialchars($user['house_no']); ?>" readonly>
                        
                        </div>

                        <div class="inputs">

                            <label for="birthdate">Birthdate:</label>
                            <input type="date" id="birthdate" name="birthdate" required onchange="updateAge()" value="<?php echo htmlspecialchars($user['birthdate']); ?>" readonly>
                        
                        </div>

                        <div class="inputs">

                            <label for="age">Age:</label>
                            <input type="text" id="age" placeholder="age" readonly value="<?php echo htmlspecialchars($user['age']); ?>">
                        
                        </div>

                        <div class="inputs">

                            <label for="gender">Gender:</label>
                            <select id="gender" name="gender" disabled>
                                <option value="<?php echo htmlspecialchars($user['gender']); ?>"><?php echo htmlspecialchars($user['gender']); ?></option>
                                <option value="Male">Male</option>
                                <option value="Female">Female</option>
                            </select>

                        </div>
                        <div class="inputs">

                            <label for="civil_status">Civil Status:</label>
                            <select id="civil_status" name="civil_status" disabled>
                                <option value="<?php echo htmlspecialchars($user['civil_status']); ?>"><?php echo htmlspecialchars($user['civil_status']); ?></option>                        
                                <option value="Single">Single</option>
                                <option value="Married">Married</option>
                                <option value="Legally Seperated">Legally Seperated</option>
                                <option value="Widow/Widower">Widow/Widower</option>
                            </select>
                        
                        </div>

                    </div>

                    <div class="right">

                        <div class="inputs">

                            <label for="birthplace">Birthplace:</label>
                            <input type="text" id="birthplace" name="birthplace" value="<?php echo htmlspecialchars($user['birthplace']); ?>" readonly>
                        
                        </div>

                        <div class="inputs">

                            <label for="religion">Religion:</label>
                            <select id="religion" name="religion" disabled>
                                <option value="<?php echo htmlspecialchars($user['religion']); ?>"><?php echo htmlspecialchars($user['religion']); ?></option>                        
                                <option value="Roman Catholic">Roman Catholic</option>
                                <option value="Iglesia ni Cristo">Iglesia ni Cristo</option>
                                <option value="Islam">Islam</option>
                                <option value="Protestant">Protestant</option>
                                <option value="Buddhist">Buddhist</option>
                                <option value="Hindu">Hindu</option>
                            </select>

                        </div>

                        <div class="inputs">

                            <label for="email">Email:</label>
                            <input type="text" id="email" name="email" placeholder="jollyhotdog@gmail.com" required pattern="[a-zA-Z0-9._%+-]+@gmail\.com" title="Please enter a valid Gmail address" value="<?php echo htmlspecialchars($user['email']); ?>" readonly>
                        
                        </div>

                        <div class="inputs">

                            <label for="contact_number">Contact Number:</label>
                            <input type="tel" id="contact_number" name="contact_number" placeholder="09XXXXXXXXX" required pattern="09[0-9]{9}" maxlength="11" title="Enter a valid 11-digit contact number starting with 09" value="<?php echo htmlspecialchars($user['contact_number']); ?>" readonly>
                        
                        </div>

                        <div class="inputs">

                            <label for="voter_status">Voter Status:</label>
                            <select id="voter_status" name="voter_status" disabled>
                                <option value="<?php echo htmlspecialchars($user['voter_status']); ?>"><?php echo htmlspecialchars($user['voter_status']); ?></option>                        
                                <option value="Yes">Yes</option>
                                <option value="No">No</option>
                            </select>
                        
                        </div>

                        <div class="inputs">

                            <label for="education_attainment">Highest Educational Attainment:</label>
                            <select id="education_attainment" name="education_attainment" disabled>
                                <option value="<?php echo htmlspecialchars($user['education_attainment']); ?>"><?php echo htmlspecialchars($user['education_attainment']); ?></option>                        
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

                        <div class="inputs">

                            <label for="occupation">Occupation:</label>
                            <input type="text" id="occupation" name="occupation" value="<?php echo htmlspecialchars($user['occupation']); ?>" readonly>
                        
                        </div>

                        <div class="inputs">

                            <label for="disability">Beneficiary ID:</label>
                            <select id="disability" name="disability" disabled>
                                <option value="<?php echo htmlspecialchars($user['disability']); ?>"><?php echo htmlspecialchars($user['disability']); ?></option>                        
                                <option value="Person with Disability">Person with Disability</option>
                                <option value="Senior Citizen">Senior Citizen</option>
                                <option value="Solo Parent">Solo Parent</option>
                                <option value="N/A">N/A</option>
                            </select>
                        
                        </div>

                        <div class="inputs">

                            <label for="vaccination_status">Vaccination Status:</label>
                            <select id="vaccination_status" name="vaccination_status" disabled>
                                <option value="<?php echo htmlspecialchars($user['vaccination_status']); ?>"><?php echo htmlspecialchars($user['vaccination_status']); ?></option>                        
                                <option value="Yes">Yes</option>
                                <option value="No">No</option>
                        </select>
                        
                        </div>

                        <div class="inputs">

                            <label for="vaccine">Vaccine:</label>
                            <select id="vaccine" name="vaccine" disabled>
                                <option value="<?php echo htmlspecialchars($user['vaccine']); ?>"><?php echo htmlspecialchars($user['vaccine']); ?></option>                        
                                <option value="Johnson & Johnson">Johnson & Johnson</option>
                                <option value="AstraZeneca">AstraZeneca</option>
                                <option value="Pfizer">Pfizer</option>
                                <option value="Moderna">Moderna</option>
                                <option value="Sputnik">Sputnik</option>
                                <option value="Sinovac">Sinovac</option>
                            </select>
                        
                        </div>
                        
                        <div class="inputs">

                            <label for="vaccination_type">Vaccination Type:</label>
                            <select id="vaccination_type" name="vaccination_type" disabled>
                                <option value="<?php echo htmlspecialchars($user['vaccination_type']); ?>"><?php echo htmlspecialchars($user['vaccination_type']); ?></option>                        
                                <option value="First Dose">1st dose</option>
                                <option value="Second Dose">2nd dose</option>
                            </select>

                        </div>
                        
                        <div class="inputs">

                            <label for="vaccination_date">Vaccination Date:</label>
                            <input type="date" id="vaccination_date" name="vaccination_date" value="<?php echo htmlspecialchars($user['vaccination_date']); ?>" readonly>
                        
                        </div>

                        <div class="inputs">

                            <label for="national_id">Valid ID #:</label>
                            <input type="text" id="national_id" name="national_id" placeholder="Enter National ID" required pattern="[A-Za-z0-9]{6,20}" title="National ID should be 6-20 characters long and contain only letters and numbers." value="<?php echo htmlspecialchars($user['national_id']); ?>" readonly>
                        
                        </div>
                    </div>
                </div>
<!-- 
                <button type="submit">Save Changes</button> -->
            </form>
        </div>
    </div>
    <!-- Change Password Modal -->
    <div id="change-password-modal" class="modal">
        <div class="modal-content">
            <span class="close" onclick="closeChangePasswordModal()">&times;</span>
            <div class="container-profile">
                <h2>Change Password</h2>
                <?php if (isset($_GET['error'])) : ?>
                    <div class="error"><?php echo htmlspecialchars($_GET['error']); ?></div>
                <?php endif; ?>
                <?php if (isset($_GET['success'])) : ?>
                    <div class="success"><?php echo htmlspecialchars($_GET['success']); ?></div>
                <?php endif; ?>
                <form action="change_password.php" method="post">
                    <div class="inputs">
                        <label for="current_password">Current Password:</label>
                        <input type="password" id="current_password" name="current_password" required>
                    </div>
                    <div class="inputs">
                        <label for="new_password">New Password:</label>
                        <input type="password" id="new_password" name="new_password" minlength="8" required>
                    </div>
                    <div class="inputs">
                        <label for="confirm_new_password">Confirm New Password:</label>
                        <input type="password" id="confirm_new_password" name="confirm_new_password" minlength="8" required>
                    </div>
                    <button type="submit">Change Password</button>
                </form>
            </div>
        </div>
    </div>

    <script type="text/javascript" src="../js/toastify.min.js"></script>                  
    <script>
        // Function to display Edit Profile modal
        function openEditProfileModal() {
            var modal = document.getElementById("edit-profile-modal");
            modal.style.display = "block";
        }

        // Function to close Edit Profile modal
        function closeEditProfileModal() {
            var modal = document.getElementById("edit-profile-modal");
            modal.style.display = "none";
        }

        // Close the modal if user clicks outside of it
        window.onclick = function(event) {
            var modals = document.getElementsByClassName('modal');
            for (var i = 0; i < modals.length; i++) {
                var modal = modals[i];
                if (event.target == modal) {
                    modal.style.display = "none";
                }
            }
        }

        // Function to display Change Password modal
        function openChangePasswordModal() {
            var modal = document.getElementById("change-password-modal");
            modal.style.display = "block";
        }

        // Function to close Change Password modal
        function closeChangePasswordModal() {
            var modal = document.getElementById("change-password-modal");
            modal.style.display = "none";
        }

        window.onclick = function(event) {
            if (event.target == document.getElementById("edit-profile-modal")) {
                closeEditProfileModal();
            }
        }

        window.onclick = function(event) {
            if (event.target == document.getElementById("edit-profile-modal")) {
                closeEditProfileModal();
            }
        }

        window.onload = function() {
            <?php if (isset($_SESSION['success'])) : ?>
                alert("<?php echo $_SESSION['success'];
                        unset($_SESSION['success']); ?>");
            <?php endif; ?>
        }
    </script>
<?php include 'footer.php'; ?>
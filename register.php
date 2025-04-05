<?php 
require_once 'connection/connect.php';
$query1 = "SELECT * FROM purok ORDER BY `name`";
$result1 = $conn->query($query1);
$purok = array();
while($row = $result1->fetch_assoc()){
    $purok[] = $row; 
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    <link rel="stylesheet" type="text/css" href="css/register.css">
    <script src="js/register.js" defer></script>
</head>

<body>
    <div class="register-form">
        <form  id="registerForm" enctype="multipart/form-data">
            <h2>Registration Form</h2>
            <label for="">Fill out the form carefully for registration.</label>
            <div class="inputs">

                <div class="input1" id="input1">
                    <div class="right">

                    <label for="lastName">Last Name:</label>
                    <input type="text" id="lastName" name="lastName" placeholder="Last Name" required oninput="this.value = this.value.toUpperCase()">

                    <label for="firstName">First Name:</label>
                    <input type="text" id="firstName" name="firstName" placeholder="First Name" required oninput="this.value = this.value.toUpperCase()">

                    <label for="middleName">Middle Name:</label>
                    <input type="text" id="middleName" name="middleName" placeholder="Middle Name" required oninput="this.value = this.value.toUpperCase()">

                    <label for="Suffix">Suffix:</label>
                    <select id="Suffix" name="Suffix" placeholder="Suffix">
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


                    <label for="national_id">Input Valid ID #:</label>
                    <input type="text" id="national_id" name="national_id" placeholder="Enter Valid ID" 
                    required pattern="[0-9]{6,20}" title="National ID should be 6-20 digits long and contain only numbers." 
                    inputmode="numeric" oninput="this.value = this.value.replace(/\D/g, '')">

                    <label for="national_id">Upload Your ID <span style="color:red">(Note: Include your address and date of birth)</span></label>
                    <input type="file" name="file"  accept="image/*" required>

                    </div>

                    <div class="left">
                        <label for="birthdate">Birthdate:</label>
                        <input type="date" id="birthdate" name="birthdate" required onchange="updateAge()">
                        
                        <label for="age">Age:</label>
                        <input type="text" id="age" name="age" placeholder="age" readonly>

                        <label for="birthplace">Birthplace:</label>
                        <input type="text" id="birthplace" name="birthplace" placeholder="Birthplace" required>

                        <label for="gender">Gender:</label>
                        <select id="gender" name="gender" required>
                            <option value="" disabled selected>--Select Gender--</option>
                            <option value="Male">Male</option>
                            <option value="Female">Female</option>
                        </select>


                        <label for="religion">Religion:</label>
                        <select id="religion" name="religion" required>
                            <option value="" disabled selected>--Select Religion--</option>
                            <option value="Roman Catholic">Roman Catholic</option>
                            <option value="Iglesia ni Cristo">Iglesia ni Cristo</option>
                            <option value="Islam">Islam</option>
                            <option value="Protestant">Protestant</option>
                            <option value="Buddhist">Buddhist</option>
                            <option value="Hindu">Hindu</option>
                        </select>


                        <label for="address">Purok:</label>
                        <select id="address" name="address" required>
                            <option value="" disabled selected>--Select Purok--</option>
                            <?php foreach($purok as $row):?>
                                <option value="<?= ucwords($row['name']) ?>"><?= $row['name'] ?></option>
                            <?php endforeach ?>
                        </select>

                    </div>
                </div>

                <div class="input2" id="input2">
                    <div class="right">
                        <label for="house_no">House No:</label>
                        <input type="text" id="house_no" name="house_no" placeholder="House No#" required>

                        <label for="civil_status">Civil Status:</label>
                        <select id="civil_status" name="civil_status" required>
                            <option value="" disabled selected>--Select Civil Status--</option>
                            <option value="Single">Single</option>
                            <option value="Married">Married</option>
                            <option value="Legally Seperated">Legally Seperated</option>
                            <option value="Widow/Widower">Widow/Widower</option>
                        </select>

                        <label for="email">Email:</label>
                        <input type="text" id="email" name="email" placeholder="jollyhotdog@gmail.com" required pattern="[a-zA-Z0-9._%+-]+@gmail\.com" title="Please enter a valid Gmail address">
                        
                        <label for="contact_number">Contact Number:</label>
                        <input type="text" id="contact_number" name="contact_number" placeholder="09XXXXXXXXX" required pattern="09[0-9]{9}" maxlength="11" title="Enter a valid 11-digit contact number starting with 09" inputmode="numeric" oninput="this.value = this.value.replace(/[^0-9]/g, '')">

                        <label for="voter_status">Voter Status:</label>
                        <select id="voter_status" name="voter_status" required>
                            <option value="" disabled selected>--Are you a voter in mamatid?--</option>
                            <option value="yes">Yes</option>
                            <option value="No">No</option>
                        </select>

                    </div>

                    <div class="left">

                        <label for="education_attainment">Highest Educational Attainment:</label>
                        <select id="education_attainment" name="education_attainment" required>
                        <option value="" disabled selected>--Select Highest Educational Attainment--</option>
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

                        <label for="occupation">Occupation:</label>
                        <input type="text" id="occupation" name="occupation" placeholder="Occupation" required>
                        <label for="disability">Beneficiary ID:</label>
                        <select id="disability" name="disability" required>
                        <option value="" disabled selected>--Select Beneficiary ID--</option>
                        <option value="Person with Disability">Person with Disability</option>
                        <option value="Senior Citizen">Senior Citizen</option>
                        <option value="Solo Parent">Solo Parent</option>
                        <option value="N/A">N/A</option>
                        </select>

                        <label for="vaccination_status">Vaccination Status:</label>
                        <select id="vaccination_status" name="vaccination_status" required>
                            <option value="" disabled selected>--Select Vaccination Status--</option>
                            <option value="Yes">Yes</option>
                            <option value="No">No</option>
                        </select>

                        <label for="vaccine">Vaccine:</label>
                        <select id="vaccine" name="vaccine" required>
                            <option value="None">None</option>
                            <option value="Johnson & Johnson">Johnson & Johnson</option>
                            <option value="AstraZeneca">AstraZeneca</option>
                            <option value="Pfizer">Pfizer</option>
                            <option value="Moderna">Moderna</option>
                            <option value="Sputnik">Sputnik</option>
                            <option value="Sinovac">Sinovac</option>
                        </select>

                        <label for="vaccination_type">Vaccination Type:</label>
                        <select id="vaccination_type" name="vaccination_type" required>
                            <option value="None">None</option>
                            <option value="First Dose">1st dose</option>
                            <option value="Second Dose">2nd dose</option>
                        </select>

                        <label for="vaccination_date">Vaccination Date:</label>
                        <input type="date" id="vaccination_date" name="vaccination_date">
                        
                    </div>
                </div>
            </div>
            <div class="footer">
                <p>Already have an account? <a href="index.php">Login</a></p>
                <div id="navigationButtons">
                    <input type="submit" value="Register">
                </div>
            </div>
        </form>
    </div>
</body>
</html>
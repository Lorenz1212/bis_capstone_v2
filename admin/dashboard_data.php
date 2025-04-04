<?php

include '../connection/connect.php';

// Define variables to hold success and error messages
$successMessage = '';
$errorMessage = '';

// Calculate total count for each category
$totalResidentsQuery = "SELECT COUNT(*) AS totalResidents FROM resident_list";
$totalMaleResidentsQuery = "SELECT COUNT(*) AS totalMaleResidents FROM resident_list WHERE gender = 'Male'";
$totalFemaleResidentsQuery = "SELECT COUNT(*) AS totalFemaleResidents FROM resident_list WHERE gender = 'Female'";
$totalVotersQuery = "SELECT COUNT(*) AS totalVoters FROM resident_list WHERE voter_status = 'Yes'";
$totalVaccinatedQuery = "SELECT COUNT(*) AS totalVaccinated FROM resident_list WHERE vaccination_status = 'Yes'";

$totalResidentsResult = mysqli_query($conn, $totalResidentsQuery);
$totalMaleResidentsResult = mysqli_query($conn, $totalMaleResidentsQuery);
$totalFemaleResidentsResult = mysqli_query($conn, $totalFemaleResidentsQuery);
$totalVotersResult = mysqli_query($conn, $totalVotersQuery);
$totalVaccinatedResult = mysqli_query($conn, $totalVaccinatedQuery);


// Fetch counts from the result
$totalResidentsCount = mysqli_fetch_assoc($totalResidentsResult)['totalResidents'];
$totalMaleResidentsCount = mysqli_fetch_assoc($totalMaleResidentsResult)['totalMaleResidents'];
$totalFemaleResidentsCount = mysqli_fetch_assoc($totalFemaleResidentsResult)['totalFemaleResidents'];
$totalVotersCount = mysqli_fetch_assoc($totalVotersResult)['totalVoters'];
$totalVaccinatedCount = mysqli_fetch_assoc($totalVaccinatedResult)['totalVaccinated'];

// Calculate total overall count
$totalOverallCount = $totalResidentsCount;

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Resident Information</title>
    <link rel="stylesheet" type="text/css" href="../css/resident_table.css"> 
</head>
<body>

<div class="dashboard">
<div class="dashboard-box">
        <h3>Total Vaccinated Residents</h3>
        <p><?php echo $totalVaccinatedCount; ?></p>
    </div>
    <div class="dashboard-box">
        <h3>Total Male Residents</h3>
        <p><?php echo $totalMaleResidentsCount; ?></p>
    </div>
    <div class="dashboard-box">
        <h3>Total Female Residents</h3>
        <p><?php echo $totalFemaleResidentsCount; ?></p>
    </div>
    <div class="dashboard-box">
        <h3>Total Voters</h3>
        <p><?php echo $totalVotersCount; ?></p>
    </div>
    <div class="dashboard-box">
        <h3>Total Residents</h3>
        <p><?php echo $totalOverallCount; ?></p>
    </div>
</div>

</body>
</html>

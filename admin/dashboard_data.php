<?php
$successMessage = '';
$errorMessage = '';

// Calculate total count for each category
$totalResidentsQuery = "SELECT COUNT(*) AS totalResidents FROM resident_list WHERE registration_status='APPROVED'";
$totalMaleResidentsQuery = "SELECT COUNT(*) AS totalMaleResidents FROM resident_list WHERE gender = 'Male' AND registration_status='APPROVED'";
$totalFemaleResidentsQuery = "SELECT COUNT(*) AS totalFemaleResidents FROM resident_list WHERE gender = 'Female' AND registration_status='APPROVED'";
$totalVotersQuery = "SELECT COUNT(*) AS totalVoters FROM resident_list WHERE voter_status = 'Yes' AND registration_status='APPROVED'";
$totalVaccinatedQuery = "SELECT COUNT(*) AS totalVaccinated FROM resident_list WHERE vaccination_status = 'Yes' AND registration_status='APPROVED'";


$totalbarangayclearance = "SELECT COUNT(*) AS total FROM request WHERE Type = 'Brgy clearance' AND status='APPROVED'";
$totalbusinessclearance = "SELECT COUNT(*) AS total FROM request WHERE Type = 'Business Clearance' AND status='APPROVED'";
$totalbrgycert = "SELECT COUNT(*) AS total FROM request WHERE Type = 'Barangay Certificate' AND status='APPROVED'";
$totalindigency = "SELECT COUNT(*) AS total FROM request WHERE Type = 'Certificate of Indigency' AND status='APPROVED'";
$totalcedula = "SELECT COUNT(*) AS total FROM request WHERE Type = 'Cedula' AND status='APPROVED'";

$totalblotter = "SELECT COUNT(*) AS total FROM blotter";

$totalResidentsResult = mysqli_query($conn, $totalResidentsQuery);
$totalMaleResidentsResult = mysqli_query($conn, $totalMaleResidentsQuery);
$totalFemaleResidentsResult = mysqli_query($conn, $totalFemaleResidentsQuery);
$totalVotersResult = mysqli_query($conn, $totalVotersQuery);
$totalVaccinatedResult = mysqli_query($conn, $totalVaccinatedQuery);

$totalbarangayclearanceResult = mysqli_query($conn, $totalbarangayclearance);
$totalbusinessclearanceResult = mysqli_query($conn, $totalbusinessclearance);
$totalbrgycertResult = mysqli_query($conn, $totalbrgycert);
$totalindigencyResult = mysqli_query($conn, $totalindigency);
$totalcedulaResult = mysqli_query($conn, $totalcedula);

$totalblotterResult = mysqli_query($conn, $totalblotter);


// Fetch counts from the result
$totalResidentsCount = mysqli_fetch_assoc($totalResidentsResult)['totalResidents'];
$totalMaleResidentsCount = mysqli_fetch_assoc($totalMaleResidentsResult)['totalMaleResidents'];
$totalFemaleResidentsCount = mysqli_fetch_assoc($totalFemaleResidentsResult)['totalFemaleResidents'];
$totalVotersCount = mysqli_fetch_assoc($totalVotersResult)['totalVoters'];
$totalVaccinatedCount = mysqli_fetch_assoc($totalVaccinatedResult)['totalVaccinated'];

$totalbarangayclearance = mysqli_fetch_assoc($totalbarangayclearanceResult)['total'];
$totalbusinessclearance = mysqli_fetch_assoc($totalbusinessclearanceResult)['total'];
$totalbrgycert = mysqli_fetch_assoc($totalbrgycertResult)['total'];
$totalindigency = mysqli_fetch_assoc($totalindigencyResult)['total'];
$totalcedula = mysqli_fetch_assoc($totalcedulaResult)['total'];

$totalblotter = mysqli_fetch_assoc($totalblotterResult)['total'];

// Calculate total overall count
$totalOverallCount = $totalResidentsCount;
?>
<div class="row g-3 mb-5">
        <!-- Total Residents -->
        <div class="col-md-3">
            <div class="card text-white bg-primary shadow">
                <div class="card-body d-flex align-items-center">
                    <i class="fa-solid fa-users fa-2x me-3"></i>
                    <div>
                        <h5 class="card-title">Total Residents</h5>
                        <h3><?php echo $totalResidentsCount; ?></h3>
                    </div>
                </div>
            </div>
        </div>

        <!-- Total Male Residents -->
        <div class="col-md-3">
            <div class="card text-white bg-info shadow">
                <div class="card-body d-flex align-items-center">
                    <i class="fa-solid fa-mars fa-2x me-3"></i>
                    <div>
                        <h5 class="card-title">Male Residents</h5>
                        <h3><?php echo $totalMaleResidentsCount; ?></h3>
                    </div>
                </div>
            </div>
        </div>

        <!-- Total Female Residents -->
        <div class="col-md-3">
            <div class="card text-white bg-danger shadow">
                <div class="card-body d-flex align-items-center">
                    <i class="fa-solid fa-venus fa-2x me-3"></i>
                    <div>
                        <h5 class="card-title">Female Residents</h5>
                        <h3><?php echo $totalFemaleResidentsCount; ?></h3>
                    </div>
                </div>
            </div>
        </div>

        <!-- Total Voters -->
        <div class="col-md-3">
            <div class="card text-white bg-warning shadow">
                <div class="card-body d-flex align-items-center">
                    <i class="fa-solid fa-check-to-slot fa-2x me-3"></i>
                    <div>
                        <h5 class="card-title">Total Voters</h5>
                        <h3><?php echo $totalVotersCount; ?></h3>
                    </div>
                </div>
            </div>
        </div>

        <!-- Total Vaccinated Residents -->
        <!-- <div class="col-md-2">
            <div class="card text-white bg-warning shadow">
                <div class="card-body d-flex align-items-center">
                    <i class="fa-solid fa-syringe fa-2x me-3"></i>
                    <div>
                        <h5 class="card-title">Vaccinated Residents</h5>
                        <h3><?php echo $totalVaccinatedCount; ?></h3>
                    </div>
                </div>
            </div>
        </div> -->
        <div class="col-md-2">
            <div class="card text-white bg-success shadow">
                <div class="card-body d-flex align-items-center">
                    <i class="fa-solid fa-file fa-2x me-3"></i>
                    <div>
                        <h5 class="card-title">Barangay Clearance</h5>
                        <h3><?php echo $totalbarangayclearance; ?></h3>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-2">
            <div class="card text-white bg-success shadow">
                <div class="card-body d-flex align-items-center">
                    <i class="fa-solid fa-file fa-2x me-3"></i>
                    <div>
                        <h5 class="card-title">Business Clearance</h5>
                        <h3><?php echo $totalbusinessclearance; ?></h3>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-2">
            <div class="card text-white bg-success shadow">
                <div class="card-body d-flex align-items-center">
                    <i class="fa-solid fa-file fa-2x me-3"></i>
                    <div>
                        <h5 class="card-title">Barangay Certificate</h5>
                        <h3><?php echo $totalbrgycert; ?></h3>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-2">
            <div class="card text-white bg-success shadow">
                <div class="card-body d-flex align-items-center">
                    <i class="fa-solid fa-file fa-2x me-3"></i>
                    <div>
                        <h5 class="card-title">Certificate Of Indigency</h5>
                        <h3><?php echo $totalindigency; ?></h3>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-2">
            <div class="card text-white bg-success shadow">
                <div class="card-body d-flex align-items-center">
                    <i class="fa-solid fa-file fa-2x me-3"></i>
                    <div>
                        <h5 class="card-title">Cedula</h5>
                        <h3><?php echo $totalcedula; ?></h3>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-2">
            <div class="card text-white bg-success shadow">
                <div class="card-body d-flex align-items-center">
                    <i class="fa-solid fa-file fa-2x me-3"></i>
                    <div>
                        <h5 class="card-title">
                            Blotter Report
                        </h5>
                        <h3><?php echo $totalblotter; ?></h3>
                    </div>
                </div>
            </div>
        </div>
    </div>


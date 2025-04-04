<?php
$vaccinationQuery = "SELECT * FROM resident_list";
$vaccinationResult = mysqli_query($conn, $vaccinationQuery);

if (mysqli_num_rows($vaccinationResult) > 0) {
    while ($row = mysqli_fetch_assoc($vaccinationResult)) {
        echo "<tr>";
        echo "<td>" . $row['accountID'] . "</td>";
        echo "<td>" . $row['national_id'] . "</td>";
        echo "<td>" . $row['lastName'] . "</td>";
        echo "<td>" . $row['firstName'] . "</td>";
        echo "<td>" . $row['age'] . "</td>"; // Assuming 'age' is a column in your database table
        echo "<td>" . $row['gender'] . "</td>"; // Assuming 'gender' is a column in your database table
        echo "<td>" . $row['birthdate'] . "</td>"; // Assuming 'birthdate' is a column in your database table
        echo "<td>" . $row['vaccination_status'] . "</td>";
        echo "<td>" . $row['vaccine'] . "</td>";
        echo "<td>" . $row['vaccination_type'] . "</td>"; // Display the vaccination_type column
        echo "<td>" . $row['vaccination_date'] . "</td>"; // Display the vaccination_date column
        // Combine view and edit buttons into the action column
        echo "<td class='button-group'>";
        echo "<button  class='btn btn-primary action-button' onclick='viewVaccinationDetails(this)'><i class='fas fa-eye'></i></button>";
        echo "</td>";
        echo "</tr>";
    }
} else {
    echo "<tr><td colspan='12'>No individuals found</td></tr>";
}
?>


<div class="container">
    <?php
    $sql = "SELECT * FROM resident_list where registration_status ='DISAPPROVED'";

    // Execute the query
    $result = $conn->query($sql);
    ?>
    <?php
    // Check if there are any rows returned
    if ($result->num_rows > 0) {
        // Output table header
        echo "<table class='table table-bordered' border='1' id='residentTableRejected'>
                <tr>
                    <th class='left'>Account ID</th>
                     <th class='left'>Valid ID #</th>
                    <th class='left'>Last Name</th>
                    <th class='left'>First Name</th>
                    <th class='left'>Middle Name</th>
                    <th class='left'>Suffix</th>
                    <th class='left'>HouseNo</th>
                    
                    <th class='left'>Address</th>
                    <th class='left'>Birthdate</th>
                    <th class='left'>Age</th>
                    <th class='left'>Gender</th>
                    <th class='left'>Civil Status</th>
                    <th class='center'>Actions</th> 
                </tr>";
                
        // Output data of each row
        while ($row = $result->fetch_assoc()) {
            $data = json_encode($row);
            echo "<tr>
                    <td class='left'>" . $row["accountID"] . "</td>
                    <td class='left'>" . $row["national_id"] . "</td>
                    <td class='left'>" . $row["lastName"] . "</td>
                    <td class='left'>" . $row["firstName"] . "</td>
                    <td class='left'>" . $row["middleName"] . "</td>
                    <td class='left'>" . $row["Suffix"] . "</td>
                    <td class='left'>" . $row["house_no"] . "</td>
                    <td class='left'>" . $row["address"] . "</td>
                    <td class='left'>" . $row["birthdate"] . "</td>
                    <td class='left'>" . $row["age"] . "</td>
                    <td class='left'>" . $row["gender"] . "</td>
                    <td class='left'>" . $row["civil_status"] . "</td>
                    <td class='center'>  
                     <a href='delete_resident.php?id=" . $row["accountID"] . "' 
                        onclick='return confirm(\"Are you sure you want to delete this resident?\");'>
                            <i class='fas fa-trash-alt'></i>
                        </a> | 
                    <a href='#' onclick='viewResidentInfo($data)'><i class='fas fa-eye'></i></a>
                </td>
                
                </tr>";
        }
        echo "</table>";

    } else {
        echo "0 results";
    }

    // Close the database connection
    ?>
</div>

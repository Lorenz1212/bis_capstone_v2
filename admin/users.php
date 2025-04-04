<?php
include 'user_navbar.php';
?>

<div class="container mt-4">
    <h2 class="mb-3">User Accounts Management</h2>
    <div class="nav-container">
        <div class="search">
            <div class="input-group mb-3">
                <input type="text" id="myInput" class="form-control" placeholder="Search users..." oninput="searchTable()">
                <button class="btn btn-outline-secondary"><i class="fa fa-search"></i></button>
            </div>
        </div>
        <div class="d-flex justify-content-end mb-3">
            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addUserModal">
                <i class="fas fa-plus"></i> Add New User
            </button>
        </div>
        
        <div class="table-responsive" style="max-height: 600px; overflow-y: auto;">
            <table class="table table-bordered table-hover" id="userTable">
                <thead class="table-dark">
                    <tr>
                        <th>ID</th>
                        <th>Username</th>
                        <th>Full Name</th>
                        <th>Email</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    // Fetch all admin users
                    $sql = "SELECT * FROM admin ORDER BY lname, fname";
                    $result = $conn->query($sql);

                    if ($result->num_rows > 0) {
                        while ($row = $result->fetch_assoc()) {
                            $fullName = $row['lname'] . ', ' . $row['fname'] . 
                                       (!empty($row['mname']) ? ' ' . substr($row['mname'], 0, 1) . '.' : '');
                            
                            echo "<tr>";
                            echo "<td>" . $row['accountID'] . "</td>";
                            echo "<td>" . htmlspecialchars($row['username']) . "</td>";
                            echo "<td>" . htmlspecialchars($fullName) . "</td>";
                            echo "<td>" . htmlspecialchars($row['email']) . "</td>";
                            echo "<td class='action-icons'>";
                            echo "<a href='#' onclick='openEditModal(" . htmlspecialchars(json_encode($row), ENT_QUOTES) . ")' class='btn btn-sm btn-warning me-1'><i class='fas fa-edit'></i></a>";
                            echo "<a href='#' onclick='confirmDelete(" . $row['accountID'] . ")' class='btn btn-sm btn-danger'><i class='fas fa-trash'></i></a>";
                            echo "</td>";
                            echo "</tr>";
                        }
                    } else {
                        echo "<tr><td colspan='5'>No users found</td></tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Add User Modal -->
<div class="modal fade" id="addUserModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Add New User</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="addUserForm" novalidate>
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label">First Name <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="fname" required
                                   pattern="[A-Za-z\s]{2,50}" 
                                   title="First name should be 2-50 alphabetic characters">
                            <div class="invalid-feedback">Please enter a valid first name (2-50 alphabetic characters)</div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Last Name <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="lname" required
                                   pattern="[A-Za-z\s]{2,50}"
                                   title="Last name should be 2-50 alphabetic characters">
                            <div class="invalid-feedback">Please enter a valid last name (2-50 alphabetic characters)</div>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Middle Name</label>
                        <input type="text" class="form-control" name="mname"
                               pattern="[A-Za-z\s]{0,50}"
                               title="Middle name should be up to 50 alphabetic characters">
                        <div class="invalid-feedback">Middle name should be alphabetic only (max 50 characters)</div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Username <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" name="username" required
                               pattern="[A-Za-z0-9_]{4,20}"
                               title="Username should be 4-20 characters (letters, numbers, underscore)">
                        <div class="invalid-feedback">Username must be 4-20 characters (letters, numbers, underscore only)</div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Email <span class="text-danger">*</span></label>
                        <input type="email" class="form-control" name="email" required
                               pattern="[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,}$">
                        <div class="invalid-feedback">Please enter a valid email address</div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Password <span class="text-danger">*</span></label>
                        <input type="password" class="form-control" name="password" required
                               pattern="^(?=.*[A-Za-z])(?=.*\d)[A-Za-z\d@$!%*#?&]{8,}$"
                               title="Password must be at least 8 characters with at least one letter and one number">
                        <div class="invalid-feedback">Password must be at least 8 characters with at least one letter and one number</div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Confirm Password <span class="text-danger">*</span></label>
                        <input type="password" class="form-control" name="confirm_password" required
                               oninput="checkPasswordMatch(this)">
                        <div class="invalid-feedback">Passwords must match</div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" onclick="validateAndAddUser()">Save User</button>
            </div>
        </div>
    </div>
</div>

<!-- Edit User Modal -->
<div class="modal fade" id="editUserModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Edit User</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="editUserForm" novalidate>
                    <input type="hidden" name="accountID" id="editAccountID">
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label">First Name <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="fname" id="editFname" required
                                   pattern="[A-Za-z\s]{2,50}">
                            <div class="invalid-feedback">Please enter a valid first name (2-50 alphabetic characters)</div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Last Name <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="lname" id="editLname" required
                                   pattern="[A-Za-z\s]{2,50}">
                            <div class="invalid-feedback">Please enter a valid last name (2-50 alphabetic characters)</div>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Middle Name</label>
                        <input type="text" class="form-control" name="mname" id="editMname"
                               pattern="[A-Za-z\s]{0,50}">
                        <div class="invalid-feedback">Middle name should be alphabetic only (max 50 characters)</div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Username <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" name="username" id="editUsername" required
                               pattern="[A-Za-z0-9_]{4,20}">
                        <div class="invalid-feedback">Username must be 4-20 characters (letters, numbers, underscore only)</div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Email <span class="text-danger">*</span></label>
                        <input type="email" class="form-control" name="email" id="editEmail" required
                               pattern="[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,}$">
                        <div class="invalid-feedback">Please enter a valid email address</div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">New Password (leave blank to keep current)</label>
                        <input type="password" class="form-control" name="password"
                               pattern="^(?=.*[A-Za-z])(?=.*\d)[A-Za-z\d@$!%*#?&]{8,}$"
                               title="Password must be at least 8 characters with at least one letter and one number">
                        <div class="invalid-feedback">Password must be at least 8 characters with at least one letter and one number</div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Confirm Password</label>
                        <input type="password" class="form-control" name="confirm_password"
                               oninput="checkEditPasswordMatch(this)">
                        <div class="invalid-feedback">Passwords must match</div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" onclick="validateAndUpdateUser()">Update User</button>
            </div>
        </div>
    </div>
</div>

<script>
// Search function
function searchTable() {
    const input = document.getElementById("myInput");
    const filter = input.value.toUpperCase();
    const table = document.getElementById("userTable");
    const tr = table.getElementsByTagName("tr");

    for (let i = 1; i < tr.length; i++) {
        const tdUsername = tr[i].getElementsByTagName("td")[1];
        const tdName = tr[i].getElementsByTagName("td")[2];
        const tdEmail = tr[i].getElementsByTagName("td")[3];
        
        if (tdUsername || tdName || tdEmail) {
            const txtValue = (tdUsername.textContent || tdUsername.innerText) + " " + 
                            (tdName.textContent || tdName.innerText) + " " + 
                            (tdEmail.textContent || tdEmail.innerText);
            
            if (txtValue.toUpperCase().indexOf(filter) > -1) {
                tr[i].style.display = "";
            } else {
                tr[i].style.display = "none";
            }
        }
    }
}

// Check password match for add form
function checkPasswordMatch(confirmPasswordInput) {
    const passwordInput = document.querySelector('#addUserForm input[name="password"]');
    if (confirmPasswordInput.value !== passwordInput.value) {
        confirmPasswordInput.setCustomValidity("Passwords must match");
        confirmPasswordInput.classList.add('is-invalid');
    } else {
        confirmPasswordInput.setCustomValidity("");
        confirmPasswordInput.classList.remove('is-invalid');
    }
}

// Check password match for edit form
function checkEditPasswordMatch(confirmPasswordInput) {
    const passwordInput = document.querySelector('#editUserForm input[name="password"]');
    if (passwordInput.value && confirmPasswordInput.value !== passwordInput.value) {
        confirmPasswordInput.setCustomValidity("Passwords must match");
        confirmPasswordInput.classList.add('is-invalid');
    } else {
        confirmPasswordInput.setCustomValidity("");
        confirmPasswordInput.classList.remove('is-invalid');
    }
}

// Validate and add user
function validateAndAddUser() {
    const form = document.getElementById('addUserForm');
    
    // Check if passwords match
    const password = form.querySelector('input[name="password"]').value;
    const confirmPassword = form.querySelector('input[name="confirm_password"]').value;
    
    if (password !== confirmPassword) {
        form.querySelector('input[name="confirm_password"]').classList.add('is-invalid');
        return;
    }
    
    // Validate all fields
    if (form.checkValidity() === false) {
        form.classList.add('was-validated');
        return;
    }
    
    // If all valid, proceed to add
    addUser();
}

// Validate and update user
function validateAndUpdateUser() {
    const form = document.getElementById('editUserForm');
    
    // Check if passwords match if password field is not empty
    const password = form.querySelector('input[name="password"]').value;
    const confirmPassword = form.querySelector('input[name="confirm_password"]').value;
    
    if (password && password !== confirmPassword) {
        form.querySelector('input[name="confirm_password"]').classList.add('is-invalid');
        return;
    }
    
    // Validate all fields
    if (form.checkValidity() === false) {
        form.classList.add('was-validated');
        return;
    }
    
    // If all valid, proceed to update
    updateUser();
}

// Open edit modal with user data
function openEditModal(userData) {
    const modal = new bootstrap.Modal(document.getElementById('editUserModal'));
    
    document.getElementById('editAccountID').value = userData.accountID;
    document.getElementById('editFname').value = userData.fname;
    document.getElementById('editLname').value = userData.lname;
    document.getElementById('editMname').value = userData.mname || '';
    document.getElementById('editUsername').value = userData.username;
    document.getElementById('editEmail').value = userData.email;
    
    // Reset validation state
    const form = document.getElementById('editUserForm');
    form.classList.remove('was-validated');
    Array.from(form.elements).forEach(element => {
        element.classList.remove('is-invalid');
    });
    
    modal.show();
}

// Add new user
async function addUser() {
    try {
        const form = document.getElementById('addUserForm');
        const formData = new FormData(form);
        
        // Remove confirm_password before sending
        formData.delete('confirm_password');
        
        const response = await fetch('add_user.php', {
            method: 'POST',
            body: formData
        });
        
        const result = await response.json();
        
        if (result.success) {
            alert('User added successfully!');
            location.reload();
        } else {
            // Handle server-side validation errors
            if (result.errors) {
                Object.keys(result.errors).forEach(field => {
                    const input = form.querySelector(`[name="${field}"]`);
                    if (input) {
                        input.classList.add('is-invalid');
                        const feedback = input.nextElementSibling;
                        if (feedback && feedback.classList.contains('invalid-feedback')) {
                            feedback.textContent = result.errors[field];
                        }
                    }
                });
            } else {
                throw new Error(result.message || 'Failed to add user');
            }
        }
    } catch (error) {
        alert('Error: ' + error.message);
    }
}

// Update user
async function updateUser() {
    try {
        const form = document.getElementById('editUserForm');
        const formData = new FormData(form);
        
        // Remove confirm_password before sending
        formData.delete('confirm_password');
        
        // If password is empty, remove it from formData
        if (!formData.get('password')) {
            formData.delete('password');
        }
        
        const response = await fetch('update_user.php', {
            method: 'POST',
            body: formData
        });
        
        const result = await response.json();
        
        if (result.success) {
            alert('User updated successfully!');
            location.reload();
        } else {
            // Handle server-side validation errors
            if (result.errors) {
                Object.keys(result.errors).forEach(field => {
                    const input = form.querySelector(`[name="${field}"]`);
                    if (input) {
                        input.classList.add('is-invalid');
                        const feedback = input.nextElementSibling;
                        if (feedback && feedback.classList.contains('invalid-feedback')) {
                            feedback.textContent = result.errors[field];
                        }
                    }
                });
            } else {
                throw new Error(result.message || 'Failed to update user');
            }
        }
    } catch (error) {
        alert('Error: ' + error.message);
    }
}

// Delete user confirmation
function confirmDelete(userId) {
    if (confirm('Are you sure you want to delete this user? This action cannot be undone.')) {
        deleteUser(userId);
    }
}

// Delete user
async function deleteUser(userId) {
    try {
        const response = await fetch('delete_user.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({ accountID: userId })
        });
        
        const result = await response.json();
        
        if (result.success) {
            alert('User deleted successfully!');
            location.reload();
        } else {
            throw new Error(result.message || 'Failed to delete user');
        }
    } catch (error) {
        alert('Error: ' + error.message);
    }
}

// Initialize modal to reset form when closed
document.getElementById('addUserModal').addEventListener('hidden.bs.modal', function () {
    const form = document.getElementById('addUserForm');
    form.reset();
    form.classList.remove('was-validated');
    Array.from(form.elements).forEach(element => {
        element.classList.remove('is-invalid');
    });
});
</script>
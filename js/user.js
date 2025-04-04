function toggleMenu() {
    var menu = document.getElementById("menu");
    menu.classList.toggle("show-menu");
}

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
function openPopupForm() {
    document.getElementById('popupForm').style.display = 'block';
}

function closePopupForm() {
    document.getElementById('popupForm').style.display = 'none';
}

function deleteRow(refNo) {
    if (confirm('Are you sure you want to delete this row?')) {
        window.location.href = 'request.php?delete=' + refNo;
    }
}

function setAmount() {
    const amounts = {
        "Brgy clearance": 60, "Business Clearance": 50, "Building Clearance": 40,
        "Barangay Certificate": 40, "Certificate of Indigency": 70, "Cedula": 60
    };
    document.getElementById("amount").value = amounts[document.getElementById("type").value] || "";
}

function notification() {
    fetch('notification.php', {
        method: 'POST'
    })
    .then(response => response.json())
    .then(result => {
        if(result.count_request){
            document.querySelector('#count_request').style.display = 'block';
            document.querySelector('#count_request').text = result.count_request;
        }else{
            document.querySelector('#count_request').style.display = 'none';
        }
        if (Array.isArray(result.data)) {
            result.data.forEach(item => {
                let status = (item.status =='APPROVED')?'linear-gradient(to right,rgb(29, 176, 0),rgb(61, 201, 119))':'linear-gradient(to right,rgb(176, 0, 0),rgb(201, 61, 61))';
                Toastify({
                    text: `${item.RefNo} (${item.Type}) your request has been ${item.status}`,
                    duration: 3000,
                    newWindow: true,
                    close: true,
                    gravity: "bottom",
                    position: "right",
                    stopOnFocus: true,
                    style: {
                        background: status
                    },
                    onClick: function () { }
                }).showToast();
            });
        } else {
            console.error("Unexpected data format:", data);
           
        }
    })
    .catch(error => console.error('Error:', error));
}
setInterval(notification, 10000);
notification();
function setDefaultDate() {
    let today = new Date();
    let birthYear = today.getFullYear() - 16;
    let month = String(today.getMonth() + 1).padStart(2, '0'); 
    let day = String(today.getDate()).padStart(2, '0'); 
    let defaultDate = `${birthYear}-${month}-${day}`;

    document.getElementById("birthdate").value = defaultDate;
}

function calculateAge(birthdate) {
    const today = new Date();
    const birthDate = new Date(birthdate);
    let age = today.getFullYear() - birthDate.getFullYear();
    const monthDifference = today.getMonth() - birthDate.getMonth();
    if (monthDifference < 0 || (monthDifference === 0 && today.getDate() < birthDate.getDate())) {
        age--;
    }
    if (age < 16) {
        alert("Age must be 16 or above.");
        document.getElementById('birthdate').value = "";
        return ""
    }
    return age;
}

function updateAge() {
    const birthdateInput = document.getElementById('birthdate').value;
    if (birthdateInput) {
        const age = calculateAge(birthdateInput);
        document.getElementById('age').value = age;
    }
}

function calculateAgeEdit(birthdate) {
    const today = new Date();
    const birthDate = new Date(birthdate);
    let age = today.getFullYear() - birthDate.getFullYear();
    const monthDifference = today.getMonth() - birthDate.getMonth();
    if (monthDifference < 0 || (monthDifference === 0 && today.getDate() < birthDate.getDate())) {
        age--;
    }
    if (age < 16) {
        alert("Age must be 16 or above.");
        document.querySelector('.birthdate').value = "";
        return ""
    }
    return age;
}

function updateAgeEdit() {
    const birthdateInput = document.querySelector('.birthdate').value;
    if (birthdateInput) {
        const age = calculateAgeEdit(birthdateInput);
        document.querySelector('.age').value = age;
    }
}

window.onload = updateAgeEdit;
window.onload = setDefaultDate;

document.addEventListener("DOMContentLoaded", function () {
    document.getElementById("add_new_resident").addEventListener("submit", function (e) {
        e.preventDefault();

        let formData = new FormData(this);

        fetch("../controller/add_new_resident.php", {
            method: "POST",
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.status === "success") {
                alert(data.message);
                document.getElementById("popupForm").style.display = "none";
            } else {
                alert("Failed: " + data.message);
            }
        })
        .catch(error => {
            console.error("Error:", error);
            alert("An error occurred. Please try again.");
        });
    });

    document.getElementById("edit_resident").addEventListener("submit", function (e) {
        e.preventDefault();

        let formData = new FormData(this);

        fetch("../controller/edit_resident.php", {
            method: "POST",
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.status === "success") {
                alert(data.message);
                location.reload(); 
            } else {
                alert("Failed: " + data.message);
            }
        })
        .catch(error => {
            console.error("Error:", error);
            alert("An error occurred. Please try again.");
        });
    });

});

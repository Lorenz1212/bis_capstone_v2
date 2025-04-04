document.addEventListener("DOMContentLoaded", function () {
    document.getElementById("registerForm").addEventListener("submit", function (e) {
        e.preventDefault();

        let formData = new FormData(this);

        fetch("controller/register.php", {
            method: "POST",
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.status === "success") {
                alert("Registration successful! Redirecting to login...");
                setTimeout(() => {
                    window.location.href = "index.php";
                }, 1000);
            }else{
                alert("Registration failed: " + data.message);
            }
        })
        .catch(error => {
            console.error("Error:", error);
            alert("An error occurred. Please try again.");
        });
    });
});

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

window.onload = setDefaultDate;
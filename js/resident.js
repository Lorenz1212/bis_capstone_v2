

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

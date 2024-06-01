function showForm() {
    document.getElementById("form").style.display = "block";
    document.getElementById("logbook").style.display = "none";
    document.getElementById("report").style.display = "none";
}

function showLogbook() {
    document.getElementById("form").style.display = "none";
    document.getElementById("logbook").style.display = "block";
    document.getElementById("report").style.display = "none";
}

function showReport() {
    document.getElementById("form").style.display = "none";
    document.getElementById("logbook").style.display = "none";
    document.getElementById("report").style.display = "block";
}

// Initially show the form
showForm();

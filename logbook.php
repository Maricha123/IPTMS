<div class="logbook-container">
    <form id="logbookForm">
        <label for="date">Date:</label>
        <input type="date" id="date" name="date" required>

        <label for="work">Work:</label>
        <textarea id="work" name="work" required></textarea>

        <button onclick="window.location.href='student_dash.html'">submit</button> 
    </form>
</div>

<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
<script>
    $(document).ready(function () {
        $("#logbookForm").submit(function (e) {
            e.preventDefault();

            var formData = $(this).serialize();

            $.ajax({
                type: "POST",
                url: "submit_logbook.php", // Create this PHP file for logbook submission
                data: formData,
                success: function (response) {
                    alert("Logbook submitted successfully!");
                    // You can add further actions here, such as clearing the form or redirecting.
                },
                error: function (error) {
                    console.error("Error submitting logbook:", error);
                }
            });
        });
    });
</script>

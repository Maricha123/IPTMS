<div class="report-container">
    <form id="reportForm">
        <label for="weekNo">Week No:</label>
        <input type="number" id="weekNo" name="weekNo" required>

        <label for="weeklyWork">Weekly Work:</label>
        <textarea id="weeklyWork" name="weeklyWork" required></textarea>

        <label for="problems">Problems:</label>
        <textarea id="problems" name="problems" required></textarea>

        <button type="button">submit</button>
    </form>
</div>

<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
<script>
    $(document).ready(function () {
        $("#reportForm").submit(function (e) {
            e.preventDefault();

            var formData = $(this).serialize();

            $.ajax({
                type: "POST",
                url: "submit_report.php", // Create this PHP file for report submission
                data: formData,
                success: function (response) {
                    alert("Report submitted successfully!");
                    // You can add further actions here, such as clearing the form or redirecting.
                },
                error: function (error) {
                    console.error("Error submitting report:", error);
                }
            });
        });
    });
</script>

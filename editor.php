<?php
session_start();

// Ensure the user is logged in before accessing the page
if (!isset($_SESSION['user_id'])) {
    header('Location: index.php');
    exit;
}
?>
<textarea id="editor">
  Start writing here...
</textarea>
<script src="https://cdn.tiny.cloud/1/no-api-key/tinymce/5/tinymce.min.js"></script>
<script>
  tinymce.init({
    selector: '#editor'
  });
</script>

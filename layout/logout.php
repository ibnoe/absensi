<?php
session_start();
unset($_SESSION['user']);
?>
<script>
	document.location = "index.php";
</script>
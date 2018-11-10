<html>

<head></head>

<body>
<div><?php
if (!empty($_GET['name']) && !empty($_GET['message'])) {
    echo 'Thank you for your message, ' . $_GET['name'] . '<br><br>';
}
?></div>
	<form method="GET">
		Name:
		<br>
		<input name="name" />
		<br> Message:
		<br>
		<textarea name="message"></textarea>
		<br>
		<input type="submit" />
	</form>
</body>

</html>

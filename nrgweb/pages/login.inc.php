<?php
if(isset($_POST["username"])) {
	$username = $_POST["username"];
	$password = md5($_POST["password"]);
	
	$query = "SELECT * FROM `gebruiker` WHERE `username`='$username' AND `password`='$password' LIMIT 1";
	
	$result = mysqli_query($conn, $query);
	
	if(mysqli_num_rows($result) > 0) {
		$_SESSION["user"] = mysqli_fetch_array($result);
		?>
		<script type="text/javascript">
			location.href="?p=menu";
		</script>
		<?php
	} else {
		$error = "Username and password not found in database";
	}
}
?>
<form method="POST">
<?php
if(isset($error)) {
	echo "<div class='error-message'>$error</div>";
}
?>

<table class="login-table">
	<tr>
		<td colspan=2>
			Log in
		</td>
	</tr>
	<tr>
		<td>
			Username
		</td>
		<td>
			<input type="textbox" name="username" />
		</td>
	</tr>
	<tr>
		<td>
			Password
		</td>
		<td>
			<input type="password" name="password" />
		</td>
	</tr>
	<tr>
		<td colspan=2>
			<input type="submit" value="Login" />
		</td>
	</tr>
</table>
<a href="?p=register">Registreer</a>
</form>

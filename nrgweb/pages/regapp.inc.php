<?php
if(isset($_POST["merk"])) {
	$type = $_POST["type"];
	$merk = $_POST["merk"];
	$typenr = $_POST["typenummer"];
	$omschr = $_POST["omschr"];
	$huishoudenId = $_SESSION["user"]["huishouden_id"];
	
	$query = "INSERT INTO `apparaat_huishouden`(`huishouden_fk`, `apparaat_fk`, `merk`, `typenr`, `omschr`) VALUES('$huishoudenId', '$type', '$merk', '$typenr', '$omschr')";
	$result = mysqli_query($conn, $query) or die(mysqli_error($conn));
	
	$msg = "Apparaat is toegevoegd";
	
}
?>
<a id="back-button" href="?p=menu"><div class="back-button">Terug naar menu</div></a><br />
<?php
if(isset($msg)) echo "<p>$msg</p>";
?>
<h3>Voeg een energieproducerend apparaat toe aan uw huishouden</h3>
<form method="POST">
	<table>
		<tr>
			<td>Apparaattype</td>
			<td>
				<select name="type">
			<?php
				$result = mysqli_query($conn, "SELECT * FROM `apparaat`");
				while($app = mysqli_fetch_array($result)) {
					echo "<option value='".$app["id"]."'>".$app["naam"]."</option>";
				}
			?>
				</select>
			</td>
		</tr>
		<tr>
			<td>
				Merk
			</td>
			<td>
				<input type="text" name="merk" />
			</td>
		</tr>
		<tr>
			<td>
				Typenummer
			</td>
			<td>
				<input type="text" name="typenummer" />
			</td>
		</tr>
		<tr>
			<td>
				Korte Omschr.
			</td>
			<td>
				<input type="text" name="omschr" />
			</td>
		</tr>
		<tr>
			<td colspan="2">
				<input type="submit" value="Opslaan" />
			</td>
		</tr>
	</table>
</form>
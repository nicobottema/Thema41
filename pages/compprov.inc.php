<a id="back-button" href="?p=menu"><div class="back-button">Terug naar menu</div></a><br />
<h4>Vergelijk Gemiddelde Productie</h4>
<table>
	<tr>
		<td>
			Postcode: <input type="text" id="postcode" value="<?php echo $_SESSION["user"]["postcode"]?>"/><br />
			<input type="checkbox" id="gebruikPostcode" /> Gebruik postcode<Br />
			<input type="checkbox" id="provinciaal" /> Vergelijk provinciaal
			
		</td>
	</tr>
	<tr>
		<td>
			Gebaseerd op apparaatsoort
		</td>
		<td>
			<select id="apparaat">
			<?php
				$result = mysqli_query($conn, "SELECT * FROM `apparaat` WHERE `id` IN (SELECT `apparaat_fk` FROM `apparaat_huishouden` WHERE `huishouden_fk`='".$_SESSION["user"]["huishouden_id"]."')");
				while($app = mysqli_fetch_array($result)) {
					echo "<option value='".$app["id"]."'>".$app["naam"]."</option>";
				}
			?>
			</select>
		</td>
		<td>
			<input type="button" value="Vergelijk" onclick="comp('vergelijkSoort')" />
		</td>
	</tr>
	<tr>
		<td>
			Gebaseerd op apparaattype
		</td>
		<td>
			<select id="apparaattype">
			<?php
				$result = mysqli_query($conn, "SELECT `apparaat_huishouden`.`id`, `apparaat`.`naam`, `apparaat_huishouden`.`merk`, `apparaat_huishouden`.`typenr` FROM `apparaat_huishouden` LEFT JOIN `apparaat` ON `apparaat`.`id` = `apparaat_huishouden`.`apparaat_fk` WHERE `huishouden_fk`='".$_SESSION["user"]["huishouden_id"]."'");
				while($app = mysqli_fetch_array($result)) {
					echo "<option value='".$app["id"]."'>".$app["naam"]." - ".$app["merk"]." ".$app["typenr"]."</option>";
				}
			?>
			</select>
			
		</td>
		<td>
			<input type="button" value="Vergelijk" onclick="comp('vergelijkType')" />
		</td>
	</tr>
</table>
<canvas id="chart" width="800" height="400"></canvas>
<div id="legend"></div>
<script type="text/javascript">
	var ctx = $("#chart").get(0).getContext("2d");

	function comp(command) {
		var apparaat = $("#apparaat").val();
		var appHh = $("#apparaattype").val();
		
		$.post( "api/api.php", { command: command, soort: apparaat, gebruikPostcode: ($("#gebruikPostcode").prop('checked') ? "1" : "0"), vergelijkPostcode: "" + $("#postcode").val(), provinciaal: ($("#provinciaal").prop('checked') ? "1" : "0"), app_hh: appHh }, function( data ) {
		  //alert( data );
		  data = JSON.parse(data);
		  var curveData = {
			labels: ["9", "10", "11", "12", "13", "14", "15", "16", "17", "18"],
			datasets: [
				{
					label: "Gemiddeld Omgeving",
					fillColor: "rgba(220,220,220,0.2)",
					strokeColor: "rgba(220,220,220,1)",
					pointColor: "rgba(220,220,220,1)",
					pointStrokeColor: "#fff",
					pointHighlightFill: "#fff",
					pointHighlightStroke: "rgba(220,220,220,1)",
					data: []
				},
				{
					label: "Gemiddeld Eigen",
					fillColor: "rgba(151,187,205,0.2)",
					strokeColor: "rgba(151,187,205,1)",
					pointColor: "rgba(151,187,205,1)",
					pointStrokeColor: "#fff",
					pointHighlightFill: "#fff",
					pointHighlightStroke: "rgba(151,187,205,1)",
					data: []
				}
			]
		};
		for(var i = 9; i <= 18; i++) {
			curveData.datasets[0]["data"][i-9] = (data["gem_uur" + i] > 0 ? data["gem_uur" + i] : 0);
			curveData.datasets[1]["data"][i-9] = (data["gem_eigen_uur" + i] > 0 ? data["gem_eigen_uur" + i] : 0);
		}
		var chart = new Chart(ctx).Line(curveData, {
			bezierCurve: false
		});
		
		document.getElementById("legend").innerHTML = "<span style='color:#bcf'>Gemiddeld Omgeving</span><br/><span style='color:#78a'>Gemiddeld Eigen</span>";
		});
		
	}
</script>
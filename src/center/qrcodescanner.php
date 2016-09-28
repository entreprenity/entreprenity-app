<div class="scanner-qr-code-container">
	<div class="scanner-qr-code">
		<div>
			<h4>Welcome to <?php echo $_SESSION['locName']; ?></h4>
		</div>
		<div class="qr-container">
			<div style="display:none" id="result"></div>
			<div class="selector" id="webcamimg" onclick="setwebcam()" align="left" ></div>
			<div class="selector" id="qrimg" onclick="setimg()" align="right" ></div>
			<center id="mainbody"><div id="outdiv"></div></center>
			<canvas id="qr-canvas" width="800" height="600"></canvas>
			<div class="corner-border tl"></div>
			<div class="corner-border tr"></div>
			<div class="corner-border bl"></div>
			<div class="corner-border br"></div>
		</div>
        <div>
            <p>Align the QR code to scan</p>
            <img class="en-logo" src="../assets/img/entreprenity-logo.png" alt="">
        </div>
		<div class="qr-table">
			<table id="activeUsersTable" class="table table-bordered">
				<thead>
					<tr>
					    <td></td>
						<td>Name</td>
						<td>Company</td>
						<td>CheckIn Time</td>
					</tr>
				</thead>
				<tbody>
					<?php
							$qry="SELECT ECL.entrpID,ECL.checkIn ,EL.firstname,EL.lastname,CP.avatar
									FROM entrp_center_login AS ECL
									LEFT JOIN entrp_login AS EL ON EL.clientid=ECL.entrpID
									LEFT JOIN client_profile AS CP ON CP.clientid=EL.clientid
									WHERE ECL.status=1
								  ";
						   $res = getData($qry);
						   $count_res = mysqli_num_rows($res);
							if($count_res > 0)
							{
							while($row = mysqli_fetch_array($res))
							{
							$entrpID		= $row['entrpID'];
							$checkIn		= $row['checkIn'];
							$firstname	= $row['firstname'];
							$lastname	= $row['lastname'];
							$avatar		= $row['avatar'];
							?>
							<tr id="<?php echo $entrpID; ?>">
							    <td><img src="../<?php echo $avatar; ?>"></td>
								<td><?php echo $firstname.' '.$lastname; ?></td>
								<td>Company</td>
								<td><?php echo $checkIn; ?></td>
							</tr>
							<?php
							}
						   }
					?>
				</tbody>
			</table>
		</div>
	</div>
</div>





	<div>Welcome to <?php echo $_SESSION['locName']; ?></div>
	<div style="display:none" id="result"></div>
	<div class="selector" id="webcamimg" onclick="setwebcam()" align="left" ></div>
	<div class="selector" id="qrimg" onclick="setimg()" align="right" ></div>
	<center id="mainbody"><div id="outdiv"></div></center>
	<canvas id="qr-canvas" width="800" height="600"></canvas>
	
	
	<table id="activeUsersTable">
	     <thead>
	         <tr>
	          <td>Name</td>
	          <td>Comany</td>
	          <td>Image</td>
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
	          <td><?php echo $firstname.' '.$lastname; ?></td>
	          <td>Comany</td>
	          <td><?php echo $avatar; ?></td>
	          <td><?php echo $checkIn; ?></td>
	        </tr>
	      <?php
	      	    }
			   }
	      ?>
	    </tbody>
	
	</table>
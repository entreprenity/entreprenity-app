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

		
<div id="successModal" class="modal fade in" >
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title">Check-In</h4>
            </div>
            <div id="welcomeMsgDiv" class="modal-body" style="color:black;">
                Welcome, <span id="checkedInUser"></span>
            </div>
        </div>
    </div> 
</div>

<div id="failureModal" class="modal fade in" >
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title">Check-In</h4>
            </div>
            <div id="errorMsgDiv" class="modal-body" style="color:black;">
                <h3>Code Scanning Failed! Please try again.</h3>
            </div>
        </div>
    </div> 
</div>
		
		
	</div>
</div>




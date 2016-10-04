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
                <img id="checkedInUserImage" class="img-circle" src="" alt="Avatar">
            </div>
            <div id="welcomeMsgDiv" class="modal-body" style="color:black;">
                <div class="arrow-down"></div>
                <p>Welcome, <span id="checkedInUser"></span>!</p>
                <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#successModal">CONTINUE</button>
            </div>
        </div>
    </div> 
</div>

<div id="failureModal" class="modal fade in" >
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                 <img id="errorIcon" class="img-circle" src="../assets/img/qr-code-scanner-fail.png" alt="Avatar" width="80">
                 <h1>Code Scanning Failed!</h1>
            </div>
            <div id="errorMsgDiv" class="modal-body" style="color:black;">
                <div class="arrow-down"></div>
                <p>Please try again</p>
                <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#failureModal">TRY AGAIN</button>
            </div>
        </div>
    </div> 
</div>
		
		
	</div>
</div>




<?php
if (!isset($GLOBALS["htmlPath"])) {
  $GLOBALS["htmlPath"] = $_SERVER["DOCUMENT_ROOT"];
}
$dRoot = $GLOBALS["htmlPath"];
$tCall = $_GET["c"];
$tUserName = $_GET["x"];
require_once $dRoot . "/classes/Membership.php";
$membership = new Membership();
$membership->confirm_Member($tUserName);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html lang="en">
<head>
	<meta charset="utf-8">

	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">

	<title><?php echo $tCall; ?> RigPi Radio Settings</title>
	<meta name="RigPi Settings" content="">
	<meta name="author" content="Howard Nurse, W6HN">

	<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0" />
	<!-- Bootstrap CSS -->
	<link rel="stylesheet" href="./Bootstrap/bootstrap.min.css">
	<script defer src="./awe/js/all.js" ></script>
	<link href="./awe/css/all.css" rel="stylesheet">
	<link href="./awe/css/fontawesome.css" rel="stylesheet">
	<link href="./awe/css/solid.css" rel="stylesheet">	
    <script src="/Bootstrap/jquery.min.js" ></script>
	<!-- Replace favicon.ico & apple-touch-icon.png in the root of your domain and delete these references -->
	<link rel="shortcut icon" href="/favicon.ico">
	<link rel="apple-touch-icon" href="/apple-touch-icon.png">
	<?php require $dRoot . "/includes/styles.php"; ?>
	<script type="text/javascript">
		var cManu='';
		var cRadioID='';
		var tMyRadio='0';
		var tMyPort=4532;
		var tMyCall="<?php echo $tCall; ?>";
		var tCall=tMyCall;
		var tMyCWPort="/dev/ttyS0";  //later found from db
		var tMyRotorPort="/dev/ttyUSB1";  //later found from db
		var tMyKeyer="non";
		var tMyPTT=1;
        var tUserName=<?php echo "'" . $tUserName . "'"; ?>;
        var tUser='';
        var tMyRadioName='';
        var tMyKeyerFunction=0;
        var tMyKeyerPort;
        var tMyKeyerIP;
        var tCWPort;
  		$(document).ready(function(){
			$('.dropdown-menu a.dropdown-toggle').on('click', function(e) {
			  if (!$(this).next().hasClass('show')) {
			    $(this).parents('.dropdown-menu').first().find('.show').removeClass("show");
			  }
			  var $subMenu = $(this).next(".dropdown-menu");
			  $subMenu.toggleClass('show');
			
			
			  $(this).parents('li.nav-item.dropdown.show').on('hidden.bs.dropdown', function(e) {
			    $('.dropdown-submenu .show').removeClass("show");
			  });
			
			
			  return false;
			});

			var el=$('#spinner');
			el.hide();

		   	function getKeyerID(which)
		   	{
				if(which=="None")
				{
					which='non';
				}else if (which=="RigPi Keyer")
				{
					which='rpk';
				}else if (which=="via CAT")
				{
					which="cat";
				}else if (which=="WinKeyer")
				{
					which="wkr";
				}
				return which;
		   	}

	  		$.post('/programs/GetUserField.php', {un:tUserName,field:'uID'}, function(response){
		  		tMyRadio=response;
				tMyPort=tMyRadio*2+4530;
				$("#curID").val(response);
				getMyRadioFields();
		        $.post('/programs/GetSetting.php',{radio: tMyRadio, field: 'Keyer', table: 'MySettings'}, function(response)
		        {
			        tMyKeyer=getKeyerID(response);
			    });
		        $.post('/programs/GetSetting.php',{radio: tMyRadio, field: 'Model', table: 'MySettings'}, function(response)
		        {
			        tMyRadioName=response;
			    });
		        $.post('/programs/GetSetting.php',{radio: tMyRadio, field: 'DX', table: 'MySettings'}, function(response)
		        {
					$('#searchText').val(response);
			    });
	
				$.getScript("/js/modalLoad.js");

				$.get('/programs/GetMyRadio.php', 'f=RotorPort&r='+tMyRadio, function(response) {
		  			tMyRotorPort=response;
	  			});

				$.post('./programs/GetSetting.php',{radio: tMyRadio, field: 'WKFunction', table: 'Keyer'}, function(response)
		        {
			        tMyKeyerFunction=response;
			    });
	
		        $.post('./programs/GetSetting.php',{radio: tMyRadio, field: 'WKRemotePort', table: 'Keyer'}, function(response)
		        {
			        tMyKeyerPort=response;
		        });
	
		        $.post('./programs/GetSetting.php',{radio: tMyRadio, field: 'WKRemoteIP', table: 'Keyer'}, function(response)
		        {
			        tMyKeyerIP=response;
		        });
		        
		        $.post('./programs/GetSetting.php',{radio: tMyRadio, field: 'KeyerPort', table: 'MySettings'}, function(response)
		        {
					tCWPort=response;
			    });
	
			});

			$(document).on('click', '.radioSave', function() {
				setMyRadioFields(0);
			});

			$(document).on('click', '.manufacturers', function() {
				var text = $(this).text();
  				$('#curManu').val(text);
  				$.post('/programs/RadioDB.php', 'm='+text, function(response) {
			        $('#radioList').empty(); //remove all child nodes
			        var newOption = response;
			        $('#radioList').append(newOption);
  				});
			});

			$(document).on('click', '.radios', function() {
				var text = $(this).text();
  				$('#curRadio').val(text);
  				tMyRadioName=text;
		  		$('#curName').val(text);
 			});
            
            $(document).on('click', '#logoutButton', function() 
            {
				openWindowWithPost("/login.php", {
				    status: "loggedout",
				    username: tUserName});
            });	
            
			function openWindowWithPost(url, data) {
			    var form = document.createElement("form");
			    form.target = "_self";
			    form.method = "POST";
			    form.action = url;
			    form.style.display = "none";
			
			    for (var key in data) {
			        var input = document.createElement("input");
			        input.type = "hidden";
			        input.name = key;
			        input.value = data[key];
			        form.appendChild(input);
			    }
			
			    document.body.appendChild(form);
			    window.open("/login.php","_self");
			    form.submit();
			};                

			$(document).on('click', '.mykeyer', function() {
				var text = $(this).text();
  				$("#curKeyer").val(text);
  				tMyKeyer=getKeyerID(text);
				if ($('#curKeyer').val()=="RigPi Keyer"){
					$("#curKeyerPort").val("/dev/ttyS0");
					tMyCWPort="/dev/ttyS0";
				}
				if ($('#curKeyer').val()=="via CAT"||$('#curKeyer').val()=='None'){
					$("#curKeyerPort").val("None");
					tMyCWPort="None";
				}
			});

			$(document).on('click', '.myport', function() {
				var text = $(this).text();
  				$("#curPort").val(text);
			});

			$(document).on('click', '.myCWPort', function() {
				var text = $(this).text();
  				$("#curKeyerPort").val(text);
			});

			$(document).on('click', '#connectButton', function() {
				var el=$('#spinner');
				el.show();
				el.addClass('fa-spin');
				var tOK=setMyRadioFields(1);
				return;
				if (tOK==0){
					return;
				}
				if (tMyKeyer=='rpk'){
					tMyCWPort='/dev/ttyS0';
				}
	
				setTimeout(function(){
	  				$.post('./programs/hamlibDo.php', {test: 0, keyer: tMyKeyer, radio: tMyRadio, user: tUserName, port: tCWPort, tcpPort: "30001", rotorPort: tMyRotorPort, keyerPort:tMyKeyerPort, keyerIP:tMyKeyerIP, keyerFunc:tMyKeyerFunction}, function(response) {
		  				if (response.length>20){
							$("#modalA-body").html(response);			  				
							$("#modalA-title").html("Radio Connection");
						  	$("#myModalAlert").modal({show:true});
			  				if (response.indexOf("Now starting RigPi Radio")>0){
				  				$('#connect').text("Radio connected");
			  				}
			  				if (tMyPTT==2){
			  					$.post('/programs/doGPIOPTT.php', {PTTControl: "on"}); 			  				
			  				}
		  				}else{
			  				alert(response);
		  				}
		  				el.removeClass('fa-spin');
		  				el.hide();
					});
				},2000);
			});

			$(document).on('click', '#disconnectButton', function() {
  				$.post('./programs/disconnectRadio.php', {radio: tMyRadio, user: tUserName, rotor: tMyRadio}, function(response) {
					$("#modalA-body").html(response);			  				
					$("#modalA-title").html("Radio Connection");
				  	$("#myModalAlert").modal({show:true});
	                $.post("/programs/SetSettings.php", {field: "MainIn", radio: tMyRadio, data: "OFF", table: "RadioInterface"});
	  				if (tMyPTT==2){
	  					$.post('/programs/doGPIOPTT.php', {PTTControl: "off"}); 			  				
	  				}
				});
			});

$(document).keydown(function(e){
var t=e.key;
e.multiple
var w=e.which;
if (w==191)
{
	if (e.shiftKey){
		<?php require $dRoot . "/includes/shortcutsOther.php"; ?>
		$("#modalCO-body").html(tSh);			  				
		$("#modalCO-title").html("Shortcut Keys");
		  $("#myModalCancelOnly").modal({show:true});
		  return false;
	}else{
		var tS1=document.activeElement.tagName;
		if (tS1=='INPUT'){
			return true;
		}else{
			$("#searchText").focus();
			return false;
		}
	}
};
if (w == 27) { 
	document.getElementById('closeModal').click();
}
	if (e.altKey){
		switch(w){
		case 65: // a
			var win="/calendar_main.php?x="+tUserName+"&c="+tCall;
			window.open(win, "_self");
			break;
		case 69: // e
			showSettings();
			e.preventDefault();
			break;
		case 72: // h
			showHelp();
			e.preventDefault();
			break;
		case 75: //k
			showKeyer();
			e.preventDefault();
			break;
		case 76: //l
			showLog();
			e.preventDefault();
			break;
			 
		case 83: // s
			showSpots();
			e.preventDefault();
			break;
		case 84: // t
			showTuner();
			e.preventDefault();
			break;
		case 87: // w
			showWeb();
			e.preventDefault();
			break;
		};
	};
});

$("input").bind("keydown", function(event) 
{
	// track enter key
	var keycode = (event.keyCode ? event.keyCode : (event.which ? event.which : event.charCode));
	if (keycode == 13) { // keycode for enter key
		if ($('#searchText').val()==''){
			return false;
		}
		var tDX=$('#searchText').val().toUpperCase();
		$('#searchText').val(tDX);
		document.getElementById('searchButton').click();
		$.post("/programs/SetSettings.php", {field: "DX", radio: tMyRadio, data: tDX, table: "MySettings"});
		return false;
	} else  {
		return true;
	}
});
  			function setRadioList(){
				$.post('/programs/RadioDB.php', 'm='+cManu, function(response) {
			        $('#radioList').empty(); //remove all child nodes
			        var newOption = response;
			        $('#radioList').append(newOption);
				});
  			}

  			function getMyRadioFields(){
				$.get('/programs/GetMyRadio.php', 'f=Manufacturer&r='+tMyRadio, function(response) {
		  			$('#curManu').val(response);
		  			cManu=response;
		  			setRadioList();
	  			});

				$.get('/programs/GetMyRadio.php', 'f=Model&r='+tMyRadio, function(response) {
		  			$('#curRadio').val(response);
	  			});

				$.get('/programs/GetMyRadio.php', 'f=Port&r='+tMyRadio, function(response) {
		  			$('#curPort').val(response);
		  			$.post('programs/portScan.php', function(response1){
			  			if (response1.indexOf(response)==-1 && response.length>4){
			  				$('#curPort').val("None");
							$("#modalA-body").html("Radio port has been set to 'None.' The specified port, "+response+", was not found.");			  				
							$("#modalA-title").html("Radio Port Error");
						  	$("#myModalAlert").modal({show:true});
			  			}
		  			});
	  			});

				$.get('/programs/GetMyRadio.php', 'f=Keyer&r='+tMyRadio, function(response) {
		  			$('#curKeyer').val(response);
		  			tMyKeyer=getKeyerID(response);
	  			});	  			

				$.get('/programs/GetMyRadio.php', 'f=PTTMode&r='+tMyRadio, function(response) {
		  			tMyPTT=response;
	  			});	  			

				$.get('/programs/GetMyRadio.php', 'f=KeyerPort&r='+tMyRadio, function(response) {
					if ($('#curKeyer').val()=="RigPi Keyer"){
  						$("#curKeyerPort").val("/dev/ttyS0");
  						tMyCWPort="/dev/ttyS0";
  					}else{
		  				$('#curKeyerPort').val(response);
		  				tMyCWPort=response;
		  			}
	  			});

				$.get('/programs/GetMyRadio.php', 'f=RadioName&r='+tMyRadio, function(response) {
		  			$('#curName').val(response);
	  			});

				$.post('/programs/GetInterface.php', {field:'powerOut',radio:tMyRadio}, function(response) {
		  			$('#curPwr').val(response);
	  			});
	  		};

		    function setMyRadioFields(nBypassAlert) {
			   var radioID;
				$.post('/programs/RadioID.php', 'o=' + $('#curRadio').val(), function(response) {
		  			radioID=response;
		  			var manuID=$('#curManu').val();
		  			if (radioID==2 && $("#curPort").val().indexOf("45")==-1){
						$("#modalA-body").html("For Hamlib NET rigctl, port must be a number: 4532, or 4534, or ... (see Help)");			  				
						$("#modalA-title").html("Radio Port Problem");
					  	$("#myModalAlert").modal({show:true});
		  				el.removeClass('fa-spin');
		  				el.hide();
		  			}else{
					  	$.post("/programs/SetMyRadioBasic.php", {m: $("#curManu").val(), 
						  	o: $("#curRadio").val(), p: $("#curPort").val(), n: $("#curName").val(), 
						  	i: tMyRadio, k: $("#curKeyer").val(), kp: $("#curKeyerPort").val(), 
						  	d: radioID}, function(response){
					  		if (nBypassAlert==0){
								$("#modalA-body").html(response);			  				
								$("#modalA-title").html("Settings Saved");
							  	$("#myModalAlert").modal({show:true});
							}else{  //connect to radio
								if (tMyKeyer=='rpk'){
									tMyCWPort='/dev/ttyS0';
								}
								$.post('./programs/hamlibDo.php', {test: 0, keyer: tMyKeyer, radio: tMyRadio, user: tUserName, port: tCWPort, tcpPort: "30001", rotorPort: tMyRotorPort, keyerPort:tMyKeyerPort, keyerIP:tMyKeyerIP, keyerFunc:tMyKeyerFunction}, function(response) {
					  				if (response.length>20){
										$("#modalA-body").html(response);			  				
										$("#modalA-title").html("Radio Connection");
									  	$("#myModalAlert").modal({show:true});
						  				if (response.indexOf("Now starting RigPi Radio")>0){
							  				$('#connect').text("Radio connected");
						  				}
						  				if (tMyPTT==2){
						  					$.post('/programs/doGPIOPTT.php', {PTTControl: "on"}); 			  				
						  				}
					  				}else{
						  				alert(response);
					  				}
					  				el.removeClass('fa-spin');
					  				el.hide();
								});
							};
						  	tMyKeyer=$("#curKeyer").val()
						  	tMyKeyer=getKeyerID(tMyKeyer);
						  	tMyCWPort=$("#curKeyerPort").val();
						  	$.post("/programs/SetSettings.php", {field:"powerOut", data:$("#curPwr").val(), radio:tMyRadio, table:"RadioInterface"}, function(response){
							});
						});
		  			};
	  			});
	  		};
	  	});

		var tUpdate = setInterval(updateTimer,1000);
		function updateTimer()
		{
	       $.post('/programs/GetInterfaceIn.php',{radio: tMyRadio, un: tUserName, myCall:<?php echo "'" .
          $tCall .
          "'"; ?>}, function(response) 
	        {
		        updateFooter();
			});
	        var now = new Date();
	        var now_hours=now.getUTCHours();
	        now_hours=("00" + now_hours).slice(-2);
	        var now_minutes=now.getUTCMinutes();
	        now_minutes=("00" + now_minutes).slice(-2);
            $("#fPanel5").text(now_hours+":"+now_minutes+'z');
			$.post("./programs/GetRotorIn.php", {rotor: tMyRadio},function(response){
				var tAData=response.split('`');
				tAData=response;
				if (tAData=="+"){
					tAData="--";
				}
				var tAz=Math.round(tAData)+"&#176;";
				$(".angle").html(tAz);
			});
	  	}

	  	$.getScript("/js/addPeriods.js");

	</script>
	<?php require $dRoot . "/programs/ManufacturersDB.php"; ?>
</head>

<body class="body-black">
	<?php require $dRoot . "/includes/header.php"; ?>
	<div class="container-fluid">
		<div class="row" style="margin-bottom:10px;" >
			<div class="col-12  col-lg-4 btn-padding">
			</div>
			<div class="col-6 col-lg-4 text-center">
				<span class="label label-success text-white" style="cursor: default; margin-top:10px;">Basic Radio Settings (User: <?php echo $tUserName; ?>)</span>
			</div>
			<div class="col-6 col-lg-4 btn-padding">
				<button class='btn btn-color radioSave' type='button'>
					<i class="fas fa-cloud-upload-alt fa-lg"></i>
				</button>
			</div>
		</div>
		<hr>
		<div class="row">
			<div class="col-lg-4 text-spacer">
				<div class="input-group">
					<div class="input-group-prepend">
						<span class="input-group-text radio-group-addon">Manuf</span>
					</div>
					<input type="text" class="form-control disable-text" id="curManu" readonly="readonly" title="Selected Radio Manufacturer" aria-lable="manufacturer" aria-describedby="manufacturer-addon">
				    <span class="input-group-btn">
			            <div class="dropdown">
						    <button class="btn btn-primary dropdown-toggle" id="manuSel" data-size="3" type="button" title="Manufacturer List" data-toggle="dropdown"><i class="fas fa-list-alt fa-lg"></i>
						    </button>
						    <ul class="dropdown-menu dropdown-menu-right menu-scroll" id="manufacturerList">
								<?php echo getRadioManufacturers(); ?>
						     </ul>
			            </div>
				    </span>
				</div>
			</div>
			<div class="col-lg-4 text-spacer ">
				<div class="input-group">
					<div class="input-group-prepend">
						<span class="input-group-text radio-group-addon">Model</span>
					</div>
					<input type="text" class="form-control disable-text" id="curRadio" readonly="readonly"  title="Selected Radio" aria-lable="radio" aria-describedby="radio-addon">
				    <span class="input-group-btn">
			            <div class="dropdown">
						    <button class="btn btn-primary dropdown-toggle" id="radioSel" data-size="3" type="button"  title="Radio List" data-toggle="dropdown"><i class="fas fa-list-alt fa-lg"></i>
						    </button>
						    <ul class="dropdown-menu dropdown-menu-right menu-scroll" id="radioList">
						     </ul>
			            </div>
				    </span>
				</div>
			</div>
			<div class="col-lg-4 text-spacer">
				<div class="input-group">
					<div class="input-group-prepend">
						<span class="input-group-text radio-group-addon">Name</span>
					</div>
					<input type="text" class="form-control"  title="Radio Name" id="curName" aria-lable="name" aria-describedby="name-addon">
				</div>
			</div>
		</div>
		<div class="row">
			<div class="col-lg-4 text-spacer">
				<div class="input-group">
					<div class="input-group-prepend">
						<span class="input-group-text radio-group-addon">R Port</span>
					</div>
					<input type="text" class="form-control" id="curPort"  title="Radio Port" aria-lable="manufacturer" aria-describedby="manufacturer-addon">
				    <span class="input-group-btn">
			            <div class="dropdown">
						    <button class="btn btn-primary dropdown-toggle" id="portSel" data-size="3" type="button" title="Port List" data-toggle="dropdown"><i class="fas fa-list-alt fa-lg"></i>
						    </button>
						    <ul class="dropdown-menu dropdown-menu-right menu-scroll" id="portList">
								<?php require $dRoot . "/programs/portScan.php"; ?>
						     </ul>
			            </div>
				    </span>
				</div>
			</div>
			<div class="col-lg-4 text-spacer">
				<div class="input-group">
					<div class="input-group-prepend">
						<span class="input-group-text radio-group-addon">Keyer</span>
					</div>
					<input type="text" class="form-control disable-text" id="curKeyer" readonly="readonly"  title="Keyer" aria-lable="keyer" aria-describedby="keyer-addon">
				    <span class="input-group-btn">
			            <div class="dropdown">
						    <button class="btn btn-primary dropdown-toggle" id="keyerSel" data-size="3" type="button" title="Keyer List" data-toggle="dropdown"><i class="fas fa-list-alt fa-lg"></i>
						    </button>
						    <ul class="dropdown-menu dropdown-menu-right menu-scroll" id="keyerList">
								<div class='mykeyer' id='non'><li><a class='dropdown-item' href='#'>None</a></li></div>
								<div class='mykeyer' id='rpk'><li><a class='dropdown-item' href='#'>RigPi Keyer</a></li></div>
								<div class='mykeyer' id='cat'><li><a class='dropdown-item' href='#'>via CAT</a></li></div>
								<div class='mykeyer' id='wkr'><li><a class='dropdown-item' href='#'>WinKeyer</a></li></div>
						     </ul>
			            </div>
				    </span>
				</div>
			</div>
			<div class="col-lg-4 text-spacer">
				<div class="input-group">
					<div class="input-group-prepend">
						<span class="input-group-text radio-group-addon">K Port</span>
					</div>
					<input type="text" class="form-control" id="curKeyerPort" readonly="readonly"  title="Keyer Port" aria-lable="keyerport" aria-describedby="keyerport-addon">
				    <span class="input-group-btn">
			            <div class="dropdown">
						    <button class="btn btn-primary dropdown-toggle" id="cwPortSel" data-size="3" type="button" title="Port List" data-toggle="dropdown"><i class="fas fa-list-alt fa-lg"></i>
						    </button>
						    <ul class="dropdown-menu dropdown-menu-right menu-scroll" id="portList">
								<?php require $dRoot . "/programs/cwPortScan.php"; ?>
						     </ul>
			            </div>
				    </span>
				</div>
			</div>
		</div>
		<div class="row">
			<div class="col-lg-4 text-spacer">
				<div class="input-group">
					<div class="input-group-prepend">
						<span class="input-group-text radio-group-addon">Tx Pwr</span>
					</div>
					<input type="text" class="form-control"  title="Transmit Meter Calibration" id="curPwr" aria-lable="pwr" aria-describedby="pwr-addon">
				</div>
			</div>
			<div class="col-6 col-md-2 text-center text-spacer">
				<button class="btn btn-outline-success btn-sm my-2 my-sm-0 text-white" id="connectButton"  title="Click to Connect to Radio" type="button">
							<i class="fas fa-play fa-fw"></i>
					Connect Radio
				</button>
			</div>
			<div class="col-6 col-md-2 text-center text-spacer">
				<div class="text-spacer" id="spinner"><i class="fas fa-sync" style="color:white;font-size:18px"></i></div>
			 </div>
			<div class="col-3 col-md-4 text-center text-spacer">
				<button class="btn btn-outline-danger btn-sm my-2 my-sm-0 text-white" id="disconnectButton"  title="Click to disconnect from Radio" type="button">
							<i class="fas fa-stop fa-fw"></i>
					Disconnect Radio
				</button>
			</div>
			</div>
		</div>
		<div class="row">
		</div>
		<div class="row">
		</div>
	</div>
    <?php require $dRoot . "/includes/footer.php"; ?>
    <?php require $dRoot . "/includes/modal.txt"; ?>
    <?php require $dRoot . "/includes/modalAlert.txt"; ?>
<?php require $dRoot . "/includes/modalCancelOnly.txt"; ?>
<script src="./Bootstrap/popper.min.js"</script>
<link rel="stylesheet" href="./Bootstrap/jquery-ui.css">
<script src="./Bootstrap/jquery-ui.js"></script>
<script src="./Bootstrap/bootstrap.min.js"></script>
<script src="js/nav-active.js"></script>
</body>
</html>

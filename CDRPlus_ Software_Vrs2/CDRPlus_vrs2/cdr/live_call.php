<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html>
<head>
<title>CDR Reporter - Ext Based</title>
<link rel='stylesheet' type='text/css' href='css/cdrtable.css' />

<script type='text/javascript' src='js/jquery-1.7.1.min.js'></script>
<script type='text/javascript' src='js/jquery-ui-1.8.17.custom.min.js'></script>
<script type='text/javascript' src='js/tablesort.js'></script>
<style type="text/css">
	#date {
	
	}
	#rotation_config {
	}
</style>
</head>
<body>
<div id="table_wrapper" > 
	<div style="font-size: 12px;">
		<div id="rotation_config" >
			<div id="date" style="float: left; width: 30%; color: white;"></div>
			<div style="float: left; width: 25%; color: white; text-align:center;">?</div>
			<div style="float: left; width: 19%; "><a href="index.php" style="text-decoration: none;cursor: pointer;">MAIN PAGE</a></div>
			<div style="float: left; width: 25%; ">
				<a href="javascript:void(0);" onclick="$('.rot_config_board').toggle();">Page Rotation Configuration</a>
				<ul class="rot_config_board" style="display: none;">
					<li><input type="checkbox" /> <span>ALL</span></li>
					<li><input type="checkbox" /> <span>Calls / Today</span></li>
					<li><input type="checkbox" /> <span>Calls / This Week</span></li>
					<li><input type="checkbox" /> <span>Calls / This Month</span></li>
					<li><input type="checkbox" /> <span>Teams / Today</span></li>
					<li><input type="checkbox" /> <span>Teams / This Week</span></li>
					<li><input type="checkbox" /> <span>Teams / This Month</span></li>
					<li style="text-align:center;"><button id="config_ok">OK</button><button id="config_cancel">Cancel</button></li>
				</ul>
			</div>
			<div style="clear: both;"></div>
		</div>
<!--		<a id="exportlink" href="export.php" >Export as csv</a><br />-->
<!--		<a id="exportemaillink" href="javascript:void(0);" >Export &amp email</a>-->
		
	</div>
	
	<div id="refresh_area"></div> 
	
	<script type='text/javascript'>
	var monthNames = ['January','February','March','April','May','June','July','August','September','October','November','December'];
	var dayNames = ['Sun','Mon','Tue','Wed','Thu','Fri','Sat'];
	
	var rotation_config;
    var refreshtimer;
    
    function zeroPad(n) {
    	return (n < 10 ? '0' : '') + n;
    }
    function dateSuffix(date)
    {
        if(date == 1) return "1st";
        else if(date == 2) return "2nd";
        else if(date == 3) return "3rd";
        else return date + "th";
    }
	function startRefreshTimer()
	{
		var pointer = 0;
		var loopdetecter = 0;

        function rotate()
		{
    		var date = new Date();
    		
    		$("#date").html(zeroPad(date.getHours()) + ":" + zeroPad(date.getMinutes()) + "  " + 
    				dayNames[date.getDay()] + " " + dateSuffix(date.getDate()) + 
    				 " " + monthNames[date.getMonth()] +  " " + date.getFullYear() );
			var url = null;
        	loopdetecter = 0;
        	
			while(loopdetecter++ < 6)
			{
				if(pointer == 0 && rotation_config.call_today) url = "calls.php?range=today";
				if(pointer == 1 && rotation_config.call_this_week) url = "calls.php?range=thisweek";
				if(pointer == 2 && rotation_config.call_this_month) url = "calls.php?range=thismonth";
				if(pointer == 3 && rotation_config.team_today) url = "teams.php?range=today";
				if(pointer == 4 && rotation_config.team_this_week) url = "teams.php?range=thisweek";
				if(pointer == 5 && rotation_config.team_this_month) url = "teams.php?range=thismonth";

				pointer = (pointer + 1) % 6;
				if(url) break; 
			}

			if(!url) return;

	        $.ajax({
	            type : "POST",
	            url  : url,
	            async : false,
	            dataType: "html",
	            cache: false,
	            success: function(data, textStatus, jqXHR) {
	            	$("#refresh_area").html(data);
	        		$("#calls_table").sort({
	        			index: url.substr(0, 5) == "calls" ? 5 : 2,
	        			ascending: false,
	        			numberCol: false	
	        		});

	        		$("#exportlink").attr("href", "export.php?param=" + url.replace("?", "&"));     	
	            },
	            error : function( jqXHR, textStatus, errorThrown ){ alert(textStatus);},
	            complete: function( jqXHR, textStatus){}
	         });				
	    }

        rotate();
        refreshtimer = setInterval( function () { rotate(); }, 12000);
	}
		

	$(document).ready(function(){
		$("#exportemaillink").click(function(){
			var addr = prompt("Please enter proper mail address.", "address@domain");
			
	        $.ajax({
	            type : "GET",
	            url  : "export.php",
	            data : $("#exportlink").attr("href").substr(11) + "&email=" + addr,
	            async : false,
	            dataType: "text",
	            cache: false,
	            success: function(data, textStatus, jqXHR) {
		            alert(data);
	            },
	            error : function( jqXHR, textStatus, errorThrown ){ alert(textStatus);},
	            complete: function( jqXHR, textStatus){}
	         });	
		});
		
		$($(".rot_config_board input")[0]).change(function (){
			$(".rot_config_board input")[1].checked = this.checked;
			$(".rot_config_board input")[2].checked = this.checked;
			$(".rot_config_board input")[3].checked = this.checked;
			$(".rot_config_board input")[4].checked = this.checked;
			$(".rot_config_board input")[5].checked = this.checked;
			$(".rot_config_board input")[6].checked = this.checked;
		});
		
		$("#config_cancel").click(function(){
			
			$(".rot_config_board input")[1].checked = rotation_config.call_today ? true : false;
			$(".rot_config_board input")[2].checked = rotation_config.call_this_week ? true : false;
			$(".rot_config_board input")[3].checked = rotation_config.call_this_month ? true : false;
			$(".rot_config_board input")[4].checked = rotation_config.team_today ? true : false;
			$(".rot_config_board input")[5].checked = rotation_config.team_this_week ? true : false;
			$(".rot_config_board input")[6].checked = rotation_config.team_this_month ? true : false;

			$('.rot_config_board').hide();
		});
		
		$("#config_ok").click(function(){
			
	        $.ajax({
	            type : "POST",
	            url  : "set-rotation-config.php",
	            data : "call_today=" + ($(".rot_config_board input")[1].checked ? 1 : 0) +
	            	   "&call_thisweek=" + ($(".rot_config_board input")[2].checked ? 1 : 0) + 
	            	   "&call_thismonth=" + ($(".rot_config_board input")[3].checked ? 1 : 0) +
	            	   "&team_today=" + ($(".rot_config_board input")[4].checked ? 1 : 0) +
	            	   "&team_thisweek=" + ($(".rot_config_board input")[5].checked ? 1 : 0) + 
	            	   "&team_thismonth=" + ($(".rot_config_board input")[6].checked ? 1 : 0),
	            async : false,
	            dataType: "text",
	            cache: false,
	            success: function(data, textStatus, jqXHR) {
		            if(data == "fail") {alert("Save failed."); return; }
	            	rotation_config.call_today = $(".rot_config_board input")[1].checked ? 1 : 0;
	            	rotation_config.call_this_week = $(".rot_config_board input")[2].checked ? 1 : 0;
	            	rotation_config.call_this_month = $(".rot_config_board input")[3].checked ? 1 : 0;
	            	rotation_config.team_today = $(".rot_config_board input")[4].checked ? 1 : 0;
	            	rotation_config.team_this_week = $(".rot_config_board input")[5].checked ? 1 : 0;
	            	rotation_config.team_this_month = $(".rot_config_board input")[6].checked ? 1 : 0;
	            	
	            },
	            error : function( jqXHR, textStatus, errorThrown ){ alert(textStatus);},
	            complete: function( jqXHR, textStatus){}
	         });

			$('.rot_config_board').hide();
		});
		
        $.ajax({
            type : "POST",
            url  : "get-rotation-config.php",
            async : false,
            dataType: "json",
            cache: false,
            success: function(data, textStatus, jqXHR) {
            	rotation_config = data;
            	rotation_config.call_today = parseInt(rotation_config.call_today);
            	rotation_config.call_this_week = parseInt(rotation_config.call_this_week);
            	rotation_config.call_this_month = parseInt(rotation_config.call_this_month);
            	rotation_config.team_today = parseInt(rotation_config.team_today);
            	rotation_config.team_this_week = parseInt(rotation_config.team_this_week);
            	rotation_config.team_this_month = parseInt(rotation_config.team_this_month);
            	
				$(".rot_config_board input")[1].checked = rotation_config.call_today ? true : false;
				$(".rot_config_board input")[2].checked = rotation_config.call_this_week ? true : false;
				$(".rot_config_board input")[3].checked = rotation_config.call_this_month ? true : false;
				$(".rot_config_board input")[4].checked = rotation_config.team_today ? true : false;
				$(".rot_config_board input")[5].checked = rotation_config.team_this_week ? true : false;
				$(".rot_config_board input")[6].checked = rotation_config.team_this_month ? true : false;
				startRefreshTimer();
            },
            error : function( jqXHR, textStatus, errorThrown ){ alert(textStatus);},
            complete: function( jqXHR, textStatus){}
         });


	});
	</script>	
</div>
</body>

</html>

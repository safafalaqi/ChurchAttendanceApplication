<?php
include_once("Conn.php");
include_once("common.php");

	class attendance{

		var $dtmDate;
		var $intDay;
		var $intAttendanceID;
		var $intMen;
		var $intWomen;
		var $intChildren;
		var $intTotal;
		var $intSundaySchoolCount;
		var $arrAttendanceRecords;
		var $strSQLString;
		var $objPagination;
		var $intRecordCount;


		function attendance(){
		}

		function javaScript(){
			ob_start();
			$strJs ="";	
		?>
			<link rel="stylesheet" type="text/css" href="attendance.css">
			<link rel="stylesheet" type="text/css" href="dhtmlxSuite/codebase/dhtmlx.css"></link>
			<link rel="stylesheet" type="text/css" href="dhtmlxGrid/skins/skyblue/dhtmlxgrid.css"></link>
			<!-- <link rel="stylesheet" type="text/css" href="dhtmlxGrid/codebase/dhtmlxgrid.css"></link> -->
			<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.8.3/jquery.min.js"></script>
			<script src="http://code.jquery.com/ui/1.9.2/jquery-ui.js"></script>
			<script type="text/javascript" src="dhtmlxGrid/codebase/dhtmlxgrid.js"></script>
			<script type="text/javascript" src="test.js"></script> 
			<script>
				var objobjMyGrid;
				var intAttendanceDetails = 0;
				var blnNewAttendance;
				window.onload = function(){
					 $("#dateFilter").datepicker();
					 // objMyGrid = new dhtmlXGridObject('attendanceDiv');                 
      //   			  objMyGrid.setHeader("Date,Day,MenCount,WomenCount,ChildrenCount,Total,SundaySchoolCount");//the headers of columns  
      //   			  objMyGrid.setImagePath("dhtmlxGrid/skins/skyblue/imgs/dhxgrid_skyblue/");
      //   			  objMyGrid.setInitWidths("80,80,80,100,100,100,115");          //the widths of columns  
      //   			  objMyGrid.setColAlign("left,left,left,left,left,left,left");       //the alignment of columns   
      //   			  objMyGrid.setColTypes("ro,ro,ro,ro,ro,ro,ro");
      //   			  objMyGrid.init();      //finishes initialization and renders the grid on the page 
      //      			  objMyGrid.enableSmartRendering(true);
      //      			  objMyGrid.load("attendanceXML.php", "xml");
				}

				function filterByDate(){
					var dtmSelectedDate = document.getElementById("dateFilter").value;
					document.getElementById("dateEntered").value = dtmSelectedDate;
					document.getElementById("frmFilter").submit();
				}
				
				function loadAll(){
					document.getElementById("blnLoadAll").value = 1;
					document.getElementById("frmFilter").submit();
				}

				function deleteAttendance(intAttendanceID){
					var response = confirm("Are you sure you want to delete this attendance?");
					if(response == true){
						document.getElementById("attendanceID").value = intAttendanceID;
						//document.getElementById("dateEntered").value = dtmSelectedDate
						document.getElementById("blnDeleteAttendance").value = 1;
						document.getElementById("frmFilter").submit();
						//alert("You said yes");
					}
				}

				// function getYear(){

				// }

				function filterByMonth(){
					var objMonthSelect = document.getElementById("MonthSelect");
					var intSelectedMonth = objMonthSelect.options[objMonthSelect.selectedIndex].value;
					var dtmYear = document.getElementById("intYear").value;
					if(intSelectedMonth == "" || dtmYear == ""){
						alert("Please fill in a month and year first");
					}
					if(intSelectedMonth != "" && dtmYear != ""){
						document.getElementById("dtmMonth").value = dtmYear + "-" + intSelectedMonth;
						document.getElementById("blnLoadByMonth").value = 1;
						document.getElementById("frmFilter").submit();
					}
					//alert("Month Format: " + dtmYear + "-" + intSelectedMonth);
				}

				// function getSelectedValue(){

				// }
				function RefreshPage(){
					location.reload();					
				}

				function showPopUpWindow(intAttendanceID){
					if(intAttendanceID == null){
						blnNewAttendance = true;
					}
					else{
						blnNewAttendance = false;
					}

					var strURL = "attendanceDetails.php?intAttendanceID="+intAttendanceID+"&blnIsNewAttendance="+blnNewAttendance;
					window.open(strURL,intAttendanceDetails,'width=570,height=430,scrollbars=yes resizable=yes');
					intAttendanceDetails++;
				}
			</script>	
			<?
			$strJs .= ob_get_contents();
			ob_end_clean();
			return $strJs;	
		}

		function toHTML(){
			ob_start();
			$strHTML = $this->javaScript();	
			?>
			<div class="newAttendanceBtn" onclick="showPopUpWindow(null)">New Attendance</div>
			<span><hr/></span>
			<form method="post" id="frmFilter" action="<?=$_SERVER['PHP_SELF'];?>">
				<fieldset style="width:410px;">
					<legend>Filters</legend>
					Filter By Date: <input type="text" id="dateFilter" class="dateDropDown"></input>
					<input type="button" value="Filter" onclick="filterByDate()"/>
					<br/>
					<div class="monthFilterSection">
					Year: <input type="text" id="intYear" style="width:80px;"></input>
					Month: <select id="MonthSelect">
							<option value=""></option>
							<option value="01">January</option>
							<option value="02">Feburary</option>
							<option value="03">March</option>
							<option value="04">April</option>
							<option value="05">May</option>
							<option value="06">June</option>
							<option value="07">July</option>
							<option value="08">August</option>
							<option value="09">September</option>
							<option value="10">October</option>
							<option value="11">November</option>
							<option value="12">December</option>
						</select>
					<input type="button" value="FilterByMonth" onclick="filterByMonth()"/>
				  </div>
				  <input type="button" value="Show All" onclick="loadAll()"/>
				  <input type="button" value="Refresh Site" onclick="RefreshPage()">	
				</fieldset>
				<input type="hidden" value="" id="dateEntered" name="dateEntered"/>
				<input type="hidden" value="" id="attendanceID" name="intAttendanceID"/>
				<input type="hidden" value="" id="dtmMonth" name="dtmMonth"/>
				<input type="hidden" value="0" id="blnLoadByMonth" name="blnLoadByMonth"/>
				<input type="hidden" value="0" id="blnLoadAll" name="blnLoadAll"/>
				<input type="hidden" value="0" id="blnDeleteAttendance" name="blnDeleteAttendance"/>
			</form>
			 
			<? 	
			if(isset($_POST["blnDeleteAttendance"]) && $_POST["blnDeleteAttendance"] == 1){
				$this->deleteAttendance($_POST["intAttendanceID"]);
			}

			if($_POST["blnLoadAll"] == 1 || $_SESSION["blnLoadAll"] == 1){
				$_SESSION["blnLoadAll"] = 1; 
				$this->loadAllAttendance();
			}

			if ($_POST["blnLoadByMonth"] == 1 || $_SESSION["blnLoadByMonth"] == 1) {
				//var_dump($_POST);
				$_SESSION["blnLoadByMonth"] = 1;
				$this->loadByMonth($_POST["dtmMonth"]);
			}
			if ($_POST["dateEntered"]) {
				$_SESSION["dateEntered"] = $_POST["dateEntered"]; 
			}

			if ($_POST["dateEntered"]  == "") {
				$this->loadAttendance($_SESSION["dateEntered"]);
			}
			else{
				$this->loadAttendance($_POST["dateEntered"]);	
			}
				//var_dump($this->arrAttendanceRecords);
			?>
			<table border="1" style="width:1550px; text-align:center;">
				<tr>
					<th>RowNO</th>
					<th>DATE</th>
					<th>SERVICE</th>
					<th>MESSAGE</th>
					<th>PREACHER</th>
					<th>FIRST TIMERS</th>
					<th>MALE</th>
					<th>MALE SINGLE</th>
					<th>FEMALE</th>
					<th>FEMALE SINGLE</th>
					<th>CHILDREN</th>
					<th>TOTAL</th>
					<th>SUNDAY SCHOOL</th>
				</tr>
				<?if($this->arrAttendanceRecords != NULL){
					$intRowCount = 0;
					//echo $this->intAttendanceID;
					foreach ($this->arrAttendanceRecords as $intAttID => $arrRows) {?>
					<?$intRowCount++;?>
						<tr>
						<td ondblclick=<?echo "deleteAttendance(".$intAttID.")";?> ><a href="#" onclick=<?echo "showPopUpWindow(".$intAttID.")";?> ><?echo $intRowCount;?></a></td>	
						<?foreach ($arrRows as $mixKey => $mixValue) {?>
							<?if($mixKey != "intAttendanceID" && $mixKey == "dtmDate"){?>
								<td><?echo formatDate($mixValue);?></td>
							<?}
							else if($mixKey != "intAttendanceID"){?>
								<td><?echo $mixValue; ?></td>
							<?}?>		
						<?}?>
						</tr> 
					<?}
				}
				else{?>
					<tr><td colspan='13'><h3 style='text-align:center;'>The is no record for that date.</h3></td></tr>
				<?}?>
			</table>
			<? 	
			$strHTML .= ob_get_contents();
			ob_end_clean();
			return $strHTML;	
		}

		function deleteAttendance($intAttendanceID){
			global $connection;
			$strSQL = "DELETE FROM asikpo_attendance.tblChurchAttendance
			 			WHERE intAttendanceID = '$intAttendanceID' "; 
				
			$rsResult = mysqli_query($connection, $strSQL);
			if($rsResult){
				echo "<script>alert('Item Deleted')</script>";
			}	
		}

		function loadByMonth($dtmMonth){
			global $connection;
			$dtmStartDate = $dtmMonth."-01";
			$dtmEndDate = $dtmMonth."-31";
			$strSQL = "SELECT intAttendanceID, dtmDate, strServiceType, strMessage, strPreacher, intFirstTimer, intMale, 
						intMaleSingle,intFemale, intFemaleSingle, intChildren, intTotal, intSundaySchool
			 			FROM asikpo_attendance.tblChurchAttendance
			 			WHERE dtmDate BETWEEN '$dtmStartDate' AND '$dtmEndDate'
			 			ORDER BY intAttendanceID";

			$rsResult = mysqli_query($connection, $strSQL);
			//echo "Query: ".$strSQL;
			// $this->intRecordCount = mysqli_num_rows($rsResult);
			while ($arrRow = mysqli_fetch_assoc($rsResult)) {
				//$this->intAttendanceID = $arrRow["intAttendanceID"];
				$this->arrAttendanceRecords[$arrRow["intAttendanceID"]] = $arrRow;
			}
			//var_dump($this->arrAttendanceRecords);	 				
		}	
		function loadAllAttendance(){
			global $connection;
			$strSQL = "SELECT intAttendanceID, dtmDate, strServiceType, strMessage, strPreacher, intFirstTimer, intMale, 
						intMaleSingle,intFemale, intFemaleSingle, intChildren, intTotal, intSundaySchool
			 			FROM asikpo_attendance.tblChurchAttendance
			 			ORDER BY intAttendanceID";
			$rsResult = mysqli_query($connection, $strSQL);

			while ($arrRow = mysqli_fetch_assoc($rsResult)) {
				//$this->intAttendanceID = $arrRow["intAttendanceID"];
				$this->arrAttendanceRecords[$arrRow["intAttendanceID"]] = $arrRow;
			}
		}

		function loadAttendance($dtmDateToFilter){
			global $connection;
			if ($dtmDateToFilter) {
				$pieces = explode("/", $dtmDateToFilter);
				$dtmNewDate = date("Y-m-d",strtotime($pieces[2]."-".$pieces[0]."-".$pieces[1]));
				$this->dtmDate = $dtmNewDate;	
			}
			else{
				$this->dtmDate = null;
			} 		
			$strSQL = "SELECT intAttendanceID, dtmDate, strServiceType, strMessage, strPreacher, intFirstTimer, intMale, 
						intMaleSingle,intFemale, intFemaleSingle, intChildren, intTotal, intSundaySchool
			 			FROM asikpo_attendance.tblChurchAttendance
			 			WHERE dtmDate = '$this->dtmDate' 
			 			ORDER BY intAttendanceID"; 
				
			$rsResult = mysqli_query($connection, $strSQL);
			// if($rsResult){
			// 	//echo "Query string ". $strSQL;
			// }
			while ($arrRow = mysqli_fetch_assoc($rsResult)) {
				$this->intAttendanceID = $arrRow["intAttendanceID"];
				$this->arrAttendanceRecords[$arrRow["intAttendanceID"]] = $arrRow;
			}
			//echo "ID: ". $this->$intAttendanceID;
		}
	}
	$objDisplayAttendance = new attendance();
	echo $objDisplayAttendance->toHTML();
?>

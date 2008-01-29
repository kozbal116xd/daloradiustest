<?php 

    include ("library/checklogin.php");
    $operator = $_SESSION['operator_user'];

	include('library/check_operator_perm.php');

	$logDebugSQL = "";

	if (isset($_POST['submit'])) {
		(isset($_REQUEST['operator_username'])) ? $operator_username = $_REQUEST['operator_username'] : $operator_username = "";
		(isset($_REQUEST['operator_password'])) ? $operator_password = $_REQUEST['operator_password'] : $operator_password = "";

	include 'library/opendb.php';

		if ( (trim($operator_username) != "") && (trim($operator_password) != "") ) {

			$sql = "SELECT * FROM ".$configValues['CONFIG_DB_TBL_DALOOPERATOR']." WHERE username='$operator_username'";
			$res = $dbSocket->query($sql);
			$logDebugSQL .= $sql . "\n";

			if ($res->numRows() == 0) {

				$sql = "insert into ".$configValues['CONFIG_DB_TBL_DALOOPERATOR']." (id, username, password) values (0, '$operator_username', '$operator_password')";
				$res = $dbSocket->query($sql);
				$logDebugSQL .= $sql . "\n";
			
				foreach ($_POST as $field => $value ) { 
					if ( ($field == "operator_username") || ($field == "operator_password") )
						continue; // we skip these variables as we have already added the user to the database

					if ($field == "submit")
						continue; // we skip these variables as it is of no important for us
			
					$sql = "UPDATE ".$configValues['CONFIG_DB_TBL_DALOOPERATOR']." SET $field='$value' WHERE username='$operator_username' ";
					$res = $dbSocket->query($sql);
					$logDebugSQL .= $sql . "\n";

				} // foreach

                                $actionStatus = "success";
                                $actionMsg = "Added to database new operator user: <b> $operator_username </b>";
                                $logAction = "Successfully added new operator user [$operator_username] on page: ";

			} else {
				// if statement returns false which means there is at least one operator
				// in the database with the same username

	                        $actionStatus = "failure";
	                        $actionMsg = "operator user already exist in database: <b> $operator_username </b>";
	                        $logAction = "Failed adding new operator user already existing in database [$operator_username] on page: ";
			}
			
		} else {
			// if statement returns false which means that the user has left an empty field for
			// either the username or password, or both

                        $actionStatus = "failure";
                        $actionMsg = "username or password are empty";
                        $logAction = "Failed adding (possible empty user/pass) new operator user [$operator_username] on page: ";
		}


	include 'library/closedb.php';

	} // if form was submitted
	

    include_once('library/config_read.php');
    $log = "visited page: ";

	
	if ($configValues['CONFIG_IFACE_PASSWORD_HIDDEN'] == "yes")
		$hiddenPassword = "type=\"password\"";
	
?>



<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">
<head>
<title>daloRADIUS</title>
<meta http-equiv="content-type" content="text/html; charset=utf-8" />
<link rel="stylesheet" href="css/1.css" type="text/css" media="screen,projection" />
<link rel="stylesheet" type="text/css" href="library/js_date/datechooser.css">
<!--[if lte IE 6.5]>
<link rel="stylesheet" type="text/css" href="library/js_date/select-free.css"/>
<![endif]-->
</head>


<?php
        include_once ("library/tabber/tab-layout.php");
?>
 
<?php

	include ("menu-config-operators.php");
	
?>
		
		<div id="contentnorightbar">
		
				<h2 id="Intro"><a href="#" onclick="javascript:toggleShowDiv('helpPage')"><?php echo $l['Intro']['configoperatorsnew.php'] ?>
				<h144>+</h144></a></h2>
				
                <div id="helpPage" style="display:none;visibility:visible" >
					<?php echo $l['helpPage']['configoperatorsnew'] ?>
					<br/>
				</div>
				<br/>

				<form name="newoperator" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">

<div class="tabber">

     <div class="tabbertab" title="Operator Info">
        <br/>

	<fieldset>

                <h302>Account Settings</h302>
		<br/>

                <label for='operator_username' class='form'>Operator Username</label>
                <input name='operator_username' type='text' id='operator_username' 
			value='<?php if (isset($operator_username)) echo $operator_username ?>' tabindex=100 />
                <br/>

                <label for='operator_password' class='form'>Operator Password</label>
                <input name='operator_password' id='operator_password' 
			value='<?php if (isset($operator_password)) echo $operator_password ?>' 
			type='<?php if (isset($operator_hiddenPassword)) echo $hiddenPassword; else echo "text"; ?>'
			tabindex=101 />
                <br/>

                <br/><br/>
                <hr><br/>

                <input type='submit' name='submit' value='<?php echo $l['buttons']['apply'] ?>' class='button' />

	</fieldset>

	</div>

     <div class="tabbertab" title="Contact Info">
        <br/>

<?php
	include_once('include/management/operatorinfo.php');
?>

	</div>

     <div class="tabbertab" title="ACL Settings">
        <br/>


<?php
        include_once('include/management/operator_tables.php');
        drawPagesPermissions($arrayPagesAvailable);
?>

	</div>

</div>	
				</form>
				
<?php
	include('include/config/logging.php');
?>
		</div>
		
		<div id="footer">
		
								<?php
        include 'page-footer.php';
?>

		
		</div>
		
</div>
</div>


</body>
</html>





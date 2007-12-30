<?php

    include ("library/checklogin.php");
    $operator = $_SESSION['operator_user'];

	include('library/check_operator_perm.php');


	//setting values for the order by and order type variables
	isset($_REQUEST['orderBy']) ? $orderBy = $_REQUEST['orderBy'] : $orderBy = "username";
	isset($_REQUEST['orderType']) ? $orderType = $_REQUEST['orderType'] : $orderType = "asc";

	
	include_once('library/config_read.php');
    $log = "visited page: ";


	$username = "";
	$username = $_REQUEST['username'];

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">
<head>

<script src="library/javascript/pages_common.js" type="text/javascript"></script>

<title>daloRADIUS</title>
<meta http-equiv="content-type" content="text/html; charset=utf-8" />
<link rel="stylesheet" href="css/1.css" type="text/css" media="screen,projection" />

</head>
 
 
<?php
	include ("menu-mng-rad-usergroup.php");
?>
		
		<div id="contentnorightbar">
		
				<h2 id="Intro"><a href="#" onclick="javascript:toggleShowDiv('helpPage')"><?php echo $l['Intro']['mngradusergrouplistuser'] ?>
				<h144>+</h144></a></h2>

				<div id="helpPage" style="display:none;visibility:visible" >
					<?php echo $l['helpPage']['mngradusergrouplistuser'] ?>
					<br/>
				</div>			
				<br/>
<?php

	
	include 'library/opendb.php';
	include 'include/management/pages_numbering.php';		// must be included after opendb because it needs to read the CONFIG_IFACE_TABLES_LISTING variable from the config file

	//orig: used as maethod to get total rows - this is required for the pages_numbering.php page
	$sql = "SELECT * FROM ".$configValues['CONFIG_DB_TBL_RADUSERGROUP']." WHERE UserName='".$dbSocket->escapeSimple($username)."';";
	$res = $dbSocket->query($sql);
	$numrows = $res->numRows();

	
	$sql = "SELECT * FROM ".$configValues['CONFIG_DB_TBL_RADUSERGROUP']." WHERE UserName='".$dbSocket->escapeSimple($username)."' ORDER BY $orderBy $orderType LIMIT $offset, $rowsPerPage;";
	$res = $dbSocket->query($sql);
	$logDebugSQL = "";
	$logDebugSQL .= $sql . "\n";

	/* START - Related to pages_numbering.php */
	$maxPage = ceil($numrows/$rowsPerPage);
	/* END */

	echo "<table border='2' class='table1'>\n";
	echo "
					<thead>
							<tr>
							<th colspan='10'>".$l['all']['Records']."</th>
							</tr>
                                                        <tr>
                                                        <th colspan='10' align='left'>
                <br/>
        ";

        if ($configValues['CONFIG_IFACE_TABLES_LISTING_NUM'] == "yes")
		setupNumbering($numrows, $rowsPerPage, $pageNum, $orderBy, $orderType, "&username=$username");

        echo " </th></tr>
                                        </thead>

                        ";


	echo "<thread> <tr>
					<th scope='col'> ".$l['all']['Username']."
					<br/>
					<a class='novisit' href=\"" . $_SERVER['PHP_SELF'] . "?username=$username&orderBy=username&orderType=asc\"> > </a>
					<a class='novisit' href=\"" . $_SERVER['PHP_SELF'] . "?username=$username&orderBy=username&orderType=desc\"> < </a>
					</th>
					<th scope='col'> ".$l['all']['Groupname']."
					<br/>
					<a class='novisit' href=\"" . $_SERVER['PHP_SELF'] . "?username=$username&orderBy=groupname&orderType=asc\"> > </a>
					<a class='novisit' href=\"" . $_SERVER['PHP_SELF'] . "?username=$username&orderBy=groupname&orderType=desc\"> < </a>
					</th>
					<th scope='col'> ".$l['all']['Priority']."
					<br/>
					<a class='novisit' href=\"" . $_SERVER['PHP_SELF'] . "?username=$username&orderBy=priority&orderType=asc\"> > </a>
					<a class='novisit' href=\"" . $_SERVER['PHP_SELF'] . "?username=$username&orderBy=priority&orderType=desc\"> < </a>
					</th>
					<th scope='col'> ".$l['all']['Action']." </th>
			</tr> </thread>";
	while($row = $res->fetchRow()) {
		echo "<tr>
				<td> $row[0] </td>
				<td> $row[1] </td>
				<td> $row[2] </td>
				<td> <a href='mng-rad-usergroup-edit.php?username=$row[0]&group=$row[1]'> ".$l['all']['edit']." </a>
					 <a href='mng-rad-usergroup-del.php?username=$row[0]&group=$row[1]'> ".$l['all']['del']." </a>
					 </td>

		</tr>";
	}

        echo "
                                        <tfoot>
                                                        <tr>
                                                        <th colspan='10' align='left'>
        ";
	setupLinks($pageNum, $maxPage, $orderBy, $orderType, "&username=$username");
        echo "
                                                        </th>
                                                        </tr>
                                        </tfoot>
                ";


	echo "</table>";

	include 'library/closedb.php';
?>


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
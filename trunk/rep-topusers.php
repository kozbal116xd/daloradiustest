<?php

    include ("menu-reports.php");

?>

<?php

	if (isset($_POST['limit']))
		$limit = $_POST['limit'];
	if (isset($_POST['order']))		
		$order = $_POST['order'];

?>
		
		
		
		
		
		<div id="contentnorightbar">
		
		<h2 id="Intro"><a href="#"><?php echo $l[Intro][reptopusers.php]; ?></a></h2>
				
				<p>
				<?php echo $l[captions][recordsfortopusers]." ".$order ?> <br/>
				</p>



<?php

        
        include 'library/opendb.php';

	$sql = "SELECT distinct(radacct.UserName), ".$configValues['CONFIG_DB_TBL_RADACCT'].".FramedIPAddress, ".$configValues['CONFIG_DB_TBL_RADACCT'].".AcctStartTime, ".$configValues['CONFIG_DB_TBL_RADACCT'].".AcctStopTime,
sum(".$configValues['CONFIG_DB_TBL_RADACCT'].".AcctSessionTime) as Time, sum(".$configValues['CONFIG_DB_TBL_RADACCT'].".AcctInputOctets) as Upload,sum(".$configValues['CONFIG_DB_TBL_RADACCT'].".AcctOutputOctets) as Download, ".$configValues['CONFIG_DB_TBL_RADACCT'].".AcctTerminateCause, ".$configValues['CONFIG_DB_TBL_RADACCT'].".NASIPAddress, sum(".$configValues['CONFIG_DB_TBL_RADACCT'].".AcctInputOctets+".$configValues['CONFIG_DB_TBL_RADACCT'].".AcctOutputOctets) as Bandwidth FROM ".$configValues['CONFIG_DB_TBL_RADACCT']." group by UserName order by $order desc limit $limit";

	$res = mysql_query($sql) or die('Query failed: ' . mysql_error());

        echo "<table border='2' class='table1'>\n";
        echo "
                        <thead>
                                <tr>
                                <th colspan='10'>Records</th>
                                </tr>
                        </thead>
                ";

        echo "<thread> <tr>
                        <th scope='col'> Username </th>
                        <th scope='col'> IP Address</th>
                        <th scope='col'> Start Time </th>
                        <th scope='col'> Stop Time </th>
                        <th scope='col'> Total Time </th>
                        <th scope='col'> Upload (Bytes) </th>
                        <th scope='col'> Download (Bytes) </th>
                        <th scope='col'> Termination </th>
                        <th scope='col'> NAS IP Address </th>
                </tr> </thread>";
        while($nt = mysql_fetch_array($res)) {
                echo "<tr>
                        <td> $nt[0] </td>
                        <td> $nt[1] </td>
                        <td> $nt[2] </td>
                        <td> $nt[3] </td>
                        <td> $nt[4] </td>
                        <td> $nt[5] </td>
                        <td> $nt[6] </td>
                        <td> $nt[7] </td>
                        <td> $nt[8] </td>
                </tr>";
        }
        echo "</table>";

        mysql_free_result($res);
        include 'library/closedb.php';
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

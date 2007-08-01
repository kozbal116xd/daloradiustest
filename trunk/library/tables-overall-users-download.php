<?php
/*******************************************************************
* Extension name: graph-alltime_download.php                       *
*                                                                  *
* Description:    this graph extension procduces a query of the    *
* alltime downloads made by all users on a daily, monthly and      *
* yearly basis.                                                    *
*                                                                  *
* Author: Liran Tal <liran@enginx.com>                             *
*                                                                  *
*******************************************************************/


if ($type == "daily") {
	daily($username, $orderBy, $orderType);
} elseif ($type == "monthly") {
	monthly($username, $orderBy, $orderType);
} elseif ($type == "yearly") {
	yearly($username, $orderBy, $orderType);
}



function daily($username, $orderBy, $orderType) {

	include 'opendb.php';

	$sql = "SELECT sum(AcctOutputOctets) as Downloads, day(AcctStartTime) AS day, UserName from ".$configValues['CONFIG_DB_TBL_RADACCT']." where UserName='$username' group by day ORDER BY $orderBy $orderType;";


        $res = mysql_query($sql) or die('<font color="#FF0000"> Query failed: ' . mysql_error() . "</font>");

	$total_downloads = 0;		// initialize variables
	$count = 0;			

	$array_downloads = array();
	$array_days = array();

        while($ent = mysql_fetch_array($res)) {

		// The table that is being procuded is in the format of:
		// +--------+------+
		// | Download | Day  |
		// +--------+------+

        	$downloads = floor($ent[0]/1024/1024);	// total downloads on that specific day
        	$day = $ent[1];		// day of the month [1-31]

		$total_downloads = $total_downloads + $downloads;
		$count = $count + 1;

		array_push($array_downloads, "$downloads");
		array_push($array_days, "$day");

        }

	// creating the table:
	echo "<br/><br/>";
        echo "<table border='2' class='table1'>\n";
        echo "
                        <thead>
                                <tr>
                                <th colspan='10'>All-time Download statistics</th>
                                </tr>
                        </thead>
                ";
        echo "<thread> <tr>
                        <th scope='col'> Downloads count in MB
						<br/>
						<a class='novisit' href=\"" . $_SERVER['PHP_SELF'] . "?username=$username&type=daily&orderBy=downloads&orderType=asc\"> > </a>
						<a class='novisit' href=\"" . $_SERVER['PHP_SELF'] . "?username=$username&type=daily&orderBy=downloads&orderType=desc\"> < </a>
						</th>
                        <th scope='col'> Day of month
						<br/>
						<a class='novisit' href=\"" . $_SERVER['PHP_SELF'] . "?username=$username&type=daily&orderBy=day&orderType=asc\"> > </a>
						<a class='novisit' href=\"" . $_SERVER['PHP_SELF'] . "?username=$username&type=daily&orderBy=day&orderType=desc\"> < </a>
						</th>
                </tr> </thread>";


	$i=0;
	foreach ($array_days as $a_day) {
                echo "<tr>
                        <td> $array_downloads[$i] </td>
                        <td> $a_day </td>
                </tr>";

		$i++;
	}
	echo "</table>";
	echo "<br/> Total downloads of <u>$total_downloads</u> <br/>";

        mysql_free_result($res);
        include 'library/closedb.php';
}





function monthly($username, $orderBy, $orderType) {

	include 'opendb.php';
        
	$sql = "SELECT sum(AcctOutputOctets) as Downloads, monthname(AcctStartTime) AS month, UserName from ".$configValues['CONFIG_DB_TBL_RADACCT']." where UserName='$username' group by month ORDER BY $orderBy $orderType;";
	$res = mysql_query($sql) or die('<font color="#FF0000"> Query failed: ' . mysql_error() . "</font>");

	$total_downloads = 0;		// initialize variables
	$count = 0;			

        $array_downloads = array();
        $array_months = array();

        while($ent = mysql_fetch_array($res)) {

		// The table that is being procuded is in the format of:
		// +--------+--------+
		// | Download | Month  |
		// +--------+--------+

        	$downloads = floor($ent[0]/1024/1024);	// total downloads on that specific month
        	$month = $ent[1];	// Month of year [1-12]

		$total_downloads = $total_downloads + $downloads;
		$count = $count + 1;

	        array_push($array_downloads, "$downloads");
	        array_push($array_months, "$month");

        }

        // creating the table:
        echo "<br/><br/>";

        echo "<table border='2' class='table1'>\n";
        echo "
                        <thead>
                                <tr>
                                <th colspan='10'>All-time Download statistics</th>
                                </tr>
                        </thead>
                ";
        echo "<thread> <tr>
                        <th scope='col'> Downloads count in MB
						<br/>
						<a class='novisit' href=\"" . $_SERVER['PHP_SELF'] . "?username=$username&type=monthly&orderBy=downloads&orderType=asc\"> > </a>
						<a class='novisit' href=\"" . $_SERVER['PHP_SELF'] . "?username=$username&type=monthly&orderBy=downloads&orderType=desc\"> < </a>
						</th>
                        <th scope='col'> Month of year
						<br/>
						<a class='novisit' href=\"" . $_SERVER['PHP_SELF'] . "?username=$username&type=monthly&orderBy=month&orderType=asc\"> > </a>
						<a class='novisit' href=\"" . $_SERVER['PHP_SELF'] . "?username=$username&type=monthly&orderBy=month&orderType=desc\"> < </a>
						</th>
                </tr> </thread>";

        $i=0;
        foreach ($array_months as $a_month) {
                echo "<tr>
                        <td> $array_downloads[$i] </td>
                        <td> $a_month </td>
                </tr>";

                $i++;
        }
	


	echo "</table>";
	echo "<br/> Total downloads of <u>$total_downloads</u> <br/>";



        mysql_free_result($res);
        include 'library/closedb.php';
}








function yearly($username, $orderBy, $orderType) {

	include 'opendb.php';

	$sql = "SELECT sum(AcctOutputOctets) as Downloads, year(AcctStartTime) AS year, UserName from ".$configValues['CONFIG_DB_TBL_RADACCT']." where UserName='$username' group by year ORDER BY $orderBy $orderType;";

        $res = mysql_query($sql) or die('<font color="#FF0000"> Query failed: ' . mysql_error() . "</font>");

	$total_downloads = 0;		// initialize variables
	$count = 0;			

        $array_downloads = array();
        $array_years = array();

        while($ent = mysql_fetch_array($res)) {

		// The table that is being procuded is in the format of:
		// +--------+-------+
		// | Download | Year  |
		// +--------+-------+

        	$downloads = floor($ent[0]/1024/1024);	// total downloads on that specific month
        	$year = $ent[1];	// Year

		$total_downloads = $total_downloads + $downloads;
		$count = $count + 1;

	        array_push($array_downloads, "$downloads");
	        array_push($array_years, "$year");

 	}

	echo "<br/><br/>";
        echo "<table border='2' class='table1'>\n";
        echo "
                        <thead>
                                <tr>
                                <th colspan='10'>All-Time Download statistics</th>
                                </tr>
                        </thead>
                ";
        echo "<thread> <tr>
                        <th scope='col'> Downloads count in MB
						<br/>
						<a class='novisit' href=\"" . $_SERVER['PHP_SELF'] . "?username=$username&type=yearly&orderBy=downloads&orderType=asc\"> > </a>
						<a class='novisit' href=\"" . $_SERVER['PHP_SELF'] . "?username=$username&type=yearly&orderBy=downloads&orderType=desc\"> < </a>
						</th>
                        <th scope='col'> Year
						<br/>
						<a class='novisit' href=\"" . $_SERVER['PHP_SELF'] . "?username=$username&type=yearly&orderBy=year&orderType=asc\"> > </a>
						<a class='novisit' href=\"" . $_SERVER['PHP_SELF'] . "?username=$username&type=yearly&orderBy=year&orderType=desc\"> < </a>
						</th>
                </tr> </thread>";


        $i=0;
        foreach ($array_years as $a_year) {
                echo "<tr>
                        <td> $array_downloads[$i] </td>
                        <td> $a_year </td>
                </tr>";

                $i++;
        }



	echo "</table>";
	echo "<br/> Total downloads of <u>$total_downloads</u> <br/>";

        mysql_free_result($res);
        include 'closedb.php';
}




?>

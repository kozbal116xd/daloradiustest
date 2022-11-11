<?php
/*
 *********************************************************************************************************
 * daloRADIUS - RADIUS Web Platform
 * Copyright (C) 2007 - Liran Tal <liran@enginx.com> All Rights Reserved.
 *
 * This program is free software; you can redistribute it and/or
 * modify it under the terms of the GNU General Public License
 * as published by the Free Software Foundation; either version 2
 * of the License, or (at your option) any later version.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 59 Temple Place - Suite 330, Boston, MA  02111-1307, USA.
 *
 *********************************************************************************************************
 *
 * Authors:    Liran Tal <liran@enginx.com>
 *             Filippo Maria Del Prete <filippo.delprete@gmail.com>
 *             Filippo Lauria <filippo.lauria@iit.cnr.it>
 *
 *********************************************************************************************************
 */
 
    include("library/checklogin.php");
    $operator = $_SESSION['operator_user'];

    include('library/check_operator_perm.php');
    include_once('library/config_read.php');
    
    // init logging variables
    $logAction = "";
    $logDebugSQL = "";
    $log = "visited page: ";
    

    isset($_REQUEST['ratename']) ? $ratename = $_REQUEST['ratename'] : $ratename = "";

    $showRemoveDiv = "block";

    if (isset($_REQUEST['ratename'])) {

        if (!is_array($ratename))
            $ratename = array($ratename);

        $allRates = "";

        include 'library/opendb.php';
    
        foreach ($ratename as $variable=>$value) {
            if (trim($value) != "") {

                $name = $value;
                $allRates .= $name . ", ";

                // delete all rates 
                $sql = "DELETE FROM ".$configValues['CONFIG_DB_TBL_DALOBILLINGRATES']." WHERE rateName='".
                        $dbSocket->escapeSimple($name)."'";
                $res = $dbSocket->query($sql);
                $logDebugSQL .= $sql . "\n";
                
                $successMsg = "Deleted rate(s): <b> $allRates </b>";
                $logAction .= "Successfully deleted rates(s) [$allRates] on page: ";
                
            } else { 
                $failureMsg = "no rate was entered, please specify a rate name to remove from database";
                $logAction .= "Failed deleting rate(s) [$allRates] on page: ";
            }

        } //foreach

        include 'library/closedb.php';

        $showRemoveDiv = "none";
    } 

    include_once("lang/main.php");
    include("library/layout.php");

    // print HTML prologue
    $title = t('Intro','billratesdel.php');
    $help = t('helpPage','billratesdel');
    
    print_html_prologue($title, $langCode);

    include("menu-bill-pos.php");
    
    if (!empty($ratename) && !is_array($ratename)) {
        $title .= " :: " . htmlspecialchars($ratename, ENT_QUOTES, 'UTF-8');
    }
    
    echo '<div id="contentnorightbar">';
    print_title_and_help($title, $help);

    include_once('include/management/actionMessages.php');
    
?>

<div id="removeDiv" style="display:<?php echo $showRemoveDiv ?>;visibility:visible" >
    <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">

    <fieldset>

        <h302> <?php echo t('title','RateInfo') ?> </h302>
        <br/>

        <label for='ratename' class='form'><?php echo t('all','RateName') ?></label>
        <input name='ratename[]' type='text' id='ratename' value='<?php echo $ratename ?>' tabindex=100 />
        <br/>

        <br/><br/>
        <hr><br/>

        <input type='submit' name='submit' value='<?php echo t('buttons','apply') ?>' tabindex=1000 
            class='button' />

    </fieldset>

    </form>
    </div>


<?php
    include('include/config/logging.php');
    print_footer_and_html_epilogue();
?>

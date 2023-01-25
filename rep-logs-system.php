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
 *             Filippo Lauria <filippo.lauria@iit.cnr.it>
 *
 *********************************************************************************************************
 */

    include("library/checklogin.php");
    $operator = $_SESSION['operator_user'];

    include('library/check_operator_perm.php');
    include_once('library/config_read.php');

    $log = "visited page: ";

    include_once("lang/main.php");
    include("library/layout.php");
    
    // parameter validation
    $systemLineCount = (array_key_exists('systemLineCount', $_GET) && isset($_GET['systemLineCount']) &&
                        intval($_GET['systemLineCount']) > 0)
                     ? intval($_GET['systemLineCount']) : 50;

    // preg quoted before usage
    $systemFilter = (array_key_exists('systemFilter', $_GET) && isset($_GET['systemFilter']))
                  ? $_GET['systemFilter'] : "";

    
    // print HTML prologue
    $title = t('Intro','replogssystem.php') . " :: $systemLineCount Lines Count";
    if (!empty($systemFilter) && $systemFilter !== '.+') {
        $title .= " with filter set to " . htmlspecialchars($systemFilter, ENT_QUOTES, 'UTF-8');
    }
    $help = t('helpPage','replogssystem');
    
    print_html_prologue($title, $langCode);

    include ("menu-reports-logs.php");      

    echo '<div id="contentnorightbar">';
    print_title_and_help($title, $help);
    
    include('library/extensions/syslog_log.php');
    include_once('include/management/actionMessages.php');

    include('include/config/logging.php');
    print_footer_and_html_epilogue();

?>

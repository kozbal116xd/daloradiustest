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

// prevent this file to be directly accessed
if (strpos($_SERVER['PHP_SELF'], '/include/menu/sidebar/acct/default.php') !== false) {
    header("Location: ../../../../index.php");
    exit;
}

include_once("library/validation.php");

global $username, $startdate, $enddate, $ipaddress, $nasipaddress;

include_once("include/management/populate_selectbox.php");
$menu_usernames = get_users('CONFIG_DB_TBL_RADACCT');
array_unshift($menu_usernames, "");

// define descriptors
$descriptors1 = array();
 
$components = array();

$components[] = array(
                            "id" => "username_menu",
                            "name" => "username",
                            "required" => true,
                            "type" => "select",
                            "selected_value" => ((isset($username)) ? $username : ""),
                            "options" => $menu_usernames,
                            "caption" => t('all','Username'),
                            "tooltipText" => t('Tooltip','Username'),
                     );

$descriptors1[] = array( 'type' => 'form', 'title' => t('button','UserAccounting'), 'action' => 'acct-username.php', 'method' => 'GET',
                         'icon' => 'person-fill', 'form_components' => $components, );

$components[] = array(
                            "name" => "startdate",
                            "type" => "date",
                            "value" => ((isset($startdate)) ? $startdate : date("Y-m-01")),
                            "caption" => t('all','StartingDate'),
                            "tooltipText" => t('Tooltip','Date'),
                     );
                     
$components[] = array(
                            "name" => "enddate",
                            "type" => "date",
                            "value" => ((isset($enddate)) ? $enddate : date("Y-m-t")),
                            "caption" => t('all','EndingDate'),
                            "tooltipText" => t('Tooltip','Date'),
                     );

$descriptors1[] = array( 'type' => 'form', 'title' => t('button','DateAccounting'), 'action' => 'acct-date.php', 'method' => 'GET',
                         'icon' => 'calendar-range', 'form_components' => $components, );

$components = array();
$components[] = array(
                            "name" => "ipaddress",
                            "type" => "text",
                            "value" => ((isset($ipaddress)) ? $ipaddress : ""),
                            "caption" => "IP address",
                            "tooltipText" => t('Tooltip','IPAddress'),
                            "sidebar" => true,
                            "pattern" => trim(LOOSE_IP_REGEX, "/"),
                            
                     );
$descriptors1[] = array( 'type' => 'form', 'title' => t('button','IPAccounting'), 'action' => 'acct-ipaddress.php', 'method' => 'GET',
                         'icon' => 'ethernet', 'form_components' => $components, );

$components = array();
$components[] = array(
                            "name" => "nasipaddress",
                            "type" => "text",
                            "value" => ((isset($nasipaddress)) ? $nasipaddress : ""),
                            "caption" => "NAS IP address",
                            "tooltipText" => t('all','NASIPAddress'),
                            "sidebar" => true,
                            "pattern" => trim(LOOSE_IP_REGEX, "/"),
                     );
$descriptors1[] = array( 'type' => 'form', 'title' => t('button','NASIPAccounting'), 'action' => 'acct-nasipaddress.php', 'method' => 'GET',
                         'icon' => 'router-fill', 'form_components' => $components, );

$descriptors1[] = array( 'type' => 'link', 'label' => t('button','AllRecords'), 'href' => 'acct-all.php',
                         'icon' => 'table', );
$descriptors1[] = array( 'type' => 'link', 'label' => t('button','ActiveRecords'), 'href' => 'acct-active.php',
                         'icon' => 'table', );

$sections = array();
$sections[] = array( 'title' => 'Users Accounting', 'descriptors' => $descriptors1 );


// add sections to menu
$menu = array(
                'title' => 'Accounting',
                'sections' => $sections,
             );

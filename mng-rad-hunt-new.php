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

    include_once("lang/main.php");
    include_once("library/validation.php");
    include("library/layout.php");
    include_once("include/management/populate_selectbox.php");

    // init logging variables
    $log = "visited page: ";
    $logAction = "";
    $logDebugSQL = "";

    // load valid huntgroups
    $valid_huntgroups = get_huntgroups();

    
    include('library/opendb.php');

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
        if (array_key_exists('csrf_token', $_POST) && isset($_POST['csrf_token']) && dalo_check_csrf_token($_POST['csrf_token'])) {
            
            $nasipaddress = (array_key_exists('nasipaddress', $_POST) && !empty(trim($_POST['nasipaddress'])) &&
                             filter_var(trim($_POST['nasipaddress']), FILTER_VALIDATE_IP) !== false)
                          ? trim($_POST['nasipaddress']) : "";
            
            $groupname = (array_key_exists('groupname', $_POST) && !empty(str_replace("%", "", trim($_POST['groupname']))))
                       ? str_replace("%", "", trim($_POST['groupname'])) : "";
            
            $groupname_enc = (!empty($groupname)) ? htmlspecialchars($groupname, ENT_QUOTES, 'UTF-8') : "";
            
            $nasportid = (array_key_exists('nasportid', $_POST) && intval(trim($_POST['nasportid'])) > 0)
                       ? intval(trim($_POST['nasportid'])) : 0;
            
            if (empty($nasipaddress) || empty($groupname)) {
                // required
                $failureMsg = sprintf("Empty/invalid IP address and/or group name");
                $logAction .= "$failureMsg on page: ";
            } else {
                
                $sql = sprintf("SELECT COUNT(id)
                                  FROM %s
                                 WHERE nasipaddress=? AND nasportid=?", $configValues['CONFIG_DB_TBL_RADHG']);
                $prep = $dbSocket->prepare($sql);
                $values = array( $nasipaddress, $nasportid, );
                $res = $dbSocket->execute($prep, $values);
                $logDebugSQL .= "$sql;\n";
                
                $exists = $res->fetchrow()[0] > 0;
                
                if ($exists) {
                    // invalid
                    $failureMsg = sprintf("%s/%s already in db", t('all','HgIPHost'), t('all','HgPortId'));
                    $logAction .= "$failureMsg on page: ";
                } else {
                    $sql = sprintf("INSERT INTO %s (id, groupname, nasipaddress, nasportid)
                                            VALUES (0, ?, ?, ?)", $configValues['CONFIG_DB_TBL_RADHG']);
                    $prep = $dbSocket->prepare($sql);
                    $values = array( $groupname, $nasipaddress, $nasportid );
                    $res = $dbSocket->execute($prep, $values);
                    $logDebugSQL .= "$sql;\n";
                    
                    if (!DB::isError($res)) {
                        $successMsg = "Successfully inserted new huntgroup (<strong>$groupname_enc</strong>)";
                        $logAction .= "Successfully inserted new huntgroup [$groupname] on page: ";
                    } else {
                        $failureMsg = "Failed to inserted new huntgroup (<strong>$groupname_enc</strong>)";
                        $logAction .= "Failed to inserted new huntgroup [$groupname_enc] on page: ";
                    }
                }
                
            }
            
        } else {
            // csrf
            $failureMsg = "CSRF token error";
            $logAction .= "$failureMsg on page: ";
        }
    }
    
    include('library/closedb.php');

    
    // print HTML prologue    
    $title = t('Intro','mngradhuntnew.php');
    $help = t('helpPage','mngradhuntnew');
    
    print_html_prologue($title, $langCode);

    include("menu-mng-rad-hunt.php");
    
    echo '<div id="contentnorightbar">';
    print_title_and_help($title, $help);
    
    include_once('include/management/actionMessages.php');

    if (!isset($successMsg)) {
        
        // descriptors 0
        $input_descriptors0 = array();
        
        $input_descriptors0[] = array(
                                        'name' => 'nasipaddress',
                                        'caption' => t('all','HgIPHost'),
                                        'type' => 'text',
                                        'value' => (isset($nasipaddress) ? $nasipaddress : ""),
                                        'pattern' => trim(IP_REGEX, '/'),
                                        'required' => true,
                                     );
                                     
        $input_descriptors0[] = array(
                                        'name' => 'groupname',
                                        'caption' => t('all','HgGroupName'),
                                        'type' => 'text',
                                        'value' => (isset($groupname) ? $groupname : ""),
                                        'required' => true,
                                     );
                                     
        $input_descriptors0[] = array(
                                        'name' => 'nasportid',
                                        'caption' => t('all','HgPortId'),
                                        'type' => 'text',
                                        'value' => (isset($nasportid) ? $nasportid : ""),
                                     );
        // descriptors 1
        $input_descriptors1 = array();
        
        $input_descriptors1[] = array(
                                        "name" => "csrf_token",
                                        "type" => "hidden",
                                        "value" => dalo_csrf_token(),
                                     );
        
        $input_descriptors1[] = array(
                                        "type" => "submit",
                                        "name" => "submit",
                                        "value" => t('buttons','apply')
                                      );
    
        open_form();
        
        // fieldset 0
        $fieldset0_descriptor = array(
                                        "title" => t('title','HGInfo'),
                                     );
                                     
        open_fieldset($fieldset0_descriptor);
        
        foreach ($input_descriptors0 as $input_descriptor) {
            print_form_component($input_descriptor);
        }
        
        close_fieldset();
        
        foreach ($input_descriptors1 as $input_descriptor) {
            print_form_component($input_descriptor);
        }
        
        close_form();
    
    }
    
    print_back_to_previous_page();
    
    include('include/config/logging.php');
    print_footer_and_html_epilogue();

?>

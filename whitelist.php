<?php
define("IN_MYBB", 1);
	require_once "./global.php";
	 global $db, $mybb, $lang, $templates, $parser, $theme, $userfields, $customfields, $profilefields, $field_hidden, $bgcolor, $alttrow;
	require_once MYBB_ROOT."inc/class_parser.php";
	require "./inc/config.php";
    add_breadcrumb("Whitelist", "whitelist.php");
	
	
	if ($mybb->settings['whitelist_on'] == 0) 
		{
		error_no_permission();
		}
	
        if ($mybb->usergroup['gid'] == '1') {

            error_no_permission ();
        } else {

			
			$teamuid = $mybb->settings['whitelist_team'];
			$whitelist_date = $mybb->settings['whitelist_datum'];
			
			// Datenbankabfrage, ausgenommen abwesende Mitglieder
			$registered= $db->query("
			SELECT * FROM mybb_users
			WHERE mybb_users.away != '1'
			AND mybb_users.uid != '$teamuid' 
			AND mybb_users.uid != '157' 
			ORDER BY username ASC
			");	
	
			while($row=$db->fetch_array($registered)) {
		
			$ownuid = $row['uid'];
			$charaktername = $row['username'];
			$charaktername = build_profile_link($row['username'], $row['uid']);
			$name = $row['username'];
 	 
			if($row[whitelist] == '0') {
				$status = "bleibt nicht";
			}
			else {
				$status = "bleibt";
			} 

			// Letzter Inplaybeitrag in der Übersicht			
			$inplay = $mybb->settings['whitelist_inplaykategorie'];
			$inplayarchiv = $mybb->settings['whitelist_inplayarchivkategorie'];
			
            $queryip = $db->query("
                SELECT * FROM ".TABLE_PREFIX."posts p
                INNER JOIN ".TABLE_PREFIX."forums f ON (p.fid = f.fid)
                INNER JOIN ".TABLE_PREFIX."threads t ON (p.tid = t.tid) AND (p.fid = t.fid)
                WHERE (f.parentlist LIKE '$inplay%' OR f.fid LIKE '$inplayarchiv')
                AND (p.uid=$ownuid) AND (p.visible = 1)
                ORDER BY p.pid DESC
                LIMIT 1
            ");
            
            $anzahl = mysqli_num_rows($queryip);
            if($anzahl > 0) {

                while($result=$db->fetch_array($queryip)) {
                    $date = my_date('relative', $result['lastpost']);    
                    if(my_strlen($result['subject']) > 15) {
                        $result['subject'] = my_substr($result['subject'], 0, 15)."..";
                    }                
                    $letzteripbeitrag = "<a href=\"showthread.php?tid=$result[tid]&pid=$result[pid]#pid$result[pid]\" target=\"blank\">$result[subject]</a> am {$date}";
                }
            }
            else $letzteripbeitrag = "Keine Inplaybeiträge";

			// Buttons
			$bleibt = "<a href=\"whitelist.php?bleibt=$ownuid\">bleibt</a>";
			$geht = "<a href=\"whitelist.php?geht=$ownuid\">geht</a>";	
				
				// Bleiben
				$bleibt = $mybb->input['bleibt'];
				if($bleibt) {
				$whitelist = $db->fetch_field($db->query("SELECT whitelist FROM ".TABLE_PREFIX."users WHERE uid = '$bleibt'"), "whitelist");
				$new_record = array(
				"whitelist" => "1"
				);
    
				$db->update_query("users", $new_record, "uid = '$bleibt'");
				redirect("whitelist.php");
				}
				
				// Gehen
				$geht = $mybb->input['geht'];
				if($geht) {
				$whitelist = $db->fetch_field($db->query("SELECT whitelist FROM ".TABLE_PREFIX."users WHERE uid = '$geht'"), "whitelist");
				$new_record = array(
				"whitelist" => "0"
				);
    
				$db->update_query("users", $new_record, "uid = '$geht'");
				redirect("whitelist.php");
				}
				
			// User löschen
			if($mybb->usergroup['canmodcp'] == 1) {
				$team_delete_bit ="";

				$weg = "<a href='whitelist.php?&del=$row[uid]' title='$name löschen'><img src=\"/images/buddy_delete.png\"></a>";
				$ver = "<a href='whitelist.php?&bleibt=$row[uid]' title='$name bleibt'><img src=\"/images/valid.png\"></a>";
				$ab = "<a href='whitelist.php?&geht=$row[uid]' title='$name bleibt nicht'><img src=\"/images/invalid.png\"></a>";
					
			// Button	
				// Löschen
				$del = $mybb->input['del'];
				if ($del) {
					$db->delete_query ("users", "uid = '$del'");
					redirect ("whitelist.php");
				}
				$uid = $mybb->user['uid'];
			
			eval("\$team_delete_bit .= \"".$templates->get("whitelist_delete_bit")."\";");
			}
			
			eval("\$whitelist_user .= \"".$templates->get("whitelist_bit")."\";");
			}

			// Abwesende Mitglieder
			$query = $db->query("
			SELECT * FROM mybb_users
			WHERE mybb_users.away = '1'
			ORDER BY username ASC
			"); 
	
			while($result=$db->fetch_array($query)) {
			$awayname = htmlspecialchars_uni($result['username']);
			$awayname = build_profile_link($result['username'], $result['uid']);

			eval("\$abwesend .= \"".$templates->get("whitelist_bit_abwesend")."\";"); 
			} 
	

			// Button: Einträge am Ende der Whitelist zurücksetzen
					
			if($mybb->usergroup['canmodcp'] == 1) {
			
			
				if(isset($_POST['aktualisieren'])) 
				{	

					$bisherquery = $db->simple_select("users","*");
					$bisherwhitelist = $db->fetch_field($bisherquery,"whitelist");

					if($bisherwhitelist = "1"){
						$update_record = array(
							"whitelist" => "0"
						);

					$db->update_query("users", $update_record);
					redirect ("whitelist.php");

					}
				}
				
			eval("\$team_delete .= \"".$templates->get("whitelist_delete")."\";");
			eval("\$whitelist_aktualisieren = \"" . $templates->get ("whitelist_bit_aktualisieren") . "\";");	
	
			}
		
		eval("\$page = \"" . $templates->get ("whitelist") . "\";");
        output_page ($page);
        }

?>
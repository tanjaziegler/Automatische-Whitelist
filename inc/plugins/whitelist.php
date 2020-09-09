<?php

// Disallow direct access to this file for security reasons
if(!defined("IN_MYBB"))
{
	die("Direct initialization of this file is not allowed.");
}

/*
 * Hier befinden sich alle Hooks
 */
$plugins->add_hook('global_intermediate', 'whitelist_header');



function whitelist_info()
{
	return array(
		"name"			=> "Automatische Whitelist",
"description"	=> "eine automatische Whitelist",
"website"		=> "",
"author"		=> "Berrie",
"authorsite"	=> "http://storming-gates.de/member.php?action=profile&uid=53",
"version"		=> "1.0",
"guid" 			=> "",
"codename"		=> "",
"compatibility" => "18*"
	);
}

function whitelist_install()
{
global $db;

    if($db->engine=='mysql'||$db->engine=='mysqli')
    {
        $db->query("ALTER TABLE ".TABLE_PREFIX."users ADD `whitelist` int(1) NOT NULL default '0'");
    }

		// Template: whitelist
    $insert_array = array(
        'title' => 'whitelist',
        'template' => $db->escape_string('<html>
<head>
<title>{$settings[\'bbname\']} - Whitelist</title>
{$headerinclude}
</head>
<body>
{$header}
	
	
	<table border="0" cellspacing="{$theme[\'borderwidth\']}" cellpadding="{$theme[\'tablespace\']}" class="tborder">
		<tr>
			<td class="thead" colspan="2">Whitelist</td>
		</tr>
		<tr>
			<td width="60%" valign="top">
				<table width="100%">
					<tr>
						<td class="tcat">Username</td>
						<td class="tcat">Status</td>						
						<td class="tcat">Letzter Inplaybeitrag</td>		
						{$team_delete}						
					</tr>
						{$whitelist_user}
				</table>
			</td>
			<td width="40%" valign="top">
				<table width="100%">
					<tr>
						<td class="tcat">Abwesende Mitglieder</td>
					</tr>
					{$abwesend}
				</table>
			</td>
		</tr>	
		{$whitelist_aktualisieren}
	</table>
	
{$footer}
</body>
</html>'),
        'sid' => '-1',
        'version' => '',
        'dateline' => TIME_NOW
    );
    $db->insert_query("templates", $insert_array);
	
	// Template: whitelist_delete
    $insert_array = array(
    'title' => 'whitelist_delete',
    'template' => $db->escape_string('<td class="tcat" width="20%">löschen</td>'),
    'sid' => '-1',
    'version' => '',
    'dateline' => TIME_NOW
);

    $db->insert_query("templates", $insert_array);
	
	// Template: whitelist_delete_bit
    $insert_array = array(
    'title' => 'whitelist_delete_bit',
    'template' => $db->escape_string('<td class="trow1" align="center">{$weg}{$ver}{$ab}</td>'),
    'sid' => '-1',
    'version' => '',
    'dateline' => TIME_NOW
);

    $db->insert_query("templates", $insert_array);

		// Template: whitelist_bit
    $insert_array = array(
    'title' => 'whitelist_bit',
    'template' => $db->escape_string('<tr>
		<td class="trow1">{$charaktername}</td>
		<td class="trow1">{$status}</td>
		<td class="trow1">{$letzteripbeitrag}</td>
		{$team_delete_bit}
	</tr>'),
    'sid' => '-1',
    'version' => '',
    'dateline' => TIME_NOW
);

    $db->insert_query("templates", $insert_array);

		// Template: whitelist_bit_abwesend
    $insert_array = array(
        'title' => 'whitelist_bit_abwesend',
        'template' => $db->escape_string('<tr>
	<td class="trow1">{$awayname}</td>
</tr>'),
        'sid' => '-1',
        'version' => '',
        'dateline' => TIME_NOW
    );

    $db->insert_query("templates", $insert_array);
	
	// Template: whitelist_bit_aktualisieren
    $insert_array = array(
        'title' => 'whitelist_bit_aktualisieren',
        'template' => $db->escape_string('<tr>
			<td class="tfoot" align="center">
				<form action="whitelist.php" method="post">
					<input type="submit" name="aktualisieren" value="Whitelist zurücksetzen" id="submit">
				</form>
			</td>
		</tr>'),
        'sid' => '-1',
        'version' => '',
        'dateline' => TIME_NOW
    );

    $db->insert_query("templates", $insert_array);
	
	// Einstellungen
  $setting_group = array(
      'name' => 'whitelist',
      'title' => 'Whitelist',
      'description' => 'Einstellung für die Whitelist',
      'disporder' => 5,
      'isdefault' => 0
  );

  $gid = $db->insert_query("settinggroups", $setting_group);

  $setting_array = array(
		'whitelist_on' => array(
		'title' => 'Whiteliste',
        'description' => 'Aktuelle Whitelite anschalten?',
        'optionscode' => 'yesno',
        'value' => '0',
        'disporder' => 0,
        'gid' => 3
      ),
	'whitelist_team' => array(
		'title' => 'Team-Account',
        'description' => 'Wenn vorhanden: welche Nummer hat der Teamaccount?',
        'optionscode' => 'text',
        'value' => '999',
        'disporder' => 2,
        'gid' => 3
      ),
	'whitelist_datum' => array(
		'title' => 'Rückmeldedatum',
        'description' => 'Bis wann kann sich zurückgemeldet werden?',
        'optionscode' => 'text',
        'value' => '01.01.1977',
        'disporder' => 3,
        'gid' => 3
      ),
	'whitelist_inplaykategorie' => array(
		'title' => 'Inplaykategorie',
        'description' => 'Welche Kategorie (Nummer) hat das Inplay?',
        'optionscode' => 'text',
        'value' => '1',
        'disporder' => 4,
        'gid' => 3
      ),
	'whitelist_inplayarchivkategorie' => array(
		'title' => 'Inplayarchivkategorie',
        'description' => 'Welche Kategorie (Nummer) hat das Archiv des Inplays?',
        'optionscode' => 'text',
        'value' => '1',
        'disporder' => 5,
        'gid' => 3
      ),
  );

  foreach($setting_array as $name => $setting)
  {
      $setting['name'] = $name;
      $setting['gid'] = $gid;

      $db->insert_query('settings', $setting);

  }
}   


function whitelist_is_installed()
{
global $db;
    if($db->field_exists('whitelist', 'users'))
    {
        return true;
    }
    return false;
}

function whitelist_uninstall()
{
  global $db;

    if($db->field_exists('whitelist', 'users'))
    {
        $db->query("ALTER TABLE ".TABLE_PREFIX."users DROP column `whitelist`");
    }

    $db->delete_query("templates", "title IN ('whitelist', 'whitelist_delete', 'whitelist_delete_bit', 'whitelist_bit', 'whitelist_bit_abwesend', 'whitelist_bit_aktualisieren')");
    rebuild_settings();
	
	// Einstellungen entfernen
  $db->delete_query('settings', "name IN ('whitelist_on', 'whitelist_team', 'whitelist_datum')");
  $db->delete_query('settinggroups', "name = 'whitelist'");

  rebuild_settings();
}


function whitelist_activate()
{
    require MYBB_ROOT.'/inc/adminfunctions_templates.php';
    find_replace_templatesets("header_welcomeblock_member", '#'.preg_quote('{$usercplink}').'#' , '{$Whitelistinfo}{$usercplink}');
	
}

function whitelist_deactivate()
{
    require MYBB_ROOT."/inc/adminfunctions_templates.php";
    find_replace_templatesets("header_welcomeblock_member", '#'.preg_quote('{$Whitelistinfo}{$usercplink}').'#' , '{$usercplink}',0);
}


// In the body of your plugin

function whitelist_header()
{
	global $db, $mybb, $templates, $wl, $wlback, $wlgone, $Whitelistinfo; 
	
	if ($mybb->settings['whitelist_on'] == 0) {}
	else {
		if ($mybb->usergroup['canusercp'] == 1) {
			$ownuid = $mybb->user['uid'];
			$whitelist_date = $mybb->settings['whitelist_datum'];
			$whitelist=$db->query("
			SELECT * FROM ".TABLE_PREFIX."users
			WHERE mybb_users.uid = '$ownuid'
			");

			$result = $db->fetch_array($whitelist); {
			$wl = $result['whitelist'];

			if($wl == 0) {
				$wl = "
				Es ist Zeit für die <a href=\"whitelist.php\">aktuelle Interessensabfrage</a>!<br />
				Die Interessensabfrage wird am <strong>{$whitelist_date}</strong> gelöscht!<br />
				<div class=\"abgelaufen\">Du hast dich noch nicht zurückgemeldet!</div>";
			}
			else {
			$wl = "
			Es ist Zeit für die <a href=\"whitelist.php\">aktuelle Interessensabfrage</a>!<br />
			Die Interessensabfrage wird am <strong>{$whitelist_date}</strong> gelöscht!<br />
			Du hast dich bereits zurückgemeldet!<br />
			<i>Du willst deine Meinung ändern?</i><br />";
			}
	  
			$wlback =  "<a href=\"whitelist.php?bleibt=$ownuid\">Ich will bleiben!</a>"; 
			$wlgone =  "<a href=\"whitelist.php?geht=$ownuid\">Nö, weg mit dem Charakter!</a>"; 
			$Whitelistinfo .= "{$wl} [{$wlback} / {$wlgone}]<br />";
			}
			
		}
	}
}

<?php
include_once "config.inc.php";

include_once "templates/header.html";
include_once "templates/host_settings_sidebar.html";

$mysql = new Mysql();

if(!@$_GET['action'])
{
	echo '<div class="span10">
	Choose left sidebar for next step.
	</div>';
}
elseif($_GET['action'] == "GlobalSettings")
{
	if(!@$_GET['do'])
	{
		$sql = "select set_id, filename, create_time from ehm_host_settings where host_id = 0 order by create_time desc";
		$mysql->Query($sql);
		echo '<div class=span10>';
		
		echo '<a href="HostSettings.php?action=GlobalSettings&do=Add" class="btn">'.$lang['addSettings'].'</a>';
		
		echo '<h2>'.$lang['globalSettings'].'</h2>';
		echo '<table class="table table-striped">';
		echo '<thead>
                <tr>
                  <th>#</th>
                  <th>'.$lang['filename'].'</th>
                  <th>'.$lang['createTime'].'</th>
                  <th>'.$lang['action'].'</th>
                </tr>
                </thead>
                <tbody>';
		$i = 1;
		while($arr = $mysql->FetchArray())
		{
			echo '<tr>
                  	<td>'.$i.'</td>
                  	<td>'.$arr['filename'].'</td>
                  	<td>'.$arr['create_time'].'</td>
                  	<td>
                  	<div class="btn-group">
   						 <a class="btn" href="HostSettings.php?action=GlobalSettings&do=Edit&setid='.$arr['set_id'].'">编辑</a>
   						 <a class="btn btn-danger" href="HostSettings.php?action=GlobalSettings&do=Remove&setid='.$arr['set_id'].'">删除</a>
                  	</div>
                  	</td>
                	</tr>';
			$i++;
		}
		echo '</tbody></table>';
		echo '</div>';
	}#not any action

	elseif ($_GET['do'] == "Add")
	{
		if(!$_POST['content'])
		{
			echo '<div class=span10>';
			echo '<h1>'.$lang['addSettings'].'</h1>';
			echo "<form method=POST>";
			echo '<label>'.$lang['filename'].'</label><br />';
			echo '<input type=text placeholder="'.$lang['filename'].'(with path: /etc/hosts...)" name=filename> <br />';
			echo '<label>'.$lang['content'].'</label><br />';
			echo '<textarea name=content></textarea><br />';
			echo '<input type=hidden name=action value="GlobalSettings">';
			echo '<input type=hidden name=do value=Add>';
			echo '<button type="submit" class="btn">'.$lang['submit'].'</button>';
			echo "</form>";
			echo '</div>';
		}
		else
		{
			$sql = "insert ehm_host_settings set filename='".$_POST['filename']."', content = '".$_POST['content']."', create_time=current_timestamp(), host_id=0";
			$mysql->Query($sql);
			echo "<script>alert('".$lang['settingAdded']."'); this.location='HostSettings.php?action=GlobalSettings';</script>";
		}
	}
	
	elseif ($_GET['do'] == "Edit")
	{
		$set_id = $_GET['setid'];
		$sql = "select * from ehm_host_settings where set_id='".$set_id."'";
		$mysql->Query($sql);
		$arr = $mysql->FetchArray();
		if(!$_POST['content'])
		{
			echo '<div class=span10>';
			echo '<h1>'.$lang['modifySettings'].'</h1>';
			echo "<form method=POST>";
			echo '<label>'.$lang['filename'].'</label><br />';
			echo '<input type=text name=filename value="'.$arr['filename'].'"> <br />';
			echo '<label>'.$lang['content'].'</label><br />';
			echo '<textarea name=content>'.$arr['content'].'</textarea><br />';
			echo '<input type=hidden name=action value="GlobalSettings">';
			echo '<input type=hidden name=set_id value="'.$set_id.'"';
			echo '<input type=hidden name=do value=Edit>';
			echo '<button type="submit" class="btn">'.$lang['submit'].'</button>';
			echo "</form>";
			echo '</div>';
		}
		else
		{
			$sql = "update ehm_host_settings set filename='".$_POST['filename']."', content = '".$_POST['content']."' where set_id='".$set_id."'";
			$mysql->Query($sql);
			echo "<script>alert('".$lang['settingUpdated']."'); this.location='HostSettings.php?action=GlobalSettings';</script>";
		}
	}

	elseif ($_GET['do'] == "Remove")
	{
		$set_id = $_GET['setid'];
		$sql = "delete from ehm_host_settings where set_id = '".$set_id."'";
		$mysql->Query($sql);
		echo "<script>alert('".$lang['settingRemoved']."'); this.location='HostSettings.php?action=GlobalSettings';</script>";
	}
	else
	{
		echo "Unknown Command";	
	}
}


include_once "templates/footer.html";
?>
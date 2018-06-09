<?php
include ("lib.php");
$key = $_GET["key"];
$action = $_GET["action"];
$var = $_GET["var"];
$obid = $_GET["obid"];
$chan = $_GET["chan"];
$spam_x = spam_x($key);
if($action == "save")
{
	$is = "no";
	select_db("stargate_t");
	$query = "SELECT * FROM api_key";
	$result = mysql_query($query);
	while ($line = mysql_fetch_array($result))
	{
		if($line[datas] == $key||$var == $line[owner])
		{
			$is = "yes";
		}
	}
	mysql_free_result($result);
	mysql_close();
	if($is == "no")
	{
	select_db("stargate_t");
	mysql_query("INSERT INTO api_key(id,datas,owner)VALUES(NULL ,'$key' ,'$var')");
	mysql_close();
	echo "Key for $var: $key";
	}
	else if($is == "yes")
	{
		echo "Key or Name in Database";
	}
}

else
{
	$allow = "no";
	select_db("stargate_t");
	$query = "SELECT * FROM api_key";
	$result = mysql_query($query);
	while ($line = mysql_fetch_array($result))
	{
		if($line[datas] == $key)
		{
			$allow = "yes";
		}
	}
	mysql_free_result($result);
	mysql_close();
	if($allow == "yes" && $spam_x != "banned")
	{
		if($action == "n2k")
		{
			$uuid = name2Key($var);
			echo"$uuid";
		}
		
		if($action == "simmap")
		{
			$uuid = "";
			select_db("slmap");
			$query = "SELECT * FROM sldb_data";
			$result = mysql_query($query);
			while ($line = mysql_fetch_array($result))
			{
				if($line[field]==$var)
				{
					$uuid = $line[value];
				}
			}
			mysql_free_result($result);
			mysql_close();
			echo"$uuid";
		}
		
		if($action == "gatesearch")
		{
			$out = "no data found for $var";
			select_db("stargate_t");
			$query = "SELECT * FROM gates";
			$result = mysql_query($query);
			while ($line = mysql_fetch_array($result))
			{
				if($var == $line[name]||$var == $line[sim]||$var == $line[owner])
				{
					$out = "$line[name]|$line[sim]|$line[kordi]|$line[owner]|$line[model]|$line[datas]|$line[radkanal]";
				}
				elseif(eregi($var,  $line[name])||eregi($var,  $line[sim])||eregi($var,  $line[owner]))
				{
					$out = "$line[name]|$line[sim]|$line[kordi]|$line[owner]|$line[model]|$line[datas]|$line[radkanal]";
				}
			}
			mysql_free_result($result);
			mysql_close();
			echo"$out";
		}
		
		if($action == "comm")
		{
			send_http_sl($chan,$var,$obid);
		}
	}
	else
	{
		echo"No Access or too many request";
	}
}
?>
<?php
//________________________________________________________________

error_reporting(0);

//________________________________________________________________
function search_sim($sim,$var)
{
$sim = urlencode($sim);
$cach = getPage("http://caworks-sl.de/TGN/app/simrating/com.php?search=$sim&var=$var");
sleep(3);
select_db("slmap");
$query = "SELECT * FROM simdata";
$result = mysql_query($query);
while ($line = mysql_fetch_array($result))
{
$simrat = $line[simrat];
}
mysql_free_result($result);
mysql_close();
select_db("slmap");
mysql_query("TRUNCATE TABLE simdata");
mysql_close();
return $simrat;
}
//________________________________________________________________
function select_db($db_name)
{
  if($db_name == "stargate_t")
  {
    $dbname="XXXXXXXXXX";
    $dbhost="localhost";
    $dbuser="XXXXXXXXXX";
    $dbpass="XXXXXXXXXX";
    mysql_connect($dbhost,$dbuser,$dbpass);
    mysql_select_db($dbname);
  }
  if($db_name == "cwdata")
  {
    $dbname="XXXXXXXXXX";
    $dbhost="localhost";
    $dbuser="XXXXXXXXXX";
    $dbpass="XXXXXXXXXX";
    mysql_connect($dbhost,$dbuser,$dbpass);
    mysql_select_db($dbname);
  }
  if($db_name == "slmap")
  {
    $dbname="XXXXXXXXXX";
    $dbhost="localhost";
    $dbuser="XXXXXXXXXX";
    $dbpass="XXXXXXXXXX";
    mysql_connect($dbhost,$dbuser,$dbpass);
    mysql_select_db($dbname);
  }
  if($db_name == "cwuser")
  {
    $dbname="XXXXXXXXXX";
    $dbhost="localhost";
    $dbuser="XXXXXXXXXXr";
    $dbpass="XXXXXXXXXX";
    mysql_connect($dbhost,$dbuser,$dbpass);
    mysql_select_db($dbname);
  }
  if($db_name == "shop")
  {
    $dbname="XXXXXXXXXX";
    $dbhost="localhost";
    $dbuser="XXXXXXXXXX";
    $dbpass="XXXXXXXXXX";
    mysql_connect($dbhost,$dbuser,$dbpass);
    mysql_select_db($dbname);
  }
}
//________________________________________________________________
function del_array($cach_delB,$delete)
{
$count = 0;
foreach($cach_delB as $check)
{
if($check == $delete)
{
unset($cach_delB[$count]);
}
$count++;
}
return $cach_delB;
}
//________________________________________________________________
function get_sim_uuid($simname)
{
$simname = urlencode($simname);
$web = "http://api.gridsurvey.com/simquery.php?region=$simname&item=objects_uuid";
$uuid_ans = getPage($web);
return $uuid_ans;
}
//________________________________________________________________
function search_gate_data($dialvalue,$send)
{
$cach_s = explode("-",$dialvalue);
$dialvalueB = "$cach_s[0]_$cach_s[1]";
select_db("stargate_t");
$query = "SELECT * FROM gates";
$result = mysql_query($query);
while ($line = mysql_fetch_array($result))
{
if($line[radkanal] == $send)
{
$send_sim = $line[sim];
$send_name = $line[name];
}
}
mysql_free_result($result);
mysql_close();
$teleportvalue = "empty";
if($dialvalue == "random")
{
select_db("stargate_t");
$query = "SELECT * FROM gates ORDER BY RAND() LIMIT 1";
$result = mysql_query($query);
while ($line = mysql_fetch_array($result))
{
if($line[radkanal] !=$send)
{
if($line[randial]=="1")
{
$kord = explode("|",$line[kordi]);
$teleportvalue = "$line[rpsim]dial|".$line[owner]."|".$line[sim]."|".$line[channel]."|".$kord[0]."|".$line[datas]."|".$line[gatestatus]."#".$line[name]."*".$line[radkanal]."|0|".$line[simrati];
}
}
}
mysql_free_result($result);
mysql_close();
}
else
{
select_db("stargate_t");
$query = "SELECT * FROM gates";
$result = mysql_query($query);
while ($line = mysql_fetch_array($result))
{
if(eregi(strtolower($dialvalueB),strtolower($line[name]))||eregi(strtolower($dialvalue),strtolower($line[name]))||eregi(strtolower($dialvalue),strtolower($line[sim]))||eregi(strtolower($dialvalue),strtolower($line[nwid]))||eregi($dialvalue,$line[datas]))
{
$cach_blacklist = explode("|",$line[blacklist]);
$blacklistA = in_array(strtolower($send_sim), $cach_blacklist);
$blacklistB = in_array(strtolower($send_name), $cach_blacklist);
if($line[radkanal] !=$send && $blacklistA == FALSE && $blacklistB == FALSE)
{
$kord = explode("|",$line[kordi]);
$teleportvalue = "$line[rpsim]dial|".$line[owner]."|".$line[sim]."|".$line[channel]."|".$kord[0]."|".$line[datas]."|".$line[gatestatus]."#".$line[name]."*".$line[radkanal]."|0|".$line[simrati];
}
}
}
mysql_free_result($result);
mysql_close();
select_db("stargate_t");
$query = "SELECT * FROM gates";
$result = mysql_query($query);
while ($line = mysql_fetch_array($result))
{
if(strtolower($dialvalue)==strtolower($line[name]))
{
$cach_blacklist = explode("|",$line[blacklist]);
$blacklistA = in_array(strtolower($send_sim), $cach_blacklist);
$blacklistB = in_array(strtolower($send_name), $cach_blacklist);
if($line[radkanal] !=$send && $blacklistA == FALSE && $blacklistB == FALSE)
{
$kord = explode("|",$line[kordi]);
$teleportvalue = "$line[rpsim]dial|".$line[owner]."|".$line[sim]."|".$line[channel]."|".$kord[0]."|".$line[datas]."|".$line[gatestatus]."#".$line[name]."*".$line[radkanal]."|0|".$line[simrati];
}
}
}
mysql_free_result($result);
mysql_close();
}
if($teleportvalue == "empty")
{
select_db("stargate_t");
$query = "SELECT * FROM opengate";
$result = mysql_query($query);
while ($line = mysql_fetch_array($result))
{
if(eregi(strtolower($dialvalue),strtolower($line[owner]))||eregi(strtolower($dialvalue),strtolower($line[sim])))
{
$teleportvalue = "nodial|".$line[owner]."|".$line[sim]."|".$line[kanal]."|".$line[posi]."|Milkyway: 10, 11, 12, 13, 14, 15|Pegasus: 10, 11, 12, 13, 14, 15, 16|1|Universe: 10, 11, 12, 13, 14, 15, 16, 17|1|0|0#".$line[sim]."*".$line[kanal]."|1";
}
}
mysql_free_result($result);
mysql_close();
}
return $teleportvalue;
}
//________________________________________________________________
function get_chev_sound_url($chan)
{
$sound = "";
$color = "";
$kanal = "";
select_db("stargate_t");
$query = "SELECT * FROM gates";
$result = mysql_query($query);
while ($line = mysql_fetch_array($result))
{
if($chan == $line[smail])
{
$model = $line[model];
$kanal = $line[radkanal];
}
}
mysql_free_result($result);
mysql_close();
select_db("stargate_t");
$query = "SELECT * FROM settings";
$result = mysql_query($query);
while ($line = mysql_fetch_array($result))
{
if(eregi($line[gatetyp],$model))
{
$color = $line[color];
$sound = $line[sound];
}
}
mysql_free_result($result);
mysql_close();
$out = "$sound|$color|$kanal";
return $out;
}
//________________________________________________________________
function generate_add($model)
{
$typ = 0;
if(eregi("Milkyway",$model)||eregi("Tollan",$model)||eregi("caworks Concept",$model)||eregi("Low Prim",$model))
{
$typ = 1;
}
if(eregi("Pegasus",$model)||eregi("Tula",$model))
{
$typ = 2;
}
if(eregi("Universe",$model))
{
$typ = 3;
}
if(eregi("Iconian",$model))
{
$typ = 4;
}
if($typ == 1)
{
$set1 = gen_zahlen(1,36,6);
$set1 = join(", ", $set1);
$set2 = gen_zahlen(1,36,7);
$set2 = join(", ", $set2);
$set3 = gen_zahlen(1,36,8);
$set3 = join(", ", $set3);
}
if($typ == 2)
{
$set1 = gen_zahlen(1,36,7);
$set1 = join(", ", $set1);
$set2 = gen_zahlen(1,36,6);
$set2 = join(", ", $set2);
$set3 = gen_zahlen(1,36,8);
$set3 = join(", ", $set3);
}
if($typ == 3)
{
$set1 = gen_zahlen(1,36,8);
$set1 = join(", ", $set1);
$set2 = gen_zahlen(1,36,7);
$set2 = join(", ", $set2);
$set3 = gen_zahlen(1,36,6);
$set3 = join(", ", $set3);
}
if($typ == 4)
{
$set1 = gen_zahlen(1,36,8);
$set1 = join(", ", $set1);
$set2 = gen_zahlen(1,36,7);
$set2 = join(", ", $set2);
$set3 = gen_zahlen(1,36,6);
$set3 = join(", ", $set3);
}
$out = "Milkyway: $set1|Pegasus: $set2|$typ|Universe: $set3";
return $out;
}
//________________________________________________________________
function generate_network_id($model)
{
$first = random_string($length = 3, $characters_array = false, $mode = 0, $test_mode = false);
$secound = random_string($length = 3, $characters_array = false, $mode = 10, $test_mode = false);
$back = "$first-$secound";
return $back;
}
//________________________________________________________________
function gen_zahlen($min, $max, $anz)
{
$werte = range($min, $max);
mt_srand ((double)microtime()*1000000);
for($x = 0; $x < $anz; $x++)
{
$i = mt_rand(1, count($werte))-1;
$erg[] = $werte[$i];
array_splice($werte, $i, 1);
}
return $erg;
}
//________________________________________________________________
function random_string($length, $characters_array, $mode, $test_mode)
{
    if (!$characters_array)
    {
        $characters_array = array('A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L',
            'M', 'N', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y',
            'Z');
    }
    if (!isset($mode))
    {
        $mode = 5;
    }
    $num = array('0', '1', '2', '3', '4', '5', '6', '7', '8', '9');
    $random = '';
    for ($i = 0; $i < $length; $i++)
    {
        if (rand(1, 10) > $mode)
        {
            $random .= $characters_array[rand(0, count($characters_array) - 1)];
        }
        else
        {
            $random .= $num[rand(0, 9)];
        }
    }
    if ($test_mode)
    {
        //----- Random Test Mode -----
        $test_array = str_split($random);
        $i = 0;
        $x = 0;
 
        foreach ($test_array as $key => $value)
        {
            if (is_numeric($value))
            {
                $i++;
            }
            else
            {
                $x++;
            }
        }
        return $random.' - '.count($test_array).' Zeichen, '.$i.' Zahle(n), '.$x.' Buchstabe(n)';
    }
    else
    {
        return $random;
    }
}
//________________________________________________________________
function spam_x($data)
{
$count_r = "0";
$time_r = "0";
$set="no";
select_db("cwdata");
$query = "SELECT * FROM spam_x";
$result = mysql_query($query);
while ($line = mysql_fetch_array($result))
{
if($data==$line[datax])
{
$set="yes";
$time_r = $line[timex];
$count_r = $line[countx];
}
}
mysql_free_result($result);
mysql_close();
if($set=="no")
{
$time = time();
$count = "1";
select_db("cwdata");
mysql_query("INSERT INTO spam_x(id,datax,timex,countx)VALUES(NULL ,'$data','$time','$count')");
mysql_close();
$out = "run";
}
elseif($set=="yes")
{
$time_is = time();
$time_be = $time_is - $time_r;
if($time_be >= 30)
{
$out = "run";
$count_r = "0";
select_db("cwdata");
$aendern = "UPDATE spam_x Set
timex = '$time_is', countx = '$count_r' 
WHERE datax = '$data'"; 
$update = mysql_query($aendern);
mysql_close();
}
elseif($count_r <= 2)
{
$out = "run";
$count_r = $count_r + 1;
select_db("cwdata");
$aendern = "UPDATE spam_x Set
countx = '$count_r'
WHERE datax = '$data'"; 
$update = mysql_query($aendern);
mysql_close();
}
elseif($count_r >= 3)
{
$out = "banned";
}
}
return $out;
}
//________________________________________________________________
function unixtotime($unix)
{
$datum = date("d.m.Y",$unix);
$uhrzeit = date("H:i:s",$unix);
$out = $datum."-".$uhrzeit;
return $out;
}
//________________________________________________________________
function getdiffdate($is,$old)
{
$exp_is_a = explode("-",$is);
$exp_is_date = explode(".",$exp_is_a[0]);
$exp_is_time = explode(":",$exp_is_a[1]);
$exp_old_a = explode("-",$old);
$exp_old_date = explode(".",$exp_old_a[0]);
$exp_old_time = explode(":",$exp_old_a[1]);
$time_is = mktime($exp_is_time[0],$exp_is_time[1],0,$exp_is_date[1],$exp_is_date[0],$exp_is_date[2]);
$time_old = mktime($exp_old_time[0],$exp_old_time[1],0,$exp_old_date[1],$exp_old_date[0],$exp_old_date[2]);
$diff=$time_old-$time_is;
$x=$diff/86400;
$weeks = 0;
$days = 0;
$w = "week";
$d = "day";
$h = "hour";
$hour = 0;
$min = 0;
if($x > 6.9)
{
$weeks = $x/7;
$expw = explode(".",$weeks);
$weeks = $expw[0];
$cacha = $weeks*7;
$cachb = $x-$cacha;
$expd = explode(".",$cachb);
$days = $expd[0];
$cachc = $cacha + $days;
$cachd = $x * 24;
$cache = $cachc *24;
$cachf = $cachd -$cache;
$exph = explode(".",$cachf);
$hour = $exph[0];
$cachh = $cachf-$hour;
$cachi = $cachh * 60;
$expmi = explode(".",$cachi);
$min = $expmi[0];
}
elseif($x<7)
{
$cachz=$diff/86400;
$expd = explode(".",$cachz);
$days = $expd[0];
$cachd = $x * 24;
$cache = $days *24;
$cachf = $cachd-$cache;
$exph = explode(".",$cachf);
$hour = $exph[0];
$cachh = $cachf-$hour;
$cachi = $cachh * 60;
$expmi = explode(".",$cachi);
$min = $expmi[0];
}
$m = "min";
$months = 0;
if($weeks > 3)
{
$months = $weeks/4;
$expf = explode(".",$months);
$months = $expf[0];
$cachf = $months*4;
$weeks = $weeks-$cachf;
}
$mm = "month";
if($days > 1)
{
$d = $d."s";
}
if($weeks > 1)
{
$w = $w."s";
}
if($hour > 1)
{
$h = $h."s";
}
if($months > 1)
{
$mm = $mm."s";
}
$out_new = $months.$mm." ".$weeks.$w." ".$days.$d." ".$hour.$h." ".$min.$m;
return $out_new;
}
//__________________________________________________________
function getnewdata($adw,$olddate)
{
$expx = explode("-",$olddate);
$new_datum = date("d.m.Y", strtotime("$expx[0] + $adw week"));
$timest = "$new_datum-$expx[1]";
return $timest;
}
//__________________________________________________________
function getPage($web)
{
$html = "";
$ch = curl_init($web);
curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.8.0.12) Gecko/20070508 Firefox/1.5.0.12");
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_HEADER, 0);
//curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
curl_setopt($ch, CURLOPT_AUTOREFERER, 1);
curl_setopt($ch, CURLOPT_TIMEOUT, 15);
$html = curl_exec($ch);
if(curl_errno($ch))
{
$html = "";
}
curl_close ($ch);
return $html;
}
//__________________________________________________________
function slProfil($url, $type)
{
$data="";
if($type == "image")
{
$cach = getPage($url);
$data = getBetween($cach,'<img alt="profile image" src="','" class="parcelimg" />');
}
if($type == "displayname")
{
$cach = getPage($url);
$data = getBetween($cach,'<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>','</title>');
}
if($type == "age")
{
$cach = getPage($url);
$data = getBetween($cach,'<p class="info"><span class="syscat">Resident Since:</span>
',')
</p>');
}
return $data;
}
//________________________________________________________________
function encodesearchurlprofil($name)
{
$key = name2Key($name);
$url = 'http://world.secondlife.com/resident/'.$key;
return $url;
}
//________________________________________________________________
function getBetween($content,$start,$end)
{
$a1 = strpos($content,$start);
$content = substr($content,$a1 + strlen($start));
while($a2 = strrpos($content,$end))
{
$content = substr($content,0,$a2);
}
return $content;
}
//__________________________________________________________
function sendToHost($host,$method,$path,$data,$useragent=0)
{ 
$fp = fsockopen($host, 80, $errno, $errstr, 30);
if( !$fp )
{
echo"$errstr ($errno)<br />\n";
}
else
{
fputs($fp, "$method $path HTTP/1.1\r\n"); 
fputs($fp, "Host: $host\r\n"); 
fputs($fp, "Content-type: text/xml\r\n"); 
fputs($fp, "Content-length: " . strlen($data) . "\r\n"); 
if ($useragent) 
fputs($fp, "User-Agent: MSIE\r\n"); 
fputs($fp, "Connection: close\r\n\r\n"); 
fputs($fp, $data); 
fclose($fp); 
}
}
//__________________________________________________________
function send_mail_sl($regard,$data,$uuid)
{
$addressor = "creator@caworks-sl.de";
$receiver = "$uuid@lsl.secondlife.com";
$mailtext = $data;
mail($receiver, $regard, $mailtext, "From: $addressor "); 
}
//__________________________________________________________
function name2Key($name)
{
$SL_SEARCH = 'http://vwrsearch.secondlife.com/client_search.php?session=00000000-0000-0000-0000-000000000000&q=';
$sName = split(' ',$name);
$data = getPage($SL_SEARCH.$sName[0].'%20'.$sName[1]);
$uuid = getBetween($data,'<h3 class="result_title">
          <a href="http://world.secondlife.com/resident/','" >');
if(!preg_match("/^[0-9a-f]{8}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{12}$/",$uuid)) $uuid = '00000000-0000-0000-0000-000000000000';
return $uuid;
}
//__________________________________________________________
function rand_char($chars)
{
$n = rand(0,(strlen($chars)-1)); 
return $chars[$n];
}
//__________________________________________________________
function id_filter($web)
{
if($web == "")
{
$zeichen = "0123456789abcdef"; 
for($x=0;$x<3;$x++)
{ 
$s2 .= rand_char($zeichen);
}
for($x=0;$x<3;$x++)
{ 
$s3 .= rand_char($zeichen);
}
}
else
{
$s1 = explode("-",$web);
$s2 = substr($s1[0], 0, 3);
$s3 = substr($s1[0], 3, 3);
}
$s2 = strtr($s2, "abcdef", "ABCDEF");
$s3 = strtr($s3, "abcdef", "ABCDEF");
$s2 = strtr($s2, "0123456789", "PPPPPPMMMM");
$s2 = strtr($s2, "ABCDEF", "PPPMMM");
$s3 = strtr($s3, "ABCDEF", "123456");
return $s2."-".$s3;
}
//__________________________________________________________
function send_http_sl($chan,$data,$uuid)
{
$xmldata = "<?xml version=\"1.0\"?><methodCall><methodName>llRemoteData</methodName>
<params><param><value><struct>
<member><name>Channel</name><value><string>".$uuid."</string></value></member>
<member><name>IntValue</name><value><int>".$chan."</int></value></member>
<member><name>StringValue</name><value><string>".$data."</string></value></member>
</struct></value></param></params></methodCall>";
sendToHost("xmlrpc.secondlife.com", "POST", "/cgi-bin/xmlrpc.cgi", $xmldata);
}
?>
<?php
include ("lib.php");
select_db("stargate_t");
mysql_query("CREATE TABLE api_key (
id INT( 255 ) NOT NULL auto_increment,
datas VARCHAR( 200 ) NOT NULL ,
owner VARCHAR( 200 ) NOT NULL ,
PRIMARY KEY (id) );");
mysql_close();
echo "Install complete";
?>
<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf8">
        <title>TestShop</title>
        <link rel="stylesheet" href="Styles.css">
    </head>
    <body text="#696969" bgcolor="#fff5ee">
        <h2 style="color:#9fbd0d">TestShop<img src="images/shop1.jpg"style="max-width:100px; max-height:100px"></h2><br>
        <a href="index.php"><img src="images/productcatalog.png" style="max-width:220px; max-height:220px"></a>
      
<?php

$ID = $_REQUEST["id"];
if (!isset($ID) || !$ID)
{
    return;
}

include("dbopen.php");

mysql_query("SET NAMES 'utf8'");

 
global $dbServer;

$sSQL = "SELECT title, description, photo, price, group_id".
        "  FROM product".
        " WHERE id = ".$ID;
$result = mysql_query($sSQL, $dbServer);

if (mysql_num_rows($result) > 0)
{
    $row = mysql_fetch_row($result);
    if ($row)
    {
        echo "<a href=\"product_listing.php?group_id=". $row[4] ."\"><img src=\"images/productlisting.png\"style=\"max-width:220px; max-height:220px\"></a>";
    }
}
        
?>
        <p>
        <table cellspacing=0 align="center" cellpadding=0 border="" bordercolor="#fff5ee"  width="800px">
            <tr>
                <td>
                    <hr size="2" color="#FFE4E1">
                </td>
            </tr>
            
<?php
        echo "<tr>".
             "    <td style=\"text-align:center\">".
             "        <h2><u>" . $row[0] . "</u></h2>".
             "        <p>".
             "        <img src=\"images/" . $row[2] . "\"  width=420 height=420 style=\"color:#e6e5e2\" border=\"1\">".
             "    </td>".
             "</tr>".
             "<tr>".
             "    <td>".
             "        <font color=\"#696969\" size=\"4\">".
             "            <p align =\"justify\"><u>DESCRIPTION:</u>".
             "                <br>". $row[1] .
             "            </p>".
             "        </font>".    
             "    </td>".
             "</tr>".
             "<tr>".
             "    <td colspan=3>".
             "        <font color=\"#6A5ACD\" size=\"5\">".
             "            <b><p>PRICE: ". $row[3] ."</p></b>".
             "        </font>".
             "    </td>".
             "</tr>";
    


mysql_free_result($result);

mysql_close($dbServer);
           
?>
            <tr>
                <td>
                     <hr size="2" color="#FFE4E1">
                </td>
            </tr>
        </table
    </body>
</html>

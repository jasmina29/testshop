<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf8">
        <title>TestShop</title>
        <link rel="stylesheet" href="Styles.css">
    </head>
    
    <body text="#696969" bgcolor="">
        <h2 style="color:#9fbd0d">TestShop<img src="images/shop1.jpg"style="max-width:100px; max-height:100px"/></h2>
        <img src="images/productcatalog.png" style="max-width:220px; max-height:220px">
        

<?php

include("dbopen.php");

mysql_query("SET NAMES 'utf8'");


//-----------------------------------------------------------------------------

function ShowTree($parentID, $levelNo)
{ 
    global $dbServer;

    $levelNo++;
    
    $sSQL = "SELECT id, title, parent_id".
            "  FROM product_group".
            " WHERE parent_id = ".$parentID.
            " ORDER BY title";
    $result = mysql_query($sSQL, $dbServer);

    if (mysql_num_rows($result) > 0)
    {
        echo("<UL>\n");
        while ( $row = mysql_fetch_array($result) )
        {
            $id = $row["id"];
            echo("    <LI>\n");
            echo("        <A");
            if ($levelNo == 3)
            {
                echo(" HREF=\"product_listing.php?group_id=". $id . "\"");
            }
            echo(">" . $row["title"] . "</A>"."  \n");
            ShowTree($id, $levelNo);
        }
        echo("</UL>\n");
    }
    $levelNo--;
}







//=============================================================================

ShowTree(0, 0);

mysql_close($dbServer); 

?>

    </body>
</html>

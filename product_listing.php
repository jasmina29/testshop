<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv = "Content-Type" content = "text/html; charset = utf8">
        <title>TestShop</title>
        <link rel = "stylesheet" href = "Styles.css">
    </head>
    <body text = "#696969" bgcolor = "#fff5ee">
        <h2 style = "color:#9fbd0d">TestShop<img src = "images/shop1.jpg"style = "max-width:100px; max-height:100px"></h2>
        <a href = "index.php"><img src = "images/productcatalog.png" style = "max-width:220px; max-height:220px"></a>
        

<?php

$group_id = 0;
if (isset($_REQUEST['group_id']))
{
    $group_id = $_REQUEST['group_id'];
}
else
{
    echo "No group provided.";
    return;
}

include("dbopen.php");

mysql_query("SET NAMES 'utf8'");
 
global $dbServer;


// Select group title from DB
$sSQL = "SELECT title".
        "  FROM product_group".
        " WHERE id = ".$group_id;
$result = mysql_query($sSQL, $dbServer);

if (mysql_num_rows($result) > 0)
{
    if ($row = mysql_fetch_row($result))
    {
        echo "<h2 style = \"text-align:center\">". $row[0] ."</h2>";
    }
}

mysql_free_result($result);

// Select vendors from DB
$sSQL = "SELECT id, name".
        "  FROM vendor";
$result = mysql_query($sSQL, $dbServer);
$vendors_db = array();
if (mysql_num_rows($result) > 0)
{
    while ($row = mysql_fetch_row($result))
    {
        $vendors_db[$row[0]] = $row[1];
    }
}
//$vendors_db
//{
//    1: "ASUS",
//    2: "Sony",
//    3: "Apple"
//}

mysql_free_result($result);

// Initialize filter parameters
$price_min = "";
$price_max = "";
$vendors_checked = array();

$sSQL2 = "";

// Compose additional condition for product request from filter parameters from the _POST[]
if (isset($_POST['ok']))
{ 
    // Price limitation
    $price_min = (float)$_POST['price(Min)'];
    $price_max = (float)$_POST['price(Max)'];
    if ($price_min > 0)
    {
        $sSQL2 .= " AND price >= " . $price_min;
    }
    else
    {
        $price_min = "";
    }
    
    if ($price_max > 0)
    {
        $sSQL2 .= " AND price <= " . $price_max;
    }
    else
    {
        $price_max = "";
    }
    
    // Selected vendors
    if (isset($_POST["vendors_checked"]))
    {
        $vendors_checked = $_POST["vendors_checked"];
        if (!empty($vendors_checked))
        {
            $vendors_checked_count = count($vendors_checked);
            if ($vendors_checked_count > 0)
            {
                $sSQL2 .= " AND vendor_id IN (";
                $sSQL_vendor_ids = "";
                foreach ($vendors_checked as $value)
                {
                    if (strlen($sSQL_vendor_ids))
                    {
                        $sSQL_vendor_ids .= ",";
                    }
                    $sSQL_vendor_ids .= $value;
                }
                $sSQL2 .= $sSQL_vendor_ids . ")";
            }
        }
    }
}

echo    "<table border=0 cellspacing=4>".
        "    <tr>".
        
        
        "        <td style = \"vertical-align:top\">".
        "        <form id = \"product_listing\" action = \"product_listing.php?group_id=" .$group_id. "\" method = \"post\">".
        "        <table border=\"1\" cellpadding = \"4\" cellspacing = \"4\" bordercolor = \"#9fbd0d\">".
        "           <tr>".
        "               <th colspan = 2>Price".
        "            </tr>".
        "                <tr>".
        "                    <td style = \"padding-top:10px; padding-bottom:10px\">Min:".
        "                    <td style = \"padding-top:10px; padding-bottom:10px\"><input type=\"number\" step=\"any\" name=\"price(Min)\" value=\"". $price_min . "\"></input>".
        "                </tr>".
        "                <tr>".
        "                    <td style = \"padding-top:10px; padding-bottom:10px\">Max:".
        "                    <td style = \"padding-top:10px; padding-bottom:10px\"><input type=\"number\" step=\"any\" name=\"price(Max)\" value=\"". $price_max . "\"></input>".
        "                </tr>".
        "                <tr>".
        "                    <td  colspan = 2>".
        "                </tr>".
        "                <tr>".
        "                    <th style = \"padding-top:5px; padding-bottom:5px\" colspan = 2>Vendor".
        "                </tr>".
        "                <tr>".
        "                    <td colspan = 2 align = center>".
        "                        <table border = 0 cellspacing = 0>";

foreach ($vendors_db as $id => $name)
{
    $checked = "";
    if (in_array($id, $vendors_checked))
    {
        $checked = " checked";
    }
    echo
        "                            <tr>".
        "                                <td><input type=\"checkbox\" name=\"vendors_checked[]\" value=\"" . $id . "\" " . $checked . ">" . $name . "</input>".
        "                                </td>".
        "                            </tr>";
}
        
echo    "                        </table>".
        "                    </td>".
        "                </tr>".
        "                <tr>".
        "                    <td colspan = 2 style = \"padding-top:10px; padding-bottom:5px; height:5px\"><input type = \"submit\" name = \"ok\" value = \"Find\" style = \"width:100%\"></input>".
        "                    </td>".
        "                </tr>".
        "            </table>".
        "            </form>".
        "        </td>".
        
        
        "        <td bgcolor = \"#fff5ee\" width = \"20px\">".
        
        
        "        <td>".                                    
        "            <table>";

// Select filtered results (products)
$sSQL = "SELECT title, short_desc, small_photo, price, id".
        "  FROM product".
        " WHERE group_id = ".$group_id.
        $sSQL2;
$result = mysql_query($sSQL, $dbServer);
$num_rows = mysql_num_rows($result);
echo "<tr>".
     "    <td>Count: ". $num_rows;

if ($num_rows > 0)
{
    while ($row = mysql_fetch_row($result))
    {
        echo "<tr>".
             "    <td>".
             "        <table border = \"0\" cellspacing = \"5\" bordercolor = \"#696969\">".
             "            <tr>".
             "                <td>".
             "                    <hr align = \"center\" size = \"2\" color = \"#FFE4E1\">".
             "                </td>".
             "            </tr>".
             "            <tr>".
             "                <td>".
             "                    <h3><p style = \"text-indent:140px\"><u>". $row[0] ."</u></p></h3>".
             "                    <p style = \"text-indent:220px\">".
             "                    <p style = \"text-indent:0px\"><a href =\"product_description.php?id= ". $row[4] ."\"><img src = \"images/". $row[2] ."\"  width = 100 height = 100 style = \"color:#e6e5e2\" border = \"1\"></a></p>".
             "               </td>".
             "            </tr>".
             "            <tr>".
             "                <td>".
             "                    <font color = \"#696969\" size = \"4\">".
             "                        <p align = \"justify\" style = \"text-indent:140px\"><u>SHORT DESCRIPTION:</u>".
             "                            <br>". $row[1] .
             "                        </p>".
             "                    </font>".
             "                </td>".
             "            </tr>".
             "            <tr>".
             "                <td colspan = 3>".
             "                    <font color = \"#6A5ACD\" size = \"2\">".
             "                        <h2><b><p style = \"text-indent:140px\">PRICE: ". $row[3] ."</p></b></h2>".
             "                    </font>".
             "                </td>".
             "            </tr>".
             "        </table>".
             "    </td>".
             "</tr>";
    }
}

mysql_free_result($result);

mysql_close($dbServer); 

?>                        
                    </table>
                </td>
            </tr>
                
            <tr>
                <td>
                    <hr align = "center" size = "2" color = "#FFE4E1">
                </td>
            </tr>
        </table>

    </body>
</html>
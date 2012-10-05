<?php
/**
 * Created by JetBrains PhpStorm.
 * User: federico
 * Date: 25/08/12
 * Time: 22:50
 * To change this template use File | Settings | File Templates.
 */

$date = "6.15.2009 13:00+01:00";
print_r(date_parse_from_format("j.n.Y H:iP", $date));
?>
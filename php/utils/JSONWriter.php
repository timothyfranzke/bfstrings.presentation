<?php
/**
 * Created by PhpStorm.
 * User: Timothy
 * Date: 1/31/2015
 * Time: 8:43 AM
 */
class JSONWriter
{
    function RecordSetToJson($mysql_result) {
        $rs = array();
        mysql_data_seek($mysql_result, 0);
        while($results = mysql_fetch_assoc($mysql_result)) {
            $rs[] = $results;
        }
        return json_encode($rs);
    }
}

<?php
/**
 * Created by PhpStorm.
 * User: d4rk
 * Date: 29.06.14
 * Time: 13:14
 */
include_once "/internal/controllerResponse.php";
class shoutBoxApiController {

    public function Read() {


        $rs = new stdClass();
        $rs->Time = date("Y-m-d H:i:s");
        $rs->Owner = new stdClass();
        $rs->Owner->Username = "Domi";
        $rs->MessageText = "HALLOOO !";
        $rs->id = 1;
        $rs_arr = array($rs);

        $response = new stdClass();
        $response->Messages = $rs_arr;
        $response->TotalCount = sizeof($rs_arr);

        $resp = new \internal\controllerResponse();
        $resp->UseMasterPage = false;
        $resp->ResponseContent = json_encode($response);
        $resp->ContentType = 'application/json';

        return $resp;
    }

    function Create() {

    }

    function Export() {
        $filename = "Domi_msg";
        header ("Content-Type: application/octet-stream");
        header ("Content-disposition: attachment; filename=".$filename.".csv");

        echo("User;Message\r\nDomi;Hallo");

    }
}

?>
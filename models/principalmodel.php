<?php

// require_once "models/logmodel.php";
// require('public/fpdf/fpdf.php');
use LDAP\Result;

class principalmodel extends Model
{

    public function __construct()
    {
        parent::__construct();
    }

    function Cargar_Datos($parametros)
    {
        try {
            $p = $parametros["cedula"];
            // $p2 = $parametros["p2"];

            set_include_path(__DIR__ . '/ssh');
            include("ssh/Net/SSH2.php");

            $key = "xtratech";
            $ssh = new Net_SSH2('10.5.3.186', 22);
            if (!$ssh->login('edinson', $key))  exit('Login Failed');
            $array = $ssh->exec('/home/edinson/credit_scoring_app/credit_venv/bin/python3  make_prediction.py '.$p);
            // $array = json_decode($array,true);
            // echo json_encode(($array));
            // //        echo json_encode($array);
            // exit();
            $a = json_decode($array, true);
            $item = [];
            $i=0;
            // foreach ($a as $row) {

            //     $f = $row["FirstInvoiceDate"] / 1000;
            //     $fm = $row["FirstInvoiceMonth"] / 1000;
            //     $fl = $row["LastInvoiceDate"] / 1000;

            //     $f = date('Y-m-d', $f);
            //     $fm = date('Y-m-d', $fm);
            //     $fl = date('Y-m-d', $fl);

            //     $arr["ClienteId"] =  $row["ClienteId"];
            //     $arr["FinalRFM"] =  $row["FinalRFM"];
            //     $arr["FirstInvoiceDate"] =  $f;
            //     $arr["FirstInvoiceMonth"] =  $fm;
            //     $arr["Frequency"] =  $row["Frequency"];
            //     $arr["FrequencyScore"] =  $row["FrequencyScore"];
            //     $arr["LastInvoiceDate"] =  $fl;
            //     $arr["Lifetime"] =  $row["Lifetime"];
            //     $arr["MonetaryScore"] =  $row["MonetaryScore"];
            //     $arr["MonetaryValue"] =  $row["MonetaryValue"];
            //     $arr["RFM"] =  $row["RFM"];
            //     $arr["Recency"] =  $row["Recency"];
            //     $arr["RecencyScore"] =  $row["RecencyScore"];
            //     $arr["RelFrequency"] =  $row["RelFrequency"];
            //     $arr["RelMonetaryValue"] =  $row["RelMonetaryValue"];
            //     $arr["RelRecency"] =  $row["RelRecency"];
            //     $arr["last_order_within_l60d"] =  $row["last_order_within_l60d"];
            //     $arr["more_than_two_orders"] =  $row["more_than_two_orders"];
            //     $arr["value_higher_than_2k"] =  $row["value_higher_than_2k"];
            //     $items[$i] = $arr;
            //     $i++;
            // }
            $ssh->disconnect(); 
            unset($ssh);
            echo json_encode($a);
            exit();
        } catch (Exception $e) {
            echo json_encode($e);
            exit();
        }
    }
}

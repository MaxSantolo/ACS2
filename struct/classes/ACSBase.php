<?php
/**
 * Created by PhpStorm.
 * User: msantolo
 * Date: 30/10/2018
 * Time: 10:27
 */


class ACSBase
{
//genera array dei citofoni a partire da tipo e centro
    public function FetchIntercoms($conn, $type, $center)
    {
        $array_intercoms = array();
        if (!isset($center) || $center == '') {
            $sql = "SELECT name,phone_num FROM acs_doors WHERE type ='" . $type . "' ";
        } else $sql = "SELECT name,phone_num FROM acs_doors WHERE type ='" . $type . "' AND center = '" . $center . "'  ";
        $array = $conn->query($sql);
        while ($row = $array->fetch_assoc()) {
            $array_intercoms[] = $row['phone_num'];
        }
        return $array_intercoms;
    }
//genera stringa in per la query
    public static function GenerateInString($array)
    {
        $intstring = "IN(";
        for ($i = 0; $i < count($array); ++$i) {
            if ($i == count($array) - 1) {
                $intstring .= "'" . $array[$i] . "')";
            } else $intstring .= "'" . $array[$i] . "',";
       }
        return $intstring;
    }
//restituisce il centro a partire dal citofono
    function ReturnCenter($conn,$intercom) {
        $center_array = $conn->query("SELECT description FROM acs_doors WHERE phone_num ='".$intercom."' ")->fetch_assoc();
        return $center = $center_array['description'];
    }
//stringa della data in italiano
    public static function DateToItalian($date, $format) {
        $english_days = array('Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday');
        $italian_days = array('Lun', 'Mar', 'Mer', 'Gio', 'Ven', 'Sab', 'Dom');
        $english_months = array('January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December');
        $italian_months = array('Gen', 'Feb', 'Mar', 'Apr', 'Mag', 'Giu', 'Lug', 'Ago', 'Set', 'Ott', 'Nov', 'Dic');
        return str_replace($english_months, $italian_months, str_replace($english_days, $italian_days, date($format, strtotime($date) ) ) );
    }
//controlla se contatto è nel crm e restituisce le stringhe vero falso
    public static function isCRM($id,$truestring,$falsestring) {
        if ($id=='') { $rstr = $falsestring; } else $rstr = $truestring;
        return $rstr;
    }
//restituisce la data di oggi formattata per mysql
    public static function Now() {
      return $now = (new DateTime('Europe/Rome'))->format('Y-m-d H:i:s');
    }
}
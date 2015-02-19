<?PHP
function _die($msg, $code) {
    echo $msg;
    exit($code);
}

function valid_date($date) {
    if (date('d-m-Y H:i', strtotime($date)) == $date) {
        return true;
    } else {
        return false;
    }
}

function valid_ip($ip) {
    $return = true;
    if (strpos($ip, ".") < 1) {
        $return = false;
    } else {
        $octets = explode(".", $ip);
        foreach ( $octets AS $octet ) {
            if ( $octet > 255 || $octet < 0 || !is_numeric($octet) ) {
                $return = false;
            }
        }
    }

    return $return;
}

function iprange($range_ip,$range_cidr) {
    $corr=(pow(2,32)-1)-(pow(2,32-$range_cidr)-1);
    $first=ip2long($range_ip) & ($corr);
    $length=pow(2,32-$range_cidr)-1;

    return array(
               'first_ip'=>$first,
               'last_ip'=>$first+$length
               );
}

function generate_startstop($ranges) {
    $startstop = array();
    foreach ($ranges as $key => $range) {
        $range = explode("/", $range);
        $startstop[] = iprange($range[0],$range[1]);
    }

    return $startstop;
}

function difference($val1, $val2, $offset) {
    if (abs($val1 - $val2) > $offset) {
        return true;
    } else {
        return false;
    }
}

?>

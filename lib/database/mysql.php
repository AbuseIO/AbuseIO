<?PHP
function _mysqli_connect() {
    if ($mysqli_connection = mysqli_connect(SQL_HOST, SQL_USER, SQL_PASS, SQL_DBNAME)) {
        return $mysqli_connection;
    }
    logger(LOG_ERR,"ERROR - Connection to the database server failed while trying to connect.");
    return false;
}

function _mysqli_query($query, $link) {
    if(is_string($link)) {
        if (($link = _mysqli_connect())===false) {
            return false;
        }
    }
    $result = mysqli_query($link, $query);
    if (mysqli_errno($link)) {
        logger(LOG_ERR,"Fatal ERROR in MySQL Query ($query), Error:".mysqli_errno($link) . ': ' . mysqli_error($link) . PHP_EOL);
        return false;
    }

    return $result;
}

function _mysqli_fetch($query) {
    if (($link = _mysqli_connect())===false) {
        return false;
    }
    if ($result = _mysqli_query($query, $link)) {
        $return = array();
        while ($row = mysqli_fetch_assoc($result)) {
            $return[] = $row;
        }
        return $return;
    }
    return false;
}

function _mysqli_num_rows($query) {
    if (($link = _mysqli_connect())===false) {
        return false;
    }
    if ($result = _mysqli_query($query, $link)) {
        $return = array();
        $return = mysqli_num_rows($result);
        return $return;
    }
    return false;
}

?>

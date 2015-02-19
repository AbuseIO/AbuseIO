<?PHP
function define_configuration() {
    if ($ini_array = parse_ini_file(APP."/etc/settings.conf")) {
        foreach ($ini_array as $key => $value) {
    		DEFINE(strtoupper($key), $value);
        }
    } else {
        die("FATAL - Unable to parse etc/settings.conf");
    }
}
?>

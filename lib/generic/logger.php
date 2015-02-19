<?PHP
function logger($priority, $message) {
    openlog('abusereporter', LOG_ODELAY | LOG_PID | LOG_CONS, LOG_LOCAL1);
    syslog($priority, $message);
    closelog();
}
?>

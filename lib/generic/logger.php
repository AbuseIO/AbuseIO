<?PHP
function logger($priority, $message) {
    openlog('abuseio', LOG_ODELAY | LOG_PID | LOG_CONS, LOG_LOCAL1);
    syslog($priority, $message);
    // Also log to console if we're running in CLI mode
    if (PHP_SAPI == 'cli') echo $message.PHP_EOL;
    closelog();
}
?>

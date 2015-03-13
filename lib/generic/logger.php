<?PHP
/******************************************************************************
* AbuseIO 3.0
* Copyright (C) 2015 AbuseIO Development Team (http://abuse.io)
*
* This program is free software; you can redistribute it and/or
* modify it under the terms of the GNU General Public License
* as published by the Free Software Foundation; either version 2
* of the License, or (at your option) any later version.
*
* This program is distributed in the hope that it will be useful,
* but WITHOUT ANY WARRANTY; without even the implied warranty of
* MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
* GNU General Public License for more details.
*
* You should have received a copy of the GNU General Public License
* along with this program; if not, write to the Free Software Foundation
* Inc., 51 Franklin St, Fifth Floor, Boston, MA 02110-1301, USA.
*******************************************************************************
*
* Core logging related functions
*
******************************************************************************/


/*
** Function: logger
** Parameters: 
**  priority(static): static syslog priority like LOG_ERR, LOG_WARNING, etc
**  message(string): the message the logging facility should receive
** Returns: Nothing
*/
function logger($priority, $message) {
    openlog('abuseio', LOG_ODELAY | LOG_PID | LOG_CONS, LOG_LOCAL1);
    syslog($priority, $message);
    // Also log to console if we're running in CLI mode
    if (PHP_SAPI == 'cli' && DEBUG == true) echo $message.PHP_EOL;
    closelog();
}
?>

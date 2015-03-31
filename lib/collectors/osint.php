<?php
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
* Feed Collector script that fetches data from Bambenek and processes the
* events into reports when needed.
*
******************************************************************************/


/*
** Function: collect_osint
** Parameters:
**  config (array): a set of configuration options
** Returns: True when the collection was successfully completed (or false when it doenst)
*/
function collect_osint($config) {
    $class  = 'Botnet infection';
    $source = 'Bambenek';
    $type   = 'ABUSE';

    $baseurl = 'http://osint.bambenekconsulting.com/feeds/';

    $feeds = array(
                    'Banjori'               => 'banjori-asn.txt',
                    'Bebloh/URLZone'        => 'bebloh-asn.txt',
    //                'Cryptolocker'          => 'cryptolocker-asn.txt',
                    'Cryptowall'            => 'cryptowall-asn.txt',
                    'Dyre'                  => 'dyre-asn.txt',
                    'Geodo'                 => 'geodo-asn.txt',
                    'Hesperbot'             => 'hesperbot-asn.txt',
                    'Matsnu'                => 'matsnu-asn.txt',
                    'Necurs'                => 'necurs-asn.txt',
                    'P2P GOZ'               => 'p2pgoz-asn.txt',
                    'Pushdo'                => 'pushdo-asn.txt',
                    'Qakbot'                => 'qakbot-asn.txt',
                    'Ramnit'                => 'ramnit-asn.txt',
                    'Symmi'                 => 'symmi-asn.txt',
                    'Tinba / TinyBanker'    => 'tinba-asn.txt',
                  );

    $fieldnames = array(
                        0 => 'asn',
                        1 => 'ip',
                        2 => 'netblock',
                        3 => 'country',
                        4 => 'rir',
                        5 => 'assigned-date',
                        6 => 'description',
                       );
    
    foreach($feeds as $name => $uri) {

        // Collect feeddata and unset first and last fields which are bogus
        $feeddata = explode("\n", file_get_contents($baseurl . $uri));
        unset($feeddata[0]);
        unset($feeddata[count($feeddata)]);

        // Only continue if there is actually some data in the feed results
        if(count($feeddata) !== 0) {
            foreach($feeddata as $id => $row) {
                $fielddata = explode(' | ', $row);
                $fields    = array_combine($fieldnames, $fielddata);

                // Pending confirmation about field layout from Bambenek
                $outReport['ip']            = '';
                $outReport['source']        = '';
                $outReport['type']          = '';
                $outReport['class']         = '';
                $outReport['information']   = array();
                $outReport['timestamp']     = '';
        
            }
        }
    }

    logger(LOG_INFO, __FUNCTION__ . " Completed message ");
    return true;
}
?>

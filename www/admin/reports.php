<?php
    $title = 'Reports';
    include('../../lib/core/loader.php');

    // Query filter
    $filter = "";
    if(!empty($_GET['Ticket']))         $filter .= " AND ID='".mysql_escape_string($_GET['Ticket'])."'";
    if(!empty($_GET['IP']))             $filter .= " AND IP LIKE '".mysql_escape_string($_GET['IP'])."'";
    if(!empty($_GET['Class']))          $filter .= " AND Class='".mysql_escape_string($_GET['Class'])."'";
    if(!empty($_GET['Source']))         $filter .= " AND Source='".mysql_escape_string($_GET['Source'])."'";
    if(!empty($_GET['Status']))         $filter .= " AND Status='".mysql_escape_string($_GET['Status'])."'";
    if(!empty($_GET['CustomerCode']))   $filter .= " AND CustomerCode='".mysql_escape_string($_GET['CustomerCode'])."'";
    if(!empty($_GET['CustomerName']))   $filter .= " AND CustomerName like '%".mysql_escape_string($_GET['CustomerName'])."%'";

    // Select only ABUSE type and OPEN status by default, unless we have other filter options
    if (empty($filter)) {
        $reportType=(isset($_GET['Type']))?mysql_escape_string($_GET['Type']):'ABUSE';
        if (!empty($reportType)) $filter .= " AND Type='$reportType'";

        $filter .= " AND Status = 'OPEN'";
    }

    if(!empty($_GET['Page']) && is_numeric($_GET['Page'])) { $page = $_GET['Page']; } else { $page = 1; }
    if(!empty($_GET['OrderBy']) && in_array($_GET['OrderBy'],array('ID','IP','Class','Status','Source','CustomerCode','CustomerName','LastSeen','Type'))) { $order = mysql_escape_string($_GET['OrderBy']); } else { $order = 'LastSeen'; }
    if(!empty($_GET['Direction']) && in_array($_GET['Direction'],array('ASC','DESC'))) { $direction = mysql_escape_string($_GET['Direction']); } else { $direction = 'DESC'; }

    // Calculate offset
    $rows_per_page = 100;
    $offset = $rows_per_page*($page-1);

    // Pagination settings
    $count = reportCount($filter);
    $pages = ceil($count / $rows_per_page);

    if ($page > $pages) {
        include('../../lib/frontend/top.php');
        echo '<h2>No results</h2>Your search did not find any reports';
        include('../lib/frontend/bottom.php');
        die();
    }

    // Fetch reports
    $filter  .= " ORDER BY ${order} ${direction}";
    if(empty($_GET['action']) || $_GET['action'] != 'DownloadCSV') {
        $filter  .= " LIMIT ${rows_per_page} OFFSET ${offset}";
    }
    $results = reportList($filter);

    // Download search results as CSV
    if(isset($_GET['action']) && $_GET['action'] == 'DownloadCSV') {
        header('Content-Type: text/csv');
        header('Content-Transfer-Encoding: Binary');
        header("Content-disposition: attachment; filename=\"export.csv\"");

        $fields = array('IP', 'Domain', 'URI', 'Type', 'Class', 'FirstSeen', 'LastSeen', 'Status', 'ReportCount', 'SelfHelpURL', 'Information');
        $seperator = ';';

        // Print CSV header row
        foreach($fields as $field) {
            print "\"$field\"" . $seperator;
        }
        print PHP_EOL;

        // Print CSV data rows
        foreach($results as $rowid => $report) {
            foreach($fields as $field) {
                if ($field == 'SelfHelpURL') {
                    $token    = md5("${report['ID']}${report['IP']}${report['Class']}");
                    $tokenurl = SELF_HELP_URL . "?id=${report['ID']}&token=" . $token;
                    print "\"${tokenurl}\"" . $seperator;

                } elseif ($field == 'Information') {
                    $info_output = "";
                    $info_array = json_decode($report['Information'], true);
                    foreach($info_array as $infofield => $infovalue) {
                        $info_output .= "$infofield='" . str_replace(array("'","\""), '', $infovalue) . "' "; 
                    }
                    print "\"${info_output}\"" . $seperator;

                } elseif ($field == 'FirstSeen') {
                    print "\"" . date("d-m-Y H:i", $report[$field]) . "\"" . $seperator;

                } elseif ($field == 'LastSeen') {
                    print "\"" . date("d-m-Y H:i", $report[$field]) . "\"" . $seperator;

                } else {
                    print "\"${report[$field]}\"" . $seperator;
                }
            }
            print PHP_EOL;
        }
        die();
    }

    include('../../lib/frontend/top.php');

    // Calculate result range for current page
    $first = $offset + 1;
    $last = $offset + $rows_per_page;
    if ($last > $count) $last = $count;

    // Calculate page range for paginator
    $first_page = max(1,$page-5);
    $last_page = min($pages,max($first_page+10,$page+5));
    $first_page = max(1,$last_page-10);

    // Build pagination links
    $paginator = '<p>';
    $uri = $_GET;
    if ($pages > 1) {
        if (!empty($uri['Page'])) unset($uri['Page']);
        $paginator .= '<div class="btn-group">';
        $prev = $page-1;
        $btn_class=($page>1)?'btn btn-default btn-sm':'btn btn-default btn-sm disabled';
        $paginator .= "<span class='${btn_class}'><a href='?".http_build_query(array_merge($uri,array('Page'=>$prev)))."'>Previous page</a></span>";
        for ($i=$first_page; $i<=$last_page; $i++) {
            $btn_class=($i == $page)?'btn btn-default btn-sm active':'btn btn-default btn-sm';
            $paginator .= "<span class='${btn_class}'><a href='?".http_build_query(array_merge($uri,array('Page'=>$i)))."'>${i}</a></span>";
        }
        $next = $page+1;
        $btn_class=($page<$pages)?'btn btn-default btn-sm':'btn btn-default btn-sm disabled';
        $paginator .= "<span class='${btn_class}'><a href='?".http_build_query(array_merge($uri,array('Page'=>$next)))."'>Next page</a></span>";
        $paginator .= '</div>';
    }
    $paginator .= " &nbsp; Showing results ${first} - ${last} of ${count}</p>";

    $downloadCSV = "<div class='pull-right'><a href='?".http_build_query(array_merge($uri,array('action'=>'DownloadCSV')))."' class='btn btn-default btn-sm'>Export to CSV</a></div>";

    echo $downloadCSV . $paginator;

?>
<table class="table table-striped table-condensed">
    <thead>
        <tr>
          <th><a href='?<?php echo http_build_query(array_merge($uri,array('OrderBy'=>'ID','Direction'=>($order='ID'&&$direction=='ASC')?'DESC':'ASC'))); ?>'>Ticket</a></th>
          <th><a href='?<?php echo http_build_query(array_merge($uri,array('OrderBy'=>'IP','Direction'=>($order='IP'&&$direction=='ASC')?'DESC':'ASC'))); ?>'>IP</a></th>
          <th><a href='?<?php echo http_build_query(array_merge($uri,array('OrderBy'=>'CustomerCode','Direction'=>($order='CustomerCode'&&$direction=='ASC')?'DESC':'ASC'))); ?>'>Customer</a></th>
          <th><a href='?<?php echo http_build_query(array_merge($uri,array('OrderBy'=>'Type','Direction'=>($order='Type'&&$direction=='ASC')?'DESC':'ASC'))); ?>'>Type</a></th>
          <th><a href='?<?php echo http_build_query(array_merge($uri,array('OrderBy'=>'Class','Direction'=>($order='Class'&&$direction=='ASC')?'DESC':'ASC'))); ?>'>Classification</a></th>
          <th><a href='?<?php echo http_build_query(array_merge($uri,array('OrderBy'=>'LastSeen','Direction'=>($order='LastSeen'&&$direction=='ASC')?'DESC':'ASC'))); ?>'>Last Seen</a></th>
          <th>Count</th>
          <th><a href='?<?php echo http_build_query(array_merge($uri,array('OrderBy'=>'Status','Direction'=>($order='Status'&&$direction=='ASC')?'DESC':'ASC'))); ?>'>Status</a></th>
        </tr>
    </thead>
    <tbody>
<?php
$labelClass = array(
    'ABUSE'     => 'warning',
    'INFO'      => 'info',
    'ALERT'     => 'danger',
    'OPEN'      => 'warning',
    'CLOSED'    => 'info',
    'ESCALATED' => 'danger',
    'NO'        => 'warning',
    'YES'       => 'info',
    '0'         => 'warning',
    '1'         => 'info',
);

foreach($results as $nr => $result) {
    if ($result['ReportCount'] != $result['LastNotifyReportcount']) {
        $ticketStatus = 'NOTIFY PENDING';
    } elseif($result['CustomerResolved'] == 1) {
        $ticketStatus = 'RESOLVED';
    } elseif($result['CustomerIgnored'] == 1) {
        $ticketStatus = 'IGNORED';
    } else {
        $ticketStatus = $result['Status'];
    }

    echo "
        <tr>
          <td><a href='ticket.php?id=${result['ID']}'>${result['ID']}</a></td>
          <td><a href='reports.php?IP=${result['IP']}'>${result['IP']}</a></td>
          <td>
            <a href='reports.php?CustomerCode=${result['CustomerCode']}'>${result['CustomerCode']}</a> -
            <a href='reports.php?CustomerName=${result['CustomerName']}'>${result['CustomerName']}</a>
          </td>
          <td><span class='label label-${labelClass[$result['Type']]}'><a href='reports.php?Type=${result['Type']}'>${result['Type']}</a></span></td>
          <td><a href='reports.php?Class=${result['Class']}'>${result['Class']}</a></td>
          <td>".date("d-m-Y H:i", $result['LastSeen'])."</td>
          <td>${result['ReportCount']}</td>
          <td><span class='label label-${labelClass[$result['Status']]}'><a href='reports.php?Status=${result['Status']}'>${ticketStatus}</a></span></span></td>
        </tr>
    ";
}
?>
    </tbody>
</table>
<?php
    echo $paginator;
    include('../../lib/frontend/bottom.php');
?>


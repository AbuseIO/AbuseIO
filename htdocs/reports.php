<?php
    $title = 'Reports';
    include('../lib/core/loader.php');
    include('../lib/frontend/top.php');

    // Query filter
    $filter = "";
    if(!empty($_GET['Ticket'])) $filter .= " AND ID='".mysql_escape_string($_GET['Ticket'])."'";
    if(!empty($_GET['IP'])) $filter .= " AND IP LIKE '".mysql_escape_string($_GET['IP'])."'";
    if(!empty($_GET['Class'])) $filter .= " AND Class='".mysql_escape_string($_GET['Class'])."'";
    if(!empty($_GET['Source'])) $filter .= " AND Source='".mysql_escape_string($_GET['Source'])."'";
    if(!empty($_GET['Type'])) $filter .= " AND Type='".mysql_escape_string($_GET['Type'])."'";
    if(!empty($_GET['CustomerCode'])) $filter .= " AND CustomerCode='".mysql_escape_string($_GET['CustomerCode'])."'";
    if(!empty($_GET['CustomerName'])) $filter .= " AND CustomerName like '%".mysql_escape_string($_GET['CustomerName'])."%'";
    if(!empty($_GET['Page']) && is_numeric($_GET['Page'])) { $page = $_GET['Page']; } else { $page = 1; }

    if(!empty($_GET['OrderBy'])) { $order = mysql_escape_string($_GET['OrderBy']); } else { $order = 'LastSeen'; }
    if(!empty($_GET['Direction']) && in_array($_GET['Direction'],array('ASC','DESC'))) { $direction = mysql_escape_string($_GET['Direction']); } else { $direction = 'DESC'; }

    // Calculate offset
    $rows_per_page = 100;
    $offset = $rows_per_page*($page-1);

    // Pagination settings
    $count = reportCount($filter);
    $pages = ceil($count / $rows_per_page);

    if ($page > $pages) {
        echo '<h2>Empty database</h2>Your database does not contain any entries yet';
        include('../lib/frontend/bottom.php');
        die();
    }

    // Fetch reports
    $filter  .= " ORDER BY ${order} ${direction} LIMIT ${rows_per_page} OFFSET ${offset}";
    $results = reportList($filter);

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

    echo $paginator;

?>
<table class="table table-striped table-condensed">
    <thead>
        <tr>
          <th><a href='?<?php echo http_build_query(array_merge($uri,array('OrderBy'=>'ID','Direction'=>($order='ID'&&$direction=='ASC')?'DESC':'ASC'))); ?>'>Ticket</a></th>
          <th><a href='?<?php echo http_build_query(array_merge($uri,array('OrderBy'=>'IP','Direction'=>($order='IP'&&$direction=='ASC')?'DESC':'ASC'))); ?>'>IP</a></th>
          <th><a href='?<?php echo http_build_query(array_merge($uri,array('OrderBy'=>'CustomerCode','Direction'=>($order='CustomerCode'&&$direction=='ASC')?'DESC':'ASC'))); ?>'>Customer</a></th>
          <th><a href='?<?php echo http_build_query(array_merge($uri,array('OrderBy'=>'Class','Direction'=>($order='Class'&&$direction=='ASC')?'DESC':'ASC'))); ?>'>Classification</a></th>
          <th><a href='?<?php echo http_build_query(array_merge($uri,array('OrderBy'=>'Source','Direction'=>($order='Source'&&$direction=='ASC')?'DESC':'ASC'))); ?>'>Source</a></th>
          <th><a href='?<?php echo http_build_query(array_merge($uri,array('OrderBy'=>'Type','Direction'=>($order='Type'&&$direction=='ASC')?'DESC':'ASC'))); ?>'>Type</a></th>
          <th><a href='?<?php echo http_build_query(array_merge($uri,array('OrderBy'=>'FirstSeen','Direction'=>($order='FirstSeen'&&$direction=='ASC')?'DESC':'ASC'))); ?>'>First Seen</a></th>
          <th><a href='?<?php echo http_build_query(array_merge($uri,array('OrderBy'=>'LastSeen','Direction'=>($order='LastSeen'&&$direction=='ASC')?'DESC':'ASC'))); ?>'>Last Seen</a></th>
          <th>Count</th>
        </tr>
    </thead>
    <tbody>
<?php
$labelClass = array(
    'ABUSE'=>'warning',
    'INFO'=>'info',
    'ALERT'=>'danger'
);

foreach($results as $nr => $result) {
    echo "
        <tr>
          <td><a href='ticket.php?id=${result['ID']}'>${result['ID']}</a></td>
          <td><a href='reports.php?IP=${result['IP']}'>${result['IP']}</a></td>
          <td>
            <a href='reports.php?CustomerCode=${result['CustomerCode']}'>${result['CustomerCode']}</a> -
            <a href='reports.php?CustomerName=${result['CustomerName']}'>${result['CustomerName']}</a>
          </td>
          <td><a href='reports.php?Class=${result['Class']}'>${result['Class']}</a></td>
          <td><a href='reports.php?Source=${result['Source']}'>${result['Source']}</a></td>
          <td><span class='label label-${labelClass[$result['Type']]}'><a href='reports.php?Type=${result['Type']}'>${result['Type']}</a></span></td>
          <td>".date("d-m-Y H:m", $result['FirstSeen'])."</td>
          <td>".date("d-m-Y H:m", $result['LastSeen'])."</td>
          <td>${result['ReportCount']}</td>
        </tr>
    ";
}
?>
    </tbody>
</table>
<?php
    echo $paginator;
    include('../lib/frontend/bottom.php');
?>


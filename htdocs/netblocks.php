<?php
    $title = 'Netblocks';
    include('../lib/core/loader.php');
    include('../lib/frontend/top.php');

    // Query filter
    $filter = "";
    if(!empty($_GET['StartIP'])) $filter .= " AND begin_in='".mysql_escape_string($_GET['StartIP'])."'";
    if(!empty($_GET['CustomerCode'])) $filter .= " AND CustomerCode LIKE '".mysql_escape_string($_GET['CustomerCode'])."'";
    if(!empty($_GET['Page']) && is_numeric($_GET['Page'])) { $page = $_GET['Page']; } else { $page = 1; }

    if(!empty($_GET['OrderBy'])) { $order = mysql_escape_string($_GET['OrderBy']); } else { $order = 'begin_in'; }
    if(!empty($_GET['Direction']) && in_array($_GET['Direction'],array('ASC','DESC'))) { $direction = mysql_escape_string($_GET['Direction']); } else { $direction = 'DESC'; }

    // Calculate offset
    $rows_per_page = 100;
    $offset = $rows_per_page*($page-1);

    // Pagination settings
    $count = netblockCount($filter);
    $pages = ceil($count / $rows_per_page);

    if ($page > $pages) {
        echo '<h2>Empty database</h2>Your database does not contain any entries yet';
        include('../lib/frontend/bottom.php');
        die();
    }

    // Fetch reports
    $filter  .= " ORDER BY ${order} ${direction} LIMIT ${rows_per_page} OFFSET ${offset}";
    $results = netblockList($filter);

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
          <th width='200'><a href='?<?php echo http_build_query(array_merge($uri,array('OrderBy'=>'ID','Direction'=>($order='ID'&&$direction=='ASC')?'DESC':'ASC'))); ?>'>Start IP</a></th>
          <th width='200'><a href='?<?php echo http_build_query(array_merge($uri,array('OrderBy'=>'IP','Direction'=>($order='IP'&&$direction=='ASC')?'DESC':'ASC'))); ?>'>End IP</a></th>
          <th><a href='?<?php echo http_build_query(array_merge($uri,array('OrderBy'=>'CustomerCode','Direction'=>($order='CustomerCode'&&$direction=='ASC')?'DESC':'ASC'))); ?>'>Customer</a></th>
          <th width='100'> </th>
        </tr>
    </thead>
    <tbody>
<?php
foreach($results as $nr => $result) {
    echo "
        <tr>
          <td>${result['begin_in']}</td>
          <td>${result['end_in']}</td>
          <td>${result['CustomerCode']} - ${result['CustomerName']}</td>
          <td>
            <a href=''>Delete</a>
            <a href=''>Edit<a>
          </td>
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


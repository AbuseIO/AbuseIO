<?php
    $title = 'Netblocks';
    include('../lib/core/loader.php');
    include('../lib/frontend/top.php');

    if (isset($_GET['action']) && $_GET['action'] == 'delNetblock' ) {
        if(is_numeric($_GET['begin_in']) || is_numeric($_GET['end_in'])) {
            netblockDelete($_GET['begin_in'], $_GET['end_in']);
        }
    }
    if (isset($_POST['action']) && $_POST['action'] == 'addNetblock' ) {
        if(empty($_POST['AddBegin_in']) || empty($_POST['AddEnd_in']) || empty($_POST['AddCustomerCode'])) {
            echo "ERROR - Not all fields were filled in";
        } elseif(!valid_ip($_POST['AddBegin_in']) || !valid_ip($_POST['AddEnd_in'])) {
            echo "ERROR - IP Addresses are incorrectly formatted";
        } else {
            $netblock = array(
                                'begin_in'      => ip2long($_POST['AddBegin_in']),
                                'end_in'        => ip2long($_POST['AddEnd_in']),
                                'CustomerCode'  => $_POST['AddCustomerCode'],
                             );

            if (!is_numeric(netblockAdd($netblock))) {
                echo "ERROR - Adding netblock failed";
            }
        }
    }


    // Query filter
    $filter = "";
    if(!empty($_GET['begin_in'])) $filter .= " AND begin_in='".mysql_escape_string($_GET['begin_in'])."'";
    if(!empty($_GET['end_in'])) $filter .= " AND begin_in='".mysql_escape_string($_GET['end_in'])."'";
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
    } else {

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

<br>

<table class="table table-striped table-condensed">
    <thead>
        <tr>
          <th width='200'><a href='?<?php echo http_build_query(array_merge($uri,array('OrderBy'=>'begin_in','Direction'=>($order='ID'&&$direction=='ASC')?'DESC':'ASC'))); ?>'>Start IP</a></th>
          <th width='200'><a href='?<?php echo http_build_query(array_merge($uri,array('OrderBy'=>'end_in','Direction'=>($order='IP'&&$direction=='ASC')?'DESC':'ASC'))); ?>'>End IP</a></th>
          <th><a href='?<?php echo http_build_query(array_merge($uri,array('OrderBy'=>'CustomerCode','Direction'=>($order='CustomerCode'&&$direction=='ASC')?'DESC':'ASC'))); ?>'>Customer</a></th>
          <th width='100'> </th>
        </tr>
    </thead>
    <tbody>
<?php
foreach($results as $nr => $result) {
    echo "
        <tr>
          <td>". long2ip($result['begin_in']) ."</td>
          <td>". long2ip($result['end_in']) ."</td>
          <td>${result['CustomerCode']} - ${result['Name']}</td>
          <td>
              <div class='btn-group pull-right'>
                  <a href='?action=delNetblock&begin_in=${result['begin_in']}&end_in=${result['end_in']}' class='btn btn-default btn-sm' title='Delete netblock' onclick='return confirm(\"Are you sure you want to delete this netblock?\");'>Delete</a>
              </div>
          </td>
        </tr>
    ";
}
?>
    </tbody>
</table>
<?php
    echo $paginator;
    }
?>

<br>
<h2>Add Netblock</h2>
<form method='POST' action="netblocks.php">
    <input type='hidden' name='action' value='addNetblock'>
    <div class="row">

        <div class="col-md-6 form-group form-group-sm">
            <label for='AddBegin_in'>First IP</label>
            <input type='text' class="form-control" name='AddBegin_in'>
        </div>
        <div class="col-md-6 form-group form-group-sm">
            <label for='AddEnd_In'>Last IP</label>
            <input type='text' class="form-control" name='AddEnd_in'>
        </div>
        <div class="col-md-6 form-group form-group-sm">
            <label for='AddCustomerCode'>Customer</label>
            <select name='AddCustomerCode' class="form-control">
                <option value='UNDEF'>Undefined customer</option>
                <?php
                    $filter = "";
                    $list = customerList($filter);
                    foreach($list as $nr => $element) {
                        echo "<option value='{$element['Code']}'>{$element['Name']}</option>";
                    }
                ?>
            </select>
        </div>

        <div class="col-md-6 form-group form-group-sm">
            <button type='submit' class="btn btn-primary">Add</button>
        </div>

    </div>
</form>

<?php
    include('../lib/frontend/bottom.php');
?>

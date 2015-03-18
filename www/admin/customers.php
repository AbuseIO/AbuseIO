<?php
    $title = 'Customers';
    include('../../lib/core/loader.php');
    include('../../lib/frontend/top.php');

    if (isset($_GET['action']) && $_GET['action'] == 'delCustomer' ) {
        customerDelete($_GET['DelCustomerCode']);
    }
    if (isset($_POST['action']) && $_POST['action'] == 'addCustomer' ) {
        if($_POST['AddAutoNotify'] == 'on') {
            $autonotify = 1;
        } else {
            $autonotify = 0;
        }

        if(empty($_POST['AddCustomerCode']) || empty($_POST['AddCustomerName'])) {
            echo "ERROR - Not all fields were filled in";
        } else {
            $customer = array(
                                'Code'       => htmlentities($_POST['AddCustomerCode']),
                                'Name'       => htmlentities($_POST['AddCustomerName']),
                                'Contact'    => htmlentities($_POST['AddCustomerContacts']),
                                'AutoNotify' => htmlentities($autonotify),
                             );

            if (!is_numeric(customerAdd($customer))) {
                echo "ERROR - Adding customer failed";
            }
        }
    }

    // Query filter
    $filter = "";
    if(!empty($_GET['Code'])) $filter .= " AND Code='".mysql_escape_string($_GET['Code'])."'";
    if(!empty($_GET['Name'])) $filter .= " AND Name='".mysql_escape_string($_GET['Name'])."'";
    if(!empty($_GET['Contact'])) $filter .= " AND Contact LIKE '".mysql_escape_string($_GET['Contact'])."'";
    if(!empty($_GET['AutoNotify'])) $filter .= " AND AutoNotify = '".mysql_escape_string($_GET['AutoNotify'])."'";
    if(!empty($_GET['Page']) && is_numeric($_GET['Page'])) { $page = $_GET['Page']; } else { $page = 1; }

    if(!empty($_GET['OrderBy']) && in_array($_GET['OrderBy'], array('Code','Name','Contact','AutoNotify'))) { $order = mysql_escape_string($_GET['OrderBy']); } else { $order = 'Code'; }
    if(!empty($_GET['Direction']) && in_array($_GET['Direction'],array('ASC','DESC'))) { $direction = mysql_escape_string($_GET['Direction']); } else { $direction = 'ASC'; }

    // Calculate offset
    $rows_per_page = 100;
    $offset = $rows_per_page*($page-1);

    // Pagination settings
    $count = customerCount($filter);
    $pages = ceil($count / $rows_per_page);

    if ($page > $pages) {
        echo '<h2>Empty database</h2>Your database does not contain any entries yet';
    } else {

        // Fetch reports
        $filter  .= " ORDER BY ${order} ${direction} LIMIT ${rows_per_page} OFFSET ${offset}";
        $results = customerList($filter);

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
          <th width='100'><a href='?<?php echo http_build_query(array_merge($uri,array('OrderBy'=>'Code','Direction'=>($order='Code'&&$direction=='ASC')?'DESC':'ASC'))); ?>'>Code</a></th>
          <th width='200'><a href='?<?php echo http_build_query(array_merge($uri,array('OrderBy'=>'Name','Direction'=>($order='Name'&&$direction=='ASC')?'DESC':'ASC'))); ?>'>Customer name</a></th>
          <th><a href='?<?php echo http_build_query(array_merge($uri,array('OrderBy'=>'Contact','Direction'=>($order='Contact'&&$direction=='ASC')?'DESC':'ASC'))); ?>'>Contact</a></th>
          <th width='100'><a href='?<?php echo http_build_query(array_merge($uri,array('OrderBy'=>'AutoNotify','Direction'=>($order='AutoNotify'&&$direction=='ASC')?'DESC':'ASC'))); ?>'>Notifications</a></th>
          <th width='100'> </th>
        </tr>
    </thead>
    <tbody>
<?php
foreach($results as $nr => $result) {
    echo "
        <tr>
          <td>${result['Code']}</td>
          <td>${result['Name']}</td>
          <td>${result['Contact']}</td>
          <td>" . ($result['AutoNotify'] ? 'YES' : 'NO') . "</td>
          <td>
              <div class='btn-group pull-right'>
                  <a href='?action=delCustomer&DelCustomerCode=${result['Code']}' class='btn btn-default btn-sm' title='Delete customer' onclick='return confirm(\"Are you sure you want to delete this customer?\");'>Delete</a>
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
<h2>Add customer</h2>
<form method='POST' action="customers.php">
    <input type='hidden' name='action' value='addCustomer'>
    <div class="row">

        <div class="col-md-6 form-group form-group-sm">
            <label for='AddCustomerCode'>Customer Code : </label>
            <input type='text' class="form-control" name='AddCustomerCode'>
        </div>
        <div class="col-md-6 form-group form-group-sm">
            <label for='AddCustomerName'>Customer Name : </label>
            <input type='text' class="form-control" name='AddCustomerName'>
        </div>
        <div class="col-md-6 form-group form-group-sm">
            <label for='AddCustomerContact'>Customer contact(s) : </label>
            <input type='text' class="form-control" name='AddCustomerContacts'>
        </div>
        <div class="col-md-6 form-group form-group-sm">
            <label for='AddAutoNotify'>Automatic notifications : </label>
            <input type='checkbox' class="form-control" name='AddAutoNotify'>
        </div>

        <div class="col-md-6 form-group form-group-sm">
            <button type='submit' class="btn btn-primary">Add</button>
        </div>

    </div>
</form>

<?php
    include('../../lib/frontend/bottom.php');
?>

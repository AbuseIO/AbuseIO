<?php
    $title = 'Analytics and statistics';
    include('../../lib/core/loader.php');
    include('../../lib/frontend/top.php');

    if(!empty($_GET['Days']) && is_numeric($_GET['Days'])) {
        $days = $_GET['Days'];
    } else {
        $days = 3560;
    }

    $uri     = $_GET;
    $summary = reportSummary($days);
    $filter  = array('All' => 3650, 'Day' => 1, 'Week' => 7, 'Month' => 30, 'Quarter' => 90, 'Year' => 365);

    if(!empty($_GET['OrderBy']) && in_array($_GET['OrderBy'],array('Count','Class')))  { $order = mysql_escape_string($_GET['OrderBy']); } else { $order = 'Class'; }
    if(!empty($_GET['Direction']) && in_array($_GET['Direction'],array('ASC','DESC'))) { $direction = mysql_escape_string($_GET['Direction']); } else { $direction = 'ASC'; }

    if($order == 'Count' && $direction == 'DESC') { arsort($summary); }
    if($order == 'Count' && $direction == 'ASC')  { asort($summary);  }
    if($order == 'Class' && $direction == 'DESC') { krsort($summary); }
    if($order == 'Class' && $direction == 'ASC')  { ksort($summary);  }


?>
<div class="row">
    <div class="col-md-1">
    <form method='GET'>
    <input type='hidden' name='OrderBy' value='<?php echo $order; ?>'>
    <input type='hidden' name='Direction' value='<?php echo $direction; ?>'>
    <select name='Days' class="form-control" style="width: 100px;">
        <?php
            foreach($filter as $name => $setting) {
                if ($setting == $days) {
                    echo "<option value='{$setting}' SELECTED>{$name}</option>" . PHP_EOL;
                } else {
                    echo "<option value='{$setting}'>{$name}</option>" . PHP_EOL;
                }
            }
        ?>
    </select>
    </div>

    <div class="col-md-1">
    &nbsp;<button type='submit' class="btn btn-primary">Apply</button>
    </div>
    </form>
</div>

<br>

<div class="row">
    <div class="col-md-6">
        <div id="summary">
            <table class="table table-striped table-condensed">
                <thead>
                    <tr>
                      <th><a href='?<?php echo http_build_query(array_merge($uri,array('OrderBy'=>'Class','Direction'=>($order='Class'&&$direction=='ASC')?'DESC':'ASC'))); ?>'>Classification</a></th>
                      <th><a href='?<?php echo http_build_query(array_merge($uri,array('OrderBy'=>'Count','Direction'=>($order='Count'&&$direction=='ASC')?'DESC':'ASC'))); ?>'>Count</a></th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                        foreach($summary as $class => $count) {
                            echo "<tr><td><a href='reports.php?Class=${class}'>${class}</a></td><td>${count}</td></tr>";
                        }
                    ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<?php include('../../lib/frontend/bottom.php'); ?>

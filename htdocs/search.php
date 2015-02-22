<?php
    $title = 'Search reports';
    include('../lib/core/loader.php');
    include('../lib/frontend/top.php');
?>
<div class="row">
    <div class="col-md-8" id="search">
        <form method='GET' action="reports.php">
        <div class="row">
            <div class="col-md-6 form-group form-group-sm">
                <label for='Ticket'>Ticket</label>
                <input type='text' class="form-control" name='Ticket'>
            </div>
            <div class="col-md-6 form-group form-group-sm">
                <label for='IP'>IP</label>
                <input type='text' class="form-control" name='IP'>
            </div>
            <div class="col-md-6 form-group form-group-sm">
                <label for='CustomerCode'>Customer Code</label>
                <input type='text' class="form-control" name='CustomerCode'>
            </div>
            <div class="col-md-6 form-group form-group-sm">
                <label for='CustomerName'>Customer Name</label>
                <input type='text' class="form-control" name='CustomerName'>
            </div>
            <div class="col-md-6 form-group form-group-sm">
                <label for='Class'>Classification</label>
                <select name='Class' class="form-control">
                    <option value=''>All</option>
                    <?php
                        $summary = reportSummary("365");
                        foreach($summary as $nr => $element) {
                            echo "<option value='{$element['Class']}'>{$element['Class']}</option>";
                        }
                    ?>
                </select>
            </div>
            <div class="col-md-6 form-group form-group-sm">
                <label for='Type'>Type</label>
                <select name='Type' class="form-control">
                    <option value=''>All</option>
                    <?php
                        $types = array('ABUSE','INFO','ALERT');
                        foreach($types as $type) {
                            echo "<option value='{$type}'>{$type}</option>";
                        }
                    ?>
                </select>
            </div>
            <div class="col-md-12">
                <button type='submit' class="btn btn-primary">Search</button>
            </div>
        </div>
        </form>
    </div>
</div>
<?php include('../lib/frontend/bottom.php'); ?>

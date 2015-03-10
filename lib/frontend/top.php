<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?php echo $title;?></title>
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="css/bootstrap-theme.min.css" rel="stylesheet">
    <link href="css/style.css" rel="stylesheet">
    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
      <script src="//oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
      <script src="//oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
  </head>
  <body>
    <nav class="navbar navbar-inverse navbar-fixed-top">
      <div class="container">
        <div class="navbar-header">
          <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target=".navbar-collapse">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>
          <a class="navbar-brand" href="https://abuse.io" target="_blank">AbuseIO</a>
        </div>
        <div class="navbar-collapse collapse">
          <ul class="nav navbar-nav">
            <?php
            $nav_items = array(
                'index.php'=>'Welcome',
                'customers.php'=>'Customers',
                'netblocks.php'=>'Netblocks',
                'reports.php'=>'Reports',
                'search.php'=>'Search',
                'analytics.php'=>'Analytics'
            );
            foreach ($nav_items as $u => $t) {
                if ($u == basename($_SERVER['SCRIPT_NAME'])) {
                    echo "<li class='active'><a href='$u'>$t <span class='sr-only'>(current)</span></a></li>";
                } else {
                    echo "<li><a href='$u'>$t</a></li>";
                }
            }
            ?>
          </ul>
        </div>
      </div>
    </nav>
    <div class="container">
        <h1 class="page-header"><?php echo $title; ?></h1>

<?php

//error_reporting(E_ALL);
//ini_set('display_errors', '1');

setlocale(LC_TIME, 'nb_NO');

$config = yaml_parse_file('../config.yml');

require_once '../lib/php-user-agent/lib/phpUserAgent.php';
require_once '../lib/php-user-agent/lib/phpUserAgentStringParser.php';
require_once '../visit.php';

$start_date = new DateTime('2013-06-01 00:00:00');
$end_date = new DateTime('2013-12-31 23:59:59');
$keys = array('os', 'browser', 'app_version', 'day');
$data = array('visits' => 0);
foreach ($keys as $key) {
    $data[$key] = array();
}

$pdo = new PDO('mysql:host=' . $config['mysql']['host'] . ';dbname=' . $config['mysql']['db'], $config['mysql']['user'], $config['mysql']['pwd']);

$stmt = $pdo->prepare('SELECT * FROM visits WHERE timestamp >= :start_date AND timestamp <= :end_date');
$stmt->execute(array(
    ':start_date' => $start_date->format('Y-m-d 00:00:00'),
    ':end_date' => $end_date->format('Y-m-d 23:59:59')
));

# Map results to object
$stmt->setFetchMode(PDO::FETCH_CLASS, 'Visit');

while($visit = $stmt->fetch()) {
    $data['visits']++;

    foreach ($keys as $key) {
        $val = $visit->$key();
        $data[$key][$val] = isset($data[$key][$val]) 
            ? $data[$key][$val] + 1 
            : 1; 
    }

}

header('Content-type: text/html; charset=utf-8');
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8" />
  <title>Stats</title>
 
  <!-- HTML5 shim, for IE6-8 support of HTML5 elements -->
  <!--[if lt IE 9]>
  <script src="//cdnjs.cloudflare.com/ajax/libs/html5shiv/3.6/html5shiv.min.js"></script>
  <![endif]-->
 
  <!-- Complete CSS (Responsive, With Icons) -->
  <link href="//netdna.bootstrapcdn.com/twitter-bootstrap/2.3.2/css/bootstrap-combined.min.css" rel="stylesheet">
</head>
<body>

  <h1>App search stats</h1>
  
  <p>
    Periode: 
    <?php echo strftime('%e. %B %Y', $start_date->getTimestamp()); ?>
    –
    <?php echo strftime('%e. %B %Y', $end_date->getTimestamp()); ?>
  </p>
  <p>
    Antall søk totalt: <?php echo $data['visits']; ?>
  </p>

  <?php
  ksort($data['day']);

  foreach ($keys as $key) {
    ?>
      <table border="1">
        <caption><?php echo $key; ?></caption>
        <tbody>        
        <?php
            foreach ($data[$key] as $label => $visits) {
                echo "<tr><td>" . $label . "</td><td>" . $visits . "</td></tr>";
            }
        ?>
        </tbody>
      </table>
    <?php
  }
  ?>

  <script src="//cdnjs.cloudflare.com/ajax/libs/jquery/1.10.1/jquery.min.js"></script> 
  <script src="//netdna.bootstrapcdn.com/twitter-bootstrap/2.3.2/js/bootstrap.min.js"></script>
  <script type='text/javascript'>     
    $(document).ready(function() {
        // ...
    });
  </script>
 
</body> 
</html>
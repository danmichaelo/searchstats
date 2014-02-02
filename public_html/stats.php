<?php

error_reporting(E_ALL);
ini_set('display_errors', '1');

setlocale(LC_TIME, 'nb_NO');

$config = json_decode(file_get_contents('../config.json'), true);

require_once '../vendor/autoload.php';
require_once '../visit.php';

$start_date = new DateTime('2013-06-01 00:00:00');
$end_date = new DateTime('2014-04-01 23:59:59');
$keys = array('os', 'browser', 'app_version', 'req_time', 'day');
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
  <meta name="viewport" content="width=device-width, initial-scale=1">
 
  <!-- HTML5 shim, for IE6-8 support of HTML5 elements -->
  <!--[if lt IE 9]>
  <script src="//cdnjs.cloudflare.com/ajax/libs/html5shiv/3.6/html5shiv.min.js"></script>
  <![endif]-->
 
  <!-- Complete CSS (Responsive, With Icons) -->
  <link rel="stylesheet" href="//netdna.bootstrapcdn.com/bootstrap/3.1.0/css/bootstrap.min.css">

</head>
<body>

 <div class="container">
  <h1>Søk i Realfagsbiblioteket-appen</h1>

  <p>
    Periode: 
    <?php echo strftime('%e. %B %Y', $start_date->getTimestamp()); ?>
    –
    <?php echo strftime('%e. %B %Y', $end_date->getTimestamp()); ?>
  </p>
  <p>
    Antall søk totalt: <?php echo $data['visits']; ?>
  </p>

  <div class="row">
  <?php
  ksort($data['day']);

  foreach ($keys as $key) {
    ?>
      <div class="col-sm-3">
      <table class="table">
        <caption><?php echo $key; ?></caption>
        <tbody>
        <?php
            foreach ($data[$key] as $label => $visits) {
                echo "<tr><td>" . $label . "</td><td>" . $visits . "</td></tr>";
            }
        ?>
        </tbody>
      </table>
      </div>
    <?php
  }
  ?>

  </div>

</body>
</html>

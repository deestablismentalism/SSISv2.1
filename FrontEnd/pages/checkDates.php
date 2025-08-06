<?php

include_once __DIR__ . '/../server_side/models/DashboardModel.php';

$data = new DashboardModel();

$date = $data->EnrolleesByDays(7);

foreach($date as $days=> $rows) {
    echo 'Date: ' .$days. ' Count: ' . $rows .'<br>';
}

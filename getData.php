<?php
        $pdo = new PDO("sqlsrv:server=10.104.37.24; Database=VutilityDB", "vutilitysys", "system");
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
        $selectedDevice = isset($_POST['selectedDevice']) ? $_POST['selectedDevice'] : null;
        $selectedDay = isset($_GET['selectedDay']) ? $_GET['selectedDay'] : date('Y-m-d');
        $selectedWeek = isset($_GET['selectedWeek']) ? $_GET['selectedWeek'] : date('Y-m-d');
        $selectedMonth = isset($_GET['selectedMonth']) ? $_GET['selectedMonth'] : date('Y-m-d');

        //for month
        $startDateMonth = date('Y-m-d', strtotime($selectedMonth));
        $endDateMonth = date('Y-m-d', strtotime($selectedMonth . '+1 month'));

        //for week
        $startDateWeek = date('Y-m-d', strtotime('monday this week', strtotime($selectedWeek)));
        $endDateWeek = date('Y-m-d', strtotime($startDateWeek . ' +7 days'));
        $week_Data = date_create("$startDateWeek");
        date_add($week_Data, date_interval_create_from_date_string("7 days"));
        $dum_week_Data = date_format($week_Data, 'Y-m-d'); 
        
        //for days
        $startDateDay = date('Y-m-d 00:00:00', strtotime($selectedDay));
        $endDateDay = date("Y-m-d 23:59:59", strtotime($startDateDay));

        if($selectedDevice !== "allDevices") { 
            require 'deviceData.php';
        } else {
            require 'Data.php';
        }
?>
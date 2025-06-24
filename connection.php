<?php 
    try {
        
        require 'getData.php';

            $statement = $pdo->prepare($sqlDay);
            $statement->execute([$startDateDay, $endDateDay]);

            if (isset($_GET['selectedMonth'])) { 
                $statement = $pdo->prepare($sqlMonthWeek);
                $statement->execute([$startDateMonth, $endDateMonth]);
            } elseif (isset($_GET['selectedWeek'])) {
                $statement = $pdo->prepare($sqlMonthWeek);
                $statement->execute([$startDateWeek, $endDateWeek]);
            } elseif (isset($_GET['selectedDay'])) {
                $statement = $pdo->prepare($sqlDay);
                $statement->execute([$startDateDay, $endDateDay]);
            }

            $data = [];

            function InsertDummyData($apiReceivedDay, $int_day){
                $string_day = strval($int_day);
                
                if(strlen($string_day) == 1) {
                    $string_day = "0".$string_day;
                }

                // 2024-06-03
                return [
                    'apiReceivedDay'                => ExtractMonthYear($apiReceivedDay) . "-" . $string_day,
                    'NanoAmpereHour'                => 0,
                    'MilliAmpere'                   => 0,
                    'averageCurrent'                => 0,
                    'maximumCurrent'                => 0,
                    'minimumCurrent'                => 0,
                    'temperature_Celsius'           => 0,
                    'supplyVoltage'                 => 0,
                    'maximumCurrent_MilliAmpere'    => 0,
                    'minimumCurrent_MilliAmpere'    => 0,
                    'accumulatedCurrent_AmpereHour' => 0,
                ];
            }

            function ExtractDayofMonth($string_date){
                return intval(substr($string_date, 8, 2));
            }

            function ExtractMonthYear($string_date){
                return substr($string_date, 0, 7);
            }
            
            function InsertDummyDataWeek($apiReceivedDay, $int_hours){
                $string_week = strval($int_hours);
                
                if(strlen($string_week) == 1) {
                    $string_week = "0" .$string_week;
                }
                
                // 2024-06-03
                return [
                    'apiReceivedDay'                => ExtractWeekday($apiReceivedDay) . "-" . $string_week,
                    'NanoAmpereHour'                => 0,
                    'MilliAmpere'                   => 0,
                    'averageCurrent'                => 0,
                    'maximumCurrent'                => 0,
                    'minimumCurrent'                => 0,
                    'temperature_Celsius'           => 0,
                    'supplyVoltage'                 => 0,
                    'maximumCurrent_MilliAmpere'    => 0,
                    'minimumCurrent_MilliAmpere'    => 0,
                    'accumulatedCurrent_AmpereHour' => 0,
                ];
            }
        
            function ExtractWeekdays($string_date){
                return intval(substr($string_date, 8, 4));
            }

            function ExtractWeekday($string_date){
                return substr($string_date, 0, 7);
            }

            function InsertDummyDataHours($apiReceivedDay, $int_hours){
                $string_hours = strval($int_hours); //get empty value

                if(strlen($string_hours) == 1) {
                    $string_hours = "0". $string_hours;
                }
                return [
                    'apiReceivedDay'                => ExtractDay($apiReceivedDay) . $string_hours,
                    'NanoAmpereHour'                => 0,
                    'MilliAmpere'                   => 0,
                    'averageCurrent'                => 0,
                    'maximumCurrent'                => 0,
                    'minimumCurrent'                => 0,
                    'temperature_Celsius'           => 0,
                    'supplyVoltage'                 => 0,
                    'maximumCurrent_MilliAmpere'    => 0,
                    'minimumCurrent_MilliAmpere'    => 0,
                    'accumulatedCurrent_AmpereHour' => 0,
                ];
            }

            function ExtractHours($string_date){
                return intval(substr($string_date, 11, 2));
            }

            function ExtractDay($string_date){
                return substr($string_date, 0, 11); //get index length of date
            }

            $last_days = 0;
            $last_week = 0;
            $last_hour = -1;

            while ($row = $statement->fetch(PDO::FETCH_ASSOC)) {
                $current_day = ExtractDayofMonth($row['apiReceivedDay']);
                $current_hours = ExtractHours($row['apiReceivedDay']);
                $current_week = ExtractWeekdays($row['apiReceivedDay']);

                if ($last_days !== null || $last_hour !== null || $last_week !== null) {
                    if (isset($_GET['selectedMonth']) && $last_days < ($current_day - 1)) {
                        for ($i = $last_days += 1; $i < $current_day; $i++) {
                            $data[] = InsertDummyData(ExtractMonthYear($row['apiReceivedDay']), $i);
                        }
                    } elseif (isset($_GET['selectedWeek']) && $last_week > ($current_week - 7)) {
                        for ($i = $last_week+=1; $i < $current_week; $i++) {
                            $data[] = InsertDummyDataWeek(ExtractWeekday($row['apiReceivedDay']), $i);
                        }
                    } elseif (isset($_GET['selectedDay']) && $last_hour < ($current_hours - 1)) {
                        for ($i = $last_hour+=1; $i < $current_hours; $i++) {
                            $data[] = InsertDummyDataHours(ExtractDay($row['apiReceivedDay']), $i);
                        }
                    }
                }
        
                $data[] = [
                    'apiReceivedDay'                => $row['apiReceivedDay'],
                    'NanoAmpereHour'                => $row['NanoAmpereHour'],
                    'MilliAmpere'                   => $row['MilliAmpere'],
                    'averageCurrent'                => $row['averageCurrent'],
                    'maximumCurrent'                => $row['maximumCurrent'],
                    'minimumCurrent'                => $row['minimumCurrent'],
                    'temperature_Celsius'           => $row['temperature_Celsius'],
                    'supplyVoltage'                 => $row['supplyVoltage'],
                    'maximumCurrent_MilliAmpere'    => $row['maximumCurrent_MilliAmpere'],
                    'minimumCurrent_MilliAmpere'    => $row['minimumCurrent_MilliAmpere'],
                    'accumulatedCurrent_AmpereHour' => $row['accumulatedCurrent_AmpereHour'],
                ];
        
                $last_days = $current_day;
                $last_week = $current_week;
                $last_hour = $current_hours;
            }

            } catch(PDOException $e) {
                die("ERROR: Could not able to execute $sql. " . $e->getMessage());
            }
            unset($pdo);
?> 
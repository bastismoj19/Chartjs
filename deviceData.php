<?php

    $sqlMonthWeek = "SELECT
    CONVERT(varchar(10), apiReceivedAt, 127) AS apiReceivedDay,
    SUM(accumulatedCurrent_NanoAmpereHour)/24 AS NanoAmpereHour,
    SUM(instantaneousCurrent_MilliAmpere)/24 AS MilliAmpere,
    AVG(maximumCurrent_Ampere) AS maximumCurrent,
    AVG(minimumCurrent_Ampere) AS minimumCurrent,
    SUM(((maximumCurrent_Ampere)+(minimumCurrent_Ampere))/2)/24  AS averageCurrent,
    AVG(temperature_Celsius) AS temperature_Celsius,
    SUM(supplyVoltage_Volt)/24 AS supplyVoltage,
    AVG(maximumCurrent_MilliAmpere) AS maximumCurrent_MilliAmpere,
    AVG(minimumCurrent_MilliAmpere) AS minimumCurrent_MilliAmpere,
    SUM(accumulatedCurrent_AmpereHour)/24 AS accumulatedCurrent_AmpereHour
    FROM (SELECT DISTINCT apiReceivedAt, accumulatedCurrent_NanoAmpereHour, instantaneousCurrent_MilliAmpere,
        maximumCurrent_Ampere, minimumCurrent_Ampere, temperature_Celsius,
        supplyVoltage_Volt, maximumCurrent_MilliAmpere, minimumCurrent_MilliAmpere,
        accumulatedCurrent_AmpereHour, devUI
    FROM RecordedData) AS DataRecords
    WHERE apiReceivedAt >= ? AND apiReceivedAt <= ?
    GROUP BY CONVERT(varchar(10), apiReceivedAt, 127) 
    ORDER BY apiReceivedDay ASC";


    $dum_date_day = $selectedDay . "T23";
    $dummy_date_day = $selectedDay . "T24";

    $sqlDay = "SELECT
        CONVERT(varchar(13), apiReceivedAt, 120) AS apiReceivedDay,  
        SUM(accumulatedCurrent_NanoAmpereHour) AS NanoAmpereHour,    
        SUM(instantaneousCurrent_MilliAmpere) AS MilliAmpere,        
        AVG(maximumCurrent_Ampere) AS maximumCurrent,                
        AVG(minimumCurrent_Ampere) AS minimumCurrent,
        SUM((maximumCurrent_Ampere)+(minimumCurrent_Ampere)/2)  AS averageCurrent,
        AVG(temperature_Celsius) AS temperature_Celsius,
        SUM(supplyVoltage_Volt) AS supplyVoltage,
        AVG(maximumCurrent_MilliAmpere) AS maximumCurrent_MilliAmpere,
        AVG(minimumCurrent_MilliAmpere) AS minimumCurrent_MilliAmpere,
        SUM(accumulatedCurrent_AmpereHour) AS accumulatedCurrent_AmpereHour,
        '' as 'indicator'
    FROM RecordedData
    WHERE devUI = '$selectedDevice' 
    AND apiReceivedAt >= ? AND apiReceivedAt <= '$dummy_date_day'
    GROUP BY CONVERT(varchar(13), apiReceivedAt, 120)
    UNION
    SELECT DISTINCT
        '$dum_date_day' AS apiReceivedDay,
        0 AS NanoAmpereHour,
        0 AS MilliAmpere,
        0 AS maximumCurrent,
        0 AS minimumCurrent,
        0 AS averageCurrent,
        0 AS temperature_Celsius,
        0 AS supplyVoltage,
        0 AS maximumCurrent_MilliAmpere,
        0 AS minimumCurrent_MilliAmpere,
        0 AS accumulatedCurrent_AmpereHour,
        'dummy' as 'indicator'
    WHERE NOT EXISTS (
        SELECT 1 FROM RecordedData
        WHERE devUI = '$selectedDevice'
        AND CONVERT(VARCHAR(13), apiReceivedAt, 120) = '$dum_date_day'
    )
    ORDER BY apiReceivedDay ASC";

?>

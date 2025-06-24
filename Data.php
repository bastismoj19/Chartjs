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

    $dum_day_Data = $selectedDay .  ("T24");    
    
    $sqlDay = " SELECT
        CONVERT(varchar(13), apiReceivedAt, 120) AS apiReceivedDay,
        SUM( accumulatedCurrent_NanoAmpereHour) AS NanoAmpereHour,
        SUM( instantaneousCurrent_MilliAmpere) AS MilliAmpere,
        AVG( maximumCurrent_Ampere) AS maximumCurrent,
        AVG( minimumCurrent_Ampere) AS minimumCurrent,
        SUM((maximumCurrent_Ampere)+(minimumCurrent_Ampere)/2)  AS averageCurrent,
        AVG( temperature_Celsius) AS temperature_Celsius,
        SUM( supplyVoltage_Volt) AS supplyVoltage,
        AVG( maximumCurrent_MilliAmpere) AS maximumCurrent_MilliAmpere,
        AVG( minimumCurrent_MilliAmpere) AS minimumCurrent_MilliAmpere,
        SUM( accumulatedCurrent_AmpereHour) AS accumulatedCurrent_AmpereHour
    FROM (SELECT DISTINCT apiReceivedAt, accumulatedCurrent_NanoAmpereHour, instantaneousCurrent_MilliAmpere,
            maximumCurrent_Ampere, minimumCurrent_Ampere, temperature_Celsius,
            supplyVoltage_Volt, maximumCurrent_MilliAmpere, minimumCurrent_MilliAmpere,
            accumulatedCurrent_AmpereHour, devUI
    FROM RecordedData) AS DataRecords
    WHERE apiReceivedAt >= ? AND apiReceivedAt < '$dum_day_Data' 
    GROUP BY CONVERT(varchar(13), apiReceivedAt, 120) 
    ORDER BY apiReceivedDay ASC";
?>
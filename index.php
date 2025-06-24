<?php require 'connection.php'?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Current and Voltage Analysis Over Time</title>
    <link rel="stylesheet" href="assets/css/bulma.css">
    <link rel="icon" href="assets/images/funai.png" type="image/png">
    <script src="assets/js/jquery.js"></script>
    <style type="text/css">
        .chartJs, .selectDiv, .chartBoxMonth, .chartBoxWeek, .chartBoxDay {
            width: 88%;
            margin: auto;
        }

        .chartBoxWeek, .chartBoxDay {
            display: none;
        }

        .div {
            box-shadow: 5px 8px 2px rgb(39, 64, 58);
        }

        .container {
            display: flex;
            justify-content: center;
            align-items: center;
        }

        /*body {
            width: 100%;
            height: 100vh;
            background-color: lightblue;
        }*/

        .button, .selectOption, .selectedDay, .selectedWeek, .selectedMonth {
            box-shadow: 2px 3px 2px rgb(39, 64, 58);
        }

        .button:hover {
            box-shadow: 0 12px 16px 0 rgba(0,0,0,0.24);
        }

        /*html, body {
            width: 100%;
            height:100%;
            }

            body {
                background: linear-gradient(-45deg, purple, cyan, #23a6d5, lightblue);
                background-size: 400% 400%;
                animation: gradient 50s ease infinite;
            }

            @keyframes gradient {
                0% {
                    background-position: 0% 50%;
                }
                50% {
                    background-position: 100% 50%;
                }
                100% {
                    background-position: 0% 50%;
                }
        }*/
    </style>
</head>
<body>
    
    <h1 class="is-size-2 has-text-centered has-text-weight-bold has-text-primary-dark">Current and Voltage Analysis Over Time</h1>
    <div class="selectDiv">
        <table>
            <tr>
                <td><input type="button" class="dayBtn button is-small is-success mt-3 ml-1" value="Day"></td>
                <td><input type="button" class="weekBtn button is-small is-success mt-3 ml-1" value="Week"></td>
                <td><input type="button" class="monthBtn button is-small is-success mt-3 ml-1" value="Month"></td>
                <td>
                    <div class="divSelect select is-small mt-3 ml-5">
                        <form class="ml-6" method="POST" action="">
                            <select name="selectedDevice" class="ml-5 selectOption">
                                <option class="jim">----Select Device----</option>
                                <option value="allDevices"> All Devices </option>
                                <option value="318FFD85C29C0A5A"> 318FFD85C29C0A5A </option>
                                <option value="41C3DBF6D1F95894"> 41C3DBF6D1F95894 </option>
                                <option value="45A7C2C9B4305269"> 45A7C2C9B4305269 </option>
                                <option value="4A52E4DBC802BBD3"> 4A52E4DBC802BBD3 </option>
                                <option value="6D25DE5A7B455464"> 6D25DE5A7B455464 </option>
                                <option value="857BC4AC7D115A8D"> 857BC4AC7D115A8D </option>
                                <option value="92FBC5288A2805B6"> 92FBC5288A2805B6 </option>
                                <option value="A23CF6FDA3847E3E"> A23CF6FDA3847E3E </option>
                                <option value="A436E101C954F23A"> A436E101C954F23A </option>
                                <option value="AB02C2D979793A18"> AB02C2D979793A18 </option>
                                <option value="AC07F801541F029B"> AC07F801541F029B </option>
                                <option value="BA84F31FC65B1720"> BA84F31FC65B1720 </option>
                                <option value="C05DF810CF966DDA"> C05DF810CF966DDA </option>
                                <option value="C492C9062066626B"> C492C9062066626B </option>
                                <option value="EE1EF115F402AB7F"> EE1EF115F402AB7F </option>
                                <option value="F67CF918C7A654AF"> F67CF918C7A654AF </option>
                            </select>
                        <td><input type="submit" class="selectBtn button is-small is-primary mt-3 ml-1" value="Submit"></td>
                        <td><h3 class= "h3 ml-4 mt-4 has-text-weight-bold">Device:</h3></td>
                        <td><input type="text" class="h3 ml-1 mt-4 has-text-weight-bold has-background-white has-text-centered" value="<?php echo "$selectedDevice"; ?>" disabled></td>
                        </form>
                    </div>
                </td>
            </tr>
        </table>
    </div>
    
    <div class="chartBoxWeek">
        <form method="get" action="">
            <input type="week" name="selectedWeek" class="selectedWeek mt-3 has-text-centered" value="<?php echo isset($selectedWeek) ? htmlspecialchars($selectedWeek) : ''; ?>">
            <button type="submit" class="submitWeekBtn button  is-small is-primary mt-2">Submit</button>
        </form>
    </div>
    
    <div class="chartBoxDay">
        <form method="get" action="">
            <input type="date" name="selectedDay" class="selectedDay mt-3 has-text-centered" value="<?php echo isset($selectedDay) ? htmlspecialchars($selectedDay) : ''; ?>">
            <button type="submit" class="submitDayBtn button  is-small is-primary mt-2">Submit</button>
        </form>
    </div>

    <div class="chartBoxMonth">
        <form method="get" action="">
            <input type="month" class="selectedMonth mt-3 has-text-centered" name="selectedMonth" value="<?php echo isset($selectedMonth) ? htmlspecialchars($selectedMonth) : ''; ?>">
            <button type="submit" class="submitMonthBtn button is-small is-primary mt-2">Submit</button>
        </form>
    </div>
    
    <div class="columns is-vcentered container">
        <div class="chartJs box m-5 div">
            <canvas id="myChart"></canvas>
        </div>
    </div>
    

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        $(".dayBtn").click(function() {
            $(".submitMonthBtn").hide();
            $(".chartBoxWeek").hide();
            $(".selectedMonth").hide();
            $(".selectedWeek").hide();
            $(".selectedDay").show();
            $(".chartBoxDay").show();
            $(".displayMnth").hide();
        });

        $(".weekBtn").click(function() {
            $(".submitMonthBtn").hide();
            $(".selectedMonth").hide();
            $(".selectedWeek").show();
            $(".chartBoxWeek").show();
            $(".selectedDay").hide();
            $(".chartBoxDay").hide();
            $(".displayMnth").hide();
        });

        $(".monthBtn").click(function() {
            $(".chartBoxWeek").hide();
            $(".selectedWeek").hide();
            $(".selectedMonth").show();
            $(".submitMonthBtn").show();
            $(".selectedDay").hide();
            $(".chartBoxDay").hide();
            $(".displayMnth").show();
        });

        $(".submitMonthBtn").click(function() {
            alert("Please select a device.");
        });

        $(".submitWeekBtn").click(function() {
            alert("Please select a device.");      
        });
    
        $(".submitDayBtn").click(function() {
            alert("Please select a device.");
        });

        var ctxMonth = document.getElementById('myChart').getContext('2d');
        var myChart = new Chart(ctxMonth, {
            type: 'line',
            data: {
                labels: <?php echo json_encode(array_column($data, 'apiReceivedDay')); ?>,
                datasets: [
                {
                    label: 'Total NanoAmpereHour',
                    data: <?php echo json_encode(array_column($data, 'NanoAmpereHour')); ?>,
                    borderColor: 'blue',
                    backgroundColor: 'blue',
                    fill: false
                }, {
                    label: 'Total AmpereHour',
                    data: <?php echo json_encode(array_column($data, 'accumulatedCurrent_AmpereHour')); ?>,
                    borderColor: 'brown',
                    backgroundColor: 'brown',
                    fill: false
                }, {
                    label: 'Total MilliAmpere',
                    data: <?php echo json_encode(array_column($data, 'MilliAmpere')); ?>,
                    borderColor: 'green',
                    backgroundColor: 'green',
                    fill: false
                }, {
                    label: 'Maximum MilliAmpere',
                    data: <?php echo json_encode(array_column($data, 'maximumCurrent_MilliAmpere')); ?>,
                    borderColor: 'violet',
                    backgroundColor: 'violet',
                    fill: false
                }, {
                    label: 'Supply Voltage',
                    data: <?php echo json_encode(array_column($data, 'supplyVoltage')); ?>,
                    borderColor: 'orange',
                    backgroundColor: 'orange',
                    fill: false
                }, {
                    label: 'Maximum Current',
                    data: <?php echo json_encode(array_column($data, 'maximumCurrent')); ?>,
                    borderColor: 'yellow',
                    backgroundColor: 'yellow',
                    fill: false
                }, {
                    label: 'Average Current',
                    data: <?php echo json_encode(array_column($data, 'averageCurrent')); ?>,
                    borderColor: 'red',
                    backgroundColor: 'red',
                    fill: false
                }, {
                    label: 'Minimum Current',
                    data: <?php echo json_encode(array_column($data, 'minimumCurrent')); ?>,
                    borderColor: 'pink',
                    backgroundColor: 'pink',
                    fill: false
                }, {
                    label: 'Temperature Celsius',
                    data: <?php echo json_encode(array_column($data, 'temperature_Celsius')); ?>,
                    borderColor: 'black',
                    backgroundColor: 'black',
                    fill: false
                }, {
                    label: 'Minimum MilliAmpere',
                    data: <?php echo json_encode(array_column($data, 'minimumCurrent_MilliAmpere')); ?>,
                    borderColor: 'cyan',
                    backgroundColor: 'cyan',
                    fill: false
                },
                ]
                     
            },            
        });

    </script>
</body>
</html>
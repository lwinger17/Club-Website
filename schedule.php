<!DOCTYPE html>
<html lang="en">
<head>
    <title>Project</title>
    <link rel="stylesheet" href="css/schedule.css">
</head>
<body>
    <div id="headerdiv">
        <?php require_once("header.php"); ?>
    </div>

    <div class="main-container">  <!-- NEW CONTAINER -->
        <div class="container">
            <div class="left-pane">

                <!-- Year navigator -->
                <div class="year-navigator">
                    <span id="currentYearDisplay"></span>  <!-- This is crucial -->
                    <select id="yearSelect">
                        <option value="" disabled selected>Click to select a year</option>
                    </select>
                </div>

                <!-- Calendar navigation buttons -->
                <div class="calendar-nav">
                    <button class="nav-button" id="prevMonth">&lt;</button>
                    <div id="currentMonth"></div>
                    <button class="nav-button" id="nextMonth">&gt;</button>
                </div>

                <!-- Calendar grid -->
                <div id="calendar-grid"></div>

                <!-- Month-wise navigation container -->
                <div class="navigation-container">
                    <div class="navigation">
                        <h3>Navigate Events by Month</h3>
                        <div id="year-display"></div>
                        <div id="month-list"></div>
                    </div>
                </div>

                <!-- All events put together container -->
                <div class="all-events-container">
                    <h3>See All Our Events</h3>
                    <div class="event-list">
                        <ol id="event-list-all"></ol>
                    </div>
                </div>
            </div>

            <!-- Right pane where events are displayed -->
            <div class="right-pane">
                <div id="eventList"></div>
                </div>
            </div>
            <!-- Footer div -->
    
            <div id="footerdiv"> 
                <?php require_once("footer.php"); ?>
            </div>
        
        </div>
    </div>
<script src="js/schedule.js"></script>
</body>
</html>
async function fetchEventsFromDatabase() {
    try {
        const response = await fetch('./scheduleRetrieveal.php');
        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }
        const data = await response.json();
        if (data.error) {
            console.error("Error fetching events:", data.error);
            return [];
        }
        if (!Array.isArray(data)) {
            console.error("Invalid data format received from server. Expected an array.");
            return [];
        }
        //console.log("Events data from server:", data);
        return data;
    } catch (error) {
        console.error('Error fetching events:', error);
        return [];
    }
}

async function getEvents() {
    const events = await fetchEventsFromDatabase();
    
let currentDate = new Date();
const calendarGrid = document.getElementById('calendar-grid');
const currentMonthDisplay = document.getElementById('currentMonth');
const prevMonthButton = document.getElementById('prevMonth');
const nextMonthButton = document.getElementById('nextMonth');

function getYearsWithEvents() {
  const years = new Set();
  events.forEach(event => {
      years.add(new Date(event.event_date).getFullYear());
  });
  //console.log(years);
  return Array.from(years).sort((a, b) => a - b); // Sort years ascending
}


function getMonthsWithEvents(year) {
  const months = new Set();
  events.forEach(event => {
      const eventDate = new Date(event.event_date);
      if (eventDate.getFullYear() === year) {
          months.add(eventDate.getMonth());
      }
  });
  return Array.from(months).sort((a, b) => a - b); // Sort months ascending
}

function populateYearDropdown() {
  const years = getYearsWithEvents();
  const yearSelect = document.getElementById('yearSelect');
  yearSelect.innerHTML = '<option value="" disabled selected>Click to select a year</option>'; // Clear existing options

  years.forEach(year => {
      const option = document.createElement('option');
      option.value = year;
      option.text = year;
      yearSelect.appendChild(option);
  });
}


function updateYearDisplay() {
  const yearDisplay = document.getElementById('currentYearDisplay');
  if (yearDisplay) { // Check if the element exists before accessing it
      yearDisplay.textContent = currentDate.getFullYear();
  }
}
function getIcon(event) {
  switch (event.event_type.toLowerCase()) {
      case "workshop":
          return "scheduleIcons/clubMeeting.png"; 
      case "networking":
          return "scheduleIcons/peopleShakingHands.png"; 
      case "competition":
          return "scheduleIcons/trophy.png"; 
      case "speaker":
          return "scheduleIcons/microphone.png"; 
      case "financial":
          return "scheduleIcons/graph.png"; 
      default:
          return "scheduleIcons/default.png"; 
  }
}



function filterEventsByMonth(events, year, month) {
  return events.filter(event => {
      const eventDate = new Date(event.event_date);
      return eventDate.getFullYear() === year && eventDate.getMonth() === month;
  });
}

function getIconOrImage(event) {
    if (event.event_img) {
        //If event_img exists, return the image path. 
        return event.event_img; 
    } else {
        //Otherwise, use the getIcon function to get the appropriate icon.
        return getIcon(event);
    }
}


function displayEvents(monthEvents) {
  const eventList = document.getElementById('eventList');
  eventList.innerHTML = ''; 

  if (monthEvents.length === 0) {
      const noEventsMessage = document.createElement('div');
      noEventsMessage.classList.add('no-events');
      noEventsMessage.innerHTML = `
          <img src="no-events-animation.gif" alt="No Events Animation">
          <h2 style="color: #DAA520; font-size: 2em; margin-top: 20px;">No events for this month</h2>
      `;
      eventList.appendChild(noEventsMessage);
  } else {
      monthEvents.forEach(event => {
          const imgSrc = getIconOrImage(event);
          const eventHTML = `
          <div class="event">
              <div class="icon-title">
                  <img class="icon" src="${imgSrc}" alt="${imgSrc === getIcon(event) ? 'Event Icon' : 'Event Image'}">
                  <h3>${event.event_title}</h3>
              </div>
              <div class="event-details"> 
                  <p>${event.event_date} at ${event.event_time}</p>
                  <p>${event.event_desc}</p>
                  <p>${event.event_detailed_desc}</p>
              </div>
          </div>
      `;
          eventList.insertAdjacentHTML('beforeend', eventHTML);
      });
  }
}

function displayAllEvents() {
  const eventListAll = document.getElementById('event-list-all');
  eventListAll.innerHTML = ''; 

  const sortedEvents = [...events].sort((a, b) => new Date(a.date) - new Date(b.date));

  sortedEvents.forEach((event, index) => {
      const iconSrc = getIcon(event);
      const listItem = document.createElement('li');
      listItem.innerHTML = `
          <img class="icon" src="${iconSrc}" alt="Event Icon">
          <div class="event-info">
              ${index + 1}. ${event.event_title}<br>
              ${event.event_date} at ${event.event_time}
          </div>
      `;
      listItem.addEventListener('click', () => {
          currentDate = new Date(event.event_date);
          updateCalendar();
          displayEvents(filterEventsByMonth(events, currentDate.getFullYear(), currentDate.getMonth()));
      });
      eventListAll.appendChild(listItem);
  });
}


function updateCalendar() {
  const month = currentDate.getMonth();
  const year = currentDate.getFullYear();
  const firstDay = new Date(year, month, 1);
  const lastDay = new Date(year, month + 1, 0);
  const daysInMonth = lastDay.getDate();
  const dayIndex = firstDay.getDay();

  const monthNames = ["January", "February", "March", "April", "May", "June",
                      "July", "August", "September", "October", "November", "December"];

  currentMonthDisplay.textContent = `${monthNames[month]} ${year}`;
  calendarGrid.innerHTML = '';

  let day = 1;
  for (let i = 0; i < 42; i++) {
      const dayElement = document.createElement('div');
      dayElement.classList.add('calendar-day');

      if (i >= dayIndex && day <= daysInMonth) {
          dayElement.textContent = day;

          // Create Date objects for precise comparison at midnight
          const calendarDayDate = new Date(year, month, day, 0, 0, 0);
          const currentDateForComparison = new Date(currentDate.getFullYear(), currentDate.getMonth(), currentDate.getDate(), 0, 0, 0);

          if (calendarDayDate.getTime() === currentDateForComparison.getTime()) {
              dayElement.classList.add('current');
          }
          day++;
      }
      calendarGrid.appendChild(dayElement);
  }

  const monthEvents = filterEventsByMonth(events, year, month);
  displayEvents(monthEvents);
  displayAllEvents();
  populateYearDropdown();
  updateYearDisplay();
  updateNavigation();
}

const yearSelect = document.getElementById('yearSelect');
yearSelect.addEventListener('change', () => {
    const selectedYear = parseInt(yearSelect.value, 10);
    if (!isNaN(selectedYear)) {
        currentDate.setFullYear(selectedYear);
        const months = getMonthsWithEvents(selectedYear);
        if (months.length > 0) {
            currentDate.setMonth(months[0]);
        }
        updateNavigation();
        updateCalendar();
    }
});


prevMonthButton.addEventListener('click', () => {
  currentDate.setMonth(currentDate.getMonth() - 1);
  updateCalendar();
});

nextMonthButton.addEventListener('click', () => {
  currentDate.setMonth(currentDate.getMonth() + 1);
  updateCalendar();
});

function updateNavigation() {
  const currentYear = currentDate.getFullYear();
  const yearDisplay = document.getElementById('year-display');
  yearDisplay.textContent = currentYear;

  const monthList = document.getElementById('month-list');
  monthList.innerHTML = ''; // Clear existing month buttons

  const monthNames = ["January", "February", "March", "April", "May", "June",
                     "July", "August", "September", "October", "November", "December"];

  const monthsWithEvents = getMonthsWithEvents(currentYear);

  if (monthsWithEvents.length === 0) {
      const noEventsMessage = document.createElement('div');
      noEventsMessage.classList.add('no-events-message'); // 
      noEventsMessage.textContent = "No events this year.";
      monthList.appendChild(noEventsMessage);
  } else {
      monthsWithEvents.forEach(monthIndex => {
          const monthSpan = document.createElement('span');
            monthSpan.classList.add('month-link'); // Add a class for styling
            monthSpan.textContent = monthNames[monthIndex];
            monthSpan.addEventListener('click', () => {
                currentDate.setMonth(monthIndex);
                updateCalendar();
            });
            monthList.appendChild(monthSpan);
      });
  }
}


function findClosestEventDate() {
  const sortedEvents = [...events].sort((a, b) => new Date(a.date) - new Date(b.date));
  const today = new Date();

  let closestFutureEvent = null;
  for (const event of sortedEvents) {
      // Explicitly set time to midnight to avoid time zone issues
      const eventDate = new Date(event.event_date);
      //eventDate.setHours(0, 0, 0, 0); // Set time to midnight

      if (eventDate >= today) {
          closestFutureEvent = eventDate;
          break;
      }
  }

  if (closestFutureEvent === null && sortedEvents.length > 0) {
      const lastEventDate = new Date(sortedEvents[sortedEvents.length - 1].date);
      //lastEventDate.setHours(0, 0, 0, 0); // Set time to midnight
      closestFutureEvent = lastEventDate;
  }

  return closestFutureEvent;
}
// Initial calendar setup:
const closestEventDate = findClosestEventDate();
if (closestEventDate) {
    currentDate = closestEventDate;
    updateCalendar();
} else {
    // Handle the case where there are no events at all
    console.log("No events were found.");
}

}

getEvents();

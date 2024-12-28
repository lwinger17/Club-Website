const prevMonthButton = document.getElementById('prevMonth');
const nextMonthButton = document.getElementById('nextMonth');
const currentMonthDisplay = document.getElementById('currentMonth');
const calendarGrid = document.getElementById('calendar-grid');

let currentDate = new Date();


const events = [
{
    title: "Entrepreneurship Workshop: Idea Validation",
    date: "2024-03-15",
    time: "6:00 PM",
    eventType: "workshop",
    description: "Learn how to validate your business ideas.",
    detailedDescription: "This workshop will cover various methods for validating your business idea, including market research, customer interviews, and competitor analysis.  Guest speaker:  Sarah Chen, Founder of InnovateTech."
},
{
    title: "Networking Mixer",
    date: "2024-03-22",
    time: "7:00 PM",
    eventType: "networking",
    description: "Connect with fellow entrepreneurs and investors.",
    detailedDescription: "Join us for an evening of networking and socializing.  Meet potential collaborators, mentors, and investors.  Light refreshments will be served."
},
{
    title: "Pitch Competition",
    date: "2024-03-29",
    time: "2:00 PM",
    eventType: "competition",
    description: "Showcase your business idea and compete for prizes!",
    detailedDescription: "This is your chance to pitch your business idea to a panel of judges and compete for valuable prizes.  Prepare a concise and compelling presentation."
},
{
    title: "Guest Speaker:  Funding Your Startup",
    date: "2024-04-05",
    time: "6:30 PM",
    eventType: "speaker",
    description: "Learn about different funding options for startups.",
    detailedDescription: "Our guest speaker, David Lee, a successful angel investor, will share insights into securing funding for your startup, including bootstrapping, angel investors, venture capital, and crowdfunding."
},
{
    title: "Financial Planning for Entrepreneurs",
    date: "2024-04-12",
    time: "10:00 AM",
    eventType: "financial",
    description: "Master the essentials of financial planning for your business.",
    detailedDescription: "This workshop will cover budgeting, forecasting, cash flow management, and other key financial aspects of running a successful business."
},

{
  title: "Trial for creation of a new list item",
  date: "2023-07-12",
  time: "10:00 AM",
  eventType: "speaker",
  description: "This is to try and see whether a new list item will be created automatically.",
  detailedDescription: "This workshop will cover budgeting, forecasting, cash flow management, and other key financial aspects of running a successful business."
}


];

function getIcon(event) {
    switch (event.eventType.toLowerCase()) {
      case "workshop":
        return "scheduleIcons/clubMeeting.png"; // Or a lightbulb icon
      case "networking":
        return "scheduleIcons/peopleShakingHands.png"; // People shaking hands icon
      case "competition":
        return "scheduleIcons/trophy.png"; // Trophy or podium icon
      case "speaker":
        return "scheduleIcons/microphone.png"; // Microphone or speaker icon
      case "financial":
        return "scheduleIcons/graph.png"; // Chart or graph icon
      default:
        return "scheduleIcons/default.png"; // Default icon if no type matches
    }
  }

  function updateYearDisplay() {
    const yearDisplay = document.getElementById('currentYearDisplay');
    yearDisplay.textContent = currentDate.getFullYear();
  }
  
  const prevYearButton = document.getElementById('prevYear');
  const nextYearButton = document.getElementById('nextYear');
  
  prevYearButton.addEventListener('click', () => {
    currentDate.setFullYear(currentDate.getFullYear() - 1);
    updateCalendar();
    updateNavigation();
    updateYearDisplay();
  });
  
  nextYearButton.addEventListener('click', () => {
    currentDate.setFullYear(currentDate.getFullYear() + 1);
    updateCalendar();
    updateNavigation();
    updateYearDisplay();
  });












function filterEventsByMonth(events, year, month) {
  return events.filter(event => {
    const eventDate = new Date(event.date);
    return eventDate.getFullYear() === year && eventDate.getMonth() === month;
  });
}


function displayEvents(monthEvents) {
    const eventList = document.getElementById('eventList');
    eventList.innerHTML = ''; // Clear previous events
    if (monthEvents.length === 0) {
      // Display "No events" message and animation
      const noEventsMessage = document.createElement('div');
      noEventsMessage.classList.add('no-events');
      noEventsMessage.innerHTML = `
        <img src="no-events-animation.gif" alt="No Events Animation">
        <h2 style="color: #DAA520; font-size: 2em; margin-top: 20px;">No events for this month</h2>
      `;
      eventList.appendChild(noEventsMessage);
    } else {

    monthEvents.forEach(event => {
      const iconSrc = getIcon(event); // Get icon source once
  
      const eventHTML = `
        <div class="event">
          <img class="icon" src="${iconSrc}" alt="Event Icon">
          <h3>${event.title}</h3>
          <p>${event.date} at ${event.time}</p>
          <p>${event.description}</p>
          <p>${event.detailedDescription}</p>
        </div>
      `;
  
      eventList.insertAdjacentHTML('beforeend', eventHTML);
    
    });
  }
  }

  function updateNavigation() {
    const year = currentDate.getFullYear();
    const yearDisplay = document.getElementById('year-display');
    yearDisplay.textContent = year;
  
    const monthList = document.getElementById('month-list');
    monthList.innerHTML = ''; // Clear previous months
  
    const monthNames = ["January", "February", "March", "April", "May", "June",
                       "July", "August", "September", "October", "November", "December"];
  
    monthNames.forEach((monthName, index) => {
      const monthButton = document.createElement('button');
      monthButton.textContent = monthName;
      monthButton.addEventListener('click', () => {
        currentDate.setMonth(index);
        updateCalendar();
        updateNavigation(); // Update navigation to reflect the selected month
      });
      monthList.appendChild(monthButton);
    });
  }

  function displayAllEvents() {
    const eventListAll = document.getElementById('event-list-all');
    eventListAll.innerHTML = ''; // Clear previous list
  
    // Sort events by date
    const sortedEvents = [...events].sort((a, b) => new Date(a.date) - new Date(b.date));
  
    sortedEvents.forEach((event, index) => {
      const iconSrc = getIcon(event);
      const listItem = document.createElement('li');
      listItem.innerHTML = `
        <img class="icon" src="${iconSrc}" alt="Event Icon">
        <div class="event-info">
          ${index + 1}. ${event.title}<br>
          ${event.date} at ${event.time}
        </div>
      `;
      listItem.addEventListener('click', () => {
        currentDate = new Date(event.date);
        updateCalendar();
        updateNavigation();
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
      const today = new Date();
      if (currentDate.getDate() === day && currentDate.getMonth() === month && currentDate.getFullYear() === year) {
        dayElement.classList.add('current');
      }
      day++;
    }

    calendarGrid.appendChild(dayElement);
  }
  const monthEvents = filterEventsByMonth(events, year, month);
  displayEvents(monthEvents);

  displayAllEvents();
  updateNavigation();
  updateYearDisplay();
}

prevMonthButton.addEventListener('click', () => {
  currentDate.setMonth(currentDate.getMonth() - 1);
  updateCalendar();
  updateNavigation();
});

nextMonthButton.addEventListener('click', () => {
  currentDate.setMonth(currentDate.getMonth() + 1);
  updateCalendar();
  updateNavigation();
});

updateCalendar();
















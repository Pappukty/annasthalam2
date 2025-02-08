



document.addEventListener("DOMContentLoaded", function() {
    // Initialize inline calendar
    flatpickr("#inline-calendar", {
        inline: true
    });

    // Initialize FullCalendar
    var calendarEl = document.getElementById("calendar");
    var eventListEl = document.getElementById("eventList");
    var calendar = new FullCalendar.Calendar(calendarEl, {
        initialView: "dayGridMonth",
        selectable: true,
        events: [],
        dateClick: function(info) {
            document.getElementById("eventDate").value = info.dateStr;
            updateEventList(info.dateStr);
        }
    });
    calendar.render();

    function updateEventList(date) {
        eventListEl.innerHTML = "";
        var events = calendar.getEvents().filter(event => event.startStr === date);
        if (events.length > 0) {
            events.forEach(event => {
                var li = document.createElement("li");
                li.className = "list-group-item";
                li.textContent = event.title;
                eventListEl.appendChild(li);
            });
        } else {
            var li = document.createElement("li");
            li.className = "list-group-item text-muted";
            li.textContent = "No events on this day.";
            eventListEl.appendChild(li);
        }
    }

    // Save Event
    document.getElementById("saveEvent").addEventListener("click", function() {
        var title = document.getElementById("eventTitle").value;
        var date = document.getElementById("eventDate").value;
        if (title && date) {
            calendar.addEvent({
                title: title,
                start: date
            });
            bootstrap.Modal.getInstance(document.getElementById("eventModal")).hide();
            updateEventList(date);
        }
    });
});
const myCalendarDisabledDates = document.getElementById('myCalendarDisabledDates')
if (myCalendarDisabledDates) {
    const optionsCalendarDisabledDates = {
        calendarDate: new Date(2022, 2, 1),
        calendars: 2,
        disabledDates: [
            [new Date(2022, 2, 4), new Date(2022, 2, 7)],
            new Date(2022, 2, 16),
            new Date(2022, 3, 16),
            [new Date(2022, 4, 2), new Date(2022, 4, 8)]
        ],
        locale: 'en-US',
        maxDate: new Date(2022, 5, 0),
        minDate: new Date(2022, 1, 1)
    }

    new coreui.Calendar(myCalendarDisabledDates, optionsCalendarDisabledDates)
}
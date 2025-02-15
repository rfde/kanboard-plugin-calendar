KB.component('calendar', function (containerElement, options) {
    var modeMapping = {
        month: 'dayGridMonth',
        week: 'timeGridWeek',
        day: 'timeGridDay'
    };
    var calendar = null;
    this.render = function () {
        var mode = modeMapping["month"];
        if (window.location.hash) { // Check if hash contains mode
            var hashMode = window.location.hash.substr(1);
            mode = modeMapping[hashMode] || mode;
        }
        calendar = new FullCalendar.Calendar(containerElement, {
            locale: options.locale,
            initialView: mode,
            nowIndicator: true,
            headerToolbar: {
                start: "prev,next today",
                center: "title",
                right: modeMapping["month"] + "," + modeMapping["week"] + "," + modeMapping["day"]
            },
            buttonText: {
                prev: "<",
                next: ">"
            },
            datesSet: function(dateInfo) {
                // Update URL hash: Map dateInfo.type back and update location.hash
                for (var id in modeMapping) {
                    if (modeMapping[id] === dateInfo.view.type) { // Found
                        window.location.hash = id;
                        break;
                    }
                }
            },
            events: options.checkUrl,
            views: {
                timeGridWeek: {
                    scrollTime: "08:00:00"
                },
                timeGridDay: {
                    scrollTime: "08:00:00"
                }
            },
            eventDrop: function(eventDropInfo) {
                var changes = {
                    "id": eventDropInfo.event.id,
                    "evt_start": eventDropInfo.event.start,
                    "evt_end": eventDropInfo.event.end,
                    "allDay": eventDropInfo.event.allDay
                };
                $.ajax({
                    cache: false,
                    url: options.saveUrl,
                    contentType: "application/json",
                    type: "POST",
                    processData: false,
                    data: JSON.stringify(changes),
                    success: function() { calendar.refetchEvents(); }
                });
            },
            eventResize: function(eventResizeInfo) {
                var changes = {
                    "id": eventResizeInfo.event.id,
                    "evt_start": eventResizeInfo.event.start,
                    "evt_end": eventResizeInfo.event.end,
                    "allDay": eventResizeInfo.event.allDay
                };
                $.ajax({
                    cache: false,
                    url: options.saveUrl,
                    contentType: "application/json",
                    type: "POST",
                    processData: false,
                    data: JSON.stringify(changes),
                    success: function() { calendar.refetchEvents(); }
                });
            }
        });
        calendar.render();
        var tasklistContainer = document.getElementById('calendar-tasklist');
        if (tasklistContainer != null) {
            calendar.setOption('eventReceive', function(info) {
                console.log(info);
                var id = info.draggedEl.getAttribute("data-id");
                if (id != null) {
                    var changes = {
                        "id": id,
                        "evt_start": info.event.start,
                        "evt_end": null,
                        "allDay": info.event.allDay
                    }
                    $.ajax({
                        cache: false,
                        url: options.saveUrl,
                        contentType: "application/json",
                        type: "POST",
                        processData: false,
                        data: JSON.stringify(changes),
                        success: function() { calendar.refetchEvents(); }
                    });
                    info.revert();
                    info.draggedEl.parentNode.removeChild(info.draggedEl);
                }
            });
            // initialize draggable events
            new FullCalendar.Draggable(tasklistContainer, {
              itemSelector: '.fc-event',
              eventData: function(eventEl) {
                return {
                  title: eventEl.innerText
                };
              }
            });
        }
    };
    
    KB.onKey('m', function() {
        if (calendar != null) {
            calendar.changeView(modeMapping["month"]);
        }
    });
    KB.onKey('w', function() {
        if (calendar != null) {
            calendar.changeView(modeMapping["week"]);
        }
    });
    KB.onKey('d', function() {
        if (calendar != null) {
            calendar.changeView(modeMapping["day"]);
        }
    });
});

KB.on('dom.ready', function () {
    function goToLink (selector) {
        if (! KB.modal.isOpen()) {
            var element = KB.find(selector);

            if (element !== null) {
                window.location = element.attr('href');
            }
        }
    }

    KB.onKey('v+c', function () {
        goToLink('a.view-calendar');
    });
});

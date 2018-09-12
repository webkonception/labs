/**
 * Created by sylvain on 28/06/17.
 */

$(document).ready(function() {

    $('#calendar').fullCalendar({
        header: {
            left: 'prev,next today',
            center: 'title',
            right: 'month,agendaWeek,agendaDay'
        },
        handleWindowResize: true,
        defaultDate: moment().format("YYYY/MM/DD"),
        defaultView: 'agendaWeek',
        locale: 'fr',
        contentHeight: 'auto',
        minTime: '07:00:00',
        maxTime: '23:00:00',
        navLinks: true, // can click day/week names to navigate views
        selectable: true,
        selectHelper: true,
        select: function(start, end) {
            start = moment(start).format();
            end = moment(end).format();
            var title = prompt('Titre de l\'évènement:');
            var description = prompt('Description:');
            var id = new Date().getTime();
            var eventData;
            $.ajax({
                url: routes.myroutes.agenda,
                data: 'title='+ title +'&id='+ id +'&description='+ description +'&start='+ start +'&end='+ end ,
                type: "POST"
            });
            if (title) {
                eventData = {
                    title: title,
                    id: id,
                    description: description,
                    start: start,
                    end: end,
                    allDay: false
                };
                $('#calendar').fullCalendar('renderEvent', eventData, true); // stick? = true
            }
            $('#calendar').fullCalendar('unselect');
        },
        editable: true,
        eventLimit: true, // allow "more" link when too many events
        eventDrop: function(event, delta) {
            start = moment(event.start).format();
            end = moment(event.end).format();
            $.ajax({
                url: routes.myroutes.agenda,
                data: 'title='+ event.title+'&description='+ event.description +'&start='+ start +'&end='+ end +'&id='+ event.id ,
                type: "POST"
            });
        },
        eventResize: function(event) {
            start = moment(event.start).format();
            end = moment(event.end).format();
            $.ajax({
                url: routes.myroutes.agenda,
                data: 'title='+ event.title+'&description='+ event.description +'&start='+ start +'&end='+ end +'&id='+ event.id ,
                type: "POST"
            });

        },
        eventClick:  function(event, jsEvent, view) {
            $('#modalTitle').html(event.title);
            $('#modalBody').html(event.description);
            $('.deleteEvent').click(function() {
                $('#calendar').fullCalendar('removeEvents',event._id);
                $.ajax({
                    url: routes.myroutes.agenda,
                    data: 'delete='+ event.id ,
                    type: "POST"
                });
            });
            $('#agendaModal').modal();
        },
        events: {
            url: routes.myroutes.json
        }
    });

    /*element.on('select', function (properties) {

        $('#modal-id').modal('show');

    });*/
});

<div class="container" id="container">
    <div class="flex flex-col w-full space-y-6">
        <p class="text-4xl font-bold font-baloo">{{ __('maintenance.bookings.title') }}</p>
        <div id='calendar'></div>
    </div>
</div>
<script>
    $(document).ready(function() {
        var urlModal = "{{ URL::route($routes['create']) }}";
        const calendarEl = document.getElementById('calendar');
        const calendar = new FullCalendar.Calendar(calendarEl, {
            height: 'auto',
            locale: 'es',
            initialView: 'dayGridMonth',
            titleFormat: { year: 'numeric', month: 'long', day: 'numeric' },
            buttonText: {
                today: 'Hoy',
                month: 'Mes',
                week: 'Semana',
                day: 'Día',
                list: 'Lista'
            },
            events: @json($data),
            dateClick: function(info) {
                let type = 'new';
                modal(urlModal + '?date=' + info.dateStr + '&type=' + type, 'Nueva Reserva', this);
            },
            eventClick: function(info) {
                let processId = info.event.extendedProps.process_id;
                let type = info.event.extendedProps.type;
                let title = type === 'booking' ? 'Ver Reserva' : 'Ver Habitación';
                if(type != 'room'){
                    modal(urlModal + '?processId=' + processId + '&type=' + type, 'Ver Reserva', this);
                }
            }
        });
        calendar.render();
    });
</script>

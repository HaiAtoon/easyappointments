App.Pages.CustomerDashboard = (function () {
    const dateFormat = vars('date_format') === 'DMY' ? 'DD/MM/YYYY' : vars('date_format') === 'MDY' ? 'MM/DD/YYYY' : 'YYYY/MM/DD';
    const timeFormat = vars('time_format') === 'regular' ? 'h:mm A' : 'HH:mm';

    function renderAppointmentCard(appointment, isPast) {
        const startMoment = moment(appointment.start_datetime);
        const endMoment = moment(appointment.end_datetime);
        const isCancelled = appointment.status === 'Cancelled';
        const rescheduleUrl = App.Utils.Url.siteUrl('booking/reschedule/' + appointment.hash);

        let statusBadge = '';

        if (isCancelled) {
            statusBadge = '<span class="badge bg-danger">' + lang('cancelled') + '</span>';
        } else if (appointment.status) {
            statusBadge = '<span class="badge bg-secondary">' + App.Utils.String.escapeHtml(appointment.status) + '</span>';
        }

        let cancellationInfo = '';

        if (isCancelled) {
            const cancelledBy = appointment.cancelled_by || '';
            const isCustomer = cancelledBy.indexOf('(customer)') !== -1;
            const cancelledByText = isCustomer
                ? lang('cancelled_by_me')
                : lang('cancelled_by') + ' ' + App.Utils.String.escapeHtml(cancelledBy.replace(/\s*\((admin|provider|secretary|customer)\)/, ''));

            cancellationInfo = '<div class="mt-2 small text-danger">';
            cancellationInfo += '<i class="fas fa-ban me-1"></i>' + cancelledByText;

            if (appointment.cancelled_at) {
                const cancelMoment = moment(appointment.cancelled_at);
                cancellationInfo += ' &middot; ' + cancelMoment.format(dateFormat + ' ' + timeFormat);
            }

            if (appointment.cancellation_reason) {
                cancellationInfo += '<br><i class="fas fa-comment me-1"></i>' + App.Utils.String.escapeHtml(appointment.cancellation_reason);
            }

            cancellationInfo += '</div>';
        }

        const titleContent = isCancelled
            ? App.Utils.String.escapeHtml(appointment.service_name || '')
            : '<a href="' + rescheduleUrl + '" class="text-decoration-none">' +
              App.Utils.String.escapeHtml(appointment.service_name || '') +
              '</a>';

        return `
            <div class="card mb-3 ${isCancelled ? 'border-danger bg-light' : ''}">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <h6 class="card-title mb-1">
                                <i class="fas fa-concierge-bell me-1 text-primary"></i>
                                ${titleContent}
                            </h6>
                            <p class="card-text text-muted small mb-1">
                                <i class="fas fa-user-md me-1"></i>
                                ${App.Utils.String.escapeHtml(appointment.provider_name || '')}
                            </p>
                            <p class="card-text small mb-0">
                                <i class="fas fa-calendar-day me-1"></i>
                                ${startMoment.format(dateFormat)}
                                &nbsp;
                                <i class="fas fa-clock me-1"></i>
                                ${startMoment.format(timeFormat)} - ${endMoment.format(timeFormat)}
                            </p>
                            ${cancellationInfo}
                        </div>
                        <div>
                            ${statusBadge}
                        </div>
                    </div>
                </div>
            </div>
        `;
    }

    function loadAppointments() {
        App.Http.Customer.getAppointments().done((response) => {
            $('#upcoming-loading, #past-loading').hide();

            if (response.upcoming && response.upcoming.length) {
                $('#upcoming-count').text(response.upcoming.length).show();
                response.upcoming.forEach((apt) => {
                    $('#upcoming-list').append(renderAppointmentCard(apt, false));
                });
            } else {
                $('#upcoming-empty').show();
            }

            if (response.past && response.past.length) {
                response.past.forEach((apt) => {
                    $('#past-list').append(renderAppointmentCard(apt, true));
                });
            } else {
                $('#past-empty').show();
            }
        });
    }

    document.addEventListener('DOMContentLoaded', loadAppointments);

    return {};
})();

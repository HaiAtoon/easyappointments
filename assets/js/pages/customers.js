/* ----------------------------------------------------------------------------
 * Easy!Appointments - Online Appointment Scheduler
 *
 * @package     EasyAppointments
 * @author      A.Tselegidis <alextselegidis@gmail.com>
 * @copyright   Copyright (c) Alex Tselegidis
 * @license     https://opensource.org/licenses/GPL-3.0 - GPLv3
 * @link        https://easyappointments.org
 * @since       v1.5.0
 * ---------------------------------------------------------------------------- */

/**
 * Customers page module.
 *
 * Manages the customer list, CRUD operations, and the tabbed dashboard layout.
 * Documentation and issued document functionality is delegated to App.Components.Documentation.
 */
App.Pages.Customers = (function () {
    const $customers = $('#customers');
    const $filterCustomers = $('#filter-customers');
    const $id = $('#customer-id');
    const $firstName = $('#first-name');
    const $lastName = $('#last-name');
    const $email = $('#email');
    const $phoneNumber = $('#phone-number');
    const $address = $('#address');
    const $city = $('#city');
    const $zipCode = $('#zip-code');
    const $idNumber = $('#id-number');
    const $timezone = $('#timezone');
    const $language = $('#language');
    const $ldapDn = $('#ldap-dn');
    const $customField1 = $('#custom-field-1');
    const $customField2 = $('#custom-field-2');
    const $customField3 = $('#custom-field-3');
    const $customField4 = $('#custom-field-4');
    const $customField5 = $('#custom-field-5');
    const $notes = $('#notes');
    const $formMessage = $('#form-message');
    const $customerAppointments = $('#customer-appointments');

    const moment = window.moment;

    let filterResults = {};
    let filterLimit = 20;

    function addEventListeners() {
        $customers.on('submit', '#filter-customers form', (event) => {
            event.preventDefault();
            const key = $filterCustomers.find('.key').val();
            $filterCustomers.find('.selected').removeClass('selected');
            filterLimit = 20;
            App.Pages.Customers.resetForm();
            App.Pages.Customers.filter(key);
        });

        $customers.on('click', '.customer-row', (event) => {
            if ($filterCustomers.find('.filter').prop('disabled')) {
                return;
            }

            const customerId = $(event.currentTarget).attr('data-id');
            const customer = filterResults.find((filterResult) => Number(filterResult.id) === Number(customerId));

            App.Pages.Customers.display(customer);
            $('#filter-customers .selected').removeClass('selected');
            $(event.currentTarget).addClass('selected');
            $('#edit-customer, #delete-customer').prop('disabled', false);
        });

        $customers.on('click', '#add-customer', () => {
            App.Pages.Customers.resetForm();
            $customers.find('#add-edit-delete-group').hide();
            $customers.find('#save-cancel-group').show();
            $('#tab-overview').find('input, select, textarea').prop('disabled', false);
            $('#tab-overview .form-label span').prop('hidden', false);
            $filterCustomers.find('button').prop('disabled', true);
            $filterCustomers.find('.results').css('color', '#AAA');
            setTabsEnabled(false);
        });

        $customers.on('click', '#edit-customer', () => {
            $('#tab-overview').find('input, select, textarea').prop('disabled', false);
            $('#tab-overview .form-label span').prop('hidden', false);
            $customers.find('#add-edit-delete-group').hide();
            $customers.find('#save-cancel-group').show();
            $filterCustomers.find('button').prop('disabled', true);
            $filterCustomers.find('.results').css('color', '#AAA');
            setTabsEnabled(false);
        });

        $customers.on('click', '#cancel-customer', () => {
            const id = $id.val();

            App.Pages.Customers.resetForm();

            if (id) {
                select(id, true);
            }
        });

        $customers.on('click', '#save-customer', () => {
            const customer = {
                first_name: $firstName.val(),
                last_name: $lastName.val(),
                email: $email.val(),
                phone_number: $phoneNumber.val(),
                address: $address.val(),
                city: $city.val(),
                zip_code: $zipCode.val(),
                id_number: $idNumber.val(),
                notes: $notes.val(),
                timezone: $timezone.val(),
                language: $language.val() || 'english',
                custom_field_1: $customField1.val(),
                custom_field_2: $customField2.val(),
                custom_field_3: $customField3.val(),
                custom_field_4: $customField4.val(),
                custom_field_5: $customField5.val(),
                ldap_dn: $ldapDn.val(),
            };

            if ($id.val()) {
                customer.id = $id.val();
            }

            if (!App.Pages.Customers.validate()) {
                return;
            }

            App.Pages.Customers.save(customer);
        });

        $customers.on('click', '#delete-customer', () => {
            const customerId = $id.val();
            const buttons = [
                {
                    text: lang('cancel'),
                    click: (event, messageModal) => {
                        messageModal.hide();
                    },
                },
                {
                    text: lang('delete'),
                    click: (event, messageModal) => {
                        App.Pages.Customers.remove(customerId);
                        messageModal.hide();
                    },
                },
            ];

            App.Utils.Message.show(lang('delete_customer'), lang('delete_record_prompt'), buttons);
        });
    }

    function save(customer) {
        App.Http.Customers.save(customer).then((response) => {
            App.Layouts.Backend.displayNotification(lang('customer_saved'));
            App.Pages.Customers.resetForm();
            $('#filter-customers .key').val('');
            App.Pages.Customers.filter('', response.id, true);
        });
    }

    function remove(id) {
        App.Http.Customers.destroy(id).then(() => {
            App.Layouts.Backend.displayNotification(lang('customer_deleted'));
            App.Pages.Customers.resetForm();
            App.Pages.Customers.filter($('#filter-customers .key').val());
        });
    }

    function validate() {
        $formMessage.removeClass('alert-danger').hide();
        $('.is-invalid').removeClass('is-invalid');

        try {
            let missingRequired = false;

            $('#tab-overview .required').each((index, requiredField) => {
                if ($(requiredField).val() === '') {
                    $(requiredField).addClass('is-invalid');
                    missingRequired = true;
                }
            });

            if (missingRequired) {
                throw new Error(lang('fields_are_required'));
            }

            const email = $email.val();

            if (email && !App.Utils.Validation.email(email)) {
                $email.addClass('is-invalid');
                throw new Error(lang('invalid_email'));
            }

            const phoneNumber = $phoneNumber.val();

            if (phoneNumber && !App.Utils.Validation.phone(phoneNumber)) {
                $phoneNumber.addClass('is-invalid');
                throw new Error(lang('invalid_phone'));
            }

            return true;
        } catch (error) {
            $formMessage.addClass('alert-danger').text(error.message).show();
            return false;
        }
    }

    function resetForm() {
        $id.val('');
        $('#tab-overview').find('input, select, textarea').val('').prop('disabled', true);
        $('#tab-overview .form-label span').prop('hidden', true);
        $('#tab-overview #timezone').val(vars('default_timezone'));
        $('#tab-overview #language').val(vars('default_language'));

        $customerAppointments.empty();

        $customers.find('#edit-customer, #delete-customer').prop('disabled', true);
        $customers.find('#add-edit-delete-group').show();
        $customers.find('#save-cancel-group').hide();

        $('#tab-overview .is-invalid').removeClass('is-invalid');
        $('#tab-overview #form-message').hide();

        $filterCustomers.find('button').prop('disabled', false);
        $filterCustomers.find('.selected').removeClass('selected');
        $filterCustomers.find('.results').css('color', '');

        if (App.Components.Documentation) {
            App.Components.Documentation.onReset();
        }

        setTabsEnabled(true);
        activateTab('tab-overview');
    }

    function display(customer) {
        $id.val(customer.id);
        $firstName.val(customer.first_name);
        $lastName.val(customer.last_name);
        $email.val(customer.email);
        $phoneNumber.val(customer.phone_number);
        $address.val(customer.address);
        $city.val(customer.city);
        $zipCode.val(customer.zip_code);
        $idNumber.val(customer.id_number);
        $notes.val(customer.notes);
        $timezone.val(customer.timezone);
        $language.val(customer.language || 'english');
        $ldapDn.val(customer.ldap_dn);
        $customField1.val(customer.custom_field_1);
        $customField2.val(customer.custom_field_2);
        $customField3.val(customer.custom_field_3);
        $customField4.val(customer.custom_field_4);
        $customField5.val(customer.custom_field_5);

        $customerAppointments.empty();

        if (!customer.appointments.length) {
            $('<p/>', {'text': lang('no_records_found')}).appendTo($customerAppointments);
        }

        customer.appointments.forEach((appointment) => {
            if (
                vars('role_slug') === App.Layouts.Backend.DB_SLUG_PROVIDER &&
                parseInt(appointment.id_users_provider) !== vars('user_id')
            ) {
                return;
            }

            if (
                vars('role_slug') === App.Layouts.Backend.DB_SLUG_SECRETARY &&
                vars('secretary_providers').indexOf(appointment.id_users_provider) === -1
            ) {
                return;
            }

            const start = App.Utils.Date.format(
                moment(appointment.start_datetime).toDate(),
                vars('date_format'),
                vars('time_format'),
                true,
            );

            const end = App.Utils.Date.format(
                moment(appointment.end_datetime).toDate(),
                vars('date_format'),
                vars('time_format'),
                true,
            );

            $('<div/>', {
                'class': 'appointment-row',
                'data-id': appointment.id,
                'html': [
                    $('<a/>', {
                        'href': App.Utils.Url.siteUrl(`calendar/reschedule/${appointment.hash}`),
                        'html': [
                            $('<i/>', {'class': 'fas fa-edit me-1'}),
                            $('<strong/>', {
                                'text': appointment.service.name + ' - ' +
                                    appointment.provider.first_name + ' ' + appointment.provider.last_name,
                            }),
                            $('<br/>'),
                        ],
                    }),
                    $('<small/>', {'text': start}),
                    $('<br/>'),
                    $('<small/>', {'text': end}),
                    $('<br/>'),
                    $('<small/>', {'text': vars('timezones')[appointment.provider.timezone]}),
                ],
            }).appendTo('#customer-appointments');
        });

        // Delegate to documentation component
        if (App.Components.Documentation) {
            App.Components.Documentation.onCustomerSelected(customer.id, customer.appointments);
        }
    }

    function filter(keyword, selectId = null, show = false) {
        App.Http.Customers.search(keyword, filterLimit).then((response) => {
            filterResults = response;

            $filterCustomers.find('.results').empty();

            response.forEach((customer) => {
                $('#filter-customers .results').append(App.Pages.Customers.getFilterHtml(customer)).append($('<hr/>'));
            });

            if (!response.length) {
                $filterCustomers.find('.results').append(
                    $('<em/>', {'text': lang('no_records_found')}),
                );
            } else if (response.length === filterLimit) {
                $('<button/>', {
                    'type': 'button',
                    'class': 'btn btn-outline-secondary w-100 load-more text-center',
                    'text': lang('load_more'),
                    'click': () => {
                        filterLimit += 20;
                        App.Pages.Customers.filter(keyword, selectId, show);
                    },
                }).appendTo('#filter-customers .results');
            }

            if (selectId) {
                App.Pages.Customers.select(selectId, show);
            }
        });
    }

    function getFilterHtml(customer) {
        const name = (customer.first_name || '[No First Name]') + ' ' + (customer.last_name || '[No Last Name]');
        let info = customer.email || '[No Email]';
        info = customer.phone_number ? info + ', ' + customer.phone_number : info;

        const html = [
            $('<strong/>', {'text': name}),
            $('<br/>'),
            $('<small/>', {'class': 'text-muted', 'text': info}),
            $('<br/>'),
        ];

        if (customer.id_number) {
            html.push(
                $('<small/>', {'class': 'text-muted', 'text': lang('id_number') + ': ' + customer.id_number}),
                $('<br/>'),
            );
        }

        return $('<div/>', {'class': 'customer-row entry', 'data-id': customer.id, 'html': html});
    }

    function select(id, show = false) {
        $('#filter-customers .selected').removeClass('selected');
        $('#filter-customers .entry[data-id="' + id + '"]').addClass('selected');

        if (show) {
            const customer = filterResults.find((filterResult) => Number(filterResult.id) === Number(id));
            App.Pages.Customers.display(customer);
            $('#edit-customer, #delete-customer').prop('disabled', false);
        }
    }

    // --- Tab Management ---

    function setTabsEnabled(enabled) {
        const $tabs = $('#customer-tabs .nav-link').not('#tab-overview-btn').not('#tab-billing-btn');

        if (enabled) {
            $tabs.removeClass('disabled');
        } else {
            $tabs.addClass('disabled');
            activateTab('tab-overview');
        }
    }

    function activateTab(tabId) {
        const tabEl = document.getElementById(tabId + '-btn');

        if (tabEl) {
            const tab = new bootstrap.Tab(tabEl);
            tab.show();
        }
    }

    function initialize() {
        App.Pages.Customers.resetForm();
        App.Pages.Customers.addEventListeners();
        App.Pages.Customers.filter('');
    }

    document.addEventListener('DOMContentLoaded', initialize);

    return {
        filter,
        save,
        remove,
        validate,
        getFilterHtml,
        resetForm,
        display,
        select,
        addEventListeners,
    };
})();

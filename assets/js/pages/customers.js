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
    const $documentationEntries = $('#documentation-entries');
    const $documentationForm = $('#documentation-form');
    const $docEntryId = $('#doc-entry-id');
    const $docProvider = $('#doc-provider');
    const $docAppointment = $('#doc-appointment');
    const $docSessionSummary = $('#doc-session-summary');

    const moment = window.moment;

    let filterResults = {};
    let filterLimit = 20;
    let currentCustomerAppointments = [];
    let trumbowygInitialized = false;

    // Register Trumbowyg direction toggle button
    if ($.trumbowyg) {
        $.trumbowyg.plugins.dirToggle = {
            init: function (trumbowyg) {
                trumbowyg.addBtnDef('dirToggle', {
                    fn: function () {
                        const $ed = trumbowyg.$ed;
                        const current = $ed.attr('dir') || 'ltr';
                        $ed.attr('dir', current === 'rtl' ? 'ltr' : 'rtl');
                    },
                    hasIcon: false,
                    text: 'A⇄א',
                    title: 'Toggle text direction (LTR/RTL)',
                });
            },
        };
    }

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

        // Documentation tab events
        $customers.on('click', '#add-documentation-entry', () => {
            showDocumentationForm();
        });

        $customers.on('click', '#save-documentation-entry', () => {
            saveDocumentationEntry();
        });

        $customers.on('click', '#close-documentation-entry', () => {
            hideDocumentationForm();
            loadDocumentationEntries($id.val());
        });

        $customers.on('click', '.documentation-entry-row', (event) => {
            const entryId = $(event.currentTarget).data('id');
            const entries = $documentationEntries.data('entries') || [];
            const entry = entries.find((e) => Number(e.id) === Number(entryId));

            if (entry) {
                showDocumentationForm(entry);
            }
        });

        $customers.on('click', '.show-edit-log', (event) => {
            event.stopPropagation();
            const entryId = $(event.currentTarget).closest('.documentation-entry-row').data('id');
            showEditLog(entryId);
        });

        // Entry PDF actions
        $customers.on('click', '#view-entry-pdf', () => {
            const entryId = $docEntryId.val();
            if (entryId) {
                App.Http.DocumentationEntries.viewEntryPdf(entryId);
            }
        });

        $customers.on('click', '#send-entry-pdf', () => {
            const entryId = $docEntryId.val();
            if (entryId) {
                App.Http.DocumentationEntries.sendEntryPdf(entryId).then(() => {
                    App.Layouts.Backend.displayNotification(lang('pdf_sent_successfully'));
                });
            }
        });

        // Issued document events
        $customers.on('click', '#add-issued-document', () => {
            showIssuedDocumentForm();
        });

        $customers.on('click', '#save-issued-document', () => {
            saveIssuedDocument();
        });

        $customers.on('click', '#cancel-issued-document', () => {
            hideIssuedDocumentForm();
        });

        $('#idoc-type').on('change', () => {
            const slug = $('#idoc-type').val();
            const $extraFields = $('#idoc-extra-fields');

            $extraFields.find('.idoc-rich-field').each(function () {
                $(this).trumbowyg('destroy');
            });

            $extraFields.empty();

            if (!slug) {
                return;
            }

            App.Http.DocumentationEntries.templateMappings(slug).then((response) => {
                const mappings = response.field_mappings || [];

                mappings.forEach((mapping) => {
                    if (!mapping.user_display) {
                        return;
                    }

                    const fieldId = 'idoc-field-' + mapping.label;
                    const displayName = mapping.name || mapping.label;

                    if (mapping.type === 'free_textarea') {
                        $extraFields.append(
                            '<div class="mb-3">' +
                            '<label class="form-label">' + displayName + '</label>' +
                            '<textarea id="' + fieldId + '" class="idoc-rich-field" data-label="' + mapping.label + '"></textarea>' +
                            '</div>',
                        );

                        const $textarea = $('#' + fieldId);

                        $textarea.trumbowyg({
                            btns: [
                                ['strong', 'em', 'underline'],
                                ['unorderedList', 'orderedList'],
                                ['removeformat'],
                                ['dirToggle'],
                            ],
                            removeformatPasted: true,
                            autogrow: true,
                            semantic: {'b': 'strong', 'i': 'em', 's': 'del', 'strike': 'del'},
                        });

                        if (vars('is_rtl')) {
                            $textarea.closest('.trumbowyg-box').find('.trumbowyg-editor').attr('dir', 'rtl');
                        }
                    } else {
                        $extraFields.append(
                            '<div class="mb-3">' +
                            '<label class="form-label">' + displayName + '</label>' +
                            '<input type="text" id="' + fieldId + '" class="form-control" data-label="' + mapping.label + '">' +
                            '</div>',
                        );
                    }
                });
            });
        });

        $customers.on('click', '.view-document-pdf', (event) => {
            const docId = $(event.currentTarget).data('id');
            App.Http.DocumentationEntries.viewDocumentPdf(docId);
        });

        $customers.on('click', '.send-document-pdf', (event) => {
            const docId = $(event.currentTarget).data('id');
            App.Http.DocumentationEntries.sendDocumentPdf(docId).then(() => {
                App.Layouts.Backend.displayNotification(lang('pdf_sent_successfully'));
            });
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

        // Reset documentation tab
        if ($documentationEntries.length) {
            $documentationEntries.html('<em class="text-muted">' + lang('no_documentation_entries') + '</em>');
            $documentationEntries.data('entries', []);
        }

        hideDocumentationForm();
        $('#add-documentation-entry').prop('disabled', true);

        setTabsEnabled(true);
        activateTab('tab-overview');

        currentCustomerAppointments = [];
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

        currentCustomerAppointments = customer.appointments || [];

        $customerAppointments.empty();

        if (!customer.appointments.length) {
            $('<p/>', {
                'text': lang('no_records_found'),
            }).appendTo($customerAppointments);
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
                            $('<i/>', {
                                'class': 'fas fa-edit me-1',
                            }),
                            $('<strong/>', {
                                'text':
                                    appointment.service.name +
                                    ' - ' +
                                    appointment.provider.first_name +
                                    ' ' +
                                    appointment.provider.last_name,
                            }),
                            $('<br/>'),
                        ],
                    }),

                    $('<small/>', {
                        'text': start,
                    }),
                    $('<br/>'),

                    $('<small/>', {
                        'text': end,
                    }),
                    $('<br/>'),

                    $('<small/>', {
                        'text': vars('timezones')[appointment.provider.timezone],
                    }),
                ],
            }).appendTo('#customer-appointments');
        });

        // Enable documentation tab actions
        $('#add-documentation-entry').prop('disabled', false);

        // Load documentation entries
        if ($documentationEntries.length) {
            loadDocumentationEntries(customer.id);
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
                    $('<em/>', {
                        'text': lang('no_records_found'),
                    }),
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
            $('<strong/>', {
                'text': name,
            }),
            $('<br/>'),
            $('<small/>', {
                'class': 'text-muted',
                'text': info,
            }),
            $('<br/>'),
        ];

        if (customer.id_number) {
            html.push(
                $('<small/>', {
                    'class': 'text-muted',
                    'text': lang('id_number') + ': ' + customer.id_number,
                }),
                $('<br/>'),
            );
        }

        return $('<div/>', {
            'class': 'customer-row entry',
            'data-id': customer.id,
            'html': html,
        });
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

    // --- Documentation Tab Functions ---

    function loadDocumentationEntries(customerId) {
        if (!App.Http.DocumentationEntries) {
            return;
        }

        App.Http.DocumentationEntries.search(customerId).then((entries) => {
            $documentationEntries.data('entries', entries);
            renderDocumentationList(entries);
        });
    }

    function renderDocumentationList(entries) {
        $documentationEntries.empty();

        if (!entries || !entries.length) {
            $documentationEntries.html('<em class="text-muted">' + lang('no_documentation_entries') + '</em>');
            return;
        }

        entries.forEach((entry) => {
            const createDate = App.Utils.Date.format(
                moment(entry.create_datetime).toDate(),
                vars('date_format'),
                vars('time_format'),
                true,
            );

            const summaryPreview = $('<div/>').html(entry.session_summary).text().substring(0, 120);

            const $row = $('<div/>', {
                'class': 'documentation-entry-row card mb-2 p-3',
                'data-id': entry.id,
                'css': {'cursor': 'pointer'},
            });

            const $header = $('<div/>', {
                'class': 'd-flex justify-content-between align-items-start',
            });

            const $headerLeft = $('<div/>');

            $('<small/>', {
                'class': 'text-muted',
                'text': createDate + (entry.provider_name ? ' - ' + entry.provider_name : ''),
            }).appendTo($headerLeft);

            if (entry.is_edited) {
                $('<span/>', {
                    'class': 'badge bg-warning text-dark ms-2',
                    'text': lang('edited'),
                }).appendTo($headerLeft);
            }

            if (entry.appointment_summary) {
                $('<br/>').appendTo($headerLeft);
                $('<small/>', {
                    'class': 'text-info',
                    'html': '<i class="fas fa-link me-1"></i>' + entry.appointment_summary,
                }).appendTo($headerLeft);
            }

            $headerLeft.appendTo($header);

            const $actions = $('<div/>', {
                'class': 'd-flex gap-1',
            });

            if (entry.is_edited) {
                $('<button/>', {
                    'class': 'btn btn-sm btn-outline-info show-edit-log',
                    'html': '<i class="fas fa-history"></i>',
                    'title': lang('edit_history'),
                }).appendTo($actions);
            }

            $actions.appendTo($header);
            $header.appendTo($row);

            if (summaryPreview) {
                $('<p/>', {
                    'class': 'mb-0 mt-2 text-truncate',
                    'text': summaryPreview + (summaryPreview.length >= 120 ? '...' : ''),
                }).appendTo($row);
            }

            $row.appendTo($documentationEntries);
        });
    }

    function showDocumentationForm(entry) {
        $documentationForm.show();
        $documentationEntries.hide();
        $('#add-documentation-entry').hide();

        // Enable form fields
        $docSessionSummary.prop('disabled', false);
        $docAppointment.prop('disabled', false);

        if (!trumbowygInitialized) {
            $docSessionSummary.trumbowyg({
                btns: [
                    ['strong', 'em', 'underline'],
                    ['unorderedList', 'orderedList'],
                    ['removeformat'],
                    ['dirToggle'],
                ],
                removeformatPasted: true,
                autogrow: true,
                semantic: {
                    'b': 'strong',
                    'i': 'em',
                    's': 'del',
                    'strike': 'del',
                },
            });
            trumbowygInitialized = true;
        }

        // Ensure Trumbowyg is enabled and set direction
        $docSessionSummary.trumbowyg('enable');

        const $editor = $documentationForm.find('.trumbowyg-editor');

        if (vars('is_rtl')) {
            $editor.attr('dir', 'rtl');
        } else {
            $editor.attr('dir', 'ltr');
        }

        // Provider field
        if (vars('role_slug') === 'admin') {
            $docProvider.prop('disabled', false);
        } else {
            $docProvider.prop('disabled', true);
        }

        $docProvider.val(vars('user_id'));

        // Appointment dropdown
        $docAppointment.find('option:not(:first)').remove();

        currentCustomerAppointments.forEach((appointment) => {
            if (appointment.is_unavailability) {
                return;
            }

            const label = appointment.service.name + ' - ' +
                App.Utils.Date.format(
                    moment(appointment.start_datetime).toDate(),
                    vars('date_format'),
                    vars('time_format'),
                    true,
                );

            $('<option/>', {
                'value': appointment.id,
                'text': label,
            }).appendTo($docAppointment);
        });

        if (entry) {
            $docEntryId.val(entry.id);
            $docSessionSummary.trumbowyg('html', entry.session_summary || '');
            $docAppointment.val(entry.id_appointments || '');
            $docProvider.val(entry.id_users_provider);

            $('#entry-saved-section').show();
            loadIssuedDocuments(entry.id);
            hideIssuedDocumentForm();
        } else {
            $docEntryId.val('');
            $docSessionSummary.trumbowyg('html', '');
            $docAppointment.val('');
            $docProvider.val(vars('user_id'));

            $('#entry-saved-section').hide();

            autoLinkCurrentAppointment();
        }
    }

    function hideDocumentationForm() {
        $documentationForm.hide();
        $documentationEntries.show();
        $('#add-documentation-entry').show();

        if (trumbowygInitialized) {
            $docSessionSummary.trumbowyg('html', '');
            $docSessionSummary.trumbowyg('disable');
        }

        $docEntryId.val('');
        $docAppointment.val('').prop('disabled', true);
        $docProvider.prop('disabled', true);

        $('#entry-saved-section').hide();
        hideIssuedDocumentForm();
        $('#issued-documents-list').empty();
    }

    function autoLinkCurrentAppointment() {
        const now = moment();

        for (const appointment of currentCustomerAppointments) {
            if (appointment.is_unavailability) {
                continue;
            }

            const start = moment(appointment.start_datetime);
            const end = moment(appointment.end_datetime);

            if (now.isBetween(start, end, null, '[]')) {
                $docAppointment.val(appointment.id);
                return;
            }
        }
    }

    function saveDocumentationEntry() {
        const summary = $docSessionSummary.trumbowyg('html');

        if (!summary || !summary.trim() || summary.trim() === '<p><br></p>') {
            App.Layouts.Backend.displayNotification(lang('fields_are_required'), 'warning');
            return;
        }

        const entry = {
            id_users_customer: $id.val(),
            id_users_provider: $docProvider.val(),
            id_appointments: $docAppointment.val() || null,
            session_summary: summary,
        };

        const entryId = $docEntryId.val();

        if (entryId) {
            entry.id = entryId;
            App.Http.DocumentationEntries.update(entry).then(() => {
                App.Layouts.Backend.displayNotification(lang('documentation_entry_saved'));
            });
        } else {
            App.Http.DocumentationEntries.store(entry).then((response) => {
                App.Layouts.Backend.displayNotification(lang('documentation_entry_saved'));
                $docEntryId.val(response.id);
                $('#entry-saved-section').show();
                loadIssuedDocuments(response.id);
            });
        }
    }

    function showEditLog(entryId) {
        App.Http.DocumentationEntries.editLog(entryId).then((log) => {
            if (!log || !log.length) {
                return;
            }

            let html = '<div class="table-responsive"><table class="table table-sm table-bordered">';
            html += '<thead><tr>';
            html += '<th>' + lang('date') + '</th>';
            html += '<th>' + lang('edited_by') + '</th>';
            html += '<th>' + lang('changed_field') + '</th>';
            html += '</tr></thead><tbody>';

            log.forEach((entry) => {
                const date = App.Utils.Date.format(
                    moment(entry.edit_datetime).toDate(),
                    vars('date_format'),
                    vars('time_format'),
                    true,
                );

                const editorName = (entry.first_name || '') + ' ' + (entry.last_name || '');

                html += '<tr>';
                html += '<td>' + date + '</td>';
                html += '<td>' + editorName + '</td>';
                html += '<td>' + entry.field_name + '</td>';
                html += '</tr>';
            });

            html += '</tbody></table></div>';

            App.Utils.Message.show(lang('edit_history'), html);
        });
    }

    // --- Issued Documents ---

    let cachedDocumentTypes = null;

    function loadIssuedDocuments(entryId) {
        App.Http.DocumentationEntries.getDocuments(entryId).then((documents) => {
            renderIssuedDocumentsList(documents);
        });
    }

    function renderIssuedDocumentsList(documents) {
        const $list = $('#issued-documents-list');
        $list.empty();

        if (!documents || !documents.length) {
            $list.html('<em class="text-muted small">' + lang('no_records_found') + '</em>');
            return;
        }

        documents.forEach((doc) => {
            const createDate = App.Utils.Date.format(
                moment(doc.create_datetime).toDate(),
                vars('date_format'),
                vars('time_format'),
                true,
            );

            const types = {general_letter: lang('general_letter'), referral: lang('referral'), certificate: lang('certificate')};
            const typeLabel = types[doc.document_type] || doc.document_type;

            const $row = $('<div/>', {
                'class': 'd-flex justify-content-between align-items-center border rounded p-2 mb-2',
            });

            const $info = $('<div/>');
            $('<strong/>', {'text': doc.title, 'class': 'small'}).appendTo($info);
            $('<br/>').appendTo($info);
            $('<small/>', {
                'class': 'text-muted',
                'text': typeLabel + ' - ' + createDate,
            }).appendTo($info);

            $info.appendTo($row);

            const $actions = $('<div/>', {'class': 'd-flex gap-1'});

            $('<button/>', {
                'class': 'btn btn-sm btn-outline-primary view-document-pdf',
                'data-id': doc.id,
                'html': '<i class="fas fa-eye"></i>',
                'title': lang('view_as_pdf'),
            }).appendTo($actions);

            $('<button/>', {
                'class': 'btn btn-sm btn-outline-success send-document-pdf',
                'data-id': doc.id,
                'html': '<i class="fas fa-envelope"></i>',
                'title': lang('send_as_pdf'),
            }).appendTo($actions);

            $actions.appendTo($row);
            $row.appendTo($list);
        });
    }

    function loadDocumentTypes(callback) {
        if (cachedDocumentTypes) {
            callback(cachedDocumentTypes);
            return;
        }

        const url = App.Utils.Url.siteUrl('documentation_entries/document_types');

        $.post(url, {csrf_token: vars('csrf_token')}).then((types) => {
            cachedDocumentTypes = types;
            callback(types);
        });
    }

    function populateTypeDropdown(types) {
        const $select = $('#idoc-type');
        $select.empty();

        $('<option/>', {'value': '', 'text': lang('please_select')}).appendTo($select);

        Object.entries(types).forEach(([slug, type]) => {
            $('<option/>', {
                'value': slug,
                'text': type.label,
            }).appendTo($select);
        });
    }

    function showIssuedDocumentForm() {
        $('#issued-document-form').show();
        $('#add-issued-document').hide();

        $('#idoc-extra-fields').empty();

        loadDocumentTypes((types) => {
            populateTypeDropdown(types);
            $('#idoc-type').prop('disabled', false).trigger('change');
        });
    }

    function hideIssuedDocumentForm() {
        $('#issued-document-form').hide();
        $('#add-issued-document').show();

        $('#idoc-extra-fields').find('.idoc-rich-field').each(function () {
            $(this).trumbowyg('destroy');
        });

        $('#idoc-type').val('');
        $('#idoc-extra-fields').empty();
    }

    function saveIssuedDocument() {
        const type = $('#idoc-type').val();
        const typeName = $('#idoc-type option:selected').text().trim();

        if (!type) {
            App.Layouts.Backend.displayNotification(lang('fields_are_required'), 'warning');
            return;
        }

        const extraFields = {};

        $('#idoc-extra-fields').find('input, textarea').each((index, el) => {
            const $el = $(el);
            const label = $el.data('label') || $el.attr('id').replace('idoc-field-', '');

            if ($el.hasClass('idoc-rich-field')) {
                extraFields[label] = $el.trumbowyg('html') || '';
            } else {
                extraFields[label] = $el.val().trim();
            }
        });

        const document = {
            id_documentation_entry: $docEntryId.val(),
            document_type: type,
            title: typeName,
            content: '',
            extra_fields: extraFields,
        };

        App.Http.DocumentationEntries.storeDocument(document).then(() => {
            App.Layouts.Backend.displayNotification(lang('document_saved'));
            hideIssuedDocumentForm();
            loadIssuedDocuments($docEntryId.val());
        });
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

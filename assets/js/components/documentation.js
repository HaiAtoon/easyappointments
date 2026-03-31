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
 * Documentation component.
 *
 * Manages the Documentation tab within the Customers page: documentation entries (session notes),
 * issued documents, PDF generation, and the Trumbowyg rich text editor lifecycle.
 */
App.Components.Documentation = (function () {
    const $customers = $('#customers');

    let $customerId;
    let $documentationEntries;
    let $documentationForm;
    let $docEntryId;
    let $docProvider;
    let $docAppointment;
    let $docSessionSummary;
    let customerAppointments = [];
    let trumbowygInitialized = false;
    let cachedDocumentTypes = null;

    /**
     * Register Trumbowyg direction toggle plugin (once globally).
     */
    function registerDirTogglePlugin() {
        if (!$.trumbowyg || $.trumbowyg.plugins.dirToggle) {
            return;
        }

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

    /**
     * Bind all documentation-related event listeners.
     */
    function addEventListeners() {
        $customers.on('click', '#add-documentation-entry', () => {
            showEntryForm();
        });

        $customers.on('click', '#save-documentation-entry', () => {
            saveEntry();
        });

        $customers.on('click', '#close-documentation-entry', () => {
            hideEntryForm();
            loadEntries($customerId.val());
        });

        $customers.on('click', '.documentation-entry-row', (event) => {
            const entryId = $(event.currentTarget).data('id');
            const entries = $documentationEntries.data('entries') || [];
            const entry = entries.find((e) => Number(e.id) === Number(entryId));

            if (entry) {
                showEntryForm(entry);
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

            destroyRichFields();
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

    // --- Documentation Entries ---

    function loadEntries(customerId) {
        if (!App.Http.DocumentationEntries) {
            return;
        }

        App.Http.DocumentationEntries.search(customerId).then((entries) => {
            $documentationEntries.data('entries', entries);
            renderEntryList(entries);
        });
    }

    function renderEntryList(entries) {
        $documentationEntries.empty();

        if (!entries || !entries.length) {
            $documentationEntries.html('<em class="text-muted">' + lang('no_documentation_entries') + '</em>');
            return;
        }

        const moment = window.moment;

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

            const $header = $('<div/>', {'class': 'd-flex justify-content-between align-items-start'});
            const $headerLeft = $('<div/>');

            $('<small/>', {
                'class': 'text-muted',
                'text': createDate + (entry.provider_name ? ' - ' + entry.provider_name : ''),
            }).appendTo($headerLeft);

            if (entry.is_edited) {
                $('<span/>', {'class': 'badge bg-warning text-dark ms-2', 'text': lang('edited')}).appendTo($headerLeft);
            }

            if (entry.appointment_summary) {
                $('<br/>').appendTo($headerLeft);
                $('<small/>', {
                    'class': 'text-info',
                    'html': '<i class="fas fa-link me-1"></i>' + entry.appointment_summary,
                }).appendTo($headerLeft);
            }

            $headerLeft.appendTo($header);

            const $actions = $('<div/>', {'class': 'd-flex gap-1'});

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

    function showEntryForm(entry) {
        $documentationForm.show();
        $documentationEntries.hide();
        $('#add-documentation-entry').hide();

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
                semantic: {'b': 'strong', 'i': 'em', 's': 'del', 'strike': 'del'},
            });
            trumbowygInitialized = true;
        }

        $docSessionSummary.trumbowyg('enable');

        const $editor = $documentationForm.find('.trumbowyg-editor');
        $editor.attr('dir', vars('is_rtl') ? 'rtl' : 'ltr');

        if (vars('role_slug') === 'admin') {
            $docProvider.prop('disabled', false);
        } else {
            $docProvider.prop('disabled', true);
        }

        $docProvider.val(vars('user_id'));

        $docAppointment.find('option:not(:first)').remove();

        const moment = window.moment;

        customerAppointments.forEach((appointment) => {
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

            $('<option/>', {'value': appointment.id, 'text': label}).appendTo($docAppointment);
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

    function hideEntryForm() {
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
        const moment = window.moment;
        const now = moment();

        for (const appointment of customerAppointments) {
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

    function saveEntry() {
        const summary = $docSessionSummary.trumbowyg('html');

        if (!summary || !summary.trim() || summary.trim() === '<p><br></p>') {
            App.Layouts.Backend.displayNotification(lang('fields_are_required'), 'warning');
            return;
        }

        const entry = {
            id_users_customer: $customerId.val(),
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

            const moment = window.moment;

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

                html += '<tr>';
                html += '<td>' + date + '</td>';
                html += '<td>' + (entry.first_name || '') + ' ' + (entry.last_name || '') + '</td>';
                html += '<td>' + entry.field_name + '</td>';
                html += '</tr>';
            });

            html += '</tbody></table></div>';

            App.Utils.Message.show(lang('edit_history'), html);
        });
    }

    // --- Issued Documents ---

    function loadIssuedDocuments(entryId) {
        App.Http.DocumentationEntries.getDocuments(entryId).then((documents) => {
            renderIssuedDocumentsList(documents);
        });
    }

    function renderIssuedDocumentsList(documents) {
        const $list = $('#issued-documents-list');
        const moment = window.moment;
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

            const $row = $('<div/>', {
                'class': 'd-flex justify-content-between align-items-center border rounded p-2 mb-2',
            });

            const $info = $('<div/>');
            $('<strong/>', {'text': doc.title, 'class': 'small'}).appendTo($info);
            $('<br/>').appendTo($info);
            $('<small/>', {'class': 'text-muted', 'text': (doc.document_type || '') + ' - ' + createDate}).appendTo($info);
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

        destroyRichFields();

        $('#idoc-type').val('');
        $('#idoc-extra-fields').empty();
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
            $('<option/>', {'value': slug, 'text': type.label}).appendTo($select);
        });
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

    /**
     * Destroy all Trumbowyg instances on issued document extra fields.
     */
    function destroyRichFields() {
        $('#idoc-extra-fields').find('.idoc-rich-field').each(function () {
            $(this).trumbowyg('destroy');
        });
    }

    // --- Public API ---

    /**
     * Initialize the documentation component. Called once on DOMContentLoaded.
     */
    function initialize() {
        $customerId = $('#customer-id');
        $documentationEntries = $('#documentation-entries');
        $documentationForm = $('#documentation-form');
        $docEntryId = $('#doc-entry-id');
        $docProvider = $('#doc-provider');
        $docAppointment = $('#doc-appointment');
        $docSessionSummary = $('#doc-session-summary');

        if (!$documentationEntries.length) {
            return;
        }

        registerDirTogglePlugin();
        addEventListeners();
    }

    /**
     * Called when a customer is selected — loads documentation entries.
     *
     * @param {Number} customerId The selected customer ID.
     * @param {Array} appointments The customer's appointments array.
     */
    function onCustomerSelected(customerId, appointments) {
        customerAppointments = appointments || [];
        $('#add-documentation-entry').prop('disabled', false);
        loadEntries(customerId);
    }

    /**
     * Called when the customer form is reset — clears documentation state.
     */
    function onReset() {
        if ($documentationEntries && $documentationEntries.length) {
            $documentationEntries.html('<em class="text-muted">' + lang('no_documentation_entries') + '</em>');
            $documentationEntries.data('entries', []);
        }

        // Destroy all Trumbowyg instances to prevent memory leaks
        destroyRichFields();

        if (trumbowygInitialized && $docSessionSummary && $docSessionSummary.length) {
            $docSessionSummary.trumbowyg('destroy');
            trumbowygInitialized = false;
        }

        hideEntryForm();
        $('#add-documentation-entry').prop('disabled', true);
        customerAppointments = [];
    }

    document.addEventListener('DOMContentLoaded', initialize);

    return {
        onCustomerSelected,
        onReset,
    };
})();

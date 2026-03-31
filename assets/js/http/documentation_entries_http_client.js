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

App.Http.DocumentationEntries = (function () {
    function search(customerId) {
        const url = App.Utils.Url.siteUrl('documentation_entries/search');

        const data = {
            csrf_token: vars('csrf_token'),
            customer_id: customerId,
        };

        return $.post(url, data);
    }

    function store(entry) {
        const url = App.Utils.Url.siteUrl('documentation_entries/store');

        const data = {
            csrf_token: vars('csrf_token'),
            documentation_entry: entry,
        };

        return $.post(url, data);
    }

    function update(entry) {
        const url = App.Utils.Url.siteUrl('documentation_entries/update');

        const data = {
            csrf_token: vars('csrf_token'),
            documentation_entry: entry,
        };

        return $.post(url, data);
    }

    function editLog(entryId) {
        const url = App.Utils.Url.siteUrl('documentation_entries/edit_log');

        const data = {
            csrf_token: vars('csrf_token'),
            documentation_entry_id: entryId,
        };

        return $.post(url, data);
    }

    function viewEntryPdf(entryId) {
        const url = App.Utils.Url.siteUrl('documentation_entries/view_entry_pdf');

        const $form = $('<form/>', {method: 'POST', action: url, target: '_blank'}).hide();
        $form.append($('<input/>', {name: 'csrf_token', value: vars('csrf_token')}));
        $form.append($('<input/>', {name: 'documentation_entry_id', value: entryId}));

        $('body').append($form);
        $form.submit();
        $form.remove();
    }

    function sendEntryPdf(entryId) {
        const url = App.Utils.Url.siteUrl('documentation_entries/send_entry_pdf');

        const data = {
            csrf_token: vars('csrf_token'),
            documentation_entry_id: entryId,
        };

        return $.post(url, data);
    }

    function storeDocument(document) {
        const url = App.Utils.Url.siteUrl('documentation_entries/store_document');

        const data = {
            csrf_token: vars('csrf_token'),
            issued_document: document,
        };

        return $.post(url, data);
    }

    function getDocuments(entryId) {
        const url = App.Utils.Url.siteUrl('documentation_entries/get_documents');

        const data = {
            csrf_token: vars('csrf_token'),
            documentation_entry_id: entryId,
        };

        return $.post(url, data);
    }

    function viewDocumentPdf(documentId) {
        const url = App.Utils.Url.siteUrl('documentation_entries/view_document_pdf');

        const $form = $('<form/>', {method: 'POST', action: url, target: '_blank'}).hide();
        $form.append($('<input/>', {name: 'csrf_token', value: vars('csrf_token')}));
        $form.append($('<input/>', {name: 'document_id', value: documentId}));

        $('body').append($form);
        $form.submit();
        $form.remove();
    }

    function sendDocumentPdf(documentId) {
        const url = App.Utils.Url.siteUrl('documentation_entries/send_document_pdf');

        const data = {
            csrf_token: vars('csrf_token'),
            document_id: documentId,
        };

        return $.post(url, data);
    }

    function templateMappings(templateSlug) {
        const url = App.Utils.Url.siteUrl('documentation_entries/template_mappings');

        const data = {
            csrf_token: vars('csrf_token'),
            template_slug: templateSlug,
        };

        return $.post(url, data);
    }

    return {
        search,
        store,
        update,
        editLog,
        viewEntryPdf,
        sendEntryPdf,
        storeDocument,
        getDocuments,
        viewDocumentPdf,
        sendDocumentPdf,
        templateMappings,
    };
})();

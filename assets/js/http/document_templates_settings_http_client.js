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

App.Http.DocumentTemplatesSettings = (function () {
    function store(formData) {
        const url = App.Utils.Url.siteUrl('document_templates_settings/store');

        formData.append('csrf_token', vars('csrf_token'));

        return $.ajax({
            url: url,
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
        });
    }

    function update(formData) {
        const url = App.Utils.Url.siteUrl('document_templates_settings/update');

        formData.append('csrf_token', vars('csrf_token'));

        return $.ajax({
            url: url,
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
        });
    }

    function destroy(templateId) {
        const url = App.Utils.Url.siteUrl('document_templates_settings/destroy');

        const data = {
            csrf_token: vars('csrf_token'),
            template_id: templateId,
        };

        return $.post(url, data);
    }

    return {
        store,
        update,
        destroy,
    };
})();

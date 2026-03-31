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

App.Pages.DocumentTemplatesSettings = (function () {
    let $templatesList;
    let $templateForm;
    let $templateId;
    let $templateName;
    let $templateSlug;
    let $templateFile;
    let $templateFileInfo;
    let $templateActive;
    let $mappingsTbody;

    let templates = [];
    let systemVariables = {};

    function buildTypeDropdownHtml(selectedValue) {
        let html = '<select class="form-select form-select-sm mapping-type">';
        html += '<option value="free_text"' + (selectedValue === 'free_text' ? ' selected' : '') + '>' + lang('free_text') + '</option>';
        html += '<option value="free_textarea"' + (selectedValue === 'free_textarea' ? ' selected' : '') + '>' + lang('free_textarea') + '</option>';

        Object.entries(systemVariables).forEach(([groupKey, group]) => {
            html += '<optgroup label="' + group.label + '">';

            Object.entries(group.variables).forEach(([varKey, varLabel]) => {
                html += '<option value="' + varKey + '"' + (selectedValue === varKey ? ' selected' : '') + '>' + varLabel + '</option>';
            });

            html += '</optgroup>';
        });

        html += '</select>';
        return html;
    }

    function addEventListeners() {
        $('#add-template').on('click', () => {
            showForm();
        });

        $('#save-template').on('click', () => {
            saveTemplate();
        });

        $('#cancel-template').on('click', () => {
            hideForm();
        });

        $('#add-mapping-row').on('click', () => {
            addMappingRow();
        });

        $mappingsTbody.on('click', '.remove-mapping', (event) => {
            $(event.currentTarget).closest('tr').remove();
        });

        $templatesList.on('click', '.edit-template', (event) => {
            const templateId = $(event.currentTarget).data('id');
            const template = templates.find((t) => Number(t.id) === Number(templateId));

            if (template) {
                showForm(template);
            }
        });

        $templatesList.on('click', '.delete-template', (event) => {
            const templateId = $(event.currentTarget).data('id');

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
                        deleteTemplate(templateId);
                        messageModal.hide();
                    },
                },
            ];

            App.Utils.Message.show(lang('delete_template'), lang('delete_record_prompt'), buttons);
        });

        $templateName.on('input', () => {
            if (!$templateId.val()) {
                const slug = $templateName.val()
                    .toLowerCase()
                    .replace(/[^a-z0-9]+/g, '_')
                    .replace(/^_|_$/g, '')
                    .substring(0, 50);
                $templateSlug.val(slug);
            }
        });
    }

    function renderList() {
        $templatesList.empty();

        if (!templates.length) {
            $templatesList.html('<em class="text-muted">' + lang('no_records_found') + '</em>');
            return;
        }

        templates.forEach((template) => {
            const mappingsCount = (template.field_mappings || []).length;
            const hasFile = !!template.file_path;
            const statusBadge = template.is_active
                ? '<span class="badge bg-success">' + lang('active') + '</span>'
                : '<span class="badge bg-secondary">' + lang('hidden') + '</span>';
            const fileBadge = hasFile
                ? '<span class="badge bg-info ms-1"><i class="fas fa-file-word"></i></span>'
                : '';

            const $row = $('<div/>', {
                'class': 'd-flex justify-content-between align-items-center border rounded p-3 mb-2',
            });

            const $info = $('<div/>');

            $('<strong/>', {'text': template.name}).appendTo($info);
            $('<span/>', {'class': 'ms-2', 'html': statusBadge + fileBadge}).appendTo($info);
            $('<br/>').appendTo($info);
            $('<small/>', {
                'class': 'text-muted',
                'text': template.slug + ' — ' + mappingsCount + ' ' + lang('fields').toLowerCase(),
            }).appendTo($info);

            $info.appendTo($row);

            const $actions = $('<div/>', {'class': 'd-flex gap-1'});

            $('<button/>', {
                'class': 'btn btn-sm btn-outline-primary edit-template',
                'data-id': template.id,
                'html': '<i class="fas fa-edit"></i>',
            }).appendTo($actions);

            $('<button/>', {
                'class': 'btn btn-sm btn-outline-danger delete-template',
                'data-id': template.id,
                'html': '<i class="fas fa-trash-alt"></i>',
            }).appendTo($actions);

            $actions.appendTo($row);
            $row.appendTo($templatesList);
        });
    }

    function showForm(template) {
        $templateForm.show();
        $templatesList.hide();
        $('#add-template').hide();

        $mappingsTbody.empty();
        $templateFile.val('');

        if (template) {
            $templateId.val(template.id);
            $templateName.val(template.name);
            $templateSlug.val(template.slug).prop('disabled', true);
            $templateActive.prop('checked', template.is_active);
            $templateFileInfo.text(template.file_path ? template.file_path : lang('no_template_file'));

            (template.field_mappings || []).forEach((mapping) => {
                addMappingRow(mapping);
            });
        } else {
            $templateId.val('');
            $templateName.val('');
            $templateSlug.val('').prop('disabled', false);
            $templateActive.prop('checked', true);
            $templateFileInfo.text('');
        }
    }

    function hideForm() {
        $templateForm.hide();
        $templatesList.show();
        $('#add-template').show();
        $mappingsTbody.empty();
    }

    function addMappingRow(mapping) {
        const label = mapping ? mapping.label.replace(/^\{|\}$/g, '') : '';
        const name = mapping ? mapping.name : '';
        const type = mapping ? mapping.type : 'free_text';
        const userDisplay = mapping ? mapping.user_display : true;

        const $row = $('<tr/>');

        $('<td/>').append($('<input/>', {
            'type': 'text',
            'class': 'form-control form-control-sm mapping-label',
            'placeholder': 'customerName',
            'value': label,
        })).appendTo($row);

        $('<td/>').append($('<input/>', {
            'type': 'text',
            'class': 'form-control form-control-sm mapping-name',
            'placeholder': lang('name'),
            'value': name,
        })).appendTo($row);

        $('<td/>').append($(buildTypeDropdownHtml(type))).appendTo($row);

        $('<td/>', {'class': 'text-center'}).append($('<input/>', {
            'type': 'checkbox',
            'class': 'form-check-input mapping-display',
            'checked': userDisplay,
        })).appendTo($row);

        $('<td/>').append($('<button/>', {
            'type': 'button',
            'class': 'btn btn-sm btn-outline-danger remove-mapping',
            'html': '<i class="fas fa-times"></i>',
        })).appendTo($row);

        $row.appendTo($mappingsTbody);
    }

    function collectMappings() {
        const mappings = [];

        $mappingsTbody.find('tr').each((index, row) => {
            const $row = $(row);
            const label = $row.find('.mapping-label').val().trim().replace(/^\{|\}$/g, '');
            const name = $row.find('.mapping-name').val().trim();
            const type = $row.find('.mapping-type').val();
            const userDisplay = $row.find('.mapping-display').prop('checked');

            if (label) {
                mappings.push({label, name, type, user_display: userDisplay});
            }
        });

        return mappings;
    }

    function saveTemplate() {
        const name = $templateName.val().trim();
        const slug = $templateSlug.val().trim();

        if (!name || !slug) {
            App.Layouts.Backend.displayNotification(lang('fields_are_required'), 'warning');
            return;
        }

        const id = $templateId.val();
        const fileInput = $templateFile[0];
        const hasFile = fileInput && fileInput.files.length > 0;

        const formData = new FormData();

        if (id) {
            formData.append('id', id);
        }

        formData.append('name', name);
        formData.append('slug', slug);
        formData.append('is_active', $templateActive.prop('checked') ? '1' : '0');
        formData.append('field_mappings', JSON.stringify(collectMappings()));
        formData.append('csrf_token', vars('csrf_token'));

        if (hasFile) {
            formData.append('template_file', fileInput.files[0]);
        }

        const url = App.Utils.Url.siteUrl(
            'document_templates_settings/' + (id ? 'update' : 'store'),
        );

        $.ajax({
            url: url,
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
        }).then((response) => {
            App.Layouts.Backend.displayNotification(lang('template_saved'));

            if (id) {
                const index = templates.findIndex((t) => Number(t.id) === Number(response.id));

                if (index !== -1) {
                    templates[index] = response.template;
                }
            } else {
                templates.push(response.template);
            }

            hideForm();
            renderList();
        });
    }

    function deleteTemplate(templateId) {
        App.Http.DocumentTemplatesSettings.destroy(templateId).then(() => {
            App.Layouts.Backend.displayNotification(lang('template_deleted'));

            const template = templates.find((t) => Number(t.id) === Number(templateId));

            if (template) {
                template.is_active = false;
            }

            renderList();
        });
    }

    function initialize() {
        $templatesList = $('#templates-list');
        $templateForm = $('#template-form');
        $templateId = $('#template-id');
        $templateName = $('#template-name');
        $templateSlug = $('#template-slug');
        $templateFile = $('#template-file');
        $templateFileInfo = $('#template-file-info');
        $templateActive = $('#template-active');
        $mappingsTbody = $('#mappings-tbody');

        templates = vars('templates') || [];
        systemVariables = vars('system_variables') || {};
        addEventListeners();
        renderList();
    }

    document.addEventListener('DOMContentLoaded', initialize);

    return {};
})();

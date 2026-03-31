<?php extend('layouts/backend_layout'); ?>

<?php section('content'); ?>

<div id="document-templates-settings-page" class="container backend-page">
    <div class="row">
        <div class="col-sm-3 offset-sm-1">
            <?php component('settings_nav'); ?>
        </div>
        <div id="document-templates-settings" class="col-sm-7">
            <div class="d-flex justify-content-between align-items-center border-bottom mb-4 py-2">
                <h4 class="text-black-50 mb-0 fw-light">
                    <?= lang('document_templates') ?>
                </h4>

                <div>
                    <?php if (can('edit', PRIV_SYSTEM_SETTINGS)): ?>
                        <button type="button" id="add-template" class="btn btn-primary">
                            <i class="fas fa-plus me-2"></i>
                            <?= lang('add_template') ?>
                        </button>
                    <?php endif; ?>
                </div>
            </div>

            <div id="templates-list"></div>

            <div id="template-form" style="display:none;">
                <div class="card p-3 mb-3">
                    <input id="template-id" type="hidden">

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label" for="template-name">
                                <?= lang('template_name') ?>
                            </label>
                            <input id="template-name" type="text" class="form-control">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label" for="template-slug">
                                <?= lang('keyword') ?>
                            </label>
                            <input id="template-slug" type="text" class="form-control" maxlength="50">
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label" for="template-file">
                            <?= lang('template_file') ?>
                        </label>
                        <input id="template-file" type="file" class="form-control" accept=".docx">
                        <div id="template-file-info" class="form-text text-muted small"></div>
                    </div>

                    <div class="mb-3">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <label class="form-label mb-0">
                                <?= lang('field_mappings') ?>
                            </label>
                            <button type="button" id="add-mapping-row" class="btn btn-sm btn-outline-secondary">
                                <i class="fas fa-plus me-1"></i>
                                <?= lang('add_field') ?>
                            </button>
                        </div>

                        <div class="table-responsive">
                            <table class="table table-sm table-bordered" id="mappings-table">
                                <thead class="table-light">
                                    <tr>
                                        <th><?= lang('label') ?></th>
                                        <th><?= lang('name') ?></th>
                                        <th><?= lang('field_type') ?></th>
                                        <th><?= lang('user_display') ?></th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody id="mappings-tbody"></tbody>
                            </table>
                        </div>
                    </div>

                    <div class="mb-3 form-check">
                        <input type="checkbox" class="form-check-input" id="template-active" checked>
                        <label class="form-check-label" for="template-active">
                            <?= lang('active') ?>
                        </label>
                    </div>

                    <div class="d-flex gap-2">
                        <button id="save-template" class="btn btn-primary btn-sm">
                            <i class="fas fa-check me-1"></i>
                            <?= lang('save') ?>
                        </button>
                        <button id="cancel-template" class="btn btn-secondary btn-sm">
                            <?= lang('cancel') ?>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php end_section('content'); ?>

<?php section('scripts'); ?>

<script src="<?= asset_url('assets/js/http/document_templates_settings_http_client.js') ?>"></script>
<script src="<?= asset_url('assets/js/pages/document_templates_settings.js') ?>"></script>

<?php end_section('scripts'); ?>

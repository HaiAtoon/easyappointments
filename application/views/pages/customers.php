<?php extend('layouts/backend_layout'); ?>

<?php section('content'); ?>

<div class="container-fluid backend-page" id="customers-page">
    <div class="row" id="customers">
        <div id="filter-customers" class="filter-records col col-12 col-md-5">
            <form class="mb-4">
                <div class="input-group mb-3">
                    <input type="text" class="key form-control" aria-label="keyword">

                    <button class="filter btn btn-outline-secondary" type="submit"
                            data-tippy-content="<?= lang('filter') ?>">
                        <i class="fas fa-search"></i>
                    </button>
                </div>
            </form>

            <h4 class="text-black-50 mb-3 fw-light">
                <?= lang('customers') ?>
            </h4>

            <?php slot('after_page_title'); ?>

            <div class="results">
                <!-- JS -->
            </div>
        </div>

        <div class="record-details col-12 col-md-7">
            <div class="btn-toolbar mb-4">
                <div id="add-edit-delete-group" class="btn-group">
                    <?php if (
                        can('add', PRIV_CUSTOMERS) &&
                        (!setting('limit_customer_access') || vars('role_slug') === DB_SLUG_ADMIN)
                    ): ?>
                        <button id="add-customer" class="btn btn-primary">
                            <i class="fas fa-plus-square me-2"></i>
                            <?= lang('add') ?>
                        </button>
                    <?php endif; ?>

                    <?php if (can('edit', PRIV_CUSTOMERS)): ?>
                        <button id="edit-customer" class="btn btn-outline-secondary" disabled="disabled">
                            <i class="fas fa-edit me-2"></i>
                            <?= lang('edit') ?>
                        </button>
                    <?php endif; ?>

                    <?php if (can('delete', PRIV_CUSTOMERS)): ?>
                        <button id="delete-customer" class="btn btn-outline-secondary" disabled="disabled">
                            <i class="fas fa-trash-alt me-2"></i>
                            <?= lang('delete') ?>
                        </button>
                    <?php endif; ?>
                </div>

                <div id="save-cancel-group" style="display:none;">
                    <button id="save-customer" class="btn btn-primary">
                        <i class="fas fa-check-square me-2"></i>
                        <?= lang('save') ?>
                    </button>
                    <button id="cancel-customer" class="btn btn-secondary">
                        <?= lang('cancel') ?>
                    </button>
                </div>

                <?php slot('after_page_actions'); ?>
            </div>

            <input id="customer-id" type="hidden">

            <ul class="nav nav-tabs mb-3" id="customer-tabs" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active" id="tab-overview-btn" data-bs-toggle="tab"
                            data-bs-target="#tab-overview" type="button" role="tab">
                        <?= lang('overview') ?>
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="tab-appointments-btn" data-bs-toggle="tab"
                            data-bs-target="#tab-appointments" type="button" role="tab">
                        <?= lang('appointments') ?>
                    </button>
                </li>
                <?php if (vars('role_slug') === DB_SLUG_ADMIN || vars('role_slug') === DB_SLUG_PROVIDER): ?>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="tab-documentation-btn" data-bs-toggle="tab"
                                data-bs-target="#tab-documentation" type="button" role="tab">
                            <?= lang('documentation') ?>
                        </button>
                    </li>
                <?php endif; ?>
                <li class="nav-item" role="presentation">
                    <button class="nav-link disabled" id="tab-billing-btn" type="button" role="tab">
                        <?= lang('billing') ?>
                    </button>
                </li>
            </ul>

            <div class="tab-content" id="customer-tab-content">
                <div class="tab-pane fade show active" id="tab-overview" role="tabpanel">
                    <div id="form-message" class="alert" style="display:none;"></div>

                    <?php if (vars('display_id_number')): ?>
                        <div class="mb-3">
                            <label for="id-number" class="form-label">
                                <?= lang('id_number') ?>
                                <?php if (vars('require_id_number')): ?>
                                    <span class="text-danger" hidden>*</span>
                                <?php endif; ?>
                            </label>
                            <input type="text" id="id-number"
                                   class="<?= vars('require_id_number') ? 'required' : '' ?> form-control" maxlength="20" disabled/>
                        </div>
                    <?php endif; ?>

                    <div class="mb-3">
                        <label for="first-name" class="form-label">
                            <?= lang('first_name') ?>
                            <?php if (vars('require_first_name')): ?>
                                <span class="text-danger" hidden>*</span>
                            <?php endif; ?>
                        </label>
                        <input type="text" id="first-name"
                               class="<?= vars('require_first_name') ? 'required' : '' ?> form-control" maxlength="100"
                               disabled/>
                    </div>

                    <div class="mb-3">
                        <label for="last-name" class="form-label">
                            <?= lang('last_name') ?>
                            <?php if (vars('require_last_name')): ?>
                                <span class="text-danger" hidden>*</span>
                            <?php endif; ?>
                        </label>
                        <input type="text" id="last-name"
                               class="<?= vars('require_last_name') ? 'required' : '' ?> form-control" maxlength="120"
                               disabled/>
                    </div>

                    <div class="mb-3">
                        <label for="email" class="form-label">
                            <?= lang('email') ?>
                            <?php if (vars('require_email')): ?>
                                <span class="text-danger" hidden>*</span>
                            <?php endif; ?>
                        </label>
                        <input type="text" id="email"
                               class="<?= vars('require_email') ? 'required' : '' ?> form-control" maxlength="120"
                               disabled/>
                    </div>

                    <div class="mb-3">
                        <label for="phone-number" class="form-label">
                            <?= lang('phone_number') ?>
                            <?php if (vars('require_phone_number')): ?>
                                <span class="text-danger" hidden>*</span>
                            <?php endif; ?>
                        </label>
                        <input type="text" id="phone-number" maxlength="60"
                               class="<?= vars('require_phone_number') ? 'required' : '' ?> form-control" disabled/>
                    </div>

                    <div class="mb-3">
                        <label for="address" class="form-label">
                            <?= lang('address') ?>
                            <?php if (vars('require_address')): ?>
                                <span class="text-danger" hidden>*</span>
                            <?php endif; ?>
                        </label>
                        <input type="text" id="address"
                               class="<?= vars('require_address') ? 'required' : '' ?> form-control"
                               maxlength="120" disabled/>
                    </div>

                    <div class="mb-3">
                        <label for="city" class="form-label">
                            <?= lang('city') ?>
                            <?php if (vars('require_city')): ?>
                                <span class="text-danger" hidden>*</span>
                            <?php endif; ?>
                        </label>
                        <input type="text" id="city" class="<?= vars('require_city') ? 'required' : '' ?> form-control"
                               maxlength="120" disabled/>
                    </div>

                    <div class="mb-3">
                        <label for="zip-code" class="form-label">
                            <?= lang('zip_code') ?>
                            <?php if (vars('require_zip_code')): ?>
                                <span class="text-danger" hidden>*</span>
                            <?php endif; ?>
                        </label>
                        <input type="text" id="zip-code"
                               class="<?= vars('require_zip_code') ? 'required' : '' ?> form-control"
                               maxlength="120" disabled/>
                    </div>

                    <div class="mb-3">
                        <label class="form-label" for="language">
                            <?= lang('language') ?>
                            <span class="text-danger" hidden>*</span>
                        </label>
                        <select id="language" class="form-select required" disabled>
                            <?php foreach (vars('available_languages') as $available_language): ?>
                                <option value="<?= $available_language ?>">
                                    <?= ucfirst($available_language) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label" for="timezone">
                            <?= lang('timezone') ?>
                            <span class="text-danger" hidden>*</span>
                        </label>
                        <?php component('timezone_dropdown', [
                            'attributes' => 'id="timezone" class="form-control required" disabled',
                            'grouped_timezones' => vars('grouped_timezones'),
                        ]); ?>
                    </div>

                    <?php if (setting('ldap_is_active')): ?>
                        <div class="mb-3">
                            <label for="ldap-dn" class="form-label">
                                <?= lang('ldap_dn') ?>
                            </label>
                            <input type="text" id="ldap-dn" class="form-control" maxlength="100" disabled/>
                        </div>
                    <?php endif; ?>

                    <?php component('custom_fields', [
                        'disabled' => true,
                    ]); ?>

                    <div class="mb-3">
                        <label class="form-label" for="notes">
                            <?= lang('notes') ?>
                        </label>
                        <textarea id="notes" rows="4" class="form-control" disabled></textarea>
                    </div>

                    <?php slot('after_primary_fields'); ?>
                </div>

                <div class="tab-pane fade" id="tab-appointments" role="tabpanel">
                    <div id="customer-appointments" class="card bg-white border"></div>

                    <?php slot('after_secondary_fields'); ?>
                </div>

                <?php if (vars('role_slug') === DB_SLUG_ADMIN || vars('role_slug') === DB_SLUG_PROVIDER): ?>
                    <div class="tab-pane fade" id="tab-documentation" role="tabpanel">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h5 class="text-black-50 fw-light mb-0"><?= lang('documentation') ?></h5>
                            <button id="add-documentation-entry" class="btn btn-sm btn-primary" disabled>
                                <i class="fas fa-plus me-1"></i>
                                <?= lang('new_entry') ?>
                            </button>
                        </div>

                        <div id="documentation-entries">
                            <em class="text-muted"><?= lang('no_documentation_entries') ?></em>
                        </div>

                        <div id="documentation-form" style="display:none;">
                            <hr>

                            <div class="mb-3">
                                <label class="form-label" for="doc-provider">
                                    <?= lang('provider') ?>
                                </label>
                                <select id="doc-provider" class="form-select" disabled>
                                    <?php foreach (vars('providers_list') ?? [] as $prov): ?>
                                        <option value="<?= $prov['id'] ?>"><?= $prov['name'] ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>

                            <div class="mb-3">
                                <label class="form-label" for="doc-appointment">
                                    <?= lang('linked_appointment') ?>
                                </label>
                                <select id="doc-appointment" class="form-select">
                                    <option value=""><?= lang('no_linked_appointment') ?></option>
                                </select>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">
                                    <?= lang('session_summary') ?>
                                </label>
                                <textarea id="doc-session-summary"></textarea>
                            </div>

                            <input id="doc-entry-id" type="hidden">

                            <div class="d-flex justify-content-between align-items-center">
                                <button id="save-documentation-entry" class="btn btn-primary btn-sm">
                                    <i class="fas fa-check me-1"></i>
                                    <?= lang('save') ?>
                                </button>
                                <button id="close-documentation-entry" class="btn btn-sm btn-outline-secondary"
                                        title="<?= lang('close') ?>">
                                    <i class="fas fa-times"></i>
                                </button>
                            </div>

                            <div id="entry-saved-section" style="display:none;">
                                <hr>

                                <div id="entry-actions" class="d-flex gap-2 mb-3">
                                    <button id="view-entry-pdf" class="btn btn-sm btn-outline-primary">
                                        <i class="fas fa-file-pdf me-1"></i>
                                        <?= lang('view_as_pdf') ?>
                                    </button>
                                    <button id="send-entry-pdf" class="btn btn-sm btn-outline-success">
                                        <i class="fas fa-envelope me-1"></i>
                                        <?= lang('send_as_pdf') ?>
                                    </button>
                                </div>

                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <h6 class="text-black-50 fw-light mb-0"><?= lang('issued_documents') ?></h6>
                                    <button id="add-issued-document" class="btn btn-sm btn-outline-primary">
                                        <i class="fas fa-plus me-1"></i>
                                        <?= lang('new_document') ?>
                                    </button>
                                </div>

                                <div id="issued-documents-list" class="mb-3"></div>

                                <div id="issued-document-form" style="display:none;">
                                    <div class="card p-3 bg-light">
                                        <div class="mb-3">
                                            <label class="form-label" for="idoc-type">
                                                <?= lang('document_type') ?>
                                            </label>
                                            <select id="idoc-type" class="form-select">
                                            </select>
                                        </div>

                                        <div id="idoc-extra-fields"></div>

                                        <div class="d-flex gap-2">
                                            <button id="save-issued-document" class="btn btn-primary btn-sm">
                                                <i class="fas fa-check me-1"></i>
                                                <?= lang('save') ?>
                                            </button>
                                            <button id="cancel-issued-document" class="btn btn-secondary btn-sm">
                                                <?= lang('cancel') ?>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>

                <div class="tab-pane fade" id="tab-billing" role="tabpanel">
                    <div class="text-center py-5 text-muted">
                        <i class="fas fa-file-invoice-dollar fa-3x mb-3"></i>
                        <h5><?= lang('coming_soon') ?></h5>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php end_section('content'); ?>

<?php section('scripts'); ?>

<script src="<?= asset_url('assets/js/http/customers_http_client.js') ?>"></script>
<script src="<?= asset_url('assets/js/http/documentation_entries_http_client.js') ?>"></script>
<script src="<?= asset_url('assets/js/components/documentation.js') ?>"></script>
<script src="<?= asset_url('assets/js/pages/customers.js') ?>"></script>

<?php end_section('scripts'); ?>

<?php
/**
 * Local variables.
 *
 * @var string $display_first_name
 * @var string $require_first_name
 * @var string $display_last_name
 * @var string $require_last_name
 * @var string $display_email
 * @var string $require_email
 * @var string $display_phone_number
 * @var string $require_phone_number
 * @var string $display_id_number
 * @var string $require_id_number
 * @var string $display_address
 * @var string $require_address
 * @var string $display_city
 * @var string $require_city
 * @var string $display_zip_code
 * @var string $require_zip_code
 * @var string $display_notes
 * @var string $require_notes
 * @var string $returning_customer
 * @var bool $customer_portal_session
 */

$returning_customer_enabled = !empty($returning_customer) && filter_var($returning_customer, FILTER_VALIDATE_BOOLEAN) && empty($manage_mode) && empty($customer_portal_session);
?>

<div id="wizard-frame-3" class="wizard-frame" style="display:none;">
    <div class="frame-container">

        <h2 class="frame-title"><?= lang('customer_information') ?></h2>

        <?php if ($returning_customer_enabled): ?>
            <div id="returning-customer-selection" class="text-center mb-4">
                <p class="mb-3"><?= lang('are_you_returning_customer') ?></p>
                <button type="button" id="btn-returning-customer" class="btn btn-primary me-2">
                    <i class="fas fa-user-check me-2"></i><?= lang('returning_customer') ?>
                </button>
                <button type="button" id="btn-new-customer" class="btn btn-outline-secondary">
                    <i class="fas fa-user-plus me-2"></i><?= lang('new_customer') ?>
                </button>
            </div>

            <div id="returning-customer-form" class="col-12 col-md-6 mx-auto mb-4" style="display:none;">
                <div class="mb-3">
                    <label for="returning-id-number" class="form-label"><?= lang('enter_id_number') ?></label>
                    <input type="text" id="returning-id-number" class="form-control" maxlength="20"
                           placeholder="<?= lang('id_number') ?>"/>
                </div>
                <div id="otp-section" style="display:none;">
                    <div class="alert alert-info small" id="otp-email-hint"></div>
                    <div class="mb-3">
                        <label for="otp-code" class="form-label"><?= lang('enter_otp_code') ?></label>
                        <input type="text" id="otp-code" class="form-control" maxlength="6" placeholder="000000"/>
                    </div>
                    <button type="button" id="btn-verify-otp" class="btn btn-primary">
                        <i class="fas fa-check me-2"></i><?= lang('verify') ?>
                    </button>
                </div>
                <div id="otp-send-section">
                    <button type="button" id="btn-send-otp" class="btn btn-primary">
                        <i class="fas fa-paper-plane me-2"></i><?= lang('send_otp') ?>
                    </button>
                </div>
                <button type="button" id="btn-back-to-selection" class="btn btn-link mt-2">
                    <i class="fas fa-arrow-<?= config('is_rtl') ? 'right' : 'left' ?> me-2"></i><?= lang('back') ?>
                </button>
            </div>
        <?php endif; ?>

        <div id="customer-form-fields" class="row frame-content" <?php if ($returning_customer_enabled): ?>style="display:none;"<?php endif; ?>>
            <div class="col-12 col-md-6 field-col mx-auto">
                <?php if ($display_id_number): ?>
                    <div class="mb-3">
                        <label for="id-number" class="form-label">
                            <?= lang('id_number') ?>
                            <?php if ($require_id_number): ?>
                                <span class="text-danger">*</span>
                            <?php endif; ?>
                        </label>
                        <input type="text" id="id-number"
                               class="<?= $require_id_number ? 'required' : '' ?> form-control" maxlength="20"/>
                    </div>
                <?php endif; ?>

                <?php if ($display_first_name): ?>
                    <div class="mb-3">
                        <label for="first-name" class="form-label">
                            <?= lang('first_name') ?>
                            <?php if ($require_first_name): ?>
                                <span class="text-danger">*</span>
                            <?php endif; ?>
                        </label>
                        <input type="text" id="first-name"
                               class="<?= $require_first_name ? 'required' : '' ?> form-control" maxlength="100"/>
                    </div>
                <?php endif; ?>

                <?php if ($display_last_name): ?>
                    <div class="mb-3">
                        <label for="last-name" class="form-label">
                            <?= lang('last_name') ?>
                            <?php if ($require_last_name): ?>
                                <span class="text-danger">*</span>
                            <?php endif; ?>
                        </label>
                        <input type="text" id="last-name"
                               class="<?= $require_last_name ? 'required' : '' ?> form-control" maxlength="120"/>
                    </div>
                <?php endif; ?>

                <?php if ($display_email): ?>
                    <div class="mb-3">
                        <label for="email" class="form-label">
                            <?= lang('email') ?>
                            <?php if ($require_email): ?>
                                <span class="text-danger">*</span>
                            <?php endif; ?>
                        </label>
                        <input type="text" id="email"
                               class="<?= $require_email ? 'required' : '' ?> form-control" maxlength="120"/>
                    </div>
                <?php endif; ?>

                <?php if ($display_phone_number): ?>
                    <div class="mb-3">
                        <label for="phone-number" class="form-label">
                            <?= lang('phone_number') ?>
                            <?php if ($require_phone_number): ?>
                                <span class="text-danger">*</span>
                            <?php endif; ?>
                        </label>
                        <input type="text" id="phone-number" maxlength="60"
                               class="<?= $require_phone_number ? 'required' : '' ?> form-control"/>
                    </div>
                <?php endif; ?>

                <?php slot('info_first_column'); ?>

                <?php component('custom_fields'); ?>

                <?php slot('after_custom_fields'); ?>
            </div>

            <div class="col-12 col-md-6 field-col mx-auto">
                <?php if ($display_address): ?>
                    <div class="mb-3">
                        <label for="address" class="form-label">
                            <?= lang('address') ?>
                            <?php if ($require_address): ?>
                                <span class="text-danger">*</span>
                            <?php endif; ?>
                        </label>
                        <input type="text" id="address" class="<?= $require_address ? 'required' : '' ?> form-control"
                               maxlength="120"/>
                    </div>
                <?php endif; ?>
                <?php if ($display_city): ?>
                    <div class="mb-3">
                        <label for="city" class="form-label">
                            <?= lang('city') ?>
                            <?php if ($require_city): ?>
                                <span class="text-danger">*</span>
                            <?php endif; ?>
                        </label>
                        <input type="text" id="city" class="<?= $require_city ? 'required' : '' ?> form-control"
                               maxlength="120"/>
                    </div>
                <?php endif; ?>
                <?php if ($display_zip_code): ?>
                    <div class="mb-3">
                        <label for="zip-code" class="form-label">
                            <?= lang('zip_code') ?>
                            <?php if ($require_zip_code): ?>
                                <span class="text-danger">*</span>
                            <?php endif; ?>
                        </label>
                        <input type="text" id="zip-code" class="<?= $require_zip_code ? 'required' : '' ?> form-control"
                               maxlength="120"/>
                    </div>
                <?php endif; ?>
                <?php if ($display_notes): ?>
                    <div class="mb-3">
                        <label for="notes" class="form-label">
                            <?= lang('notes') ?>
                            <?php if ($require_notes): ?>
                                <span class="text-danger">*</span>
                            <?php endif; ?>
                        </label>
                        <textarea id="notes" maxlength="500"
                                  class="<?= $require_notes ? 'required' : '' ?> form-control" rows="1"></textarea>
                    </div>
                <?php endif; ?>

                <?php slot('info_second_column'); ?>
            </div>

        </div>
    </div>

    <div class="command-buttons">
        <button type="button" id="button-back-3" class="btn button-back btn-outline-secondary"
                data-step_index="3">
            <i class="fas fa-chevron-<?= config('is_rtl') ? 'right' : 'left' ?> me-2"></i><?= lang('back') ?>
        </button>
        <button type="button" id="button-next-3" class="btn button-next btn-dark"
                data-step_index="3">
            <?= lang('next') ?><i class="fas fa-chevron-<?= config('is_rtl') ? 'left' : 'right' ?> ms-2"></i>
        </button>
    </div>
</div>

<?php if ($returning_customer_enabled): ?>
<div id="existing-customer-modal" class="modal fade">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><?= lang('existing_customer') ?></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p><?= lang('existing_customer_message') ?></p>
            </div>
            <div class="modal-footer">
                <button type="button" id="btn-go-returning" class="btn btn-primary" data-bs-dismiss="modal">
                    <i class="fas fa-user-check me-2"></i><?= lang('returning_customer') ?>
                </button>
            </div>
        </div>
    </div>
</div>
<?php endif; ?>

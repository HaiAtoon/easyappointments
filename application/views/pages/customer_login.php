<?php extend('layouts/account_layout'); ?>

<?php section('content'); ?>

<h2><?= lang('customer_area') ?></h2>

<p>
    <small>
        <?= lang('login_to_customer_area') ?>
    </small>
</p>

<hr>
<div class="alert d-none"></div>

<form id="customer-login-form">
    <div class="mb-3 mt-5">
        <label for="customer-id-number" class="form-label">
            <?= lang('id_number') ?>
        </label>
        <input type="text" id="customer-id-number" placeholder="<?= lang('enter_id_number') ?>"
               class="form-control" maxlength="20" required/>
    </div>

    <div id="customer-otp-section" style="display:none;">
        <div class="alert alert-info small" id="customer-otp-hint"></div>

        <div class="mb-3">
            <label for="customer-otp-code" class="form-label">
                <?= lang('otp_code') ?>
            </label>
            <input type="text" id="customer-otp-code" placeholder="<?= lang('enter_otp_code') ?>"
                   class="form-control" maxlength="6"/>
        </div>

        <div class="d-flex justify-content-between align-items-center mb-5">
            <button type="button" id="btn-customer-verify" class="btn btn-primary">
                <i class="fas fa-check me-2"></i>
                <?= lang('verify') ?>
            </button>
        </div>
    </div>

    <div id="customer-send-section">
        <div class="d-flex justify-content-between align-items-center mb-5">
            <a href="<?= site_url() ?>" class="text-muted"><?= lang('back') ?></a>

            <button type="submit" id="btn-customer-send-otp" class="btn btn-primary">
                <i class="fas fa-paper-plane me-2"></i>
                <?= lang('send_otp') ?>
            </button>
        </div>
    </div>
</form>
<?php end_section('content'); ?>

<?php section('scripts'); ?>

<script src="<?= asset_url('assets/js/http/customer_http_client.js') ?>"></script>
<script src="<?= asset_url('assets/js/pages/customer_login.js') ?>"></script>

<?php end_section('scripts'); ?>

<?php extend('layouts/account_layout'); ?>

<?php section('content'); ?>

<h2><?= lang('customer_area') ?></h2>

<hr>

<div class="alert alert-success">
    <?= lang('customer_logged_out') ?>
</div>

<div class="d-flex justify-content-between align-items-center mt-4">
    <a href="<?= site_url() ?>" class="text-muted"><?= lang('back') ?></a>

    <a href="<?= site_url('customer') ?>" class="btn btn-primary">
        <i class="fas fa-sign-in-alt me-2"></i>
        <?= lang('customer_login') ?>
    </a>
</div>

<?php end_section('content'); ?>

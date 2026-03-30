<!doctype html>
<html lang="<?= config('language_code') ?>" <?= config('is_rtl') ? 'dir="rtl"' : '' ?>>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no">
    <meta name="theme-color" content="#35A768">
    <meta name="google" content="notranslate">

    <?php slot('meta'); ?>

    <title><?= vars('page_title') ?? lang('customer_area') ?> | Easy!Appointments</title>

    <link rel="icon" type="image/x-icon" href="<?= asset_url('assets/img/favicon.ico') ?>">
    <link rel="icon" sizes="192x192" href="<?= asset_url('assets/img/logo.png') ?>">

    <link rel="stylesheet" type="text/css"
          href="<?= asset_url('assets/css/themes/' . setting('theme', 'default') . '.css') ?>">
    <link rel="stylesheet" type="text/css" href="<?= asset_url('assets/css/general.css') ?>">
    <link rel="stylesheet" type="text/css" href="<?= asset_url('assets/css/pages/customer_dashboard.css') ?>">

    <?php if (config('is_rtl')): ?>
        <link rel="stylesheet" type="text/css" href="<?= asset_url('assets/css/rtl.css') ?>">
    <?php endif; ?>

    <?php slot('styles'); ?>
</head>
<body class="<?= config('is_rtl') ? 'rtl' : '' ?>">

<nav class="navbar navbar-expand-sm navbar-dark bg-dark mb-4">
    <div class="container">
        <a class="navbar-brand" href="<?= site_url('customer/dashboard') ?>">
            <?= e(setting('company_name')) ?>
        </a>
        <div class="d-flex align-items-center">
            <span class="text-light me-3 small">
                <i class="fas fa-user me-1"></i>
                <?= e(session('customer_name')) ?>
            </span>
            <a href="<?= site_url('customer/logout') ?>" class="btn btn-outline-light btn-sm">
                <i class="fas fa-sign-out-alt me-1"></i>
                <?= lang('logout') ?>
            </a>
        </div>
    </div>
</nav>

<div class="container">
    <?php slot('content'); ?>
</div>

<div class="container mt-4 mb-3">
    <div class="text-center">
        <small class="text-muted">
            Powered by
            <a href="https://easyappointments.org">Easy!Appointments</a>
        </small>
    </div>
</div>

<script src="<?= asset_url('assets/vendor/jquery/jquery.min.js') ?>"></script>
<script src="<?= asset_url('assets/vendor/@popperjs-core/popper.min.js') ?>"></script>
<script src="<?= asset_url('assets/vendor/bootstrap/bootstrap.min.js') ?>"></script>
<script src="<?= asset_url('assets/vendor/moment/moment.min.js') ?>"></script>
<script src="<?= asset_url('assets/vendor/moment-timezone/moment-timezone-with-data.min.js') ?>"></script>
<script src="<?= asset_url('assets/vendor/@fortawesome-fontawesome-free/fontawesome.min.js') ?>"></script>
<script src="<?= asset_url('assets/vendor/@fortawesome-fontawesome-free/solid.min.js') ?>"></script>

<script src="<?= asset_url('assets/js/app.js') ?>"></script>
<script src="<?= asset_url('assets/js/utils/date.js') ?>"></script>
<script src="<?= asset_url('assets/js/utils/file.js') ?>"></script>
<script src="<?= asset_url('assets/js/utils/http.js') ?>"></script>
<script src="<?= asset_url('assets/js/utils/lang.js') ?>"></script>
<script src="<?= asset_url('assets/js/utils/message.js') ?>"></script>
<script src="<?= asset_url('assets/js/utils/string.js') ?>"></script>
<script src="<?= asset_url('assets/js/utils/url.js') ?>"></script>
<script src="<?= asset_url('assets/js/utils/validation.js') ?>"></script>

<?php component('js_vars_script'); ?>
<?php component('js_lang_script'); ?>

<?php slot('scripts'); ?>

</body>
</html>

<?php extend('layouts/customer_layout'); ?>

<?php section('content'); ?>

<div class="row">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h3 class="mb-0">
                <i class="fas fa-calendar-alt me-2"></i>
                <?= lang('customer_dashboard') ?>
            </h3>
            <a href="<?= site_url() ?>" class="btn btn-primary">
                <i class="fas fa-plus me-2"></i>
                <?= lang('book_appointment_title') ?>
            </a>
        </div>

        <ul class="nav nav-tabs mb-4" role="tablist">
            <li class="nav-item">
                <a class="nav-link active" data-bs-toggle="tab" href="#upcoming-tab">
                    <i class="fas fa-clock me-1"></i>
                    <?= lang('upcoming_appointments') ?>
                    <span id="upcoming-count" class="badge bg-primary ms-1" style="display:none;"></span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" data-bs-toggle="tab" href="#past-tab">
                    <i class="fas fa-history me-1"></i>
                    <?= lang('past_appointments') ?>
                </a>
            </li>
        </ul>

        <div class="tab-content">
            <div id="upcoming-tab" class="tab-pane fade show active">
                <div id="upcoming-loading" class="text-center py-4">
                    <div class="spinner-border text-primary" role="status"></div>
                </div>
                <div id="upcoming-empty" class="text-muted text-center py-4" style="display:none;">
                    <?= lang('no_upcoming_appointments') ?>
                </div>
                <div id="upcoming-list"></div>
            </div>

            <div id="past-tab" class="tab-pane fade">
                <div id="past-loading" class="text-center py-4">
                    <div class="spinner-border text-primary" role="status"></div>
                </div>
                <div id="past-empty" class="text-muted text-center py-4" style="display:none;">
                    <?= lang('no_past_appointments') ?>
                </div>
                <div id="past-list"></div>
            </div>
        </div>
    </div>
</div>

<?php end_section('content'); ?>

<?php section('scripts'); ?>

<script src="<?= asset_url('assets/js/http/customer_http_client.js') ?>"></script>
<script src="<?= asset_url('assets/js/pages/customer_dashboard.js') ?>"></script>

<?php end_section('scripts'); ?>

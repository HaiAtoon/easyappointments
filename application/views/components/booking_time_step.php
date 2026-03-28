<?php
/**
 * Local variables.
 *
 * @var array $grouped_timezones
 */
?>

<div id="wizard-frame-2" class="wizard-frame" style="display:none;">
    <div class="frame-container">

        <h2 class="frame-title"><?= lang('appointment_date_and_time') ?></h2>

        <div class="frame-content">
            <div class="mx-auto" style="max-width: 340px;">
                <div id="select-date"></div>

                <?php slot('after_select_date'); ?>
            </div>

            <div id="select-time" class="mx-auto" style="max-width: 340px;">
                <div class="mb-3">
                    <label for="select-timezone" class="form-label">
                        <?= lang('timezone') ?>
                    </label>
                    <?php component('timezone_dropdown', [
                        'attributes' => 'id="select-timezone" class="form-select" value="UTC"',
                        'grouped_timezones' => $grouped_timezones,
                    ]); ?>
                </div>

                <?php slot('after_select_timezone'); ?>

                <div id="available-hours"></div>

                <?php slot('after_available_hours'); ?>
            </div>
        </div>
    </div>

    <div class="command-buttons">
        <button type="button" id="button-back-2" class="btn button-back btn-outline-secondary"
                data-step_index="2">
            <?php if (config('is_rtl')): ?>
                <?= lang('back') ?><i class="fas fa-chevron-right ms-2"></i>
            <?php else: ?>
                <i class="fas fa-chevron-left me-2"></i><?= lang('back') ?>
            <?php endif; ?>
        </button>
        <button type="button" id="button-next-2" class="btn button-next btn-dark"
                data-step_index="2">
            <?php if (config('is_rtl')): ?>
                <i class="fas fa-chevron-left me-2"></i><?= lang('next') ?>
            <?php else: ?>
                <?= lang('next') ?><i class="fas fa-chevron-right ms-2"></i>
            <?php endif; ?>
        </button>
    </div>
</div>

<?php
/**
 * Local variables.
 *
 * @var string $otp_code
 * @var array $customer
 * @var array $settings
 */

$is_rtl = in_array(config('language'), ['hebrew', 'arabic', 'persian']);
$dir = $is_rtl ? 'rtl' : 'ltr';
$text_align = $is_rtl ? 'right' : 'left';
?>

<html lang="<?= $is_rtl ? 'he' : 'en' ?>" dir="<?= $dir ?>">
<head>
    <title><?= lang('your_otp_code') ?> | Easy!Appointments</title>
</head>
<body style="font: 13px arial, helvetica, tahoma; direction: <?= $dir ?>; text-align: <?= $text_align ?>;">

<div class="email-container" style="width: 650px; border: 1px solid #eee; margin: 30px auto;">
    <div id="header"
         style="background-color: <?= $settings['company_color'] ?? '#429a82' ?>; height: 45px; padding: 10px 15px; text-align: center;">
        <strong id="logo" style="color: white; font-size: 20px; margin-top: 10px; display: inline-block">
            <?= e($settings['company_name']) ?>
        </strong>
    </div>

    <div id="content" style="padding: 10px 15px; min-height: 300px;">
        <h2>
            <?= lang('your_otp_code') ?>
        </h2>

        <p>
            <?= sprintf(lang('otp_email_greeting'), e($customer['first_name'])) ?>
        </p>

        <p>
            <?= lang('otp_email_message') ?>
        </p>

        <div style="text-align: center; margin: 30px 0;">
            <span style="font-size: 32px; font-weight: bold; letter-spacing: 8px; background: #f5f5f5; padding: 15px 30px; border-radius: 8px; display: inline-block;">
                <?= e($otp_code) ?>
            </span>
        </div>

        <p>
            <?= lang('otp_email_expiry') ?>
        </p>

        <p style="color: #999; font-size: 12px;">
            <?= lang('otp_email_ignore') ?>
        </p>
    </div>

    <div id="footer" style="padding: 10px; text-align: center; margin-top: 10px;
                border-top: 1px solid #EEE; background: #FAFAFA;">
        Powered by
        <a href="https://easyappointments.org" style="text-decoration: none;">
            Easy!Appointments
        </a>
        |
        <a href="<?= e($settings['company_link']) ?>" style="text-decoration: none;">
            <?= e($settings['company_name']) ?>
        </a>
    </div>
</div>

</body>
</html>

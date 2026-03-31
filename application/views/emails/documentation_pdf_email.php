<?php
/**
 * @var array $customer
 * @var array $settings
 * @var string $document_type
 * @var string $password_field
 */

$is_rtl = in_array(config('language'), ['hebrew', 'arabic', 'persian']);
$dir = $is_rtl ? 'rtl' : 'ltr';
$text_align = $is_rtl ? 'right' : 'left';
$password_notice = $password_field === 'id_number'
    ? lang('pdf_password_notice')
    : lang('pdf_password_notice_phone');
?>

<html lang="<?= $is_rtl ? 'he' : 'en' ?>" dir="<?= $dir ?>">
<head>
    <title><?= lang('documentation_pdf_subject') ?> | <?= e($settings['company_name']) ?></title>
</head>
<body style="font: 13px arial, helvetica, tahoma; direction: <?= $dir ?>; text-align: <?= $text_align ?>;">

<div class="email-container" style="width: 650px; border: 1px solid #eee; margin: 30px auto;">
    <div id="header"
         style="background-color: <?= $settings['company_color'] ?? '#429a82' ?>; height: 45px; padding: 10px 15px; text-align: center;">
        <strong id="logo" style="color: white; font-size: 20px; margin-top: 10px; display: inline-block">
            <?= e($settings['company_name']) ?>
        </strong>
    </div>

    <div id="content" style="padding: 10px 15px; min-height: 200px;">
        <h2>
            <?= lang('documentation_pdf_subject') ?>
        </h2>

        <p>
            <?= sprintf(lang('otp_email_greeting'), e($customer['first_name'])) ?>
        </p>

        <p>
            <?= lang('document') ?>: <strong><?= e(lang($document_type) ?: $document_type) ?></strong>
        </p>

        <div style="background: #f5f5f5; padding: 15px; border-radius: 8px; margin: 20px 0;">
            <i>&#128274;</i> <?= $password_notice ?>
        </div>
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

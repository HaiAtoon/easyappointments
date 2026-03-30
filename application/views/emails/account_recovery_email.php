<?php
/**
 * Local variables.
 *
 * @var string $subject
 * @var string $message
 * @var array $settings
 */

$is_rtl = in_array(config('language'), ['hebrew', 'arabic', 'persian']);
$dir = $is_rtl ? 'rtl' : 'ltr';
$text_align = $is_rtl ? 'right' : 'left';
?>
<html lang="<?= $is_rtl ? 'he' : 'en' ?>" dir="<?= $dir ?>">
<head>
    <title><?= $subject ?> | Easy!Appointments</title>
</head>
<body style="font: 13px arial, helvetica, tahoma; direction: <?= $dir ?>; text-align: <?= $text_align ?>;">

<div class="email-container" style="width: 650px; border: 1px solid #eee; margin: 30px auto;">
    <div id="header"
         style="background-color: <?= $settings['company_color'] ?? '#429a82' ?>; height: 45px; padding: 10px 15px; text-align: center;">
        <strong id="logo" style="color: white; font-size: 20px; margin-top: 10px; display: inline-block">
            <?= e($settings['company_name']) ?>
        </strong>
    </div>

    <div id="content" style="padding: 10px 15px; min-height: 400px">
        <h2>
            <?= $subject ?>
        </h2>
        <p>
            <?= preg_replace('/\.(\s)/', '.<br><br>$1', $message) ?>
        </p>
    </div>

    <div id="footer" style="padding: 10px; text-align: center; margin-top: 10px;
                border-top: 1px solid #EEE; background: #FAFAFA;">
        Powered by
        <a href="https://easyappointments.org" style="text-decoration: none;">
            Easy!Appointments
        </a>
        |
        <a href="<?= $settings['company_link'] ?>" style="text-decoration: none;">
            <?= e($settings['company_name']) ?>
        </a>
    </div>
</div>

</body>
</html>

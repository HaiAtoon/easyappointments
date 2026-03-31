<?php
/**
 * @var string $content
 * @var array $customer
 * @var array $provider
 * @var array|null $appointment
 * @var string $document_type
 * @var string $type_label
 * @var array $extra_fields
 * @var string $session_date
 * @var bool $is_rtl
 * @var string $company_name
 * @var string $company_logo
 * @var string $company_color
 */

$dir = $is_rtl ? 'rtl' : 'ltr';
$text_align = $is_rtl ? 'right' : 'left';
$customer_name = trim(($customer['first_name'] ?? '') . ' ' . ($customer['last_name'] ?? ''));
$provider_name = trim(($provider['first_name'] ?? '') . ' ' . ($provider['last_name'] ?? ''));
$professional_title = $provider['custom_field_1'] ?? '';
$license_number = $provider['custom_field_2'] ?? '';
?>
<html dir="<?= $dir ?>">
<head>
    <style>
        body {
            font-family: dejavusans, sans-serif;
            font-size: 12px;
            direction: <?= $dir ?>;
            text-align: <?= $text_align ?>;
            color: #333;
            margin: 0;
            padding: 0;
        }
        .header {
            border-bottom: 3px solid <?= e($company_color) ?>;
            padding-bottom: 15px;
            margin-bottom: 20px;
        }
        .header-top {
            text-align: center;
            margin-bottom: 10px;
        }
        .company-name {
            font-size: 22px;
            font-weight: bold;
            color: <?= e($company_color) ?>;
        }
        .header-info {
            font-size: 11px;
            color: #666;
        }
        .header-info td {
            padding: 2px 10px;
        }
        .doc-title {
            text-align: center;
            font-size: 18px;
            font-weight: bold;
            margin: 25px 0;
            color: <?= e($company_color) ?>;
        }
        .extra-field {
            margin-bottom: 10px;
            padding: 8px 12px;
            background: #f8f9fa;
            border-radius: 4px;
        }
        .extra-field-label {
            font-weight: bold;
            color: #555;
        }
        .content {
            line-height: 1.8;
            margin: 20px 0;
            min-height: 300px;
        }
        .footer {
            border-top: 2px solid <?= e($company_color) ?>;
            padding-top: 15px;
            margin-top: 40px;
        }
        .signature-area {
            margin-top: 40px;
        }
        .signature-line {
            border-bottom: 1px solid #333;
            width: 200px;
            margin-bottom: 5px;
            display: inline-block;
        }
        .provider-detail {
            font-size: 11px;
            color: #555;
        }
    </style>
</head>
<body>

<div class="header">
    <div class="header-top">
        <?php if (!empty($company_logo)): ?>
            <img src="<?= $company_logo ?>" alt="Logo" style="max-height: 60px; max-width: 200px; margin-bottom: 8px;"><br>
        <?php endif; ?>
        <span class="company-name"><?= e($company_name) ?></span>
    </div>

    <table width="100%" class="header-info">
        <tr>
            <td><strong><?= lang('customer') ?>:</strong> <?= e($customer_name) ?></td>
            <td style="text-align: <?= $is_rtl ? 'left' : 'right' ?>;">
                <strong><?= lang('session_date') ?>:</strong> <?= e($session_date) ?>
            </td>
        </tr>
        <?php if (!empty($customer['id_number'])): ?>
            <tr>
                <td><strong><?= lang('id_number') ?>:</strong> <?= e($customer['id_number']) ?></td>
                <td></td>
            </tr>
        <?php endif; ?>
    </table>
</div>

<div class="doc-title">
    <?= e($type_label) ?>
</div>

<?php if (!empty($extra_fields)): ?>
    <?php foreach ($extra_fields as $field_name => $field_value): ?>
        <?php if (!empty($field_value)): ?>
            <div class="extra-field">
                <span class="extra-field-label">
                    <?= e(lang($field_name) ?: $field_name) ?>:
                </span>
                <?= e($field_value) ?>
            </div>
        <?php endif; ?>
    <?php endforeach; ?>
<?php endif; ?>

<div class="content">
    <?= $content ?>
</div>

<div class="footer">
    <div class="signature-area">
        <div class="signature-line">&nbsp;</div><br>
        <strong><?= e($provider_name) ?></strong>
        <?php if (!empty($professional_title)): ?>
            <br><span class="provider-detail"><?= lang('professional_title') ?>: <?= e($professional_title) ?></span>
        <?php endif; ?>
        <?php if (!empty($license_number)): ?>
            <br><span class="provider-detail"><?= lang('license_number') ?>: <?= e($license_number) ?></span>
        <?php endif; ?>
    </div>
</div>

</body>
</html>

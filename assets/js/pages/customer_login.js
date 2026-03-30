App.Pages.CustomerLogin = (function () {
    const $form = $('#customer-login-form');
    const $idNumber = $('#customer-id-number');
    const $otpCode = $('#customer-otp-code');
    const $otpSection = $('#customer-otp-section');
    const $sendSection = $('#customer-send-section');
    const $otpHint = $('#customer-otp-hint');
    const $alert = $('.alert:not(.alert-info)');

    let customerId = null;

    function onFormSubmit(event) {
        event.preventDefault();

        const idNumber = $idNumber.val().trim();

        if (!idNumber) {
            $idNumber.addClass('is-invalid');
            return;
        }

        $idNumber.removeClass('is-invalid');
        $('#btn-customer-send-otp').prop('disabled', true);
        $alert.addClass('d-none');

        App.Http.Customer.sendOtp(idNumber)
            .done((response) => {
                if (response.found) {
                    customerId = response.customer_id;
                    $otpHint.text(lang('otp_sent_to').replace('%s', response.masked_email));
                    $sendSection.hide();
                    $idNumber.prop('readonly', true);
                    $otpSection.show();
                    $otpCode.focus();
                } else {
                    $alert.removeClass('d-none alert-success').addClass('alert-danger');
                    $alert.text(lang('customer_not_found'));
                }
            })
            .always(() => {
                $('#btn-customer-send-otp').prop('disabled', false);
            });
    }

    function onVerifyClick() {
        const otpCode = $otpCode.val().trim();

        if (!otpCode) {
            $otpCode.addClass('is-invalid');
            return;
        }

        $otpCode.removeClass('is-invalid');
        $('#btn-customer-verify').prop('disabled', true);
        $alert.addClass('d-none');

        App.Http.Customer.verifyOtp(customerId, otpCode)
            .done((response) => {
                if (response.valid) {
                    window.location.href = App.Utils.Url.siteUrl('customer/dashboard');
                } else {
                    $alert.removeClass('d-none alert-success').addClass('alert-danger');
                    $alert.text(lang('otp_invalid'));
                }
            })
            .always(() => {
                $('#btn-customer-verify').prop('disabled', false);
            });
    }

    $form.on('submit', onFormSubmit);
    $('#btn-customer-verify').on('click', onVerifyClick);

    return {};
})();

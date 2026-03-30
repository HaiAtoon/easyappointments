App.Http.Customer = (function () {
    function sendOtp(idNumber) {
        const url = App.Utils.Url.siteUrl('customer_portal/send_otp');
        const data = {csrf_token: vars('csrf_token'), id_number: idNumber};
        return $.post(url, data);
    }

    function verifyOtp(customerId, otpCode) {
        const url = App.Utils.Url.siteUrl('customer_portal/verify_otp');
        const data = {csrf_token: vars('csrf_token'), customer_id: customerId, otp_code: otpCode};
        return $.post(url, data);
    }

    function getAppointments() {
        const url = App.Utils.Url.siteUrl('customer_portal/get_appointments');
        const data = {csrf_token: vars('csrf_token')};
        return $.post(url, data);
    }

    return {
        sendOtp,
        verifyOtp,
        getAppointments,
    };
})();

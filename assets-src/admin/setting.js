import $ from 'jquery';

$(function () {
    if ($('#RY_WT_ecpay_shipping_auto_get_no').length) {
        $('#RY_WT_ecpay_shipping_auto_get_no').on('click', function () {
            const $item = $('#RY_WTP_ecpay_shipping_auto_with_scheduler').closest('tr');
            if ($('#RY_WT_ecpay_shipping_auto_get_no').prop('checked')) {
                $item.show();
            } else {
                $item.hide();
            }
        }).trigger('click').trigger('click');
    }

    if ($('#RY_WT_smilepay_shipping_auto_get_no').length) {
        $('#RY_WT_smilepay_shipping_auto_get_no').on('click', function () {
            const $item = $('#RY_WTP_smilepay_shipping_auto_with_scheduler').closest('tr');
            if ($('#RY_WT_smilepay_shipping_auto_get_no').prop('checked')) {
                $item.show();
            } else {
                $item.hide();
            }
        }).trigger('click').trigger('click');
    }
});

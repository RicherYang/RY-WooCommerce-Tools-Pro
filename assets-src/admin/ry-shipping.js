import $ from 'jquery';

$(function () {
    if ($('#RY_WT_ecpay_shipping_auto_get_no').length) {
        $('#RY_WT_ecpay_shipping_auto_get_no').on('click', function () {
            var $item = $('#RY_WTP_ecpay_shipping_auto_with_scheduler').closest('tr');
            if ($('#RY_WT_ecpay_shipping_auto_get_no').prop('checked')) {
                $item.show();
            } else {
                $item.hide();
            }
        }).trigger('click').trigger('click');
    }

    if ($('#_shipping_cvs_store_ID').length) {
        $('.ry-choose-cvs').on('click', function () {
            var html = '<form id="RyECPayChooseCvs" action="' + ECPayInfo.postUrl + '" method="post">';
            for (var idx in ECPayInfo.postData) {
                html += '<input type="hidden" name="' + idx + '" value="' + ECPayInfo.postData[idx] + '">';
            }
            html += '</form>';
            document.body.innerHTML += html;
            document.getElementById('RyECPayChooseCvs').submit();
        });

        if (typeof ECPayInfo.newStore == 'object') {
            $('#_shipping_cvs_store_ID').val(ECPayInfo.newStore.CVSStoreID);
            $('#_shipping_cvs_store_name').val(ECPayInfo.newStore.CVSStoreName);
            $('#_shipping_cvs_store_address').val(ECPayInfo.newStore.CVSAddress);
            $('#_shipping_cvs_store_telephone').val(ECPayInfo.newStore.CVSTelephone);
            $('.ry-choose-cvs').parents('.order_data_column').find('a.edit_address').trigger('click');
        }
    }
});

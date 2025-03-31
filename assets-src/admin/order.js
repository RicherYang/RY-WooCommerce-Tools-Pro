import $ from 'jquery';

$(function () {
    if ($('.ry-choose-cvs').length) {
        $('.ry-choose-cvs').on('click', function () {
            let html = '<form id="RyECPayChooseCvs" action="' + RyInfo.ecpay.postUrl + '" method="post">';
            for (var idx in RyInfo.ecpay.postData) {
                html += '<input type="hidden" name="' + idx + '" value="' + RyInfo.ecpay.postData[idx] + '">';
            }
            html += '</form>';
            document.body.innerHTML += html;
            document.getElementById('RyECPayChooseCvs').submit();
        });

        if (typeof RyInfo.ecpay.newStore == 'object') {
            $('#_shipping_cvs_store_ID').val(RyInfo.ecpay.newStore.CVSStoreID);
            $('#_shipping_cvs_store_name').val(RyInfo.ecpay.newStore.CVSStoreName);
            $('#_shipping_cvs_store_address').val(RyInfo.ecpay.newStore.CVSAddress);
            $('#_shipping_cvs_store_telephone').val(RyInfo.ecpay.newStore.CVSTelephone);
            $('.ry-choose-cvs').parents('.order_data_column').find('a.edit_address').trigger('click');
        }
    }

    if ($('#ry-show-payment-info').length) {
        $('#ry-show-payment-info').on('click', function () {
            let $btn = $(this);
            $btn.addClass(['disabled'])
                .prop('disabled', true);
            $.ajax({
                url: ajaxurl,
                method: 'POST',
                data: {
                    action: 'RY_payment_info',
                    orderid: $btn.data('orderid'),
                    _ajax_nonce: RyInfo._nonce.payment
                },
                success: function (response) {
                    $btn.removeClass(['disabled'])
                        .prop('disabled', false);
                    if (response.success) {
                        $(this).WCBackboneModal({
                            template: 'ry-modal-view-payment-info',
                            variable: response.data
                        });
                    }
                }
            });

        });
    }

    if ($('.ry-show-shipping-info').length) {
        $('.ry-show-shipping-info').on('click', function () {
            let $btn = $(this);
            $btn.addClass(['disabled'])
                .prop('disabled', true);
            console.log(RyInfo);
            $.ajax({
                url: ajaxurl,
                method: 'POST',
                data: {
                    action: 'RY_shipping_info',
                    orderid: $btn.data('orderid'),
                    id: $btn.data('id'),
                    _ajax_nonce: RyInfo._nonce.shipping
                },
                success: function (response) {
                    $btn.removeClass(['disabled'])
                        .prop('disabled', false);
                    if (response.success) {
                        $(this).WCBackboneModal({
                            template: 'ry-modal-view-shipping-info',
                            variable: response.data
                        });
                    }
                }
            });

        });
    }
});

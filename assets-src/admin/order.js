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
                }
            }).done(function (Jdata) {
                if (Jdata.success) {
                    $(this).WCBackboneModal({
                        template: 'ry-modal-view-payment-info',
                        variable: Jdata.data
                    });
                }
            }).always(function () {
                $btn.removeClass(['disabled'])
                    .prop('disabled', false);
            });
        });
    }

    if ($('.ry-show-shipping-info').length) {
        $('.ry-show-shipping-info').on('click', function () {
            let $btn = $(this);
            $btn.addClass(['disabled'])
                .prop('disabled', true);
            $.ajax({
                url: ajaxurl,
                method: 'POST',
                data: {
                    action: 'RY_shipping_info',
                    orderid: $btn.data('orderid'),
                    id: $btn.data('id'),
                    _ajax_nonce: RyInfo._nonce.shipping
                }
            }).done(function (Jdata) {
                if (Jdata.success) {
                    $(this).WCBackboneModal({
                        template: 'ry-modal-view-shipping-info',
                        variable: Jdata.data
                    });
                }
            }).always(function () {
                $btn.removeClass(['disabled'])
                    .prop('disabled', false);
            });
        });
    }

    if ($('#ry-show-refound-info').length) {
        $('#ry-show-refound-info').on('click', function () {
            let $btn = $(this);
            $btn.addClass(['disabled'])
                .prop('disabled', true);
            $('#ry-refound-info').html('');
            $.ajax({
                url: ajaxurl,
                method: 'POST',
                data: {
                    action: 'RY_refound_info',
                    orderid: $btn.data('orderid'),
                    _ajax_nonce: RyInfo._nonce.refound
                }
            }).done(function (Jdata) {
                if (Jdata.success) {
                    const template = wp.template('ry-refound-info');
                    $('#ry-refound-info').html(template(Jdata.data));
                }
            }).always(function () {
                $btn.removeClass(['disabled'])
                    .prop('disabled', false);
            });
        });

        $('#ry-refound-info').on('click', '.refound-action', function () {
            let $btn = $(this);
            $btn.addClass(['disabled'])
                .prop('disabled', true);
            $.ajax({
                url: ajaxurl,
                method: 'POST',
                data: {
                    action: 'RY_refound_action',
                    orderid: $btn.data('orderid'),
                    refound: $btn.data('refound'),
                    amount: $('#ry-refound-amount').val(),
                    _ajax_nonce: RyInfo._nonce.refound
                }
            }).always(function () {
                location.reload();
            });
        });
    }
});

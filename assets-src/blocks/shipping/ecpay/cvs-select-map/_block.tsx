import React from 'react';

import { __ } from '@wordpress/i18n';

import ChooseCvsForm from './_choose_cvs';
import { getSelectedRate } from '../../../base/_shipping-rate';

const Block = (props): JSX.Element | null => {
    const selectedRate = getSelectedRate();

    if (selectedRate === null) {
        return null;
    }

    if ('ry_ecpay_shipping_cvs' != selectedRate.method_id.substring(0, 21)) {
        return null;
    }

    return <div
        className={'ry-woo-tools-ecpay-cvs-select-map'}>
        <ChooseCvsForm
            label={__('Choose convenience store', 'ry-woocommerce-tools-pro')}
            selectedRate={selectedRate} />
    </div >;
};

export default Block;

import type { CartShippingRate } from '@woocommerce/types';

import React from 'react';

import { __ } from '@wordpress/i18n';
import { Button } from '@wordpress/components';
import { getSetting } from '@woocommerce/settings';

import { getRateMetaValue } from '../../../base/_shipping-rate';

interface formProps {
    label: string;
    selectedRate: CartShippingRate;
}

const settings = getSetting('ry_ecpay_cvs_select_block_data', {});

const ChooseCvsForm = ({ label, selectedRate }: formProps): JSX.Element | null => {
    const postData = Object.assign({}, settings.postData, {
        LogisticsType: getRateMetaValue(selectedRate, 'LogisticsType'),
        LogisticsSubType: getRateMetaValue(selectedRate, 'LogisticsSubType')
    });

    const cvsInfo = getRateMetaValue(selectedRate, 'LogisticsInfo');
    const hasCvsInfo = '' !== cvsInfo;

    return (
        <>
            <form method="POST" action={settings.postUrl}>
                {Object.keys(postData).map(name => (
                    <input
                        type="hidden"
                        name={name}
                        value={postData[name]} />
                ))}
                <Button type="submit">{label}</Button>
            </form >
            {hasCvsInfo && cvsInfo.CVSStoreName != '' && (
                <div
                    className={'wc-block-components-radio-control-accordion-content'} >
                    {__('Store name:', 'ry-woocommerce-tools-pro')}
                    {cvsInfo.CVSStoreName}
                </div>
            )}
            {hasCvsInfo && cvsInfo.CVSStoreName != '' && (
                <div
                    className={'wc-block-components-radio-control-accordion-content'} >
                    {__('Store address:', 'ry-woocommerce-tools-pro')}
                    {cvsInfo.CVSAddress}
                </div>
            )}
            {hasCvsInfo && cvsInfo.CVSStoreName != '' && (
                <div
                    className={'wc-block-components-radio-control-accordion-content'} >
                    {__('Store telephone:', 'ry-woocommerce-tools-pro')}
                    {cvsInfo.CVSTelephone}
                </div>
            )}
        </>
    );
};

export default ChooseCvsForm;

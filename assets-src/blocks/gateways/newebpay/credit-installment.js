import { __ } from '@wordpress/i18n';
import { decodeEntities } from '@wordpress/html-entities';
import { registerPaymentMethod } from '@woocommerce/blocks-registry';
import { getSetting } from '@woocommerce/settings';

import NumberOfPeriods from '../../base/_number-of-periods';
import PaymentLabel from '../../base/_payment-label';

const defaultLabel = __('NewebPay Credit(installment)', 'ry-woocommerce-tools-pro');
const settings = getSetting('ry_newebpay_credit_installment_data', {});
const label = decodeEntities(settings.title || defaultLabel);

const Label = ({ ...props }) => {
    return <PaymentLabel
        label={label}
        icon={settings.icons}
        {...props} />;
};


const Content = ({ ...props }) => {
    return (<>
        {decodeEntities(settings.description || '')}
        <NumberOfPeriods
            allPeriods={settings.number_of_periods}
            {...props} />
    </>);
};

const RY_NewebPay_Credit_Installment = {
    name: 'ry_newebpay_credit_installment',
    label: <Label />,
    placeOrderButtonLabel: decodeEntities(settings.button_title || ''),
    content: <Content />,
    edit: <Content />,
    canMakePayment: () => true,
    ariaLabel: label,
    supports: {
        features: settings.supports,
    },
};

registerPaymentMethod(RY_NewebPay_Credit_Installment);

import { __ } from '@wordpress/i18n';
import { decodeEntities } from '@wordpress/html-entities';
import { registerPaymentMethod } from '@woocommerce/blocks-registry';
import { getSetting } from '@woocommerce/settings';

import PaymentLabel from '../../base/_payment-label';

const defaultLabel = __('NewebPay WebATM', 'ry-woocommerce-tools-pro');
const settings = getSetting('ry_newebpay_webatm_data', {});
const label = decodeEntities(settings.title || defaultLabel);

const Label = ({ ...props }) => {
    return <PaymentLabel
        label={label}
        icon={settings.icons}
        {...props} />;
};

const Content = () => {
    return decodeEntities(settings.description || '');
};

const RY_NewebPay_Webatm = {
    name: 'ry_newebpay_webatm',
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

registerPaymentMethod(RY_NewebPay_Webatm);

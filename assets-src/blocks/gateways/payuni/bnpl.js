import { decodeEntities } from '@wordpress/html-entities';
import { registerPaymentMethod } from '@woocommerce/blocks-registry';
import { getSetting } from '@woocommerce/settings';

import PaymentLabel from '../../base/_payment-label';

const settings = getSetting('ry_payuni_bnpl_data', {});
const label = decodeEntities(settings.title || RyPayuniBnplBlockParams.defaultTitle);

const Label = ({ ...props }) => {
    return <PaymentLabel
        label={label}
        icon={settings.icons}
        {...props} />;
};

const Content = () => {
    return decodeEntities(settings.description || '');
};

registerPaymentMethod({
    name: 'ry_payuni_bnpl',
    label: <Label />,
    placeOrderButtonLabel: decodeEntities(settings.button_title || ''),
    content: <Content />,
    edit: <Content />,
    canMakePayment: () => true,
    ariaLabel: label,
    supports: {
        features: settings.supports,
    },
});

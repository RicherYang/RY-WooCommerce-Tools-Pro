import type { CartShippingRate, CartShippingPackageShippingRate } from '@woocommerce/types';

import { select } from '@wordpress/data';

export const getSelectedRate = (): CartShippingRate | null => {
    const store = select('wc/store/cart');
    const shippingRates = store.getShippingRates();

    if (0 === shippingRates.length) {
        return null;
    }

    const selectedRate = shippingRates[0].shipping_rates.find(
        (rate: CartShippingPackageShippingRate) => rate.selected
    );
    return selectedRate;
}

export const getRateMetaValue = (shippingRate: CartShippingRate, key: string) => {
    if (shippingRate?.meta_data) {
        const match = shippingRate.meta_data.find(
            (meta: { key: string; }) => meta.key === key
        );
        return match ? match.value : '';
    }
    return '';
};

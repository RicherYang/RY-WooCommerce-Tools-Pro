import React from 'react';

import { SelectControl } from '@wordpress/components';
import { useEffect, useState } from '@wordpress/element';

const NumberOfPeriods = ({ allPeriods, ...props }): JSX.Element => {
    const usedPeriod = allPeriods[0];
    const [selectedPeriod, setSelectedPeriod] = useState(usedPeriod);
    const { eventRegistration, emitResponse } = props;

    useEffect(() => {
        const unsubscribe = eventRegistration.onPaymentSetup(() => {
            return {
                type: emitResponse.responseTypes.SUCCESS,
                meta: {
                    paymentMethodData: {
                        number_of_period: selectedPeriod ? selectedPeriod : usedPeriod,
                    },
                },
            };
        });
        return () => unsubscribe();
    }, [eventRegistration.onPaymentSetup, selectedPeriod]);

    return <SelectControl
        className="wc-block-components-select"
        value={selectedPeriod ? selectedPeriod : usedPeriod}
        options={allPeriods.map((period: string) => ({
            label: period,
            value: period,
        }))}
        onChange={(usedPeriod) => setSelectedPeriod(usedPeriod)} />;
};

export default NumberOfPeriods;

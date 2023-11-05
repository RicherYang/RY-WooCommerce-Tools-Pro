import React from "react";

const PaymentLabel = ({ label, icon, ...props }): JSX.Element => {
    const { PaymentMethodLabel, PaymentMethodIcons } = props.components;
    return <>
        <PaymentMethodLabel
            text={label} />
        <PaymentMethodIcons
            icons={[icon]}
            align="right" />
    </>;
};

export default PaymentLabel;

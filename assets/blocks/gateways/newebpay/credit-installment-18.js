(()=>{"use strict";const e=window.React,t=window.wp.i18n,n=window.wp.htmlEntities,i=window.wc.wcBlocksRegistry,l=window.wc.wcSettings,a=({label:t,icon:n,...i})=>{const{PaymentMethodLabel:l,PaymentMethodIcons:a}=i.components;return(0,e.createElement)(e.Fragment,null,(0,e.createElement)(l,{text:t}),(0,e.createElement)(a,{icons:[n],align:"right"}))},c=(0,t.__)("NewebPay Credit(18 installment)","ry-woocommerce-tools-pro"),o=(0,l.getSetting)("ry_newebpay_credit_installment_18_data",{}),r=(0,n.decodeEntities)(o.title||c),s=()=>(0,n.decodeEntities)(o.description||""),d={name:"ry_newebpay_credit_installment_18",label:(0,e.createElement)((({...t})=>(0,e.createElement)(a,{label:r,icon:o.icons,...t})),null),placeOrderButtonLabel:(0,n.decodeEntities)(o.button_title||""),content:(0,e.createElement)(s,null),edit:(0,e.createElement)(s,null),canMakePayment:()=>!0,ariaLabel:r,supports:{features:o.supports}};(0,i.registerPaymentMethod)(d)})();
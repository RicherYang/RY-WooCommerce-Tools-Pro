(()=>{"use strict";const e=window.React,t=window.wp.i18n,n=window.wp.htmlEntities,c=window.wc.wcBlocksRegistry,o=window.wc.wcSettings,a=({label:t,icon:n,...c})=>{const{PaymentMethodLabel:o,PaymentMethodIcons:a}=c.components;return(0,e.createElement)(e.Fragment,null,(0,e.createElement)(o,{text:t}),(0,e.createElement)(a,{icons:[n],align:"right"}))},i=(0,t.__)("ECPay TWQR","ry-woocommerce-tools-pro"),l=(0,o.getSetting)("ry_ecpay_twqr_data",{}),r=(0,n.decodeEntities)(l.title||i),s=()=>(0,n.decodeEntities)(l.description||""),w={name:"ry_ecpay_twqr",label:(0,e.createElement)((({...t})=>(0,e.createElement)(a,{label:r,icon:l.icons,...t})),null),placeOrderButtonLabel:(0,n.decodeEntities)(l.button_title||""),content:(0,e.createElement)(s,null),edit:(0,e.createElement)(s,null),canMakePayment:()=>!0,ariaLabel:r,supports:{features:l.supports}};(0,c.registerPaymentMethod)(w)})();
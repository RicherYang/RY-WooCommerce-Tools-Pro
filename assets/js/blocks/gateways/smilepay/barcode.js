(()=>{"use strict";const e=window.React,t=window.wp.i18n,n=window.wp.htmlEntities,o=window.wc.wcBlocksRegistry,a=window.wc.wcSettings,i=({label:t,icon:n,...o})=>{const{PaymentMethodLabel:a,PaymentMethodIcons:i}=o.components;return(0,e.createElement)(e.Fragment,null,(0,e.createElement)(a,{text:t}),(0,e.createElement)(i,{icons:[n],align:"right"}))},l=(0,t.__)("SmilePay BARCODE","ry-woocommerce-tools-pro"),c=(0,a.getSetting)("ry_smilepay_barcode_data",{}),r=(0,n.decodeEntities)(c.title||l),s=()=>(0,n.decodeEntities)(c.description||""),d={name:"ry_smilepay_barcode",label:(0,e.createElement)((({...t})=>(0,e.createElement)(i,{label:r,icon:c.icons,...t})),null),placeOrderButtonLabel:(0,n.decodeEntities)(c.button_title||""),content:(0,e.createElement)(s,null),edit:(0,e.createElement)(s,null),canMakePayment:()=>!0,ariaLabel:r,supports:{features:c.supports}};(0,o.registerPaymentMethod)(d)})();
(()=>{"use strict";const e=window.React,t=window.wp.i18n,n=window.wp.htmlEntities,i=window.wc.wcBlocksRegistry,l=window.wc.wcSettings,c=({label:t,icon:n,...i})=>{const{PaymentMethodLabel:l,PaymentMethodIcons:c}=i.components;return(0,e.createElement)(e.Fragment,null,(0,e.createElement)(l,{text:t}),(0,e.createElement)(c,{icons:[n],align:"right"}))},o=(0,t.__)("SmilePay CVS 711","ry-woocommerce-tools-pro"),a=(0,l.getSetting)("ry_smilepay_cvs_711_data",{}),r=(0,n.decodeEntities)(a.title||o),s=()=>(0,n.decodeEntities)(a.description||""),m={name:"ry_smilepay_cvs_711",label:(0,e.createElement)((({...t})=>(0,e.createElement)(c,{label:r,icon:a.icons,...t})),null),placeOrderButtonLabel:(0,n.decodeEntities)(a.button_title||""),content:(0,e.createElement)(s,null),edit:(0,e.createElement)(s,null),canMakePayment:()=>!0,ariaLabel:r,supports:{features:a.supports}};(0,i.registerPaymentMethod)(m)})();
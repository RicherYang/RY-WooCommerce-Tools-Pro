(()=>{"use strict";const e=window.React,t=window.wp.i18n,n=window.wp.htmlEntities,a=window.wc.wcBlocksRegistry,i=window.wc.wcSettings,l=({label:t,icon:n,...a})=>{const{PaymentMethodLabel:i,PaymentMethodIcons:l}=a.components;return(0,e.createElement)(e.Fragment,null,(0,e.createElement)(i,{text:t}),(0,e.createElement)(l,{icons:[n],align:"right"}))},o=(0,t.__)("SmilePay ATM","ry-woocommerce-tools-pro"),c=(0,i.getSetting)("ry_smilepay_atm_data",{}),r=(0,n.decodeEntities)(c.title||o),s=()=>(0,n.decodeEntities)(c.description||""),m={name:"ry_smilepay_atm",label:(0,e.createElement)((({...t})=>(0,e.createElement)(l,{label:r,icon:c.icons,...t})),null),placeOrderButtonLabel:(0,n.decodeEntities)(c.button_title||""),content:(0,e.createElement)(s,null),edit:(0,e.createElement)(s,null),canMakePayment:()=>!0,ariaLabel:r,supports:{features:c.supports}};(0,a.registerPaymentMethod)(m)})();
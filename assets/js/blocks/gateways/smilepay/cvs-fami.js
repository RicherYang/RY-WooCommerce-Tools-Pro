(()=>{"use strict";const e=window.React,t=window.wp.i18n,n=window.wp.htmlEntities,i=window.wc.wcBlocksRegistry,a=window.wc.wcSettings,l=({label:t,icon:n,...i})=>{const{PaymentMethodLabel:a,PaymentMethodIcons:l}=i.components;return(0,e.createElement)(e.Fragment,null,(0,e.createElement)(a,{text:t}),(0,e.createElement)(l,{icons:[n],align:"right"}))},c=(0,t.__)("SmilePay CVS FamilyMart","ry-woocommerce-tools-pro"),o=(0,a.getSetting)("ry_smilepay_cvs_fami_data",{}),r=(0,n.decodeEntities)(o.title||c),s=()=>(0,n.decodeEntities)(o.description||""),m={name:"ry_smilepay_cvs_fami",label:(0,e.createElement)((({...t})=>(0,e.createElement)(l,{label:r,icon:o.icons,...t})),null),placeOrderButtonLabel:(0,n.decodeEntities)(o.button_title||""),content:(0,e.createElement)(s,null),edit:(0,e.createElement)(s,null),canMakePayment:()=>!0,ariaLabel:r,supports:{features:o.supports}};(0,i.registerPaymentMethod)(m)})();
(()=>{"use strict";var e={20:(e,t,r)=>{var o=r(609),n=Symbol.for("react.element"),s=Symbol.for("react.fragment"),a=Object.prototype.hasOwnProperty,i=o.__SECRET_INTERNALS_DO_NOT_USE_OR_YOU_WILL_BE_FIRED.ReactCurrentOwner,c={key:!0,ref:!0,__self:!0,__source:!0};function l(e,t,r){var o,s={},l=null,p=null;for(o in void 0!==r&&(l=""+r),void 0!==t.key&&(l=""+t.key),void 0!==t.ref&&(p=t.ref),t)a.call(t,o)&&!c.hasOwnProperty(o)&&(s[o]=t[o]);if(e&&e.defaultProps)for(o in t=e.defaultProps)void 0===s[o]&&(s[o]=t[o]);return{$$typeof:n,type:e,key:l,ref:p,props:s,_owner:i.current}}t.Fragment=s,t.jsx=l,t.jsxs=l},848:(e,t,r)=>{e.exports=r(20)},609:e=>{e.exports=window.React}},t={};function r(o){var n=t[o];if(void 0!==n)return n.exports;var s=t[o]={exports:{}};return e[o](s,s.exports,r),s.exports}(()=>{const e=window.wp.i18n,t=window.wp.htmlEntities,o=window.wc.wcBlocksRegistry,n=window.wc.wcSettings;r(609);var s=r(848);const a=({label:e,icon:t,...r})=>{const{PaymentMethodLabel:o,PaymentMethodIcons:n}=r.components;return(0,s.jsxs)(s.Fragment,{children:[(0,s.jsx)(o,{text:e}),(0,s.jsx)(n,{icons:[t],align:"right"})]})},i=(0,e.__)("ECPay ATM","ry-woocommerce-tools-pro"),c=(0,n.getSetting)("ry_ecpay_atm_data",{}),l=(0,t.decodeEntities)(c.title||i),p=({...e})=>(0,s.jsx)(a,{label:l,icon:c.icons,...e}),d=()=>(0,t.decodeEntities)(c.description||""),_={name:"ry_ecpay_atm",label:(0,s.jsx)(p,{}),placeOrderButtonLabel:(0,t.decodeEntities)(c.button_title||""),content:(0,s.jsx)(d,{}),edit:(0,s.jsx)(d,{}),canMakePayment:()=>!0,ariaLabel:l,supports:{features:c.supports}};(0,o.registerPaymentMethod)(_)})()})();
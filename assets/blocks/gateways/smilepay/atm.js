(()=>{"use strict";var e={20:(e,t,r)=>{var o=r(609),n=Symbol.for("react.element"),s=Symbol.for("react.fragment"),i=Object.prototype.hasOwnProperty,a=o.__SECRET_INTERNALS_DO_NOT_USE_OR_YOU_WILL_BE_FIRED.ReactCurrentOwner,c={key:!0,ref:!0,__self:!0,__source:!0};function l(e,t,r){var o,s={},l=null,p=null;for(o in void 0!==r&&(l=""+r),void 0!==t.key&&(l=""+t.key),void 0!==t.ref&&(p=t.ref),t)i.call(t,o)&&!c.hasOwnProperty(o)&&(s[o]=t[o]);if(e&&e.defaultProps)for(o in t=e.defaultProps)void 0===s[o]&&(s[o]=t[o]);return{$$typeof:n,type:e,key:l,ref:p,props:s,_owner:a.current}}t.Fragment=s,t.jsx=l,t.jsxs=l},848:(e,t,r)=>{e.exports=r(20)},609:e=>{e.exports=window.React}},t={};function r(o){var n=t[o];if(void 0!==n)return n.exports;var s=t[o]={exports:{}};return e[o](s,s.exports,r),s.exports}const o=window.wp.i18n,n=window.wp.htmlEntities,s=window.wc.wcBlocksRegistry,i=window.wc.wcSettings;r(609);var a=r(848);const c=({label:e,icon:t,...r})=>{const{PaymentMethodLabel:o,PaymentMethodIcons:n}=r.components;return(0,a.jsxs)(a.Fragment,{children:[(0,a.jsx)(o,{text:e}),(0,a.jsx)(n,{icons:[t],align:"right"})]})},l=(0,o.__)("SmilePay ATM","ry-woocommerce-tools-pro"),p=(0,i.getSetting)("ry_smilepay_atm_data",{}),d=(0,n.decodeEntities)(p.title||l),_=({...e})=>(0,a.jsx)(c,{label:d,icon:p.icons,...e}),y=()=>(0,n.decodeEntities)(p.description||""),w={name:"ry_smilepay_atm",label:(0,a.jsx)(_,{}),placeOrderButtonLabel:(0,n.decodeEntities)(p.button_title||""),content:(0,a.jsx)(y,{}),edit:(0,a.jsx)(y,{}),canMakePayment:()=>!0,ariaLabel:d,supports:{features:p.supports}};(0,s.registerPaymentMethod)(w)})();
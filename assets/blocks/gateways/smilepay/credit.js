(()=>{"use strict";var e={20:(e,t,r)=>{var o=r(609),n=Symbol.for("react.element"),s=Symbol.for("react.fragment"),i=Object.prototype.hasOwnProperty,a=o.__SECRET_INTERNALS_DO_NOT_USE_OR_YOU_WILL_BE_FIRED.ReactCurrentOwner,c={key:!0,ref:!0,__self:!0,__source:!0};function l(e,t,r){var o,s={},l=null,d=null;for(o in void 0!==r&&(l=""+r),void 0!==t.key&&(l=""+t.key),void 0!==t.ref&&(d=t.ref),t)i.call(t,o)&&!c.hasOwnProperty(o)&&(s[o]=t[o]);if(e&&e.defaultProps)for(o in t=e.defaultProps)void 0===s[o]&&(s[o]=t[o]);return{$$typeof:n,type:e,key:l,ref:d,props:s,_owner:a.current}}t.Fragment=s,t.jsx=l,t.jsxs=l},848:(e,t,r)=>{e.exports=r(20)},609:e=>{e.exports=window.React}},t={};function r(o){var n=t[o];if(void 0!==n)return n.exports;var s=t[o]={exports:{}};return e[o](s,s.exports,r),s.exports}(()=>{const e=window.wp.i18n,t=window.wp.htmlEntities,o=window.wc.wcBlocksRegistry,n=window.wc.wcSettings;r(609);var s=r(848);const i=({label:e,icon:t,...r})=>{const{PaymentMethodLabel:o,PaymentMethodIcons:n}=r.components;return(0,s.jsxs)(s.Fragment,{children:[(0,s.jsx)(o,{text:e}),(0,s.jsx)(n,{icons:[t],align:"right"})]})},a=(0,e.__)("SmilePay Credit","ry-woocommerce-tools-pro"),c=(0,n.getSetting)("ry_smilepay_credit_data",{}),l=(0,t.decodeEntities)(c.title||a),d=({...e})=>(0,s.jsx)(i,{label:l,icon:c.icons,...e}),p=()=>(0,t.decodeEntities)(c.description||""),_={name:"ry_smilepay_credit",label:(0,s.jsx)(d,{}),placeOrderButtonLabel:(0,t.decodeEntities)(c.button_title||""),content:(0,s.jsx)(p,{}),edit:(0,s.jsx)(p,{}),canMakePayment:()=>!0,ariaLabel:l,supports:{features:c.supports}};(0,o.registerPaymentMethod)(_)})()})();
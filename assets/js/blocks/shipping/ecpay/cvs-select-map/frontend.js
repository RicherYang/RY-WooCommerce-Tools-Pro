(()=>{"use strict";const e=window.wc.blocksCheckout,t=window.React,o=window.wp.i18n,c=window.wp.components,s=window.wc.wcSettings,n=window.wp.data,a=(e,t)=>{if(e?.meta_data){const o=e.meta_data.find((e=>e.key===t));return o?o.value:""}return""},r=(0,s.getSetting)("ry_ecpay_cvs_select_block_data",{}),l=({label:e,selectedRate:s})=>{const n=Object.assign({},r.postData,{LogisticsType:a(s,"LogisticsType"),LogisticsSubType:a(s,"LogisticsSubType")}),l=a(s,"LogisticsInfo"),i=""!==l;return(0,t.createElement)(t.Fragment,null,(0,t.createElement)("form",{method:"POST",action:r.postUrl},Object.keys(n).map((e=>(0,t.createElement)("input",{type:"hidden",name:e,value:n[e]}))),(0,t.createElement)(c.Button,{type:"submit"},e)),i&&(0,t.createElement)("div",{className:"wc-block-components-radio-control-accordion-content"},(0,o.__)("Convenience store:","ry-woocommerce-tools-pro"),l.CVSStoreName))},i=JSON.parse('{"$schema":"https://schemas.wp.org/trunk/block.json","apiVersion":3,"name":"ry-woocommerce-tools/ry-ecpay-cvs-select-block","version":"3.3.1","title":"ECPay select cvs store","description":"","parent":["woocommerce/checkout-shipping-methods-block"],"supports":{"align":false,"html":false,"multiple":false,"reusable":false,"inserter":false},"attributes":{"lock":{"type":"object","default":{"remove":true,"move":false}}},"textdomain":"ry-woocommerce-tools-pro"}');(0,e.registerCheckoutBlock)({metadata:i,component:e=>{const c=(()=>{const e=(0,n.select)("wc/store/cart").getShippingRates();return 0===e.length?null:e[0].shipping_rates.find((e=>e.selected))})();return null===c||"ry_ecpay_shipping_cvs"!=c.method_id.substring(0,21)?null:(0,t.createElement)("div",{className:"ry-woo-tools-ecpay-cvs-select-map"},(0,t.createElement)(l,{label:(0,o.__)("Choose convenience store","ry-woocommerce-tools-pro"),selectedRate:c}))}})})();
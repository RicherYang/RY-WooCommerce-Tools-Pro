<?php

function rywtp_ecpay_info_to_name($info)
{
    static $info_name = [];
    if (empty($info_name)) {
        $info_name = [
            'WebATM_TAISHIN' => _x('WebATM_TAISHIN', 'ecpay info', 'ry-woocommerce-tools-pro'), // 台新銀行 WebATM
            'WebATM_ESUN' => _x('WebATM_ESUN', 'ecpay info', 'ry-woocommerce-tools-pro'), // 玉山銀行 WebATM
            'WebATM_BOT' => _x('WebATM_BOT', 'ecpay info', 'ry-woocommerce-tools-pro'), // 台灣銀行 WebATM
            'WebATM_FUBON' => _x('WebATM_FUBON', 'ecpay info', 'ry-woocommerce-tools-pro'), // 台北富邦 WebATM
            'WebATM_CHINATRUST' => _x('WebATM_CHINATRUST', 'ecpay info', 'ry-woocommerce-tools-pro'), // 中國信託 WebATM
            'WebATM_FIRST' => _x('WebATM_FIRST', 'ecpay info', 'ry-woocommerce-tools-pro'), // 第一銀行 WebATM
            'WebATM_CATHAY' => _x('WebATM_CATHAY', 'ecpay info', 'ry-woocommerce-tools-pro'), // 國泰世華 WebATM
            'WebATM_MEGA' => _x('WebATM_MEGA', 'ecpay info', 'ry-woocommerce-tools-pro'), // 兆豐銀行 WebATM
            'WebATM_LAND' => _x('WebATM_LAND', 'ecpay info', 'ry-woocommerce-tools-pro'), // 土地銀行 WebATM
            'WebATM_TACHONG' => _x('WebATM_TACHONG', 'ecpay info', 'ry-woocommerce-tools-pro'), // 大眾銀行 WebATM
            'WebATM_SINOPAC' => _x('WebATM_SINOPAC', 'ecpay info', 'ry-woocommerce-tools-pro'), // 永豐銀行 WebATM
            'ATM_TAISHIN' => _x('ATM_TAISHIN', 'ecpay info', 'ry-woocommerce-tools-pro'), // 台新銀行 ATM
            'ATM_ESUN' => _x('ATM_ESUN', 'ecpay info', 'ry-woocommerce-tools-pro'), // 玉山銀行 ATM
            'ATM_BOT' => _x('ATM_BOT', 'ecpay info', 'ry-woocommerce-tools-pro'), // 台灣銀行 ATM
            'ATM_FUBON' => _x('ATM_FUBON', 'ecpay info', 'ry-woocommerce-tools-pro'), // 台北富邦 ATM
            'ATM_CHINATRUST' => _x('ATM_CHINATRUST', 'ecpay info', 'ry-woocommerce-tools-pro'), // 中國信託 ATM
            'ATM_FIRST' => _x('ATM_FIRST', 'ecpay info', 'ry-woocommerce-tools-pro'), // 第一銀行 ATM
            'ATM_LAND' => _x('ATM_LAND', 'ecpay info', 'ry-woocommerce-tools-pro'), // 土地銀行 ATM
            'ATM_CATHAY' => _x('ATM_CATHAY', 'ecpay info', 'ry-woocommerce-tools-pro'), // 國泰世華銀行 ATM
            'ATM_TACHONG' => _x('ATM_TACHONG', 'ecpay info', 'ry-woocommerce-tools-pro'), // 大眾銀行 ATM
            'ATM_PANHSIN' => _x('ATM_PANHSIN', 'ecpay info', 'ry-woocommerce-tools-pro'), // 板信銀行 ATM
            'CVS_CVS' => _x('CVS_CVS', 'ecpay info', 'ry-woocommerce-tools-pro'), // 超商代碼繳款
            'CVS_OK' => _x('CVS_OK', 'ecpay info', 'ry-woocommerce-tools-pro'), // OK 超商代碼繳款
            'CVS_FAMILY' => _x('CVS_FAMILY', 'ecpay info', 'ry-woocommerce-tools-pro'), // 全家超商代碼繳款
            'CVS_HILIFE' => _x('CVS_HILIFE', 'ecpay info', 'ry-woocommerce-tools-pro'), // 萊爾富超商代碼繳款
            'CVS_IBON' => _x('CVS_IBON', 'ecpay info', 'ry-woocommerce-tools-pro'), // 7-11 ibon 代碼繳款
            'BARCODE_BARCODE' => _x('BARCODE_BARCODE', 'ecpay info', 'ry-woocommerce-tools-pro'), // 超商條碼繳款
            'Credit_CreditCard' => _x('Credit_CreditCard', 'ecpay info', 'ry-woocommerce-tools-pro'), // 信用卡
            'Flexible_Installment' => _x('Flexible_Installment', 'ecpay info', 'ry-woocommerce-tools-pro'), // 圓夢彈性分期
            'TWQR_OPAY' => _x('TWQR_OPAY', 'ecpay info', 'ry-woocommerce-tools-pro'), // 歐付寶TWQR 行動支付
            'BNPL_URICH' => _x('BNPL_URICH', 'ecpay info', 'ry-woocommerce-tools-pro'), // 裕富數位無卡分期

            'family' => _x('family', 'ecpay info', 'ry-woocommerce-tools-pro'), // 全家
            'hilife' => _x('hilife', 'ecpay info', 'ry-woocommerce-tools-pro'), // 萊爾富
            'okmart' => _x('okmart', 'ecpay info', 'ry-woocommerce-tools-pro'), // OK超商
            'ibon' => _x('ibon', 'ecpay info', 'ry-woocommerce-tools-pro'), // 7-11
        ];
    }

    return $info_name[$info] ?? $info;
}

function rywtp_ecpay_shipping_to_name($shipping)
{
    static $shipping_name = [];
    if (empty($shipping_name)) {
        $shipping_name = [
            'CVS_FAMI' => _x('CVS_FAMI', 'ecpay shipping', 'ry-woocommerce-tools-pro'), // 全家物流(B2C)',
            'CVS_UNIMART' => _x('CVS_UNIMART', 'ecpay shipping', 'ry-woocommerce-tools-pro'), // 7-ELEVEN超商物流(B2C)',
            'CVS_UNIMARTFREEZE' => _x('CVS_UNIMARTFREEZE', 'ecpay shipping', 'ry-woocommerce-tools-pro'), // 7-ELEVEN冷凍店取(B2C)',
            'CVS_FAMIC2C' => _x('CVS_FAMIC2C', 'ecpay shipping', 'ry-woocommerce-tools-pro'), // 全家物流(C2C)',
            'CVS_UNIMARTC2C' => _x('CVS_UNIMARTC2C', 'ecpay shipping', 'ry-woocommerce-tools-pro'), // 7-ELEVEN超商物流(C2C)',
            'CVS_HILIFE' => _x('CVS_HILIFE', 'ecpay shipping', 'ry-woocommerce-tools-pro'), // 萊爾富物流(B2C)',
            'CVS_HILIFEC2C' => _x('CVS_HILIFEC2C', 'ecpay shipping', 'ry-woocommerce-tools-pro'), // 萊爾富物流(C2C)',
            'CVS_OKMARTC2C' => _x('CVS_OKMARTC2C', 'ecpay shipping', 'ry-woocommerce-tools-pro'), // OK超商(C2C)',
            'HOME_TCAT' => _x('HOME_TCAT', 'ecpay shipping', 'ry-woocommerce-tools-pro'), // 黑貓物流',
            'HOME_POST' => _x('HOME_POST', 'ecpay shipping', 'ry-woocommerce-tools-pro'), // 中華郵政',
        ];
    }

    return $shipping_name[$shipping] ?? $shipping;
}

function rywtp_ecpay_status_to_name($status)
{
    static $status_name = [];
    if (empty($status_name)) {
        $status_name = [
            '300' => '訂單處理中(綠界已收到訂單資料)',
            '310' => '訂單上傳物流中',
            '311' => '訂單傳送物流成功',
            '320' => '郵局已確認資料，可列印',
            '325' => '退貨訂單處理中(綠界已收到訂單資料)',
            '2000' => '已申請門市變更',
            '2001' => '訂單傳送超商成功',
            '2002' => '出貨單號不合規則',
            '2003' => 'XML檔內出貨單號重複',
            '2004' => '出貨單號重複上傳使用(驗收時發現)',
            '2005' => '日期格式不符',
            '2006' => '訂單金額或代收金額錯誤',
            '2007' => '商品類型為空',
            '2008' => '訂單為空',
            '2009' => '門市店號為空',
            '2010' => '出貨日期為空',
            '2011' => '出貨金額為空',
            '2012' => '出貨編號不存在',
            '2013' => '母廠商不存在',
            '2014' => '子廠商不存在',
            '2015' => '出貨編號已存在(單筆)',
            '2016' => '門市已關轉店，將進行退貨處理',
            '2017' => '出貨日期不符合規定',
            '2018' => '服務類型不符規定(如只開取貨付款服務，卻使用純取貨服務)',
            '2019' => '商品類型不符規定',
            '2020' => '廠商尚未申請店配服務',
            '2021' => '同一批次出貨編號重複(批次)',
            '2022' => '出貨金額不符規定',
            '2023' => '取貨人姓名為空',
            '2024' => '訂單傳送超商成功',
            '2025' => '門市轉店號(舊門市店號已更新)',
            '2026' => '無此門市，將進行退貨處理',
            '2027' => '門市指定時間不配送，後續配送中',
            '2028' => '門市關轉店，3日內未更新SUP(新店號)便至退貨流程',
            '2029' => '門市尚未開店',
            '2030' => '物流中心驗收成功',
            '2031' => '等待賣家出貨',
            '2032' => '包裝異常，請洽客服',
            '2033' => '包裹超材，退回賣家',
            '2034' => '違禁品(退貨及罰款處理)',
            '2035' => '訂單資料重複上傳',
            '2036' => '訂單超過驗收期限(賣家未出貨)',
            '2037' => '取件門市關轉，請重選門市',
            '2038' => '標籤錯誤，廠退處理',
            '2039' => '標籤錯誤，廠退處理',
            '2040' => '標籤錯誤，廠退處理',
            '2041' => '物流中心理貨中',
            '2042' => '包裹遺失，進入賠償程序',
            '2043' => '門市指定時間不配送，後續配送中',
            '2044' => '賣家已取退回包裹',
            '2045' => '不正常到貨(商品提早到物流中心)，廠退處理',
            '2046' => '貨件未取退回大智通物流中心',
            '2047' => '正常二退(退貨時間延長，在判賠期限內退回)',
            '2048' => '包裝異常，請洽客服',
            '2049' => '門市關店，將進行退貨處理',
            '2050' => '門市轉店，將進行退貨處理',
            '2051' => '賣家要求提早退貨',
            '2052' => '違禁品(退貨及罰款處理)',
            '2053' => '門市刷A給B，請洽客服',
            '2054' => '賣家要求提早退貨',
            '2055' => '包裹退至物流中心',
            '2057' => '車輛故障，後續配送中',
            '2058' => '天候不佳，後續配送中',
            '2059' => '道路中斷，後續配送中',
            '2060' => '門市停業，廠退處理',
            '2061' => '包裹異常，請洽客服',
            '2062' => '包裹異常，請洽客服',
            '2063' => '包裹配達取件門市',
            '2065' => '買家未取包裹，將退回物流中心',
            '2066' => '包裹異常，請洽客服',
            '2067' => '買家已到店取貨',
            '2068' => '賣家已到門市寄件',
            '2069' => '退貨便收件(商品退回指定C門市)',
            '2070' => '賣家已取退回包裹',
            '2071' => '門市代碼格式錯誤',
            '2072' => '包裹已退至原寄件門市',
            '2073' => '包裹配達取件門市',
            '2074' => '買家未取包裹，將退回物流中心',
            '2075' => '賣家未取包裹，將退回物流中心',
            '2076' => '買家未取包裹，已退回物流中心',
            '2077' => '賣家未取包裹，待申請退回',
            '2078' => '買家未取包裹，已退回物流中心',
            '2079' => '買家未取貨退回物流中心-商品瑕疵(進物流中心)',
            '2080' => '買家未取貨退回物流中心-超材',
            '2081' => '買家未取貨退回物流中心-違禁品(退貨及罰款處理)',
            '2082' => '買家未取貨退回物流中心-訂單資料重複上傳',
            '2083' => '買家未取貨退回物流中心-已過門市進貨日(未於指定時間內寄至物流中心)',
            '2084' => '買家未取貨退回物流中心-第一段標籤規格錯誤',
            '2085' => '買家未取貨退回物流中心-第一段標籤無法判讀',
            '2086' => '買家未取貨退回物流中心-第一段標籤資料錯誤',
            '2087' => '買家未取貨退回物流中心-物流中心理貨中',
            '2088' => '買家未取貨退回物流中心-商品遺失',
            '2089' => '買家未取退回物流中心-門市指定不配送(六、日)',
            '2092' => '買家未取退回物流中心-門市關轉',
            '2093' => '買家未取退回物流中心-爆量',
            '2094' => '包裹異常，請洽客服',
            '2095' => '天候路況不佳',
            '2096' => '賣家未取包裹，待申請退回',
            '2097' => '賣家未取包裹，宅配退回中',
            '2101' => '門市關轉店',
            '2102' => '門市舊店號更新',
            '2103' => '無取件門市資料',
            '2104' => '門市關轉，請重選門市',
            '2105' => '已申請門市變更',
            '3001' => '轉運中(即集貨)',
            '3002' => '不在家',
            '3003' => '配完',
            '3004' => '送錯BASE(送錯營業所)',
            '3005' => '送錯CENTER(送錯轉運中心)',
            '3006' => '配送中',
            '3007' => '公司行號休息',
            '3008' => '地址錯誤，聯繫收件人',
            '3009' => '搬家',
            '3010' => '轉寄(如原本寄到A，改寄B)',
            '3011' => '暫置營業所(收件人要求至營業所取貨)',
            '3012' => '到所(收件人要求到站所取件)',
            '3013' => '當配下車(當日配送A至B營業所，已抵達B營業所)',
            '3014' => '當配上車(當日配送從A至B營業所，已抵達A營業所)',
            '3015' => '空運配送中',
            '3016' => '配完狀態刪除',
            '3017' => '退回狀態刪除(代收退貨刪除)',
            '3018' => '包裹配達取件門市',
            '3019' => '包裹已退至原寄件門市',
            '3020' => '買家未取包裹，將退回物流中心',
            '3021' => '賣家未取包裹，待申請退回',
            '3022' => '買家已到店取貨',
            '3023' => '賣家已取退回包裹',
            '3024' => '物流中心驗收成功',
            '3025' => '買家未取包裹，已退回物流中心',
            '3029' => '包裹已配達指定取件門市',
            '3031' => '包裹已退至指定寄件門市',
            '3032' => '賣家已到門市寄件',
            '3033' => 'EC客戶要求提早退貨',
            '3117' => '拒收(調查處理中)',
            '3118' => '暫置營業所',
            '3119' => '暫置營業所(假日暫置)',
            '3120' => '預備配送中',
            '3121' => '轉交門市配達',
            '3122' => '另約時間',
            '3301' => '交寄郵件',
            '3302' => '各區郵局招領中',
            '3303' => '投遞不成功',
            '3304' => '依寄件人指示銷毀',
            '3305' => '拒收',
            '3306' => '退回投遞不成功',
            '3307' => 'i郵箱取件成功',
            '3308' => '投遞成功',
            '3309' => '投遞成功(收受人領取)',
            '3310' => '已退回寄件人',
            '3311' => '退回郵件投遞中',
            '3312' => '貨件投遞中',
            '3313' => '郵局貨件運轉中',
            '3314' => '到達i郵箱',
            '3315' => 'i郵箱逾期退招領',
            '4001' => '買家已到門市寄件',
            '4002' => '退貨商品已至物流中心',
            '4003' => '退件包裹異常，協尋中',
            '4004' => '包裹遺失，進入賠償程序',
            '5001' => '損壞，站所將協助退貨',
            '5002' => '遺失',
            '5003' => 'BASE列管(寄件人和收件人聯絡不到)',
            '5004' => '賣家未取包裹，待申請退回',
            '5005' => '代收退貨',
            '5006' => '代收毀損',
            '5007' => '代收遺失',
            '5008' => '退貨配完',
            '5009' => '包裹異常，請洽客服',
            '7001' => '超大(通常發生於司機取件，不取件)',
            '7002' => '超重(通常發生於司機取件，不取件)',
            '7003' => '地址錯誤，聯繫收件人',
            '7004' => '航班延誤',
            '7005' => '託運單刪除',
            '7006' => '包裹遺失，進入賠償程序',
            '7007' => '包裹遺失，進入賠償程序',
            '7008' => '包裹破損，請洽客服',
            '7009' => '包裝異常，請洽客服',
            '7010' => '包裝異常，請洽客服',
            '7011' => '取件門市關轉，請重選門市',
            '7012' => '條碼錯誤，廠退處理',
            '7013' => '訂單超過驗收期限(賣家未出貨)',
            '7014' => '等待賣家出貨',
            '7015' => '條碼重複，請洽客服',
            '7016' => '包裹超材，退回賣家',
            '7017' => '取件包裹異常，協尋中',
            '7018' => '包裹遺失，進入賠償程序',
            '7019' => '寄件包裹異常，協尋中',
            '7020' => '包裹遺失，進入賠償程序',
            '7021' => '包裹異常，請洽客服',
            '7022' => '包裹異常，請洽客服',
            '7023' => '包裹異常，請洽客服',
            '7024' => '另約時間',
            '7025' => '電聯不上',
            '7026' => '資料有誤',
            '7027' => '無件可退',
            '7028' => '超大超重',
            '7029' => '已回收',
            '7030' => '別家收走',
            '7031' => '商品未到',
            '7032' => '寄件門市關轉，請重選門市',
            '7033' => '資料異常',
            '7034' => '貨物進店發生異常，請洽客服',
            '7035' => '逾期未領，貨件銷毀',
            '7036' => '貨件破損，請洽客服',
            '7037' => '訂單上傳失敗',
            '7038' => '門市驗收異常，請洽客服',
            '7101' => '取件門市關轉，請重選門市',
            '7102' => '包裹配送驗收異常-無進貨資料',
            '7103' => '包裹配送驗收異常-條碼錯誤',
            '7104' => '包裹配送驗收異常-超材',
            '7105' => '包裹配送驗收異常-大物流包裝不良(滲漏)',
            '7106' => '包裹配送驗收異常-小物流破損',
            '7107' => '包裹配送驗收異常-門市反應商品包裝不良(滲漏)',
            '7201' => '寄件門市關轉，請重選門市',
            '7202' => '退貨包裹配送驗收異常-無進貨資料',
            '7203' => '退貨包裹配送驗收異常-條碼錯誤',
            '7204' => '退貨包裹配送驗收異常-超材',
            '7205' => '退貨包裹配送驗收異常-大物流包裝不良(滲漏)',
            '7206' => '退貨包裹配送驗收異常-小物流破損',
            '7207' => '退貨包裹配送驗收異常-門市反應商品包裝不良(滲漏)',
            '7255' => '包裹退至物流中心',
            '7300' => '郵局接收資料異常',
            '7301' => '郵件破損-無法投遞',
            '7302' => '郵件遺失',
            '9001' => '退貨已取',
            '9002' => '退貨已取',
            '9999' => '訂單取消',
        ];
    }

    return $status_name[$status] ?? $status;
}

function rywtp_newebpay_type_to_name($type)
{
    static $type_name = [];
    if (empty($type_name)) {
        $type_name = [
            'CREDIT' => _x('CREDIT', 'newebpay type', 'ry-woocommerce-tools-pro'), // 信用卡
            'VACC' => _x('VACC', 'newebpay type', 'ry-woocommerce-tools-pro'), // 銀行 ATM 轉帳
            'WEBATM' => _x('WEBATM', 'newebpay type', 'ry-woocommerce-tools-pro'), // 網路銀行轉帳
            'BARCODE' => _x('BARCODE', 'newebpay type', 'ry-woocommerce-tools-pro'), // 超商條碼
            'CVS' => _x('CVS', 'newebpay type', 'ry-woocommerce-tools-pro'), // 超商代碼繳
            'LINEPAY' => _x('LINEPAY', 'newebpay type', 'ry-woocommerce-tools-pro'), // LINE Pay
            'ESUNWALLET' => _x('ESUNWALLET', 'newebpay type', 'ry-woocommerce-tools-pro'), // 玉山 Wallet
            'TAIWANPAY' => _x('TAIWANPAY', 'newebpay type', 'ry-woocommerce-tools-pro'), // 台灣 Pay
            'CVSCOM' => _x('CVSCOM', 'newebpay type', 'ry-woocommerce-tools-pro'), // 超商取貨付款
            'FULA' => _x('FULA', 'newebpay type', 'ry-woocommerce-tools-pro'), // Fula 付啦
        ];
    }

    return $type_name[$type] ?? $type;
}

function rywtp_newebpay_method_to_name($method)
{
    static $method_name = [];
    if (empty($method_name)) {
        $method_name = [
            'CREDIT' => _x('CREDIT', 'newebpay method', 'ry-woocommerce-tools-pro'), // 台灣發卡機構核發之信用卡
            'FOREIGN' => _x('FOREIGN', 'newebpay method', 'ry-woocommerce-tools-pro'), // 國外發卡機構核發之卡
            'NTCB' => _x('NTCB', 'newebpay method', 'ry-woocommerce-tools-pro'), // 國民旅遊卡
            'UNIONPAY' => _x('UNIONPAY', 'newebpay method', 'ry-woocommerce-tools-pro'), // 銀聯卡
            'APPLEPAY' => _x('APPLEPAY', 'newebpay method', 'ry-woocommerce-tools-pro'), // ApplePay
            'GOOGLEPAY' => _x('GOOGLEPAY', 'newebpay method', 'ry-woocommerce-tools-pro'), // GooglePay
            'SAMSUNGPAY' => _x('SAMSUNGPAY', 'newebpay method', 'ry-woocommerce-tools-pro'), // SamsungPay
        ];
    }

    return $method_name[$method] ?? $method;
}

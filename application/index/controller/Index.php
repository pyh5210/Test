<?php
namespace app\index\controller;

use think\App;
use \think\Controller;
use think\Loader;
use \think\Session;
use think\Db;

class Index extends Controller
{
    private $res;

    public function __construct(App $app = null)
    {
        parent::__construct($app);
        $this->res=[];
    }

    public function index()
    {

        $header=[
            ['name'=>'guid','title'=>'系统唯一序号','type'=>'text','local'=>'704ea0f1-b229-4e89-9f2f-c2e550e95c86'],
            ['name'=>'appType','title'=>'报送类型','type'=>'select','option'=>['1'=>'新增','2'=>'变更','3'=>'删除'],'local'=>'1'],
            ['name'=>'appTime','title'=>'报送时间','type'=>'text','local'=>'20180507153001'],
            ['name'=>'appStatus','title'=>'报送状态','type'=>'select','option'=>['1'=>'暂存','2'=>'申报'],'local'=>'2'],
            ['name'=>'orderType','title'=>'订单类型','type'=>'select','option'=>['I'=>'进口商品订单','E'=>'出口商品订单'],'local'=>'E'],
            ['name'=>'orderNo','title'=>'订单编号','type'=>'text','local'=>'order2018050711340001'],
            ['name'=>'ebpCode','title'=>'电商平台代码','type'=>'text','local'=>'1105910159'],
            ['name'=>'ebpName','title'=>'电商平台名称','type'=>'text','local'=>'东方物通科技(北京)有限公司'],
            ['name'=>'ebcCode','title'=>'电商企业代码','type'=>'text','local'=>'1105910159'],
            ['name'=>'ebcName','title'=>'电商企业名称','type'=>'text','local'=>'东方物通科技(北京)有限公司'],
            ['name'=>'goodsValue','title'=>'商品金额','type'=>'text','local'=>'12345678912345.12345'],
            ['name'=>'freight','title'=>'运杂费','type'=>'text','local'=>'0'],
            ['name'=>'currency','title'=>'币制','type'=>'text','local'=>'142'],
            ['name'=>'note','title'=>'备注','type'=>'text','local'=>'test'],

        ];

        $main=[
            ['name'=>'gnum','title'=>'序号','type'=>'text','local'=>'1'],
            ['name'=>'itemNo','title'=>'企业商品货号','type'=>'text','local'=>'AF001-001'],
            ['name'=>'itemName','title'=>'企业商品名称','type'=>'text','local'=>'小米盒子'],
            ['name'=>'itemDescribe','title'=>'企业商品描述','type'=>'text','local'=>'小米盒子'],
            ['name'=>'barCode','title'=>'条形码','type'=>'text','local'=>'2345123'],
            ['name'=>'unit','title'=>'计量单位','type'=>'text','local'=>'aa'],
            ['name'=>'currency','title'=>'币制','type'=>'text','local'=>'142'],
            ['name'=>'qty','title'=>'数量','type'=>'text','local'=>'100'],
            ['name'=>'price','title'=>'单价','type'=>'text','local'=>'20'],
            ['name'=>'totalPrice','title'=>'总价','type'=>'text','local'=>'2000'],
            ['name'=>'note','title'=>'备注','type'=>'text','local'=>'test'],
        ];

        $tail=[
            ['name'=>'copCode','title'=>'传输企业代码','type'=>'text','local'=>'1105910159'],
            ['name'=>'copName','title'=>'传输企业名称','type'=>'text','local'=>'东方物通科技(北京)有限公司'],
            ['name'=>'dxpMode','title'=>'报文传输模式','type'=>'text','local'=>'DXP'],
            ['name'=>'dxpId','title'=>'报文传输编号','type'=>'text','local'=>'DXPLGS0000000001'],
            ['name'=>'note','title'=>'备注','type'=>'text','local'=>'test']
        ];
       
        $this->assign('header',$header);
        $this->assign('main',$main);
        $this->assign('tail',$tail);
        return $this->fetch();
    }

    public function submit(){
        $post=$this->request->param();
   
        $header=[];
        $list=[];
        $tail=[];
        foreach ($post['header'] as $k1=>$v1){
            $header[$v1['name']]=!empty($v1['value'])?$v1['value']:' ';
        } 

        $header['freight']=!empty($header['freight'])?$header['freight']:'0';
        foreach ($post['list'] as $k2=>$v2){
            $list[$v2['name']][]=!empty($v2['value'])?$v2['value']:' ';
        }
        foreach ($post['tail'] as $k3=>$v3){
            $tail[$v3['name']]=!empty($v3['value'])?$v3['value']:' ';
        }

        $header_html="
            <ceb:Order>
            <ceb:OrderHead>
                <ceb:guid>{$header['guid']}</ceb:guid>
                <ceb:appType>{$header['appType']}</ceb:appType>
                <ceb:appTime>{$header['appTime']}</ceb:appTime>
                <ceb:appStatus>{$header['appStatus']}</ceb:appStatus>
                <ceb:orderType>{$header['orderType']}</ceb:orderType>
                <ceb:orderNo>{$header['orderNo']}</ceb:orderNo>
                <ceb:ebpCode>{$header['ebpCode']}</ceb:ebpCode>
                <ceb:ebpName>{$header['ebpName']}</ceb:ebpName>
                <ceb:ebcCode>{$header['ebcCode']}</ceb:ebcCode>
                <ceb:ebcName>{$header['ebcName']}</ceb:ebcName>
                <ceb:goodsValue>{$header['goodsValue']}</ceb:goodsValue>
                <ceb:freight>{$header['freight']}</ceb:freight>
                <ceb:currency>{$header['currency']}</ceb:currency>
                <ceb:note>{$header['note']}</ceb:note>
            </ceb:OrderHead>";


        $len =count($list['gnum']);

        for($i=0;$i<$len;$i++){
            $list_html[$i]="
            {$header_html}
            <ceb:OrderList>
                <ceb:gnum>{$list['gnum'][$i]}</ceb:gnum>
                <ceb:itemNo>{$list['itemNo'][$i]}</ceb:itemNo>
                <ceb:itemName>{$list['itemName'][$i]}</ceb:itemName>
                <ceb:itemDescribe>{$list['itemDescribe'][$i]}</ceb:itemDescribe>
                <ceb:barCode>{$list['barCode'][$i]}</ceb:barCode>
                <ceb:unit>{$list['unit'][$i]}</ceb:unit>
                <ceb:currency>{$list['currency'][$i]}</ceb:currency>
                <ceb:qty>{$list['qty'][$i]}</ceb:qty>
                <ceb:price>{$list['price'][$i]}</ceb:price>
                <ceb:totalPrice>{$list['totalPrice'][$i]}</ceb:totalPrice>
                <ceb:note>{$list['note'][$i]}</ceb:note>
            </ceb:OrderList>
            </ceb:Order>";
        };

        $tail_html="
            <ceb:BaseTransfer>
                <ceb:copCode>{$tail['copCode']}</ceb:copCode>
                <ceb:copName>{$tail['copName']}</ceb:copName>
                <ceb:dxpMode>{$tail['dxpMode']}</ceb:dxpMode>
                <ceb:dxpId>{$tail['dxpId']}</ceb:dxpId>
                <ceb:note>{$tail['note']}</ceb:note>
            </ceb:BaseTransfer>
        ";


        $header='<?xml version="1.0" encoding="UTF-8"?>
            <ceb:CEB303Message guid="311af125-6fed-4603-8c5d-49b1fa4b4b9b" version="1.0"  xmlns:ceb="http://www.chinaport.gov.cn/ceb" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance">';
        $bottom='';
        $html='';
        foreach ($list_html as $n =>$m){
            $html.=$m;
        }

        $m='<ds:Signature xmlns:ds="http://www.w3.org/2000/09/xmldsig#">
            <ds:SignedInfo>
            <ds:CanonicalizationMethod Algorithm="http://www.w3.org/TR/2001/REC-xml-c14n-20010315"></ds:CanonicalizationMethod>
            <ds:SignatureMethod Algorithm="http://www.w3.org/2000/09/xmldsig#rsa-sha1"></ds:SignatureMethod>
            <ds:Reference URI="">
            <ds:Transforms>
            <ds:Transform Algorithm="http://www.w3.org/2000/09/xmldsig#enveloped-signature"></ds:Transform>
            </ds:Transforms>
            <ds:DigestMethod Algorithm="http://www.w3.org/2000/09/xmldsig#sha1"></ds:DigestMethod>
            <ds:DigestValue>SF/P+2sVsRQ9dJIqJSroW6ajb0Y=</ds:DigestValue>
            </ds:Reference>
            </ds:SignedInfo>
            <ds:SignatureValue>
            AI0wdcUHaaOR+FZ7W8lN6FrjzS+iru1qoBTCxm4S6knmLFLunPkLueELV69nYZr4x+uCPnNDD/wq
            jSqyPnH2xrrx8EFvsIhxNMCi+IlfS1z440YmeMEXnhff0pxSBGgrhETrq1tqp6QBZE5siBF4ow10
            0Q9RKaB+OMs4AB6I+0g=
            </ds:SignatureValue>
            <ds:KeyInfo>
            <ds:KeyName>0001</ds:KeyName>
            <ds:X509Data>
            <ds:X509Certificate>
            MIIEWzCCA8SgAwIBAgIDAJknMA0GCSqGSIb3DQEBBQUAMHYxCzAJBgNVBAYTAmNuMREwDwYDVQQK
            Hgh1NVtQU+NcuDENMAsGA1UECx4EAEMAQTENMAsGA1UECB4EUxdOrDEjMCEGA1UEAx4aTi1W/XU1
            W1BT41y4ZXBjbk4tX8NfAFPRUzoxETAPBgNVBAceCE4cZblef1c6MB4XDTE1MDUxOTAwMDAwMFoX
            DTQ5MTIwODAwMDAwMFowJTEjMCEGA1UEAx4aADEAM1P3W8aUpW1Li9VnDVKhVmhfAFPRUzowgZ8w
            DQYJKoZIhvcNAQEBBQADgY0AMIGJAoGBAKoQc7txXMb5VuXJnALpKQ1mAxFW2hxPwRhXY0mirNIL
            2gLY2z7ysqRvkpSRzr3BEq97xWLlXkQDG1QnzvTvh1YQzrffARSlCy6dYJ3YgM+Bps2NsPXhw1Lk
            vk0wn7LXAskEbwRgLihu1pH6/IGEocgeFusWTXT6B/ppTiEXL/87AgMBAAGjggJGMIICQjALBgNV
            HQ8EBAMCBsAwCQYDVR0TBAIwADCBoAYDVR0jBIGYMIGVgBQsaDiQrlh9ryILr2BYMK/wvmRvlqF6
            pHgwdjELMAkGA1UEBhMCY24xETAPBgNVBAoeCHU1W1BT41y4MQ0wCwYDVQQLHgQAQwBBMQ0wCwYD
            VQQIHgRTF06sMSMwIQYDVQQDHhpOLVb9dTVbUFPjXLhlcGNuTi1fw18AU9FTOjERMA8GA1UEBx4I
            ThxluV5/VzqCASUwHQYDVR0OBBYEFPcD90hfpSLKzuhaE3NvhU1xwHDAMEIGA1UdIAQ7MDkwNwYG
            K4EHAQECMC0wKwYIKwYBBQUHAgEWH2h0dHA6Ly9jcHMuY2hpbmFwb3J0Lmdvdi5jbi9DUFMwQgYD
            VR0fBDswOTA3oDWgM4YxaHR0cDovL2xkYXAuY2hpbmFwb3J0Lmdvdi5jbjo4MDg4L2R6a2EwMDAt
            MTk2LmNybDA9BggrBgEFBQcBAQQxMC8wLQYIKwYBBQUHMAGGIWh0dHA6Ly9vY3NwLmNoaW5hcG9y
            dC5nb3YuY246ODA4ODAqBgorBgEEAalDZAUBBBwWGtbQufq159fTv9qwtsr9vt3W0NDEv6q3osf4
            MBoGCisGAQQBqUNkBQYEDBYKUzAyMDEyMDAzODAaBgorBgEEAalDZAUJBAwWClMwMjAxMjAwMzgw
            EgYKKwYBBAGpQ2QCBAQEFgJDQTASBgorBgEEAalDZAIBBAQWAjE5MBMGBSpWCwcFBAoWCLXn19O/
            2rC2MA0GCSqGSIb3DQEBBQUAA4GBAFqdOOqCs/0zfJj5NM3UPXzAK/yIyx6b8ZEQXuY/aojzE46Q
            QXX1/N+G3DsKPvUhXQj1mAsZQeT0aMiUa1aNCd0P8p+PsfrB9E5oZnFhp4cLDkkuh2gx+MCFOHe2
            oEbi2/nCZpvWRJ34id5szTIw1n96/nrrg2+qFk+ddFr0xRzz
            </ds:X509Certificate>
            </ds:X509Data>
            </ds:KeyInfo>
            </ds:Signature>
            </ceb:CEB303Message>';

        $html=$header.$html.$tail_html.$m;

        $base64_html=base64_encode($html);

        session('two',$base64_html);
        session('one',$html);

        return [];
    }

    public function content(){

        $this->assign('one',session('one'));
        $this->assign('two',session('two'));
        return $this->fetch();
    }


}

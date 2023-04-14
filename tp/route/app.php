<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006~2018 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: liu21st <liu21st@gmail.com>
// +----------------------------------------------------------------------
use thans\jwt\facade\JWTAuth;
use think\facade\Route;

Route::get('think', function () {
    return 'hellThinkPHP6!';
});
Route::get('gettoken',function () {
    $builder = JWTAuth::builder(['user_id' => 1]);
    dump($builder);
});
Route::get('php',function (){
    return 'token has gotten';
})->middleware(\thans\jwt\middleware\JWTAuth::class);

//登录接口
Route::post('user/login', 'admin.Index/login');
//登录拿取用户信息
Route::get('user/userinfo','admin.Index/userinfo')->middleware(\thans\jwt\middleware\JWTAuth::class);
//图片上传删除
Route::post('common/uploadpic','common.common/uploadpic');
Route::delete('common/deletePic','common.common/deletePic');




//hardware接口
Route::group('hardware',function (){
    Route::get('category/gettoppingcount','hardware.category/getToppingCount');
    Route::get('category/getcategorylistbypage','hardware.category/getCategoryListByPage');
    Route::get('category/getcategorylist','hardware.category/getCategoryList');
    Route::delete('category/deletecategorybyid','hardware.category/deleteCategoryById');
    Route::put('category/commitupdate','hardware.category/commitUpdate');
    Route::post('category/commitinsert','hardware.category/commitInsert');

    Route::get('product/gettoppingcount','hardware.product/getToppingCount');
    Route::get('product/getproductbypage','hardware.product/getProductByPage');
    Route::put('product/updatestatus','hardware.product/updateStatus');
    Route::put('product/updateproduct','hardware.product/updateProduct');
    Route::get('product/getproductbypage','hardware.product/getProductBypage');
    Route::delete('product/deleteproducts','hardware.product/deleteProducts');
    Route::post('product/insertproductwithpic','hardware.product/insertProductWithPic');
    //车间设备
    Route::get('equipment/getequipmentinfo','hardware.equipment/getEquipmentInfo');
    Route::put('equipment/updateequipment','hardware.equipment/updateEquipment');
})->middleware(\thans\jwt\middleware\JWTAuth::class)->allowCrossDomain(['Authorization']);


Route::group('company',function (){
    Route::get('exhibition/getexhibitionlist','company.exhibition/getExhibitionList');
    Route::put('exhibition/commitupdate','company.exhibition/commitUpdate');
    Route::delete('exhibition/deleteexhibition','company.exhibition/deleteExhibition');
    Route::post('exhibition/insertexhibition','company.exhibition/insertExhibition');
})->middleware(\thans\jwt\middleware\JWTAuth::class)->allowCrossDomain(['Authorization']);

Route::group('mail',function (){
    Route::get('message/getmessagelist','mail.message/getMessageList');

    Route::delete('message/deletemessages','mail.message/deleteMessages');
})->middleware(\thans\jwt\middleware\JWTAuth::class)->allowCrossDomain(['Authorization']);
Route::post('mail/message/insertmessage','mail.message/insertMessage');
Route::group('dashboard',function (){
    Route::get('piclist/getpiclist','dashboard.piclist/getPicList');
    Route::post('piclist/insertpic','dashboard.piclist/insertPic');
    Route::put('piclist/updatepic','dashboard.piclist/updatePic');
    Route::delete('piclist/deletepic','dashboard.piclist/deletePic');
    Route::get('article/getarticleinfo','dashboard.article/getArticleInfo');
    Route::put('article/updatearticle','dashboard.article/updateArticle');
})->middleware(\thans\jwt\middleware\JWTAuth::class)->allowCrossDomain(['Authorization']);

Route::get('common/test/list','common.test/list');
Route::get('common/test/product','common.test/product');
Route::get('common/test/exhibition','common.test/exhibition');
Route::get('common/test/introduce','common.test/introduce');
Route::get('common/getproductbypage','common.common/getProductBypage');
Route::get('common/getproductlistinmobile','common.common/getProductListInMobile');
//website
Route::get('website/about', 'website/about')->middleware(\app\middleware\Checklang::class);
Route::get('website/product', 'website/product')->middleware(\app\middleware\Checklang::class);
Route::get('website/product_detail', 'website/product_detail')->middleware(\app\middleware\Checklang::class);
Route::get('website/exhibition', 'website/exhibition')->middleware(\app\middleware\Checklang::class);
Route::get('website/contact', 'website/contact')->middleware(\app\middleware\Checklang::class);
Route::get('website', 'website/home')->middleware(\app\middleware\Checklang::class);
Route::get('/', 'website/home')->middleware(\app\middleware\Checklang::class);

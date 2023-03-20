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
use think\facade\Route;

Route::get('think', function () {
    return 'hello,ThinkPHP6!';
});
Route::get('gettoken','index/gettoken');
Route::get('php',function (){
    return 'token has gotten';
})->middleware(\thans\jwt\middleware\JWTAuth::class);

//登录接口
Route::post('user/login', 'admin.Index/login');
//登录拿取用户信息
Route::get('user/userinfo','admin.Index/userinfo')->middleware(\thans\jwt\middleware\JWTAuth::class);
Route::post('common/uploadpic','common.common/uploadpic');

//hardware接口
Route::group('hardware',function (){
    Route::get('category/getcategorylistbypage','hardware.category/getCategoryListByPage');
    Route::get('category/getcategorylist','hardware.category/getCategoryList');
    Route::delete('category/deletecategorybyid','hardware.category/deleteCategoryById');
    Route::put('category/commitupdate','hardware.category/commitUpdate');
    Route::post('category/commitinsert','hardware.category/commitInsert');

    Route::get('product/getproductbypage','hardware.product/getProductByPage');
    Route::put('product/updateproduct','hardware.product/updateProduct');
    Route::get('product/getproductbypage','hardware.product/getProductBypage');
    Route::delete('product/deleteproducts','hardware.product/deleteProducts');
    Route::post('product/insertproductwithpic','hardware.product/insertProductWithPic');
    //车间设备
    Route::get('equipment/getequipmentinfo','hardware.equipment/getEquipmentInfo');
    Route::put('equipment/updateequipment','hardware.equipment/updateEquipment');
})->middleware(\thans\jwt\middleware\JWTAuth::class);

Route::group('company',function (){
    Route::get('exhibition/getexhibitionlist','company.exhibition/getExhibitionList');
    Route::put('exhibition/commitupdate','company.exhibition/commitUpdate');
    Route::delete('exhibition/deleteexhibition','company.exhibition/deleteExhibition');
    Route::post('exhibition/insertexhibition','company.exhibition/insertExhibition');
})->middleware(\thans\jwt\middleware\JWTAuth::class);

Route::group('mail',function (){
    Route::get('message/getmessagelist','mail.message/getMessageList');
})->middleware(\thans\jwt\middleware\JWTAuth::class);

Route::get('common/test/list','common.test/list');
Route::get('common/test/product','common.test/product');
Route::get('common/test/exhibition','common.test/exhibition');
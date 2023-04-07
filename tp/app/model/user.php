<?php

namespace app\model;

use think\Model;

/**
 * @mixin \think\Model
 */
class user extends Model
{
    //primarykey 默认‘id’
    protected $pk = 'user_id';
    //对应数据库名称
    protected $table = 'user';
}

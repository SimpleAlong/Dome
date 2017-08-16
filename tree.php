<?php
class tree  
{
 /**
 * 递归无限级分类【先序遍历算】，获取任意节点下所有子孩子
 * @param array $arrCate 待排序的数组
 * @param int $parent_id 父级节点
 * @param int $level 层级数
 * @return array $arrTree 排序后的数组
 */
 

    function getMenuTree($arrCat, $parent_id = 0, $level = 0)
    {
        static  $arrTree = array(); //使用static代替global
        if( empty($arrCat)) return FALSE;
        $level++;
        foreach($arrCat as $key => $value)
        {
            if($value['parent_id' ] == $parent_id)
            {
                $value[ 'level'] = $level;
                $arrTree[] = $value;
                unset($arrCat[$key]); //注销当前节点数据，减少已无用的遍历
                getMenuTree($arrCat, $value[ 'id'], $level);
            }
        }
       
        return $arrTree;
    }

    function make_tree($arr, $parent_id=0){
        $new_arr = array();
        foreach($arr as $k=>$v){
                if($v['parent_id'] == $parent_id){
                    $new_arr[] = $v;
                    unset($arr[$k]);
                }
        }
 
        foreach($new_arr as $key =>&$val){
                $val['children'] = $this->make_tree($arr, $val['id']);
        }
      
        return $new_arr;


    }
    function add_namepre($arr, $prestr='') {
       
            $new_arr = array();
            foreach ($arr as $k=>$v) {
                if ($prestr) {
                    if ($v == end($arr)) {
                        $v['name'] = $prestr.'└─ '.$v['name'];
                    } else {
                        $v['name'] = $prestr.'├─ '.$v['name'];
                    }
                }
 
                if ($prestr == '') {
                    $prestr_for_children = '　 ';
                } else {
                    if ($v == end($arr)) {
                        $prestr_for_children = $prestr.'　　 ';
                    } else {
                        $prestr_for_children = $prestr.'│　 ';
                    }
                }
                $v['children'] = $this->add_namepre($v['children'], $prestr_for_children);
 
                $new_arr[] = $v;
            }
            return $new_arr;
        }

     public function make_options($data,$depth=0,$recursion_count=0, $ancestor_ids=''){
          $recursion_count++;
           $str = '';
          foreach ($data as $k => $v) {
           $str .= "<option value='{$v["id"]}' data-depth='{$recursion_count}' data-ancestor_ids='".ltrim($ancestor_ids,',')."'>{$v['name']}</option>";
          if($v['parent_id'] == 0)
          {
            $recursion_count = 1;
          }
          if($depth==0 || $recursion_count<$depth) {
            $str .= $this->make_options($v['children'], $depth, $recursion_count, $ancestor_ids.','.$v['id']);
          }
          
       } 
       return $str;
     }
}

/**
 * 测试数据
 */
$arrCate = array(  //待排序数组
  array( 'id'=>1, 'name' =>'顶级栏目一', 'parent_id'=>0),
  array( 'id'=>2, 'name' =>'顶级栏目二', 'parent_id'=>0),
  array( 'id'=>3, 'name' =>'栏目三', 'parent_id'=>1),
  array( 'id'=>4, 'name' =>'栏目四', 'parent_id'=>1),
  array( 'id'=>5, 'name' =>'栏目五', 'parent_id'=>1),
  array( 'id'=>6, 'name' =>'栏目六', 'parent_id'=>2),
  array( 'id'=>7, 'name' =>'栏目七', 'parent_id'=>3),
  array( 'id'=>8, 'name' =>'栏目八', 'parent_id'=>3),
  array( 'id'=>9, 'name' =>'栏目九', 'parent_id'=>3),
);
header('Content-type:text/html; charset=utf-8'); //设置utf-8编码
echo '<pre>';
$tree = new tree();
/*
  Array
(
    [0] => Array
        (
            [id] => 1
            [name] => 顶级栏目一
            [parent_id] => 0
            [children] => Array
                (
                    [0] => Array
                        (
                            [id] => 3
                            [name] => 栏目三
                            [parent_id] => 1
                            [children] => Array
                                (
                                    [0] => Array
                                        (
                                            [id] => 7
                                            [name] => 栏目七
                                            [parent_id] => 3
                                            [children] => Array
                                                (
                                                )

                                        )

 */

$new_arr = $tree->make_tree($arrCate,0);
/*
Array
(
    [0] => Array
        (
            [id] => 1
            [name] => 顶级栏目一
            [parent_id] => 0
            [children] => Array
                (
                    [0] => Array
                        (
                            [id] => 3
                            [name] => 　 ├─ 栏目三
                            [parent_id] => 1
                            [children] => Array
                                (
                                    [0] => Array
                                        (
                                            [id] => 7
                                            [name] => 　 │　 ├─ 栏目七
                                            [parent_id] => 3
                                            [children] => Array
                                                (
                                                )

                                        )


 */

$arr = $tree->add_namepre($new_arr);
/*
  顶级栏目一
  　 ├─ 栏目三　
     │　 ├─ 栏目七　 
     │　 ├─ 栏目八　 
     │　 └─ 栏目九　 
     ├─ 栏目四　 
     └─ 栏目五
  顶级栏目二　 
     └─ 栏目六

 */
$get = $tree->make_options($arr,0,0);
print_r($get);

echo '</pre>';

?>
<?php
/*
Plugin Name: wp-hatmore-img
Plugin URI: http://hatmore.com
Description:1: 发送图片地址，直接贴图。2: 去掉评论中的链接。
Version: 0.5
Author: if
Author URI: http://hatmore.com
*/

//替换功能函数
function newcomment($comment) {
    $newcomment = preg_replace_callback('/https?:\/\/([A-z0-9]+[_\-]?[A-z0-9]+\.)*[A-z0-9]+\-?[A-z0-9]+\.[A-z]{2,}(\/.*)*\/?/i', "addimg", $comment);
    return $newcomment;    
}
//验证是url否是图片url,是就加 IMG 标签
function addimg( $url ){
                       
                         $imgurl = preg_replace( ' /((^http[s]{0,1}:)\/\/(.+).(gif|jpg|bmp|png))/i ', '<img src="$1"/>',$url[0]); 
                         //一级处理，处理以gif,jpg,bmp,png为后缀的图片URL
                         if( $imgurl !== $url[0]) {
                                 return  $imgurl;
                           //二级缓处理,处理非常见图片格式以及不到后缀的图片URL
                         } elseif (function_exists('get_headers')){
                
                                    $ct = @get_headers($url[0], 1);
                                    $cts = $ct['Content-Type'];
                
                                    if(is_int(stripos($cts, "image"))){ 
                                            return '<img src="' . $url[0] . '" data-type="undefined" />';                                 
                                    } else {return var_dump($cts);}
                           //三级缓处理    
                         } else if ( @getimagesize($url[0]) ) { 
                                 return '<img src="' . $url[0] . '" data-type="undefined" />'; 
                         }

                         }


//add_filter( 'comment_text', 'newcomment' );//文章评论页
add_filter( 'get_comment_text', 'newcomment' );//系统自带工具评论显示图片
remove_filter('comment_text', 'make_clickable', 9);
?>
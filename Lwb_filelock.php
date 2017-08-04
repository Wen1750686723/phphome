<?php
/**
 * 文件锁处理类库（类库）
 * ============================================================================
 * 版权所有 2017 文搏，并保留所有权利。
 * 网站地址: http://www.widerwill.com；
 * ----------------------------------------------------------------------------
 * ============================================================================
 * $Author: liuwenbohhh $
 * $Id: Lwb_filelock.php 17155 2017-02-06 06:29:05Z $
        /*
*lock_thisfile：获得独享锁
*@param $tmpFileStr 用来作为共享锁文件的文件名（可以随便起一个名字）
*@param $locktype 锁类型，缺省为false(非阻塞型，也就是一旦加锁失败则直接返回false),设置为true则会一直等待加锁成功才返回
*@return 如果加锁成功，则返回锁实例(当使用unlock_thisfile方法的时候需要这个参数)，加锁失败则返回false.
$tmpFileStr = "/tmp/mylock.loc";
// 等待取得操作权限,如果要立即返回则把第二个参数设为false.
$lockhandle = lock_thisfile($tmpFileStr,true);
if($lockhandle){
    // 在这里进行所有需要独占的事务处理。
    // ... ...
    // 事务处理完毕。
    unlock_thisfile($lockhandle,$tmpFileStr);
}
*/
function lock_thisfile($tmpFileStr,$locktype=false){
    if($locktype == false)
        $locktype = LOCK_EX|LOCK_NB;
    $can_write = 0;
    $lockfp = @fopen($tmpFileStr.".lock","w");
    if($lockfp){
        $can_write = @flock($lockfp,$locktype);
    }
    if($can_write){
        return $lockfp;
    }
    else{
        if($lockfp){
            @fclose($lockfp);
            @unlink($tmpFileStr.".lock");
        }
        return false;
    }
}
/** 
*unlock_thisfile：对先前取得的锁实例进行解锁
*@param $fp lock_thisfile方法的返回值
*@param $tmpFileStr 用来作为共享锁文件的文件名（可以随便起一个名字）
*/
function unlock_thisfile($fp,$tmpFileStr){
    @flock($fp,LOCK_UN);
    @fclose($fp);
    @fclose($fp);
    @unlink($tmpFileStr.".lock");
}
?>
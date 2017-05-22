<?php
/**
 * mysql处理类库（类库）
 * ============================================================================
 * 版权所有 2017 文搏，并保留所有权利。
 * 网站地址: http://www.widerwill.com；
 * ----------------------------------------------------------------------------
 * ============================================================================
 * $Author: liuwenbohhh $
 * $Id: Lwb_mysql.php 17155 2017-02-06 06:29:05Z $
 */
class Lwb_mysql{
	private $Host = 'localhost';

    /* 数据库名称 */
    private $dbName = 'myrewrite';

    /* 用户名 */
    private $UserName = 'root';

    /* 连接密码 */
    private $Password = 'root';

    /* 数据库编码 */
    private $dbCharSet = 'utf8';

    /* 错误信息 */
    private $errorMsg;

    /* 最后一次执行的SQL */
    private $lastSql;

    /* 字段信息 */
    private $fields = array();

    /* 最后一次插入的ID */
    public $lastInsID = null;

    /* 数据库连接ID */
    private $linkID = 0;

    /* 当前查询ID */
    private $queryID = null;


    public function __construct($config = array()) {
        if ($config["DBName"] != '')
            $this->dbName = $config["DBName"];
    	}	
        if ($config["Host"] != '')
            $this->Host = $config["Host"];
    	}
    	if ($config["DBName"] != '')
            $this->dbName = $config["DBName"];
    	}
    	if ($config["UserName"] != '')
            $this->UserName = $config["UserName"];
    	}
    	if ($config["Password"] != '')
            $this->Password = $config["Password"];
    	}
    	if ($config["dbCharSet"] != '')
            $this->ddbCharSet = $config["dbCharSet"];
    	}

        $this->connect();
    }

    /**
      +----------------------------------------------------------
     * 连接数据库方法
      +----------------------------------------------------------
     * @access public
      +----------------------------------------------------------
     */
    public function connect() {

        if ($this->linkID == 0) {
            $this->linkID = mysql_connect($this->Host, $this->UserName, $this->Password, true, CLIENT_MULTI_RESULTS);
            if (!$this->linkID) {
                $this->errorMsg = '数据库连接错误\r\n' . mysql_error();
                $this->halt();
            }
        }
        if (!mysql_select_db($this->dbName, $this->linkID)) {
            $this->errorMsg = '打开数据库失败' . mysql_error($this->linkID);
            $this->halt('打开数据库失败');
        }
        $dbVersion = mysql_get_server_info($this->linkID);
        if ($dbVersion >= "4.1") {
            //使用UTF8存取数据库 需要mysql 4.1.0以上支持
            mysql_query("SET NAMES '" . $this->dbCharSet . "'", $this->linkID);
        }
        //设置CharSet
        mysql_query('set character set \'' . $this->dbCharSet . '\'', $this->linkID);
        //设置 sql_model
        if ($dbVersion > '5.0.1') {
            mysql_query("SET sql_mode=''", $this->linkID);
        }
    }

    /**
      +----------------------------------------------------------
     * 释放查询结果
      +----------------------------------------------------------
     * @access public
      +----------------------------------------------------------
     */
    public function free() {
		if($this->queryID != null)
        	mysql_free_result($this->queryID);
        $this->queryID = null;
    }

    /**
      +----------------------------------------------------------
     * 执行语句
      +----------------------------------------------------------
     * @access public
      +----------------------------------------------------------
     * @param string $sql  sql指令
      +----------------------------------------------------------
     * @return bool or resource
      +----------------------------------------------------------
     */
    public function execute($sql) {

        if ($this->linkID == 0)
            $this->connect();
        $this->lastSql = $sql;
        $this->queryID = mysql_query($sql);
        if (false == $this->queryID) {
            $this->errorMsg = 'SQL语句执行失败\r\n' . mysql_error($this->linkID);
            return false;
        } else {
            return $this->queryID;
        }
    }

    /**
      +----------------------------------------------------------
     * 获取记录集的行数
      +----------------------------------------------------------
     * @access public
      +----------------------------------------------------------
     * @param string $sql  sql指令 可为空
     * 如为空：返回上一结果集记录数
     * 如不为空：返回当前sql语句的记录数 
      +----------------------------------------------------------
     * @return integer
      +----------------------------------------------------------
     */
    public function getRowsNum($sql = '') {

        if ($this->linkID == 0) {
            $this->connect();
        }
        if ($sql != '') {
            $this->lastSql = $sql;
            $this->execute($sql);
        }
        return mysql_num_rows($this->queryID);
    }

    /**
      +----------------------------------------------------------
     * 表单数据直接插入到数据表中
      +----------------------------------------------------------
     * @access public
      +----------------------------------------------------------
     * @param string $tableName 数据表名
      +----------------------------------------------------------
     * @return 执行成功返回插入记录的索引记录，失败返回false
      +----------------------------------------------------------
     */
    public function form2db($tableName) {

        $data = $_POST;
        $this->fields = $this->getFields($tableName);
        $data = $this->_facade($data);
        if ($this->insert($tableName, $data)) {
            return $this->lastInsID;
        } else {
            return false;
        }
    }

    /**
      +----------------------------------------------------------
     * 数据直接插入到数据表中
      +----------------------------------------------------------
     * @access public
      +----------------------------------------------------------
     * @param string $tableName 数据表名
      +----------------------------------------------------------
     * @param array $data 插入的数据 数据键名对应字段名，键值对应值
      +----------------------------------------------------------
     * @return boolean
      +----------------------------------------------------------
     */
    public function insert($tableName, $data) {

        $values = $fields = array();
        foreach ($data as $key => $val) {
            $value = '\'' . addslashes($val) . '\'';
            if (is_scalar($value)) { // 过滤非标量数据
                $values[] = $value;
                $fields[] = '`'.$key.'`';
            }
        }
        $sql = 'INSERT INTO ' . trim($tableName) . '(' . implode(',', $fields) . ') VALUES(' . implode(',', $values) . ')';
        $this->lastSql = $sql;
        if ($this->execute($sql)) {
            $this->lastInsID = mysql_insert_id($this->linkID);
            return true;
        } else {
            $this->errorMsg = '插入失败\r\n' . mysql_error($this->linkID);
            return false;
        }
    }

    /**
      +----------------------------------------------------------
     * 更新操作
      +----------------------------------------------------------
     * @access public
      +----------------------------------------------------------
     * @param string $tableName 数据表名
      +----------------------------------------------------------
     * @param array $data 插入的数据 数据键名对应字段名，键值对应值
      +----------------------------------------------------------
     * @param array $condition 更新条件，为安全起见，不能为空
      +----------------------------------------------------------
     * @param array $isForm 可为空，缺省为true
     * 如果为true，会当成表单更新数据表来处理，自动映射字段
     * 如果为false，会当成普通的更新来处理，不会自动映射字段
      +----------------------------------------------------------
     * @return boolean
      +----------------------------------------------------------
     */
    public function update($tableName, $data, $condition, $isForm = true) {
        if (empty($condition)) {
            $this->errorMsg = '没有设置更新条件';
            return false;
        }
        if ($isForm) {
            $this->fields = $this->getFields($tableName);
            $data = $this->_facade($data);
        }
        $sql = 'UPDATE ' . trim($tableName) . ' SET ';
        foreach ($data as $key => $val) {
            $sql .= '`'.$key.'`' . '=\'' . $val . '\',';
        }
        $sql = substr($sql, 0, strlen($sql) - 1);
        $sql .= ' WHERE ' . $condition;
        if ($this->execute($sql)) {
            return true;
        } else {
            $this->errorMsg = '更新失败\r\n' . mysql_error($this->linkID);
            return false;
        }
    }

    /**
      +----------------------------------------------------------
     *  删除操作
      +----------------------------------------------------------
     * @access public
      +----------------------------------------------------------
     * @param string $tableName 数据表名
      +----------------------------------------------------------
     * @param array $condition 更新条件，为安全起见，不能为空
      +----------------------------------------------------------
     * @return boolean
      +----------------------------------------------------------
     */
    public function delete($tableName, $condition = '') {

        if (empty($condition)) {
            $this->errorMsg = '没有设置条件';
            return false;
        }
        $sql = 'delete from ' . $tableName . ' where 1=1 and ' . $condition;
        if (!$this->execute($sql))
            return false;
        return true;
    }
    
    /**
     +----------------------------------------------------------
     * 利用__call魔术方法实现一些特殊的Model方法
     +----------------------------------------------------------
     * @access public
     +----------------------------------------------------------
     * @param string $method 方法名称
     * @param array $args 调用参数
     +----------------------------------------------------------
     * @return mixed
     +----------------------------------------------------------
     */
    public function __call($method,$args){
        
        /*根据某个字段获取记录字段的值
         * 例1：getFieldByid(student_info,100,name)---获取学生表中id为100的学生姓名
         * 例2：getFieldByxh(student_info,201215030223,address)---获取学生表中学号为201015030223的地址
         * 注："getFieldBy"不区分大小写，后面的字段名区分大小写
		 * 返回值：string
         */
        if(strtolower(substr($method,0,10)) == 'getfieldby'){
            $name = substr($method,10);
            $sql = 'select `'.$args[2].'` from '.$args[0].' where '.$name.'=\''.$args[1].'\'';
			if($this->execute($sql)){
            	$row = mysql_fetch_array($this->queryID);
            	return $row[0];
			}else{
				return false;
			}
        }
		 /*根据某个字段和值获取某条记录
         * 例1：getByid(student_info,100)---获取学生表中id为100的学生信息
         * 例2：getByxh(student_info,201215030223)---获取学生表中学号为201015030223的学生信息
         * 注："getBy"不区分大小写，后面的字段名区分大小写
		 * 返回值：array
         */
		elseif(strtolower(substr($method,0,5)) == 'getby'){
			$ret = array();
			$name = substr($method,5);
			$sql = 'select * from '.$args[0].' where '.$name.'=\''.$args[1].'\'';
			if($this->execute($sql)){
				$row = mysql_fetch_array($this->queryID);
				return $row;
			}else{
				return false;
			}
		}
    }

    /**
      +----------------------------------------------------------
     *  弹出错误提示，并终止运行
      +----------------------------------------------------------
     * @access public
      +----------------------------------------------------------
     * @param string $msg 错误消息，可为空
      +----------------------------------------------------------
     */
    public function halt($msg = '') {
        if ($msg != '') {
            $msg .= '\r\n';
        }
        $msg .= mysql_error($this->linkID);
        die($msg);
    }

    /**
      +----------------------------------------------------------
     *  获取最后一次查询ID
      +----------------------------------------------------------
     * @access public
      +----------------------------------------------------------
     */
	 public function getQueryId(){
		 return $this->queryID;
	 }
	 
	 /**
      +----------------------------------------------------------
     *  获取最后一次数据库操作错误信息
      +----------------------------------------------------------
     * @access public
      +----------------------------------------------------------
     */
    public function getLastError() {

        return $this->errorMsg;
    }

    /**
      +----------------------------------------------------------
     *  获取最后一次执行的SQL语句
      +----------------------------------------------------------
     * @access public
      +----------------------------------------------------------
     */
    public function getLastSql() {

        return $this->lastSql;
    }

    /**
      +----------------------------------------------------------
     *  获取最后一次插入数据库记录的索引ID号
      +----------------------------------------------------------
     * @access public
      +----------------------------------------------------------
     */
    public function getLastInsID() {
        return $this->lastInsID;
    }

    /**
      +----------------------------------------------------------
     *  获取上一次操作影响的行数
      +----------------------------------------------------------
     * @access public
      +----------------------------------------------------------
     */
    public function getAffectedRows() {
        return mysql_affected_rows($this->linkID);
    }

    /**
      +----------------------------------------------------------
     * 取得数据表的字段信息
      +----------------------------------------------------------
     * @access public
      +----------------------------------------------------------
     */
    public function getFields($tableName) {
        $result = array();
        $this->execute('SHOW COLUMNS FROM ' . $this->parseKey($tableName));
        while ($row = mysql_fetch_array($this->queryID)) {
            $result[] = $row;
        }
        $info = array();
        if ($result) {
            foreach ($result as $key => $val) {
                $info[$val['Field']] = array(
                    'name' => $val['Field'],
                    'type' => $val['Type'],
                    'notnull' => (bool) ($val['Null'] === ''), // not null is empty, null is yes
                    'default' => $val['Default'],
                    'primary' => (strtolower($val['Key']) == 'pri'),
                    'autoinc' => (strtolower($val['Extra']) == 'auto_increment'),
                );
            }
        }
        return $info;
    }

    /**
      +----------------------------------------------------------
     * 字段和表名处理添加`
      +----------------------------------------------------------
     * @access protected
      +----------------------------------------------------------
     * @param string $key
      +----------------------------------------------------------
     * @return string
      +----------------------------------------------------------
     */
    protected function parseKey(&$key) {
        $key = trim($key);
        if (false !== strpos($key, ' ') || false !== strpos($key, ',') || false !== strpos($key, '*') || false !== strpos($key, '(') || false !== strpos($key, '.') || false !== strpos($key, '`')) {
            //如果包含* 或者 使用了sql方法 则不作处理
        } else {
            $key = '`' . $key . '`';
        }
        return $key;
    }

    /**
      +----------------------------------------------------------
     * 对保存到数据库的数据进行处理
      +----------------------------------------------------------
     * @access protected
      +----------------------------------------------------------
     * @param mixed $data 要操作的数据
      +----------------------------------------------------------
     * @return boolean
      +----------------------------------------------------------
     */
    private function _facade($data) {
        // 检查非数据字段
        if (!empty($this->fields)) {
            foreach ($data as $key => $val) {
                if (!array_key_exists($key, $this->fields)) {
                    unset($data[$key]);
                }
            }
        }
        return $data;
    }
	
	public function close(){
		mysql_close($this->linkID);
	}
	
	public function __destruct(){
		$this->close();
		
	}

}
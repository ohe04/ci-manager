<?php

/**
 * 使用於工作或API一般回傳結果
 * @author webber
 */
class TaskResult {

    /**
     * @var bool 是否執行成功
     */
    public $IsSuccess;

    /**
     * @var string 結果訊息
     */
    public $Message;

    /**
     * @var mixed 結果值
     */
    public $Value;

    public function __construct($is_success = false, $msg = "") {
        $this->IsSuccess = $is_success;
        $this->Message = $msg;
    }

}

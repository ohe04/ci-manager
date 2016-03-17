<?php

/**
 * Description of Pagination
 *
 * @author webber
 */
class Pagination {
    
/**
     * 建立分頁功能
     * @param int $TotalItems 總資料筆數
     * @param int $CurrentPage 目前頁數
     * @param int $CountPerPage 每頁筆數
     */
    function __construct($TotalItems, $CurrentPage, $CountPerPage) {
        $this->TotalItems = $TotalItems;
        $this->CurrentPage = $CurrentPage;
        $this->CountPerPage = $CountPerPage;
    }
 
    private $TotalItems;
    private $CurrentPage;
    private $CountPerPage;
 
    /**
     * 取得建立分頁功能模組Html
     * @param string $linkBase 尚未加入page=xx的網址
     */
    public function ShowModule($linkBase, $preFix = '&') {
        $totalPage = $this->GetTotalPages();
        $hasPrepage = $this->HasPrevPage();
        $startPage = $this->GetStartPage();
        $endPage = $this->GetEndPage();
 
        if ($totalPage > 1) {
            ?>
            <div class="pagination pagination-centered">
                <ul>
                    <?php
                    if ($hasPrepage) {
                        $link = $linkBase . $preFix . "page=" . $this->GetPrevPage();
                        echo "<li><a href='{$link}'>上一頁</a></li>";
                    }
                    for ($i = $startPage; $i <= $endPage; $i++) {
                        if ($i == $this->CurrentPage) {
                            echo "<li class='active'><a>{$i}</a></li>";
                        } else {
                            $link = $linkBase . $preFix . "page=" . $i;
                            echo "<li class='active'><a href='{$link}'>{$i}</a></li>";
                        }
                    }
                    if ($this->HasNextPage()) {
                        $link = $linkBase . $preFix . "page=" . $this->GetNextPage();
                        echo "<li><a href='{$link}'>下一頁</a></li>";
                    }
                    ?>
                </ul>
            </div>
            <?php
        }
    }
 
    public function GetTotalItems() {
        return $this->TotalItems;
    }
 
    public function GetCurrentPage() {
        return $this->CurrentPage;
    }
 
    public function GetCountPerPage() {
        return $this->CountPerPage;
    }
 
    public function GetStartPage() {
        $result1 = floor(($this->CurrentPage - 1) / 10.0);
        $result = $result1 * 10 + 1;
        return intval($result);
    }
 
    /**
     * 取得分頁組最後一頁
     * @return int 頁數
     */
    public function GetEndPage() {
 
        $result = $this->GetStartPage() + 10 - 1;
        $totalPage = $this->GetTotalPages();
        if ($result > $totalPage) {
            $result = $totalPage;
        }
        return $result;
    }
 
    /**
     * 取得上十頁（的第一頁）
     * @return int 頁數
     */
    public function GetPrevTenPage() {
        //return (this.StartPage - 10) < 1 ? 1 : this.StartPage - 10;
        $startPage = $this->GetStartPage();
 
        $result = ($startPage - 10) < 1 ? 1 : $startPage - 10;
        return $result;
    }
 
    /**
     * 是否有上十頁
     * @return boolean 判斷結果
     */
    public function HasPrevTenPages() {
        return $this->GetStartPage() > 10;
    }
 
    /**
     * 取得上一頁
     * @return int 頁數
     */
    public function GetPrevPage() {
        return ($this->CurrentPage - 1) < 1 ? 1 : $this->CurrentPage - 1;
    }
 
    /**
     * 是否有上一頁
     * @return boolean 判斷結果
     */
    public function HasPrevPage() {
        return $this->CurrentPage > 1;
    }
 
    /**
     * 是否有下十頁
     * @return boolean 判斷結果
     */
    public function HasNextTenPages() {
        return $this->GetTotalPages() > $this->GetEndPage();
    }
 
    /**
     * 取得下十頁（的第一頁）
     * @return int 頁數
     */
    public function GetNextTenPage() {
        $endPage = $this->GetEndPage();
        $totalPages = $this->GetTotalPages();
        return ( ($endPage + 1) > $totalPages) ? $totalPages : ($endPage + 1);
    }
 
    /**
     * 是否有下一頁
     * @return boolean 判斷結果
     */
    public function HasNextPage() {
        if ($this->GetTotalPages() > $this->CurrentPage) {
            return true;
        } else {
            return false;
        }
    }
 
    /**
     * 取得下一頁
     * @return int 頁數
     */
    public function GetNextPage() {
        $totalPage = $this->GetTotalPages();
        $result1 = ($this->CurrentPage + 1) > $totalPage;
 
        return $result1 ? $totalPage : ($this->CurrentPage + 1);
    }
 
    /**
     * 起始筆數（假設第一頁則為1第二頁則為11）
     * @return int 第幾筆
     */
    public function GetStartIndex() {
        if ($this->TotalItems == 0) {
            return 0;
        } else {
            return $this->GetSkipCount() + 1;
        }
    }
 
    /**
     * 結尾筆數（假設第一頁為10第二頁則為20）
     * @return int 第幾筆
     */
    public function GetEndIndex() {
        $totalPage = $this->GetTotalPages();
        $result = $this->GetStartIndex() + $this->CountPerPage - 1;
        if ($result > $totalPage) {
            $result = $totalPage;
        }
        return $result;
    }
 
    public function GetTotalPages() {
        $result1 = ceil($this->TotalItems / floatval($this->CountPerPage));
 
        $pages = intval($result1);
        return ($pages < 1 ? 1 : $pages);
    }
 
    /**
     * 需跳過的資料數（若每頁十筆時 第一頁則為0，第二頁則為10）
     * @return int 跳過數量
     */
    public function GetSkipCount() {
        $totalPage = $this->GetTotalPages();
        if ($this->CurrentPage > $totalPage) {
            $this->CurrentPage = $totalPage;
        }
        return $this->CountPerPage * ($this->CurrentPage - 1);
    }
 
    /**
     * Ajax分頁時需跳過的資料數（若每頁十筆時 第一頁則為0，第二頁則為10 若超過Totalpage將不會自動調整）
     * @return int 跳過數量
     */
    public function GetSkipCountAjaxPage() {
        return $this->CountPerPage * ($this->CurrentPage - 1);
    }
    
}

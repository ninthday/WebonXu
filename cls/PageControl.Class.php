<?php

/**
 * Description of PageControl
 *
 * @author Jeffy_shih
 */
class PageControl {

    private $intNowPage;
    private $intTotalRow;
    private $intPagesize;

    function __construct($intNowPage = 1, $intPagesize = 10, $intTotalRow) {
        $this->intNowPage = $intNowPage;
        $this->intPagesize = $intPagesize;
        $this->intTotalRow = $intTotalRow;
    }

    public function setNowPage($intPage) {
        $this->intNowPage = $intPage;
    }

    public function getBeginRow() {
        $intBegin = ($this->intNowPage - 1) * $this->intPagesize;
        return $intBegin;
    }

    public function getPerPageSize() {
        return $this->intPagesize;
    }

    public function getPagelist() {
        $aryPageList = array();
        //Total Pages number
        $intTotalpage = ceil($this->intTotalRow / $this->intPagesize);
        //輸出頁數起使和結束的判斷
        if ($intTotalpage <= 10) {
            $list_first = 1;
            $list_last = $intTotalpage;
        } else {
            if ($this->intNowPage + 7 > $intTotalpage) {
                $list_first = $intTotalpage - 9;
                $list_last = $intTotalpage;
            } else {
                if ($this->intNowPage <= 3) {
                    $list_first = 1;
                    $list_last = 10;
                } else {
                    $list_first = $this->intNowPage - 2;
                    $list_last = $this->intNowPage + 7;
                }
            }
        }

        //判斷回到第一頁符號要不要出現
        if ($this->intNowPage != 1 && $intTotalpage > 10) {
            array_push($aryPageList, array(TRUE, 1));
        } else {
            array_push($aryPageList, array(FALSE, 0));
        }

        //組合頁數
        for ($i = $list_first; $i <= $list_last; $i++) {
            if ($i == $this->intNowPage) {
                array_push($aryPageList, array(FALSE, $i));
            } else {
                array_push($aryPageList, array(TRUE, $i));
            }
        }

        //判斷跳至最後一頁要不要出現
        if ($intTotalpage > 10 && $this->intNowPage != $intTotalpage) {
            array_push($aryPageList, array(TRUE, $intTotalpage));
        } else {
            array_push($aryPageList, array(FALSE, 0));
        }
        
        return $aryPageList;
    }

}

?>

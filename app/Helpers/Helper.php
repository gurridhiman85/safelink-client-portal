<?php
namespace App\Helpers;
use App\Mail\ShareCampaignEmail;
use App\Mail\ShareModelEmail;
use App\Model\CampaignTemplate;
use App\Model\ModelScoreTemplate;
use App\Model\ReportTemplate;
use App\Model\Setting;
use App\Model\UAFieldMapping;
use Auth;
use Intervention\Image\Image;
use DB;
use PDF;
use Session;
use App\User;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use App\Mail\ShareReportEmail;
use Illuminate\Support\Facades\Mail;

class Helper
{
    /**
     * Created By : Gurpreet Singh
     * Purpose : Get dynamic pagination
     *
     * @param $page
     * @param $records
     * @param $total_records
     * @param $title
     * @param $record_per_page
     * @return string
     */
    public static function ajax_pagination($page,$records,$total_records,$title,$record_per_page) {
        $prev = $page - 1;
        $next = $page + 1;
        $adjacents = "2";
        $lastpage = ceil($total_records/$record_per_page);
        $lpm1 = $lastpage - 1;
        $pagination = "";
        if($lastpage > 1)
        {
            $pagination .= "<div class='pagination'>";
            if ($page > 1)
                $pagination.= "<a href=\"#Page=".($prev)."\" onClick='changePagination(".($prev).");'>&laquo; Previous&nbsp;&nbsp;</a>";
            else
                $pagination.= "<span class='disabled'>&laquo; Previous&nbsp;&nbsp;</span>";
            if ($lastpage < 7 + ($adjacents * 2))
            {
                for ($counter = 1; $counter <= $lastpage; $counter++)
                {
                    if ($counter == $page)
                        $pagination.= "<span class='current'>$counter</span>";
                    else
                        $pagination.= "<a href=\"#Page=".($counter)."\" onClick='changePagination(".($counter).");'>$counter</a>";

                }
            }

            elseif($lastpage > 5 + ($adjacents * 2))
            {
                if($page < 1 + ($adjacents * 2))
                {
                    for($counter = 1; $counter < 4 + ($adjacents * 2); $counter++)
                    {
                        if($counter == $page)
                            $pagination.= "<span class='current'>$counter</span>";
                        else
                            $pagination.= "<a href=\"#Page=".($counter)."\" onClick='changePagination(".($counter).");'>$counter</a>";
                    }
                    $pagination.= "...";
                    $pagination.= "<a href=\"#Page=".($lpm1)."\" onClick='changePagination(".($lpm1).");'>$lpm1</a>";
                    $pagination.= "<a href=\"#Page=".($lastpage)."\" onClick='changePagination(".($lastpage).");'>$lastpage</a>";

                }
                elseif($lastpage - ($adjacents * 2) > $page && $page > ($adjacents * 2))
                {
                    $pagination.= "<a href=\"#Page=\"1\"\" onClick='changePagination(1);'>1</a>";
                    $pagination.= "<a href=\"#Page=\"2\"\" onClick='changePagination(2);'>2</a>";
                    $pagination.= "...";
                    for($counter = $page - $adjacents; $counter <= $page + $adjacents; $counter++)
                    {
                        if($counter == $page)
                            $pagination.= "<span class='current'>$counter</span>";
                        else
                            $pagination.= "<a href=\"#Page=".($counter)."\" onClick='changePagination(".($counter).");'>$counter</a>";
                    }
                    $pagination.= "..";
                    $pagination.= "<a href=\"#Page=".($lpm1)."\" onClick='changePagination(".($lpm1).");'>$lpm1</a>";
                    $pagination.= "<a href=\"#Page=".($lastpage)."\" onClick='changePagination(".($lastpage).");'>$lastpage</a>";
                }
                else
                {
                    $pagination.= "<a href=\"#Page=\"1\"\" onClick='changePagination(1);'>1</a>";
                    $pagination.= "<a href=\"#Page=\"2\"\" onClick='changePagination(2);'>2</a>";
                    $pagination.= "..";
                    for($counter = $lastpage - (2 + ($adjacents * 2)); $counter <= $lastpage; $counter++)
                    {
                        if($counter == $page)
                            $pagination.= "<span class='current'>$counter</span>";
                        else
                            $pagination.= "<a href=\"#Page=".($counter)."\" onClick='changePagination(".($counter).");'>$counter</a>";
                    }
                }
            }
            if($page < $counter - 1)
                $pagination.= "<a href=\"#Page=".($next)."\" onClick='changePagination(".($next).");'>Next &raquo;</a>";
            else
                $pagination.= "<span class='disabled'>Next &raquo;</span>";

            $pagination.= "</div>";
            return $pagination;
        }

    }

    /**
     * Created By : Gurpreet Singh
     * Purpose : Get dynamic pagination version 2
     *
     * @param $page
     * @param $position
     * @param $record_per_page
     * @param $records
     * @param $total_records
     * @return string
     */
    public static function ajax_pagination_v2($page,$position,$record_per_page,$records,$total_records){
        $pagination = '';

        /************ Previous button ----- *********************/
        if($page == 1){
            $pagination .= '<a class="paginate_button" aria-controls="taskList" )=""><i class="fa fa-chevron-left"></i></a>';
        }elseif ($page > 1){
            $pagination .= '<a class="paginate_button" aria-controls="taskList" data-idx="'.($page - 1).'" tabindex="'.($page - 1).'" onclick="pagination_v2(this,\'All\')"><i class="fa fa-chevron-left"></i></a>';
        }
        /************ Previous button ----- *********************/

        $pagination .= '<b>'.($position + 1).'</b> - <b>'.($total_records >= $record_per_page ? $record_per_page : $total_records).' of '.$total_records.'</b>';


        /************ Next button ----- *********************/
        if(($total_records) > $record_per_page){
            $pagination .= '<a class="paginate_button" aria-controls="taskList" data-idx="'.($page + 1).'" tabindex="'.($page + 1).'" onclick="pagination_v2(this,\'All\')"><i class="fa fa-chevron-right"></i></a>';
        }else{
            $pagination .= '<a class="paginate_button" aria-controls="taskList" )=""><i class="fa fa-chevron-right"></i></a>';
        }
        /************ Next button ----- *********************/
        return $pagination;
    }

    public static function pagination_v1($total_records,$records_per_page,$page,$type,$position =0,$taskcount = 0){
        $start = ($page - 1) * $records_per_page;
        $prev = $page - 1;
        $next = $page + 1;
        $pagination = "";
        $lastpage = ceil($total_records / $records_per_page);
        if($lastpage > 1){
            $pagination .= "<div class='dataTables_paginate paging_simple_numbers mlst-pgn-poschn' id='taskList_paginate'>";

            if($prev == 0){
                $pagination .= "<a class='paginate_button disabled' style='border: 1px solid #b7dee8;cursor: pointer;' aria-controls='taskList'><i class='fa fa-chevron-left p-1'></i></a>";
            } else {
                $pagination .= "<a class='paginate_button' style='border: 1px solid #b7dee8;cursor: pointer;' aria-controls='taskList' data-idx='".($prev)."' onclick=pagination_v2(this,'$type')><i class='fa fa-chevron-left p-1'></i></a>";
            }
            $nPos = $position + 1;
            if(($taskcount >= $records_per_page) && $position == 0) {
                $pagination .= " <b>" . $nPos . "</b> - <b>" . $records_per_page . " of " . $total_records ."</b>";
            } else if(($taskcount >= $records_per_page) && $position > 0) {
                $recds = $position + $taskcount;
                $pagination .= " <b>" . $nPos. "</b> - <b>" . $recds . " of " . $total_records ."</b>";
            } else {
                $recds = $position + $taskcount;
                $pagination .= " <b>" . $nPos . "</b> - <b>" . $recds . " of " . $total_records ."</b>";
            }

            if($next == ($lastpage + 1)){
                $pagination .= " <a class='paginate_button disabled' style='border: 1px solid #b7dee8;cursor: pointer;' aria-controls='taskList'><i class='fa fa-chevron-right p-1'></i></a>";
            } else {
                $pagination .= " <a class='paginate_button' style='border: 1px solid #b7dee8;cursor: pointer;' aria-controls='taskList' data-idx='".($next)."' tabindex='" .($next). "' onclick=pagination_v2(this,'$type')><i class='fa fa-chevron-right p-1'></i></a>";
            }

            $pagination .="</div>";
        }
        return $pagination;
    }

    public static function pagination_v2($total_records,$records_per_page,$page,$type,$position =0,$taskcount = 0,$funCnt = 2){
        $start = ($page - 1) * $records_per_page;
        $prev = $page - 1;
        $next = $page + 1;
        $pagination = "";
        $lastpage = ceil($total_records / $records_per_page);
        if($lastpage > 1){
            $pagination .= "<div class='dataTables_paginate paging_simple_numbers mlst-pgn-poschn' id='taskList_paginate'>";

            if($prev == 0){
                $pagination .= "<a class='paginate_button disabled mr-1' style='border: 1px solid #b7dee8;cursor: pointer;' aria-controls='taskList'><i class='fa fa-angle-double-left p-1' style='color: #b7dee8;'></i></a>";

                $pagination .= "<a class='paginate_button disabled' style='border: 1px solid #b7dee8;cursor: pointer;' aria-controls='taskList'><i class='fa fa-chevron-left p-1' style='color: #b7dee8;'></i></a>";

            } else {
                $pagination .= "<a class='paginate_button mr-1' style='border: 1px solid #b7dee8;cursor: pointer;' aria-controls='taskList' data-idx='1' onclick=pagination_v".$funCnt."(this,'$type')><i class='fa fa-angle-double-left p-1' style='color: #b7dee8;'></i></a>";

                $pagination .= "<a class='paginate_button' style='border: 1px solid #b7dee8;cursor: pointer;' aria-controls='taskList' data-idx='".($prev)."' onclick=pagination_v".$funCnt."(this,'$type')><i class='fa fa-chevron-left p-1' style='color: #b7dee8;'></i></a>";
            }
            $nPos = $position + 1;
            if(($taskcount >= $records_per_page) && $position == 0) {
                $pagination .= " <b>" . $nPos . "</b> - <b>" . $records_per_page . " of " . $total_records ."</b>";
            } else if(($taskcount >= $records_per_page) && $position > 0) {
                $recds = $position + $taskcount;
                $pagination .= " <b>" . $nPos. "</b> - <b>" . $recds . " of " . $total_records ."</b>";
            } else {
                $recds = $position + $taskcount;
                $pagination .= " <b>" . $nPos . "</b> - <b>" . $recds . " of " . $total_records ."</b>";
            }

            if($next == ($lastpage + 1)){
                $pagination .= " <a class='paginate_button disabled' style='border: 1px solid #b7dee8;cursor: pointer;' aria-controls='taskList'><i class='fa fa-chevron-right p-1' style='color: #b7dee8;'></i></a>";

                $pagination .= " <a class='paginate_button disabled' style='border: 1px solid #b7dee8;cursor: pointer;' aria-controls='taskList'><i class='fa fa-angle-double-right p-1' style='color: #b7dee8;'></i></a>";

            } else {
                $pagination .= " <a class='paginate_button' style='border: 1px solid #b7dee8;cursor: pointer;' aria-controls='taskList' data-idx='".($next)."' tabindex='" .($next). "' onclick=pagination_v".$funCnt."(this,'$type')><i class='fa fa-chevron-right p-1' style='color: #b7dee8;'></i></a>";

                $pagination .= " <a class='paginate_button' style='border: 1px solid #b7dee8;cursor: pointer;' aria-controls='taskList' data-idx='".($lastpage)."' tabindex='" .($lastpage). "' onclick=pagination_v".$funCnt."(this,'$type')><i class='fa fa-angle-double-right p-1' style='color: #b7dee8;'></i></a>";
            }

            $pagination .="</div>";
        }
        return $pagination;
    }

    public static function pagination_v3($total_records,$records_per_page,$page,$type,$position =0,$taskcount = 0,$funCnt = 2){
        $start = ($page - 1) * $records_per_page;
        $prev = $page - 1;
        $next = $page + 1;
        $pagination = "";
        $lastpage = ceil($total_records / $records_per_page);
        if($lastpage > 1){
            $pagination .= "<div class='dataTables_paginate paging_simple_numbers mlst-pgn-poschn' id='taskList_paginate'>";

            if($prev == 0){
                $pagination .= "<a class='paginate_button disabled mr-1' style='border: 1px solid #b7dee8;cursor: pointer;' aria-controls='taskList'><i class='fas fa-angle-double-left p-1' style='color: #b7dee8;'></i></a>";

                $pagination .= "<a class='paginate_button disabled' style='border: 1px solid #b7dee8;cursor: pointer;' aria-controls='taskList'><i class='fa fa-chevron-left p-1' style='color: #b7dee8;'></i></a>";

            } else {
                $pagination .= "<a class='paginate_button mr-1' style='border: 1px solid #b7dee8;cursor: pointer;' aria-controls='taskList' data-idx='1' onclick=pagination_v".$funCnt."(this,'$type')><i class='fas fa-angle-double-left p-1' style='color: #b7dee8;'></i></a>";

                $pagination .= "<a class='paginate_button' style='border: 1px solid #b7dee8;cursor: pointer;' aria-controls='taskList' data-idx='".($prev)."' onclick=pagination_v".$funCnt."(this,'$type')><i class='fa fa-chevron-left p-1' style='color: #b7dee8;'></i></a>";
            }
            $nPos = $position + 1;
            if(($taskcount >= $records_per_page) && $position == 0) {
                $pagination .= " <b>" . $nPos . "</b> - <b>" . $records_per_page ."</b>";
            } else if(($taskcount >= $records_per_page) && $position > 0) {
                $recds = $position + $taskcount;
                $pagination .= " <b>" . $nPos. "</b> - <b>" . $recds ."</b>";
            } else {
                $recds = $position + $taskcount;
                $pagination .= " <b>" . $nPos . "</b> - <b>" . $recds ."</b>";
            }

            if($next == ($lastpage + 1)){
                $pagination .= " <a class='paginate_button disabled' style='border: 1px solid #b7dee8;cursor: pointer;' aria-controls='taskList'><i class='fa fa-chevron-right p-1' style='color: #b7dee8;'></i></a>";

                $pagination .= " <a class='paginate_button disabled' style='border: 1px solid #b7dee8;cursor: pointer;' aria-controls='taskList'><i class='fas fa-angle-double-right p-1' style='color: #b7dee8;'></i></a>";

            } else {
                $pagination .= " <a class='paginate_button' style='border: 1px solid #b7dee8;cursor: pointer;' aria-controls='taskList' data-idx='".($next)."' tabindex='" .($next). "' onclick=pagination_v".$funCnt."(this,'$type')><i class='fa fa-chevron-right p-1' style='color: #b7dee8;'></i></a>";

                $pagination .= " <a class='paginate_button' style='border: 1px solid #b7dee8;cursor: pointer;' aria-controls='taskList' data-idx='".($lastpage)."' tabindex='" .($lastpage). "' onclick=pagination_v".$funCnt."(this,'$type')><i class='fas fa-angle-double-right p-1' style='color: #b7dee8;'></i></a>";
            }

            $pagination .="</div>";
        }
        return $pagination;
    }

    public static function applyFilters($filters, $query, $type)
    {
        $txtSearch = isset($filters['searchterm']) ? $filters['searchterm'][0] : '';
        if ($txtSearch != "") {
            if($type == 'master_products'){
                $query->where('Name', 'LIKE', "%{$txtSearch}%");
                $query->orWhere('Descriptions', 'LIKE', "%{$txtSearch}%");
                $query->orWhere('Price', 'LIKE', "%{$txtSearch}%");
            }

            if($type == 'subscription_terms'){
                $query->where('Name', 'LIKE', "%{$txtSearch}%");
                $query->orWhere('Term', 'LIKE', "%{$txtSearch}%");
            }

            if($type == 'users'){
                $query->where('name', 'LIKE', "%{$txtSearch}%");
                $query->orWhere('email', 'LIKE', "%{$txtSearch}%");
                $query->orWhere('user_type', 'LIKE', "%{$txtSearch}%");
            }

            if($type == 'billings'){
                $query->where('company_name', 'LIKE', "%{$txtSearch}%");
                $query->orWhere('billing_email', 'LIKE', "%{$txtSearch}%");
                $query->orWhere('first_name', 'LIKE', "%{$txtSearch}%");
                $query->orWhere('last_name', 'LIKE', "%{$txtSearch}%");
            }
        }

    }

    public static function formatBytes($bytes, $precision = 2) {
        $units = array('B', 'KB', 'MB', 'GB', 'TB');

        $bytes = max($bytes, 0);
        $pow = floor(($bytes ? log($bytes) : 0) / log(1024));
        $pow = min($pow, count($units) - 1);

        // Uncomment one of the following alternatives
        // $bytes /= pow(1024, $pow);
        // $bytes /= (1 << (10 * $pow));

        return number_format(round($bytes, $precision), 2) . ' ' . $units[$pow];
    }
}
?>

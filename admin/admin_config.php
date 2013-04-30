<?php

require_once ('../config.php');
include('../main_admin.php');

if (isset($_COOKIE['examiner']))
    {
        echo ('
        <nav class="admin_menu">
            <ul>
                <li><a title="'. _ADMIN_HOME. '" href="../admin"><span data-icon="h" class="menu_ico" aria-hidden="true"></span><div class="menu_txt">'. _ADMIN_HOME. '</div></a></li>
                <li><a title="'. _ADMIN_INDEX_ADD_EXAM. '" href="add_test"><span data-icon="a" class="menu_ico" aria-hidden="true"></span><div class="menu_txt">'. _ADMIN_INDEX_ADD_EXAM. '</div></a></li>
                <li><a title="'. _ADMIN_EDIT_EXAMS. '" href="all_tests"><span data-icon="t" class="menu_ico" aria-hidden="true"></span><div class="menu_txt">'. _ADMIN_EDIT_EXAMS. '</div></a></li>
                <li><a title="'. _ADMIN_DEFINE_DEFAULT. '" href="default"><span data-icon="d" class="menu_ico" aria-hidden="true"></span><div class="menu_txt">'. _ADMIN_DEFINE_DEFAULT. '</div></a></li>
                <li><a title="'. _ADMIN_ADD_EDIT_USER. '" href="users"><span data-icon="u" class="menu_ico" aria-hidden="true"></span><div class="menu_txt">'. _ADMIN_ADD_EDIT_USER. '</div></a></li>
                <li><a title="'. _ADMIN_CHARTS. '" href="charts"><span data-icon="c" class="menu_ico" aria-hidden="true"></span><div class="menu_txt">'. _ADMIN_CHARTS. '</div></a></li>
                <li><a title="'. _ADMIN_SETTINGS . '" href="settings"><span data-icon="s" class="menu_ico" aria-hidden="true"></span><div class="menu_txt">'. _ADMIN_SETTINGS. '</div></a></li>
                <li><a title="' ._ALTLOGOUT. '" href="logout"><span data-icon="l" class="menu_ico" aria-hidden="true"></span><div class="menu_txt">'. _ALTLOGOUT. '</div></a></li>
            </ul>
            <a href="#" id="pull" title="Menu"><span data-icon="=" aria-hidden="true"></span></a>
        </nav>
        </header>');
    }
else {
    echo ('</header>');
}
$result_rtl=$db->db_query("select * from settings where id = '1'");
$rtl_array=$db->single();

if ($rtl_array[4] == 1)
    {
    $align="right";
    }
else
    {
    $align="left";
    }

function pagination($per_page = 10, $page = 1, $url = '', $total){

    $adjacents = "2";

    $page = ($page == 0 ? 1 : $page);
    $start = ($page - 1) * $per_page;

    $prev = $page - 1;
    $next = $page + 1;
    $lastpage = ceil($total/$per_page);
    $lpm1 = $lastpage - 1;

    $pagination = "";
    if($lastpage > 1)
    {
        echo ('<div id="pages"><div class="details">Page '.$page.' of '.$lastpage.'</div><ul class="pagination">');
        $pagination .= "";

        if ($lastpage < 7 + ($adjacents * 2))
        {
            for ($counter = 1; $counter <= $lastpage; $counter++)
            {
                if ($counter == $page)
                    $pagination.= '<li id="current_page">'.$counter.'</li>';
                else
                    $pagination.= '<a href="'.$url.$counter.'"><li>'.$counter.'</li></a>';
            }
        }
        elseif($lastpage > 5 + ($adjacents * 2))
        {
            if($page < 1 + ($adjacents * 2))
            {
                for ($counter = 1; $counter < 4 + ($adjacents * 2); $counter++)
                {
                    if ($counter == $page)
                        $pagination.= '<li id="current_page">'.$counter.'</li>';
                    else
                        $pagination.= '<a href="'.$url.$counter.'"><li>'.$counter.'</li></a>';
                }
                $pagination.= "<li class='dot'>...</li>";
                $pagination.= '<a href="'.$url.$lpm1.'"><li class="blast">'.$lpm1.'</li></a>';
                $pagination.= '<a href="'.$url.$lastpage.'"><li class="last">'.$lastpage.'</li></a>';
            }
            elseif($lastpage - ($adjacents * 2) > $page && $page > ($adjacents * 2))
            {
                $pagination.= '<a href="'.$url.'1"><li class="first">1</li></a>';
                $pagination.= '<a href="'.$url.'2"><li class="bfirst">2</li></a>';
                $pagination.= '<li class="bfirst dot">...</li>';
                for ($counter = $page - $adjacents; $counter <= $page + $adjacents; $counter++)
                {
                    if ($counter == $page)
                        $pagination.= '<li id="current_page">'.$counter.'</li>';
                    else
                        $pagination.= '<a href="'.$url.$counter.'"><li>'.$counter.'</li></a>';
                }
                $pagination.= '<li class="blast dot">..</li>';
                $pagination.= '<a href="'.$url.$lpm1.'"><li class="blast">'.$lpm1.'</li></a>';
                $pagination.= '<a href="'.$url.$lastpage.'"><li class="last">'.$lastpage.'</li></a>';
            }
            else
            {
                $pagination.= '<a href="'.$url.'1"><li class="first">1</li></a>';
                $pagination.= '<a href="'.$url.'2"><li class="bfirst">2</li></a>';
                $pagination.= '<li class="bfirst dot">..</li>';
                for ($counter = $lastpage - (2 + ($adjacents * 2)); $counter <= $lastpage; $counter++)
                {
                    if ($counter == $page)
                        $pagination.= '<li id="current_page">'.$counter.'</li>';
                    else
                        $pagination.= '<a href='.$url.$counter.'><li>'.$counter.'</li></a>';
                }
            }
        }

        if ($page < $counter - 1){
            $pagination.= '<a href='.$url.$next.'><li class="next">Next</li></a>';
// $pagination.= "<li><a href='{$url}$lastpage'>Last</a></li>";
        }else{
//$pagination.= "<li><a class='current'>Next</a></li>";
// $pagination.= "<li><a class='current'>Last</a></li>";
        }
        $pagination.= "</ul></div>\n";
    }
    return $pagination;
}

?>
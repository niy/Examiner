<?php

header("Content-Type: text/html; charset=utf-8");

if (!isset($_COOKIE['examiner'])) {
    header('Location: index');
} else {
    include('admin_config.php');

    if (isset($_REQUEST["tid"])) {

        if (isset($_REQUEST["no"])) {
            $tid = $_REQUEST["tid"];
            $edit_test = "UPDATE tests SET Be_Default=0 WHERE id=:tid";
            $pars = array(
                ':tid' => $tid
            );
            $edit_test = $db->db_query($edit_test,$pars);
        } else {
            $tid = $_REQUEST["tid"];
            $sqlstring = "UPDATE tests SET Be_Default='0'";
            $result = $db->db_query($sqlstring);

            $edit_test = "UPDATE tests SET Be_Default=1 WHERE id=:tid";
            $pars = array(
                ':tid' => $tid
            );
            $edit_test = $db->db_query($edit_test,$pars);
        }

    } //else //if(!isset($_REQUEST["tid"]))
    //{

        $ineachpage=12;

        if (!(isset($_REQUEST["p"]))) {
            $start = 0;
            $finish = $start + $ineachpage;
        } else {
            $start = ($_REQUEST["p"]-1) * $ineachpage;
            $finish = $start + $ineachpage;
        }
        $result2 = $db->db_query("SELECT * FROM tests ORDER BY id DESC");
        $num_users = $db->rowCount();

        $result = $db->db_query("SELECT * FROM tests ORDER BY id LIMIT ".$start.", ".$ineachpage);

        echo ('
	        <article id="set_default">
			<div class="clearfix pagehead">
			<h1>' . _ADMIN_DEFINE_DEFAULT . '</h1>
			</div>'
        );

        if (!$rec = $db->single()) {
            echo('
                    <div class="clearfix pagehead">
                        <h1>' . _ADMIN_SHOWW_ALL_EXAMS . '</h1>
                        <a id="add_test_b" class="button good" href="add_test" title="' . _ADMIN_INDEX_ADD_EXAM . '"><span data-icon="a" aria-hidden="true"></span></a>
                        </div><div class="content">
                            <div class="info_box clearfix" >
                                <div class="box_icon" data-icon="y" aria-hidden="true"></div>
                                <div class="content clearfix">
                                ' . _ADMIN_NO_EXAM_FOUND . '
                                </div>
                            </div>
                        </div>
                    </article>');
            include ('../footer.php');
            include('../footer_end.php');
            die();
        }
        echo ('
	    <table class="test_list">
		<thead>
			<tr>
			    <th scope="col" id="is_def"></th>
				<th scope="col" id="name">' . _EXAM_NAME . '</th>
				<th scope="col" id="def">' . _EXAM_BE_DEFAULT . '</th>
			</tr>
		</thead>
		');
        echo ('<tbody>');
        $tr_num = 1;
        $ds="";
        $recs = $db->resultset();
        foreach ($recs as $i => $rec) {
            if ($rec[3] == 1) {
                $d_class="correct_q";
                $d_sign="correct_sign";
                $ds= '<span data-icon="d" aria-hidden="true"></span>';
                $Be_Default =
                    '<a class="bar_icon delete" title="' . _DEFAULT_NOT_BE_BEFAULT . '" href="default?tid=' . $rec[0]. '&no"><span data-icon="v" aria-hidden="true" class="grid_img"></span></a>';
            }
            else {
                $d_class="";
                $d_sign="";
                $ds= '';
                $Be_Default =
                    '<a class="bar_icon add" title="' . _DEFAULT_BE_BEFAULT . '" href="default?tid=' . $rec[0]. '"><span data-icon="d" aria-hidden="true" class="grid_img"></span></a>';
            }
            if ($tr_num % 2 == 0)
                $tr_class = 'even';
            else
                $tr_class = '';
            echo ('
            <tr class="'.$tr_class.' '. $d_class . '">
                <td class="t_f '. $d_sign .'">' . $ds . '</td>
				<td>' . $rec[1] . '</td>
				<td class="def">' . $Be_Default . '</td>
			</tr>');
            $tr_num++;
        }

        echo ('</tbody></table>');

        /**Pagination**/
        $page=1;
        if(isset($_GET['p']) && $_GET['p']!=''){
            $page=$_GET['p'];
        }
        echo pagination($ineachpage,$page,'?p=',$num_users);
        /**</Pagination>**/

        echo ('</article>');
    //}
}
?>

<?php include('../footer.php');
include('../footer_end.php');
?>
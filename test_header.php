<?php
echo ('
    <header id="test_header">
    <h1><div id="floater"></div><a id="logo" class="logo" href="index">Examiner</a></h1>
    ');
if (isset ($_SESSION['examiner_user'])) {
    echo ('
    <nav class="admin_menu">
            <ul>
                <li><a title="' ._EXAM_ENTER_WITH_NEW_USER_NAME. '" href="logout"><span data-icon="l" aria-hidden="true"></span></a></li>
            </ul>
        </nav>
        <div class="cntdwn"><div id="time_icon" data-icon="4" aria-hidden="true" title="' . _EXAM_EXAM_TIME . '"></div><div id="cntdwn" class="content"></div></div>
    ');
}
echo ('</header>');
?>
<?php

echo ('
    <div class="content box">

	<label for="question"><div class="label '. $align .'">' . _ADMIN_ADD_Q_Q . '</div></label>
	<textarea id="question" dir="' . $rtl_input . '" name="question" style="width: 100%; height: 10em"></textarea>
	<div class="label '. $align .'"><a href="javascript:setup();">' . _ADMIN_ADD_EXAM_ENABLE_ALL_EDITOR . '</a></div>

	<input type="radio" value="1" id="a1" name="answer">
	<label for="a1">' . _ADMIN_ADD_CHOICE1 . '</label>
	<textarea id="choice1" dir="' . $rtl_input . '" name="choice1" style="width: 100%; height: 7em"></textarea>

	<input type="radio" value="2" id="a2" name="answer"></td>
	<label for="a2">' . _ADMIN_ADD_CHOICE2 . '</label>
	<textarea id="elm2" dir="' . $rtl_input . '" name="choice2" style="width: 100%; height: 7em"></textarea>

	<input type="radio" value="3" id="a3" name="answer"></td>
	<label for="a3">' . _ADMIN_ADD_CHOICE3 . '</label>
	<textarea id="elm3" dir="' . $rtl_input . '" name="choice3" style="width: 100%; height: 7em"></textarea>

	<input type="radio" value="4" id="a4" name="answer"></td>
	<label for="a4">' . _ADMIN_ADD_CHOICE4 . '</label>
	<textarea id="elm4" dir="' . $rtl_input . '" name="choice4" style="width: 100%; height: 7em"></textarea>

    <div class="button_wrap left">
	<input class="button good" style="float:left; margin-right:1em;" type="submit" value="' . _ADMIN_NEXT_Q . '" />
    <input class="button bad" type=button name=bt1 value="' . _ADMIN_FORM_CANCEL . '" onclick="dosubmit()"/>
    </div>

    </div>
    </article>
');
?>
Type your current password and what you wish your new password to be.
<br/>
<form method="POST" action="">
<table class='formTable'>
<tr><th>Old Password:</th><td style='text-align:right'><input type='password' name='oldpassword' /></td></tr>
<tr><th>New Password:</th><td style='text-align:right'><input type='password' name='newpassword' /></td></tr>
<tr><th>Retype Password:</th><td style='text-align:right'><input type='password' name='retypedpassword' /></td></tr>
<tr><th colspan="2" class="submitRow"><input type='submit' value="Change Password" /></th></tr>
</table>
</form>
<if:leader>
<h2>Reset Password:</h2>
This form will reset the password of the specified user. Their new password will be the same as their username.
<form method="POST" action="">
<table class='formTable'>
<tr>
    <th>User:</th>
    <td style='text-align:right'>
        <select name='userreset'>
            <loop:users>
            <option value="<tag:users[].ID />"><tag:users[].ID /> [<tag:users[].name />]</option>
            </loop:users>
        </select>
    </td>
</tr>
<tr><th colspan="2" class="submitRow"><input type='submit' value="Reset" /></th></tr>
</table>
</form>
</if:leader>

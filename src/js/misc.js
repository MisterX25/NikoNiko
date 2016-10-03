/**
 * Created by Xavier on 04.09.16.
 */
function resetLink_conf()
{
    rl = document.getElementById('resetpwdlnk');
    rl.onclick = function() {
        rf = document.getElementById('resetpwd');
        rf.className = rf.className.replace(/\bhidden\b/,''); // remove hidden class
        return false;
    }
}

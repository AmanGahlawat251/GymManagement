<!DOCTYPE html
    PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
    <title>
        eTimeTrackLite Server Online
    </title>
    <script type="text/javascript">
        window.onload = function () {
            if (top.location.href != self.location.href)
                top.location.href = 'Default.aspx';
        };


        function OpenPopUpWindow() {
            var iMyWidth;
            var iMyHeight;
            iMyWidth = (window.screen.width / 2) - (75 + 10);
            iMyHeight = (window.screen.height / 2) - (100 + 50);
            window.open("ForgotPassword.aspx?", "PopupChild", "status=no,height=150,width=460,resizable=yes,left=" + iMyWidth + ",top=" + iMyHeight + ",screenX=" + iMyWidth + ",screenY=" + iMyHeight + ",toolbar=no,menubar=no,scrollbars=no,location=no,directories=no");
        }
    </script>



    <link href="StyleSheet.css" rel="stylesheet" type="text/css" />
</head>

<body topmargin="0" leftmargin="0">
    <form name="form1" method="post" action="./Default.aspx" id="form1">
        <input type="hidden" name="__VIEWSTATE" id="__VIEWSTATE" value="/wEPDwUKMTA3MjkzNzg3MWRkEYhS9qub1n0uiUA3g9VhN1iyy/tQYeOx181lYIUQdjo=" />


        <script type="text/javascript">
            //<![CDATA[
DisableHistory()//]]>
        </script>

        <input type="hidden" name="__VIEWSTATEGENERATOR" id="__VIEWSTATEGENERATOR" value="AEA5C644" />
        <table cellpadding="0" cellspacing="0">
            <tr style="font-size: 0;">
                <td style="width: 403px; height: 85px;">
                    <img alt="Logo" src="images/logo.gif" border="0" />
                </td>
                <td align="right" width="100%">
                    <img alt="Top Header Image" src="images/tophimg.gif" border="0" />
                </td>
            </tr>
            <tr style="font-size: 0;">
                <td colspan="2" style="background-color: lightsteelblue;">
                    <img alt="Top Header Divider" src="images/header_divider.gif" />
                </td>
            </tr>
            <tr>
                <td colspan="2" align="right">
                    <img alt="Background" src="images/bck1.gif" />
                </td>
            </tr>
        </table>
        <link rel='StyleSheet' media='all' type='text/css' href='/iclock/Styles/mainwindow/blue/blue.css'>

        <!--   obout Dialog v.1.6.0.3  http://www.obout.com   -->

        <div id='StaffloginDialog_container'>
            <div id='StaffloginDialog_content' style='display:none;'>
                <table style="background-color: #F1F2EF; width: 100%;" cellpadding="0" cellspacing="0">
                    <tr style="font-size: 0;">
                        <td>
                            <img src="images/login-header.gif" />
                        </td>
                    </tr>
                    <tr>
                        <td style="padding-left: 30px; padding-top: 20px;" align="left">
                            <table cellpadding="1px" style="width: 350px;">
                                <tr>
                                    <td style="width: 100px;" align="right">
                                        <b>
                                    <span id="StaffloginDialog_Label1">Login Name</span></b>
                                    </td>
                                    <td align="left">
                                        <input name="StaffloginDialog$txt_LoginName" type="text" id="StaffloginDialog_txt_LoginName" onkeydown="return (event.keyCode!=222);" />
                                    </td>
                                </tr>
                                <tr>
                                    <td align="right">
                                        <b>
                                    <span id="StaffloginDialog_Label2">Password</span></b>
                                    </td>
                                    <td align="left">
                                        <input name="StaffloginDialog$Txt_Password" type="password" id="StaffloginDialog_Txt_Password" />
                                    </td>
                                </tr>
                                <tr>
                                    <td align="right" colspan="2">
                                        <table>
                                            <tr>
                                                <td>
                                                    <span id="StaffloginDialog_lbl_InValidError"><font color="Red"></font></span>
                                                </td>
                                                <td align="right">
                                                    <input type="submit" name="StaffloginDialog$Btn_Ok" value="Login" id="StaffloginDialog_Btn_Ok" />&nbsp;&nbsp;&nbsp;
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                </td>
                                                <td align="right">
                                                    <a id="StaffloginDialog_lnkForgotPasswprd"
                                                        href="javascript:__doPostBack(&#39;StaffloginDialog$lnkForgotPasswprd&#39;,&#39;&#39;)">Forgot&nbsp;Password?</a>
                                                    &nbsp;&nbsp;&nbsp;
                                                </td>
                                            </tr>
                                        </table>
                                </tr>
                                <tr>
                                </tr>
                                <tr>
                                </tr>
                                <tr>
                                </tr>
                                <tr>
                                </tr>
                                <tr>
                                </tr>
                                <tr>
                                </tr>
                            </table>
                        </td>
                    </tr>
                </table>
            </div>
        </div>
        <link rel='StyleSheet' media='all' type='text/css' href='/iclock/Styles/mainwindow/blue/blue.css'>

        <!--   obout Dialog v.1.6.0.3  http://www.obout.com   -->

        <div id='wnd_UpdateLicesnseMessageWindow_container'>
            <div id='wnd_UpdateLicesnseMessageWindow_content' style='display:none;'>
                <table>
                    <tr>
                        <td>
                            <fieldset style="width: 280px">
                                <legend>Update Licesnse Key</legend>
                                <table>
                                    <tr>
                                        <td>
                                            Your Evalution period has expired. Please update licesnse key?
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                            <input type="submit" name="wnd_UpdateLicesnseMessageWindow$Btn_LicenseYes" value="Yes" id="wnd_UpdateLicesnseMessageWindow_Btn_LicenseYes" />
                                    &nbsp;<input id="Btn_LicenseNo" type="button" value="No" onclick="javascript:wnd_UpdateLicesnseMessageWindow.Close();" />
                                        </td>
                                    </tr>
                                </table>
                            </fieldset>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <span id="wnd_UpdateLicesnseMessageWindow_lbl_DelError"><font color="Red"></font></span>
                        </td>
                    </tr>
                </table>
            </div>
        </div>&nbsp;
        <link rel='StyleSheet' media='all' type='text/css' href='/iclock/Styles/mainwindow/blue/blue.css'>

        <!--   obout Dialog v.1.6.0.3  http://www.obout.com   -->

        <div id='wnd_EvalutionPeriodMessageWindow_container'>
            <div id='wnd_EvalutionPeriodMessageWindow_content' style='display:none;'>
                <table width="100%" cellpadding="1" class="Table" style="border-top-style: none;
            border-right-style: none; border-left-style: none; border-bottom-style: none;">
                    <tr>
                        <td>
                            <table>
                                <tr>
                                    <td>
                                        <span id="wnd_EvalutionPeriodMessageWindow_lbl_Evalution"></span>
                                    </td>
                                </tr>
                                <tr>
                                    <td align="center">
                                        <input type="submit" name="wnd_EvalutionPeriodMessageWindow$btn_EvalutionOk" value="Ok" id="wnd_EvalutionPeriodMessageWindow_btn_EvalutionOk" />
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <span id="wnd_EvalutionPeriodMessageWindow_Label3"><font color="Red"></font></span>
                        </td>
                    </tr>
                </table>
            </div>
        </div>


        <script type="text/javascript"
            src="/iclock/WebResource.axd?d=yJHU15gMb_s8pHf5uxrJz1mzfsK9UaflCzbUYn7WyGE9b0yDzzA3kgNKfbu1LRAFhRN2pWUVcJExmiuKq8KohGFgIw7YOCMbjQMA8zGbkVXFIho8iUZCEHdlJjqggIEzmJ_8cUMRZyCRcajzj4rvUqKat9lspXAyo0jVzNjjFdU1&t=638458420460000000">
        </script>
        <script type="text/javascript"
            src="/iclock/WebResource.axd?d=VZhakQYnsLV974Dr4e0SJkN47ihlftKFlpt-yVhHWYRvzt5gfYEw6B5YbfS8eldoQ2G74IaKs1CllZx4Nr9BgUZXXBrq2BMh39Tfh3LD8xot6jdjwrkhTXTm9VWfWY2UO7eX-63xuOj0K1caMDAwqyd1Yl9hyiHj0vkIleAkTrE1&t=638458420460000000">
        </script>
        <script type='text/javascript' src='/iclock/Styles/mainwindow/blue/blue.js'></script>
        <script type='text/javascript'>
            bluePreloadImage('/iclock/Styles/mainwindow/blue');
        </script>
        <script type='text/javascript'>
            var StaffloginDialog;function initStaffloginDialog(){try{StaffloginDialog = new Dialog('StaffloginDialog','/iclock/Styles/mainwindow','blue','StaffloginDialog_container','StaffloginDialog_content','Login Window','','SCREEN_CENTER',function(){},function(){},function(){},function(){},function(){},function(){},0,0,420,220,100,25,99,true,true,true,true);}catch(e){alert('obout Window init:'+e.toString());}}oldStaffloginDialogload = window.onload;window.onload=function(evt){if (oldStaffloginDialogload!=null) oldStaffloginDialogload();initStaffloginDialog();}; 
        </script>
        <script type='text/javascript'>
            var wnd_UpdateLicesnseMessageWindow;function initwnd_UpdateLicesnseMessageWindow(){try{wnd_UpdateLicesnseMessageWindow = new Dialog('wnd_UpdateLicesnseMessageWindow','/iclock/Styles/mainwindow','blue','wnd_UpdateLicesnseMessageWindow_container','wnd_UpdateLicesnseMessageWindow_content','Update License Key','','SCREEN_CENTER',function(){},function(){},function(){},function(){},function(){},function(){},0,0,300,125,100,25,99,false,false,true,false);}catch(e){alert('obout Window init:'+e.toString());}}oldwnd_UpdateLicesnseMessageWindowload = window.onload;window.onload=function(evt){if (oldwnd_UpdateLicesnseMessageWindowload!=null) oldwnd_UpdateLicesnseMessageWindowload();initwnd_UpdateLicesnseMessageWindow();}; 
        </script>
        <script type='text/javascript'>
            var wnd_EvalutionPeriodMessageWindow;function initwnd_EvalutionPeriodMessageWindow(){try{wnd_EvalutionPeriodMessageWindow = new Dialog('wnd_EvalutionPeriodMessageWindow','/iclock/Styles/mainwindow','blue','wnd_EvalutionPeriodMessageWindow_container','wnd_EvalutionPeriodMessageWindow_content','eSSL Cloud Service','','SCREEN_CENTER',function(){},function(){},function(){},function(){},function(){},function(){},0,0,300,135,100,25,99,false,true,true,false);}catch(e){alert('obout Window init:'+e.toString());}}oldwnd_EvalutionPeriodMessageWindowload = window.onload;window.onload=function(evt){if (oldwnd_EvalutionPeriodMessageWindowload!=null) oldwnd_EvalutionPeriodMessageWindowload();initwnd_EvalutionPeriodMessageWindow();}; 
        </script>
    </form>
</body>

</html>
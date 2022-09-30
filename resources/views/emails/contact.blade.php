<!DOCTYPE html>
<html lang="en">
    <head>
        <head>
            <title>{{ config('ethiopay.EMAIL.SUBJECT_PRE_TEXT') . 'Contact' }}</title>
        </head>
    </head>
    <body>
        @include('emails.include.header')
            <table align="center">
                <tr>
                    <td align="left"><b>Name : </b></td>
                    <td align="left">{{ $user['name'] }}</td>
                </tr>
                <tr>
                    <td align="left"><b>Email : </b></td>
                    <td align="left">{{ $user['email'] }}</td>
                </tr>
                <tr>
                    <td align="left"><b>Phone Number : </b></td>
                    <td align="left">{{ $user['phone_code'] }}{{ $user['phone_number'] }}</td>
                </tr>
                <tr>
                    <td align="left"><b>Subject : </b></td>
                    <td align="left">{{ $user['subject'] }}</td>
                </tr>
                <tr>
                    <td align="left"><b>Message : </b></td>
                    <td align="left">{{ $user['message'] }}</td>
                </tr>
            </table>

            <div style="padding: 22px 10px;">
                <p style="font-size:12px; color:#484848; font-weight:regular;">Powered by <a href="#" style="font-size:12px; color:#8BA0B3; display: inline-block; text-decoration: none; font-weight: bold;">Enthiopay</a></p>
                <p style="font-size:12px; color:#484848;line-height: 20px;font-weight:regular;">This email has been sent to <a href="" style="font-size:12px; color:#8BA0B3; display: inline-block; text-decoration: none; font-weight: bold;">{{ env('MAIL_FROM_ADDRESS') }}</a>, and it is srictky confidential. Do not forward it.Unsubscribe from mail notification list.</p>
                <p style="font-size:12px; color:#484848; font-weight:regular;">Can’t read this email? <a href="#" style="font-size:12px; color:#8BA0B3; display: inline-block; text-decoration: none;font-weight:bold;">Click here to view online</a>.</p>
            </div>
        </div>
    </div>
    </body>
</html>
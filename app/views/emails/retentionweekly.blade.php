<html>
    <head><title>Retention Weekly</title></head>
    <body>
        <style>
            @import url(http://fonts.googleapis.com/css?family=Source+Sans+Pro:400,700,600);
        </style>
        <div>
            <table width="100%" cellspacing="0" cellpadding="0" border="0" style="background-color:rgb(25,30,29);border-bottom:1px solid rgb(220,218,216)">
                <tbody>
                    <tr>
                        <td width="620" align="center">
                            <table width="620" height="40" cellspacing="0" cellpadding="0" border="0" align="center" style="background-color:rgb(25,30,29)">
                                <tbody>
                                    <tr>
                                        <td width="620" align="center">
                                            <table width="620" cellspacing="0" cellpadding="0" border="0" align="center" style="border-collapse:collapse">
                                                <tbody>
                                                    <tr>
                                                        <td valign="bottom" width="620" height="30" align="center">
                                                            <div style="min-height:10px;font-size:11px;line-height:10px">&nbsp;</div>
                                                            <a target="_blank" title="Visit thenello.com" href="{{ Config::get('app.frontend_url') }}">
                                                                <img width="57" height="23" border="0" src="https://ci4.googleusercontent.com/proxy/Gxnfumwa2i6dsq63Nuz7H00y2hppdzMEzsD0wXOmWQKRf2D4Btz6xwqTwJnQ0KTUVeSUWc_gyI4c1u0NRrxAg73GDLlrCLWiMlY4qg=s0-d-e1-ft#http://pixelative.co/demo/email-assets/nello-logo2.png" alt="nello logo" style="text-decoration:none;display:block;outline:medium none;color:rgb(203,32,39);font-size:20px;margin:0px auto" class="CToWUd">
                                                            </a>
                                                            <div style="min-height:10px;font-size:11px;line-height:10px">&nbsp;</div>
                                                        </td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </td>
                    </tr>
                </tbody>
            </table>
            <table width="100%" cellspacing="0" cellpadding="0" border="0" style="background-color:#f5f3f0">
                <tbody>
                    <tr>
                        <td>
                            <table width="620" cellspacing="0" cellpadding="0" border="0" align="center">
                                <tbody>
                                    <tr>
                                        <td align="center">
                                            <div style="min-height:30px;font-size:30px">&nbsp;</div>
                                            <table width="600" cellspacing="0" cellpadding="0" border="0" style="border-collapse:collapse">
                                                <tbody>
                                                    <tr>
                                                        <td align="center" style="font-family: 'Source Sans Pro', 'Helvetica Neue', Helvetica, Arial, sans-serif;font-size:16px;line-height:22px;color:#666666">
                                                            Hi {{ $name }},
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td height="3" style="font-size:3px;line-height:3px" colspan="1">&nbsp;</td>
                                                    </tr>
                                                </tbody>
                                            </table>


                                            @if(count($highestYieldProperties)>0)
                                            <table width="600" cellspacing="0" cellpadding="0" border="0">
                                                <tbody>
                                                    <tr>
                                                        <td align="center" style="font-family: 'Source Sans Pro', 'Helvetica Neue', Helvetica, Arial, sans-serif;font-size:22px;font-weight:600;color:#818181">
                                                            Here are some high yield properties for you:
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td height="20" style="font-size:20px;line-height:20px" colspan="1">&nbsp;</td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                            <table width="600" cellspacing="0" cellpadding="0" border="0" align="center" style="border-collapse:collapse">
                                                <tbody>
                                                    <tr>
                                                        <td align="center">
                                                            <table cellspacing="0" cellpadding="0" border="0" align="center" style="border-collapse:collapse">
                                                                <tbody>
                                                                    <tr>

                                                                        @foreach($highestYieldProperties as $highYieldProperty)
                                                                        <td valign="top" style="padding-right:15px">
                                                                            <table width="155" cellspacing="0" cellpadding="0" border="0" align="center" style="border-collapse:collapse">
                                                                                <tbody>
                                                                                    <tr>
                                                                                        <td>
                                                                                            <table cellspacing="0" cellpadding="0" border="0" style="border-radius:3px;border:1px solid #d9d9d9">
                                                                                                <tbody>
                                                                                                    <tr>
                                                                                                        <td>
                                                                                                            <a target="_blank" title="Elmsdale Road, Croydon CR0" href="{{ Config::get('app.frontend_url') }}/#!/property/{{ $highYieldProperty->id }}">
                                                                                                                <img width="153" height="102" border="0" style="text-decoration:none;display:block;outline:none;border-top-left-radius:2px;
                                                                                                                     border-top-right-radius:2px;background-color:#f9faf9" src="{{ $highYieldProperty->image !='' ? $highYieldProperty->image : 'assets/images/no-image.jpg' }}" class="CToWUd">
                                                                                                            </a>
                                                                                                        </td>
                                                                                                    </tr>
                                                                                                    <tr>
                                                                                                        <td width="100%" align="left" style="font-size:18px;font-family:helvetica,sans-serif;padding:8px 10px 8px 10px;background-color:#ffffff;border-bottom:1px solid #d9d9d9;border-bottom-left-radius:2px;border-bottom-right-radius:2px">
                                                                                                            <a href="{{ Config::get('app.frontend_url') }}/#!/property/{{ $highYieldProperty->id }}" style="font-family: 'Source Sans Pro', 'Helvetica Neue', Helvetica, Arial, sans-serif; text-decoration:none;font-weight:700;font-size:18px;color:#2A96C2;" target="_blank">&pound;{{ $highYieldProperty->price }}</a>
                                                                                                        </td>                                                                         </td>
                                                                                                    </tr>
                                                                                                    <tr>
                                                                                                        <td width="100%" align="left" style="font-family: 'Source Sans Pro', 'Helvetica Neue', Helvetica, Arial, sans-serif; padding:10px;
                                                                                                            background-color:#ffffff;font-size: 12px">{{ substr(str_replace(',', ', ',$highYieldProperty->address), 0,30) }}</td>
                                                                                                    </tr>
                                                                                                    <tr>
                                                                                                        <td width="153px" align="right" style="border-top:1px solid #d9d9d9;padding:6px 10px 6px 10px;background-color:#ededed">

                                                                                                            <table width="100%" cellspacing="0" cellpadding="0" border="0" style="border-collapse:collapse">
                                                                                                                <tbody>
                                                                                                                    <tr>
                                                                                                                        <td align="left" style="font-size:11px;font-family: 'Source Sans Pro', 'Helvetica Neue', Helvetica, Arial, sans-serif">
                                                                                                                            <div style="font-size:16px; font-weight: 700">
                                                                                                                                &pound;{{ number_format($highYieldProperty->rent, 2, '.',',') }}
                                                                                                                            </div>
                                                                                                                            <div style="font-weight:400;font-size:11px;">
                                                                                                                                Rental Income
                                                                                                                            </div>
                                                                                                                        </td>
                                                                                                                        <td align="right" style="font-size:11px;font-family:'Source Sans Pro', 'Helvetica Neue', Helvetica, Arial, sans-serif">
                                                                                                                            <div style="font-size:16px; font-weight: 700">
                                                                                                                                {{ $highYieldProperty->yield }} %
                                                                                                                            </div>
                                                                                                                            <div style="font-weight:400;font-size:11px;">
                                                                                                                                Yield
                                                                                                                            </div>
                                                                                                                        </td>
                                                                                                                    </tr>
                                                                                                                </tbody>
                                                                                                            </table>
                                                                                                        </td>
                                                                                                    </tr>
                                                                                                </tbody>
                                                                                            </table>
                                                                                        </td>
                                                                                    </tr>
                                                                                    <tr>
                                                                                        <td height="20" style="font-size:20px;line-height:20px" colspan="1">&nbsp;</td>
                                                                                    </tr>
                                                                                </tbody>
                                                                            </table>
                                                                        </td>                                                                
                                                                        @endforeach

                                                                    </tr>
                                                                </tbody>
                                                            </table>
                                                        </td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                            @endif

                                            @if(count($highReductionProperties)>0)
                                            <table width="600" cellspacing="0" cellpadding="0" border="0">
                                                <tbody>

                                                    <tr>
                                                        <td align="center" style="font-family: 'Source Sans Pro', 'Helvetica Neue', Helvetica, Arial, sans-serif;font-size:22px;font-weight:600;color:#818181">
                                                            Here are some properties with high reduction in price:
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td height="20" style="font-size:20px;line-height:20px" colspan="1">&nbsp;</td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                            <table width="600" cellspacing="0" cellpadding="0" border="0" align="center" style="border-collapse:collapse">
                                                <tbody>
                                                    <tr>
                                                        <td align="center">
                                                            <table cellspacing="0" cellpadding="0" border="0" align="center" style="border-collapse:collapse">
                                                                <tbody>
                                                                    <tr>

                                                                        @foreach($highReductionProperties as $highReducedProperty)
                                                                        <td valign="top" style="padding-right:15px">
                                                                            <table width="155" cellspacing="0" cellpadding="0" border="0" align="center" style="border-collapse:collapse">
                                                                                <tbody>
                                                                                    <tr>
                                                                                        <td>
                                                                                            <table cellspacing="0" cellpadding="0" border="0" style="border-radius:3px;border:1px solid #d9d9d9">
                                                                                                <tbody>
                                                                                                    <tr>
                                                                                                        <td>
                                                                                                            <a target="_blank" title="Elmsdale Road, Croydon CR0" href="{{ Config::get('app.frontend_url') }}/#!/property/{{ $highReducedProperty->id }}">
                                                                                                                <img width="153" height="102" border="0" style="text-decoration:none;display:block;outline:none;border-top-left-radius:2px;border-top-right-radius:2px;background-color:#f9faf9" 
                                                                                                                     src="{{ $highReducedProperty->image !='' ? $highReducedProperty->image : 'assets/images/no-image.jpg' }}" class="CToWUd">
                                                                                                            </a>
                                                                                                        </td>
                                                                                                    </tr>
                                                                                                    <tr>
                                                                                                        <td width="100%" align="left" style="font-size:18px;font-family:helvetica,sans-serif;padding:8px 10px 8px 10px;background-color:#ffffff;border-bottom:1px solid #d9d9d9;border-bottom-left-radius:2px;border-bottom-right-radius:2px">
                                                                                                            <a href="{{ Config::get('app.frontend_url') }}/#!/property/{{ $highReducedProperty->id }}" style="font-family: 'Source Sans Pro', 'Helvetica Neue', Helvetica, Arial, sans-serif; text-decoration:none;font-weight:700;font-size:18px;color:#2A96C2;" target="_blank">
                                                                                                                &pound;{{ $highReducedProperty->price }}</a>                                                                         
                                                                                                        </td>
                                                                                                    </tr>
                                                                                                    <tr>
                                                                                                        <td width="100%" align="left" style="font-family: 'Source Sans Pro', 'Helvetica Neue', Helvetica, Arial, sans-serif; padding:10px;background-color:#ffffff;font-size: 12px">
                                                                                                            {{ substr(str_replace(',', ', ', $highReducedProperty->address), 0,30) }}</td>
                                                                                                    </tr>
                                                                                                    <tr>
                                                                                                        <td width="153px" align="right" style="border-top:1px solid #d9d9d9;padding:6px 10px 6px 10px;background-color:#ededed">

                                                                                                            <table width="100%" cellspacing="0" cellpadding="0" border="0" style="border-collapse:collapse">
                                                                                                                <tbody>
                                                                                                                    <tr>
                                                                                                                        <td align="left" style="font-size:11px;font-family: 'Source Sans Pro', 'Helvetica Neue', Helvetica, Arial, sans-serif">
                                                                                                                            <div style="font-size:16px; font-weight: 700">
                                                                                                                                &pound;{{ number_format($highYieldProperty->rent, 2, '.',',') }}
                                                                                                                            </div>
                                                                                                                            <div style="font-weight:400;font-size:11px;">
                                                                                                                                Rental Income
                                                                                                                            </div>
                                                                                                                        </td>
                                                                                                                        <td align="right" style="font-size:11px;font-family:'Source Sans Pro', 'Helvetica Neue', Helvetica, Arial, sans-serif">
                                                                                                                            <div style="font-size:16px; font-weight: 700">
                                                                                                                                {{ number_format($highReducedProperty->yield, 2, '.',',') }} %
                                                                                                                            </div>
                                                                                                                            <div style="font-weight:400;font-size:11px;">
                                                                                                                                Yield
                                                                                                                            </div>
                                                                                                                        </td>
                                                                                                                    </tr>
                                                                                                                </tbody>
                                                                                                            </table>
                                                                                                        </td>
                                                                                                    </tr>
                                                                                                    <tr>
                                                                                                        <td width="100%" align="left" style="font-family: 'Source Sans Pro', 'Helvetica Neue', Helvetica, Arial, sans-serif; padding:10px;background-color:#ffffff;font-size: 13px; font-weight: 700;color:#F44647;border-top:1px solid #d9d9d9;">
                                                                                                            {{ number_format($highReducedProperty->redpercent, 2, '.',',') }}% reduction in price
                                                                                                        </td>
                                                                                                    </tr>
                                                                                                </tbody>
                                                                                            </table>
                                                                                        </td>
                                                                                    </tr>
                                                                                    <tr>
                                                                                        <td height="20" style="font-size:20px;line-height:20px" colspan="1">&nbsp;</td>
                                                                                    </tr>
                                                                                </tbody>
                                                                            </table>
                                                                        </td>
                                                                        @endforeach

                                                                    </tr>
                                                                </tbody>
                                                            </table>
                                                        </td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                            @endif



                                            <table width="600" cellspacing="0" cellpadding="0" border="0" style="border-collapse:collapse">
                                                <tbody>
                                                    <tr>
                                                        <td>
                                                            <table align="center" width="180" cellspacing="0" cellpadding="0" border="0" style="border-collapse:collapse">
                                                                <tbody>
                                                                    <tr>
                                                                        <td align="center" width="180" height="44" style="border-radius:4px;background-color:#F44647;">
                                                                            <a target="_blank" style="height:46px;display:block;text-decoration:none;color:#ffffff;font-size:18px;" href="{{ Config::get('app.frontend_url') }}/#!/search"><img width="280" height="50" style="border:none;outline:none;display:block;text-decoration:none;" alt="Find More Deals" src="http://pixelative.co/demo/email-assets/find-deals.png">
                                                                            </a>
                                                                        </td>
                                                                    </tr>
                                                                </tbody>
                                                            </table>
                                                        </td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                            <div style="min-height:30px;font-size:30px">&nbsp;</div>
                                            <table cellspacing="0" cellpadding="0" border="0" style="border-collapse:collapse">
                                                <tbody>
                                                    <tr>
                                                        <td>
                                                            <table width="100%" cellspacing="0" cellpadding="0" border="0" align="center" style="border-collapse:collapse">
                                                                <tbody>
                                                                    <tr>
                                                                        <td align="center" style="font-family:helvetica neue,helvetica,sans-serif;font-size:11px;color:#999999;line-height:18px;padding:0 50px 0 50px">
                                                                            This email was sent to <a target="_blank" style="color:#979797;text-decoration:none" href="charlesajid@gmail.com">charlesajid@gmail.com</a>.
                                                                            <br> Don't want to receive this type of email? <a target="_blank" style="color:#979797;text-decoration:underline;font-weight:bold" href="mailto:contact@thenello.com?subject=Unsubscribe">Unsubscribe</a>.
                                                                            <br> &copy; nello.
                                                                            <br>
                                                                            <a target="_blank" style="color:#979797;text-decoration:underline;font-weight:bold" href="{{ Config::get('app.frontend_url') }}/#!/pages/privacy_policy">Privacy Policy</a> |
                                                                            <a target="_blank" style="color:#979797;text-decoration:underline;font-weight:bold" href="{{ Config::get('app.frontend_url') }}/#!/pages/terms_of_use">Terms and Conditions</a>
                                                                        </td>
                                                                    </tr>
                                                                </tbody>
                                                            </table>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td height="30" style="font-size:30px;line-height:30px" colspan="1">&nbsp;</td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </body>
</html>
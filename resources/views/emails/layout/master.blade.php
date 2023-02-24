<!DOCTYPE html>
<html>

<head>
    <title>Foodics</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <style>
        @font-face {
            font-family: 'Avenir LT Std';
            src: url('AvenirLTStd-Roman.woff2') format('woff2'),
            url('AvenirLTStd-Roman.woff') format('woff');
            font-weight: normal;
            font-style: normal;
        }

        .action-button {
            background-color: #039f68;
            padding: 15px 25px;
            border-radius: 4px;
            color: #fff;
            text-decoration: none;
        }

        table.transaction-summary {
            border: 1px solid #cccccc;
            border-collapse: collapse;
        }
        table.transaction-summary td {
            border: 1px solid #cccccc;
            text-align: left;
            padding: 4px;
        }
    </style>
</head>

<body style="width: 100% !important; -webkit-text-size-adjust: 100%; overflow-wrap:break-word; word-wrap:break-word;hyphens:auto; background: #efefef !important; -ms-text-size-adjust: 100%; margin: 0;
padding: 0; font-family: 'Avenir LT Std',sans-serif; line-height: 160%; font-size: 16px;color: #222;">

<table width="500px" cellspacing="0" cellpadding="0"  style="margin:0 auto;">
    <tbody>

    <tr>
        <td style="height: 60px; ">
            <div></div>
        </td>
    </tr>
    <tr>
        <td style="background-color:#ffffff;">
            <div style="display: block;	text-align:center; margin: 0px;  padding:20px; padding-top:70px;">
                <img style="width:154px; height:auto;" src="https://www.foodics.com/wp-content/uploads/2021/12/foodics-logo.svg" alt="Foodics Logo">
            </div>
            <div style="-webkit-border:1px solid rgba(112, 112, 112, 22%);
                    -moz-border:1px solid rgba(112, 112, 112, 22%);
                    -o-border:1px solid rgba(112, 112, 112, 22%);
                    -ms-border:1px solid rgba(112, 112, 112, 22%);
                    border:1px solid rgba(112, 112, 112, 22%); width:80%; margin:0 auto;"></div>
        </td>
    </tr>
    <!-- Affix the email body here -->
    @yield('content')

    @include('emails.includes.footer')
    </tbody>
</table>
</body>

</html>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Receipt</title>
    <style>
        * {
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Helvetica, Arial, sans-serif, "Apple Color Emoji", "Segoe UI Emoji", "Segoe UI Symbol";
        }

        html {
            margin: 0 !important;
            padding: 0 !important;
        }

        table td {
            padding-top: 10px;
            padding-left: 10px;
            padding-right: 32px;
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Helvetica, Arial, sans-serif, "Apple Color Emoji", "Segoe UI Emoji", "Segoe UI Symbol" !important;
        }

        .logo-container {
            width: 75px;
            height: 75px;
            border-radius: 50%;
            border: 5px solid #ffffff;
            overflow: hidden;
        }

        .logo-container img {
            width: 100%;
            height: 100%;
        }

        .text-gray {
            color: #374b58;
        }

        .text-dark {
            color: #222222;
        }

        .fw-bold {
            font-weight: bold;
        }

        .light-green-bg {
            background-color: #e3f3ef
        }

        .main-content {
            width: 100%;
        }

        .content-section table tr td {
            padding:10px 20px;
        }

        tr.footer-section {
            width: 100%;
        }

        table.bordered{
            border-top: 2px solid #efefef;
        }

        table.bordered tr td {
            border-bottom: 2px solid #efefef;
        }
        @page {
            margin: 10px 0 !important;
            padding: 10px 0 !important;
        }

        @media print {
            tr.address-block {
                background-color: #e3f3ef;
                -webkit-print-color-adjust: exact;
            }
            tr.content-section table tr:first-child {
                background-color: #035438;
                -webkit-print-color-adjust: exact;
            }
            tr.footer-section {
                width: 100%;
                background-color:#e3f3ef;
                -webkit-print-color-adjust: exact;
            }
            .logo-container {
                overflow: hidden;
            }
        }
    </style>
</head>
<body>

<div class="main-content">
    <table cellpadding="0" cellspacing="0" style="width: 100%;border:none;">
        <tbody>
        <tr class="light-green-bg address-block">
            <td colspan="4">
                <table cellpadding="0" cellspacing="0" style="width:94%; margin-left:auto;margin-right:auto;border:none;">
                    <tbody>
                    <tr>
                        <td style="width:25%;">
                            {{-- business Logo --}}
                            <div class="logo-container">
                                <img src="https://www.foodics.com/wp-content/uploads/2021/12/foodics-logo.svg" alt="Foodics Logo"/>
                            </div>
                        </td>
                        <td></td>
                        <td></td>
                        <td style="width: 25%; text-align:right;padding-bottom:20px;" class="text-gray">
                            <h4 style="font-size: 25px;font-weight:bolder">Receipt</h4>
                            {{-- business name --}}
                            <p>
                                Foodics LTD
                            </p>
                            {{-- business address --}}
                            <p>
                                Foodics | Under the supervision and control of SAMA, and licensed as a Fintech company | Imam Saud Bin Faisal Rd, Riyadh 13515, 2nd floor
                            </p>
                            {{-- phone contact --}}
                            <p>
                                8001000119
                            </p>
                        </td>
                    </tr>
                    </tbody>
                </table>
            </td>
        </tr>

        <!-- Affix the email body here -->
        @yield('content')

        <tr class="footer-section light-green-bg">
            <td colspan="4" style="text-align: center;padding-top:20px;padding-bottom:35px;">
                <img src="https://www.foodics.com/wp-content/uploads/2021/12/foodics-logo.svg" height="50px"  alt="Foodics Logo"/>
            </td>
        </tr>
        </tbody>
    </table>
</div>
</body>
</html>


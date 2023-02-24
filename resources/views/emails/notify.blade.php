@extends('emails.layout.master')

@section('content')

    <tr>
        <td style="background-color:#fff; font-family: 'Avenir LT Std', sans-serif; ">
            <div style="display: block; margin: 0px; padding-top: 20px; background-color:#fff; font-family: 'Avenir LT Std', sans-serif; ">
                <div style="text-align: center;">
                    <h2 style="color: #121212; font-weight: 600; font-size: 1.5rem;">Hello there!</h2>

                    <p style="max-width: 75%; line-height: 1.5; font-weight: 300; margin: 0 auto; color: #121212;text-align: justify">
                        The ingredient <b>{{ $ingredient->name }}</b> has gone below its 50% threshold.
                    </p>
                    <p style="font-weight: bold;color:#039f68;">Ingredient Details</p>
                    <table class="transaction-summary" style="width: 75% !important; -webkit-text-size-adjust: 100%; overflow-wrap:break-word; word-wrap:break-word;hyphens:auto; background: #ffffff !important; -ms-text-size-adjust: 100%; margin: 0 auto;
                    padding: 0; font-family: 'Avenir LT Std',sans-serif; line-height: 160%; font-size: 16px;color: #444;">
                        <tbody>
                        <tr>
                            <td>Ingredient</td>
                            <td>
                                {{ $ingredient->name }}
                            </td>
                        </tr>
                        <tr>
                            <td>Qty. Available</td>
                            <td>{{ $ingredient->available_stock_in_gram }}</td>
                        </tr>
                        <tr>
                            <td>Is Out Of Stock</td>
                            <td>{{ $ingredient->is_out_of_stock ? 'Yes' : 'No' }}</td>
                        </tr>
                        <tr>
                            <td>Threshold</td>
                            <td>{{ $ingredient->threshold_qty }}</td>
                        </tr>
                        </tbody>
                    </table>
                    <p></p>
                </div>
            </div>
        </td>
    </tr>

@endsection

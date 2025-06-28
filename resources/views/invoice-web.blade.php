<!DOCTYPE html>
<html lang="en">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Invoice | Mandraled Studios</title>
</head>

<body>
    <style>
        p {
            margin: 2px 0px 4px 0px;
        }
    </style>
    <div style="width:600pt; margin: 0 auto; font-family:'Poppins', sans-serif; ">
        <table style="width: 100%; margin-bottom: 30pt;">
            <thead>
                <tr>
                    <td>
                        <img width="260" height="64" alt="image"
                            src="https://www.mandraled.com/assets/img/logo.png" style="margin-left: -6px;" />
                    </td>
                </tr>
                <tr>
                    <td>
                        Toovipuram 6th street, <br />
                        Tuticorin
                    </td>
                    <td style="font-weight:bold; text-align:right;">
                        INVOICE
                    </td>
                </tr>
                <tr>
                    <td>
                        Tamil Nadu - 628003
                    </td>
                    <td style="text-align:right;">
                        Invoice Number: {{ $invoice->invoice_number }}
                    </td>
                </tr>
                <tr>
                    <td>
                        <a href="tel:+918217688795">
                            Ph: +91 8217688795
                        </a>
                    </td>
                    <td style="text-align:right;">
                        Date: {{ date('d/M/Y', ($invoice->invoice_date)) }}
                    </td>
                </tr>
                <tr>
                    <td>
                        <a href="mailto:info@mandraled.com">info@mandraled.com</a>
                    </td>
                    <td style="text-align:right;">
                        Due Date: {{ date('d/M/Y', ($invoice->invoice_duedate)) }}
                    </td>
                </tr>
                <tr>
                    <td>

                    </td>
                    <td style="text-align:right;">
                        Invoice Total: <strong> INR <span class="s2" style="font-family: 'Roboto', sans-serif;">₹</span>{{ $invoice->invoice_total }} </strong>
                    </td>
                </tr>
                <tr>
                    <td> BILL TO </td>
                </tr>
                <tr>
                    <td> {{$client->company_name}} </td>
                </tr>
                <tr>
                    @php
                        $state = \App\Models\State::find($client->address->state_id) ?? '';
                    @endphp
                    <td>
                        {{$client->address->building_number}},<br/>
                        {{$client->address->street_address}},<br/>
                        {!!$client->address->location ? $client->address->location.',<br/>' : '' !!}
                        {{$state->name}} <br/>
                    </td>
                    <td style="text-align:right;">
                        Ph: {{$client->phone_number ?? $client->contacts->first()->phone}}
                    </td>
                </tr>
                <tr>
                    <td>
                        Pincode: {{$client->address->zipcode}}<br/>
                        {{$client->gstin ? 'GSTIN:'.$client->gstin : ''}}
                    </td>
                    <td style="text-align:right;">
                        @php
                            $email = $client->email ? $client->email : ($client->contacts->first()->email ? $client->contacts->first()->email : '') ;
                        @endphp
                        <a href="{{$email??''}}"> {{$email}} </a>
                    </td>
                </tr>
            </thead>
            <tbody></tbody>
        </table>
        <table style="width: 100%;" cellspacing="0" cellpadding="5">
            <tr style="width: 100%;">
                <td width="23%" style="border-bottom-style:solid;border-bottom-width:1pt">
                    <p class="s5" style="text-indent: 0pt;line-height: 6pt;text-align: left;">ITEM</p>
                </td>
                <td width="34%" style="border-bottom-style:solid;border-bottom-width:1pt">
                    <p class="s5" style="text-indent: 0pt;line-height: 6pt;text-align: left;">DESCRIPTION</p>
                </td>
                <td width="5%" style="border-bottom-style:solid;border-bottom-width:1pt">
                    <p style="text-indent: 0pt;text-align: left;"><br /></p>
                </td>
                <td width="15%" style="border-bottom-style:solid;border-bottom-width:1pt">
                    <p class="s5" style="padding-right: 15pt;text-indent: 0pt;line-height: 6pt;text-align: right;">
                        PRICE
                    </p>
                </td>
                <td width="8%" style="border-bottom-style:solid;border-bottom-width:1pt">
                    <p class="s5" style="padding-right: 4pt;text-indent: 0pt;line-height: 6pt;text-align: right;">
                        QTY
                    </p>
                </td>
                <td width="15%" style="border-bottom-style:solid;border-bottom-width:1pt">
                    <p class="s5" style="text-indent: 0pt;line-height: 6pt;text-align: right;">AMOUNT</p>
                </td>
            </tr>
            @if (count($line_items) > 0 )
                @foreach ($line_items as $item)
                    @php
                        $product = \App\Models\Product::find($item->product_id);
                        if(! $product) {
                            $product->name = 'Sample Product';
                            $product->description = 'Test';
                        } else {
                            if(!empty($item->description)) {
                                $description = $product->description . "<br/>". $item->description;
                            } else {
                                $description = $product->description;
                            }
                            $quantity = $item->quantity_override ? $item->quantity_override : $product->default_quantity;
                        }
                    @endphp
                    <tr style="width: 100%;">
                        <td
                            style="border-top-style:solid;border-top-width:1pt;border-bottom-style:solid;border-bottom-width:1pt;border-bottom-color:#BEBEBE">
                            <p class="s6" style="padding-top: 5pt;text-indent: 0pt;text-align: left;">{{$product->name}}</p>
                        </td>
                        <td
                            style="border-top-style:solid;border-top-width:1pt;border-bottom-style:solid;border-bottom-width:1pt;border-bottom-color:#BEBEBE">
                            <p class="s7" style="padding-top: 2pt;text-indent: 0pt;text-align: left;">{!!$description!!}
                            </p>
                        </td>
                        <td
                            style="border-top-style:solid;border-top-width:1pt;border-bottom-style:solid;border-bottom-width:1pt;border-bottom-color:#BEBEBE">
                            <p style="text-indent: 0pt;text-align: left;"><br /></p>
                        </td>
                        <td
                            style="border-top-style:solid;border-top-width:1pt;border-bottom-style:solid;border-bottom-width:1pt;border-bottom-color:#BEBEBE">
                            <p class="s5" style="padding-top: 5pt;padding-right: 15pt;text-indent: 0pt;text-align: right;">
                                <span style="font-family: 'Roboto', sans-serif;">₹{{$item->price_override}}</span></p>
                        </td>
                        <td
                            style="border-top-style:solid;border-top-width:1pt;border-bottom-style:solid;border-bottom-width:1pt;border-bottom-color:#BEBEBE">
                            <p class="s7" style="padding-top: 5pt;padding-right: 4pt;text-indent: 0pt;text-align: right;">
                            {{$quantity}}
                            </p>
                        </td>
                        <td
                            style="border-top-style:solid;border-top-width:1pt;border-bottom-style:solid;border-bottom-width:1pt;border-bottom-color:#BEBEBE">
                            <p class="s5" style="padding-top: 5pt;text-indent: 0pt;text-align: right; font-family: 'Roboto', sans-serif;">
                                ₹<span class="s7"> {{$quantity * $item->price_override}} </span></p>
                        </td>
                    </tr>
                @endforeach
            @endif
            
            <tr style="width: 100%;">
                <td style="border-top-style:solid;border-top-width:1pt;border-top-color:#BEBEBE">
                    <p style="text-indent: 0pt;text-align: left;"><br /></p>
                </td>
                <td style="border-top-style:solid;border-top-width:1pt;border-top-color:#BEBEBE">
                    <p style="text-indent: 0pt;text-align: left;"><br /></p>
                </td>
                <td style="border-top-style:solid;border-top-width:1pt;border-top-color:#BEBEBE">
                    <p style="padding-top: 2pt;text-indent: 0pt;text-align: left;"><br /></p>
                    <p class="s5" style="text-indent: 0pt;text-align: left;">SUBTOTAL</p>
                </td>
                <td style="border-top-style:solid;border-top-width:1pt;border-top-color:#BEBEBE">
                    <p style="text-indent: 0pt;text-align: left;"><br /></p>
                </td>
                <td style="border-top-style:solid;border-top-width:1pt;border-top-color:#BEBEBE">
                    <p style="text-indent: 0pt;text-align: left;"><br /></p>
                </td>
                <td style="border-top-style:solid;border-top-width:1pt;border-top-color:#BEBEBE">
                    <p style="text-indent: 0pt;text-align: left;"><br /></p>
                    <p class="s5" style="text-indent: 0pt;text-align: right; font-family: 'Roboto', sans-serif;">₹<span class="s7">{{$invoice->invoice_subtotal}}</span>
                    </p>
                </td>
            </tr>
            <!-- Discount -->
            <tr style="width: 100%;">
                <td style="">
                    <p style="text-indent: 0pt;text-align: left;"><br /></p>
                </td>
                <td style="">
                    <p style="text-indent: 0pt;text-align: left;"><br /></p>
                </td>
                <td style="">
                    <p class="s5" style="padding-top: 1pt;text-indent: 0pt;text-align: left;">DISCOUNT:</p>
                </td>
                <td style="">
                    <p style="text-indent: 0pt;text-align: left;"><br /></p>
                </td>
                <td style="">
                    <p style="text-indent: 0pt;text-align: left;"><br /></p>
                </td>
                <td style="">
                    <p class="s7" style="padding-top: 1pt;text-indent: 0pt;text-align: right; font-family: 'Roboto', sans-serif;">-<span
                            class="s5">₹</span>{{$invoice->discount_value}}</p>
                </td>
            </tr>
            <!-- Round Off -->
            <tr style="width: 100%;">
                <td style="">
                    <p style="text-indent: 0pt;text-align: left;"><br /></p>
                </td>
                <td style="">
                    <p style="text-indent: 0pt;text-align: left;"><br /></p>
                </td>
                <td style="">
                    <p class="s5" style="padding-top: 1pt;text-indent: 0pt;text-align: left;">ROUND OFF:</p>
                </td>
                <td style="">
                    <p style="text-indent: 0pt;text-align: left;"><br /></p>
                </td>
                <td style="">
                    <p style="text-indent: 0pt;text-align: left;"><br /></p>
                </td>
                <td style="">
                    <p class="s7" style="padding-top: 1pt;text-indent: 0pt;text-align: right; font-family: 'Roboto', sans-serif;">
                        {{$invoice->round_off}}
                    </p>
                </td>
            </tr>
            <!-- Tax 1 -->
            @if ($invoice->tax1_label)
            <tr style="width: 100%;">
                <td style="">
                    <p style="text-indent: 0pt;text-align: left;"><br /></p>
                </td>
                <td style="">
                    <p style="text-indent: 0pt;text-align: left;"><br /></p>
                </td>
                <td style="border-bottom-style:solid;border-bottom-width:1pt;border-bottom-color:#BEBEBE">
                    <p class="s5" style="padding-top: 1pt;text-indent: 0pt;text-align: left;">{{$invoice->tax1_label}}</p>
                </td>
                <td style="border-bottom-style:solid;border-bottom-width:1pt;border-bottom-color:#BEBEBE">
                    <p style="text-indent: 0pt;text-align: left;"><br /></p>
                </td>
                <td style="border-bottom-style:solid;border-bottom-width:1pt;border-bottom-color:#BEBEBE">
                    <p style="text-indent: 0pt;text-align: left;"><br /></p>
                </td>
                <td style="border-bottom-style:solid;border-bottom-width:1pt;border-bottom-color:#BEBEBE">
                    <p class="s5" style="padding-top: 1pt;text-indent: 0pt;text-align: right; font-family: 'Roboto', sans-serif;">₹<span
                            class="s7">{{$invoice->tax1_value}}</span></p>
                </td>
            </tr>
            @endif
            <!-- Tax 2 -->
            @if ($invoice->tax2_label)
            <tr style="width: 100%;">
                <td style="">
                    <p style="text-indent: 0pt;text-align: left;"><br /></p>
                </td>
                <td style="">
                    <p style="text-indent: 0pt;text-align: left;"><br /></p>
                </td>
                <td style="border-bottom-style:solid;border-bottom-width:1pt;border-bottom-color:#BEBEBE">
                    <p class="s5" style="padding-top: 1pt;text-indent: 0pt;text-align: left;">{{$invoice->tax2_label}}</p>
                </td>
                <td style="border-bottom-style:solid;border-bottom-width:1pt;border-bottom-color:#BEBEBE">
                    <p style="text-indent: 0pt;text-align: left;"><br /></p>
                </td>
                <td style="border-bottom-style:solid;border-bottom-width:1pt;border-bottom-color:#BEBEBE">
                    <p style="text-indent: 0pt;text-align: left;"><br /></p>
                </td>
                <td style="border-bottom-style:solid;border-bottom-width:1pt;border-bottom-color:#BEBEBE">
                    <p class="s5" style="padding-top: 1pt;text-indent: 0pt;text-align: right; font-family: 'Roboto', sans-serif;">₹<span
                            class="s7">{{$invoice->tax2_value}}</span></p>
                </td>
            </tr>
            @endif
            <tr style="width: 100%;">
                <td style="">
                    <p style="text-indent: 0pt;text-align: left;"><br /></p>
                </td>
                <td style="">
                    <p style="text-indent: 0pt;text-align: left;"><br /></p>
                </td>
                <td
                    style="border-top-style:solid;border-top-width:1pt;border-top-color:#BEBEBE;border-bottom-style:solid;border-bottom-width:1pt;border-bottom-color:#BEBEBE">
                    <p class="s5" style="padding-top: 6pt;text-indent: 0pt;text-align: left;">TOTAL</p>
                </td>
                <td
                    style="border-top-style:solid;border-top-width:1pt;border-top-color:#BEBEBE;border-bottom-style:solid;border-bottom-width:1pt;border-bottom-color:#BEBEBE">
                    <p style="text-indent: 0pt;text-align: left;"><br /></p>
                </td>
                <td
                    style="border-top-style:solid;border-top-width:1pt;border-top-color:#BEBEBE;border-bottom-style:solid;border-bottom-width:1pt;border-bottom-color:#BEBEBE">
                    <p style="text-indent: 0pt;text-align: left;"><br /></p>
                </td>
                <td
                    style="border-top-style:solid;border-top-width:1pt;border-top-color:#BEBEBE;border-bottom-style:solid;border-bottom-width:1pt;border-bottom-color:#BEBEBE">
                    <p class="s5" style="font-weight:bold;padding-top: 5pt;text-indent: 0pt;text-align: right; font-family: 'Roboto', sans-serif;">₹<span
                            class="s7">{{$invoice->invoice_total}}</span></p>
                </td>
            </tr>
        </table>
        <p style="padding-left: 5pt;text-indent: 0pt;text-align: left; margin-top:40pt">Thank you for choosing
            Mandraled Studios.
        </p>
    </div>
</body>

</html>

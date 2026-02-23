@extends('layouts.admin')
<title>{{ $stud->name }}Invoices Details</title>
@section('content')
<div class="container-fluid py-4">
    <div class="row pt-4 p-2">
        <div class="col-12">
            <div class="card my-4">
                <div class="card-header p-0 position-relative mt-n4 mx-3 z-index-2">
                    <div class="bg-gradient-info shadow-primary border-radius-lg pt-4 pb-3">
                        <h4 class="text-white text-capitalize ps-3">Stud Details Table</h4>
                        <h6 class="text-white text-capitalize ps-3">Stud Name:- {{ $stud->name }}</h6>
                    </div>
                </div>
                <div class="card-body px-0 pb-2 m-2">
                    <div class="table-responsive p-0">
                        @php
                                $totalMedInvoicePrice = 0;
                                $totalSupInvoicePrice = 0;
                                @endphp
                            @foreach ($stud->externalinvoices as $invoiceitem)
                                @foreach ($invoiceitem->medexternalinvoices as $item)
                                    @php
                                        $totalPrice = $item->qty * $item->unitprice;
                                        $totalMedInvoicePrice += $totalPrice;
                                    @endphp
                                @endforeach
                                @foreach ($invoiceitem->supexternalinvoices as $item)
                                    @php
                                        $totalPrice = $item->qty * $item->unitprice;
                                        $totalSupInvoicePrice += $totalPrice;
                                    @endphp
                                @endforeach
                            @endforeach
                        <table class="table align-items-center mb-0 text-center">
                            <thead>
                                <tr>
                                    <th style="background-color: #338ded">{{$stud->name}}</th>
                                    <th style="background-color: #338ded">{{$stud->description}}</th>
                                    <th style="background-color: #338ded">{{ $stud->externalinvoices->count()}} Total Invoice</th>
                                </tr>
                                @if ($stud->externalinvoices->count() !== 0)
                                <tr>
                                    <th style="background-color: #338ded">Total Invoice Price: {{ $totalMedInvoicePrice + $totalSupInvoicePrice }}.EGP </th>
                                    <th style="background-color: #338ded">
                                        @if ($stud->externalinvoices->sum('paid') == $totalMedInvoicePrice + $totalSupInvoicePrice)
                                        <span class="text-success">all Paid</span>
                                        @else
                                            Total Invoice Paid: {{ $stud->externalinvoices->sum('paid')}}.EGP
                                        @endif
                                        </th>
                                    <th style="background-color: #338ded">
                                        @if ($stud->externalinvoices->sum('paid') > $totalMedInvoicePrice + $totalSupInvoicePrice)
                                            <span class="text-info">GEC Debit: {{ $stud->externalinvoices->sum('paid') - ($totalMedInvoicePrice + $totalSupInvoicePrice) }}.EGP</span>
                                        @elseif ($stud->externalinvoices->sum('paid') == 0)
                                            <span class="text-danger">UnPaid</span>
                                        @elseif ($stud->externalinvoices->sum('paid') == $totalMedInvoicePrice + $totalSupInvoicePrice)
                                            <span class="text-success">all Paid</span>
                                        @else
                                        Total Invoice UnPaid: {{ ($totalMedInvoicePrice + $totalSupInvoicePrice) - $stud->externalinvoices->sum('paid') }}.EGP
                                        @endif
                                    </th>
                                </tr>
                                @endif
                            </thead>
                        </table>
                        <br>
                    </div>
                </div>
                @if ($externalmedinvoices->count() !== 0)
                    <div class="container-fluid p-4" id="medinvoice">
                        <div class="text-end">
                            <h5>
                                <a href="#supinvoice">
                                    Supplies Invoices ??!
                                </a>
                            </h5>
                        </div>
                        <div class="row mb-4">

                            <div class="table-responsive">
                                <table class="table table-bordered table-striped p-3">
                                    <tr class="text-center">
                                        <th style="background-color: #338ded">
                                            Medical Invoices
                                        </th>
                                        <th style="background-color: #338ded">
                                            Total Medical Invoices Price: {{ $totalMedInvoicePrice }}
                                        </th>
                                    </tr>
                                </table>
                            </div>
                            @foreach ($externalmedinvoices as $invoiceitem)
                                <div class="card-body table-responsive px-0 pb-2 mx-2">
                                        <table class="table table-bordered table-striped p-3">
                                            <thead class="text-center">
                                                <tr>
                                                    <th>Description</th>
                                                    <th>Quantity</th>
                                                    <th>Unit Price</th>
                                                    <th>Total Price</th>
                                                </tr>
                                            </thead>
                                            <tbody class="text-center">
                                                @php
                                                    $totalInvoicePrice = 0;
                                                @endphp
                                                @foreach ($invoiceitem->medexternalinvoices as $item)
                                                @php
                                                    $totalPrice = $item->qty * $item->unitprice;
                                                    $totalInvoicePrice += $totalPrice;
                                                @endphp
                                                <tr>
                                                    <td>{{ $item->description }}</td>
                                                    <td>{{ $item->qty }}</td>
                                                    <td>{{ $item->unitprice }}</td>
                                                    <td>{{ $totalPrice }}</td>
                                                </tr>
                                                @endforeach
                                                <tr>
                                                    <td class="text-end text-Primary" colspan="5">
                                                        Total Invoice Price: {{ $totalInvoicePrice }}.EGP
                                                        @if ($invoiceitem->paid == $totalInvoicePrice)
                                                          /  <span class="text-success">all Paid</span>
                                                    @elseif($invoiceitem->paid > $totalInvoicePrice)
                                                        / Paid: {{ $invoiceitem->paid }}.EGP
                                                        / <span class="text-info"> GEC Debit: {{ $invoiceitem->paid - $totalInvoicePrice}}.EGP</span>
                                                    @elseif($invoiceitem->paid == 0)
                                                          /  <span class="text-danger">UnPaid</span>
                                                        @else
                                                        / Paid: {{ $invoiceitem->paid }}.EGP
                                                        / UnPaid: {{ $totalInvoicePrice - $invoiceitem->paid }}.EGP
                                                        @endif
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                </div>
                            @endforeach
                            <div class="pages text-center">
                                {{ $externalmedinvoices->links() }}
                            </div>
                        </div>
                    </div>
                @endif
                @if ($externalsupinvoices->count() !== 0)
                    <div class="container-fluid p-4" id="supinvoice">
                        <div class="text-end">
                            <h5>
                                <a href="#medinvoice">
                                    Medical Invoices ??!
                                </a>
                            </h5>
                        </div>
                        <div class="row mb-4">
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped p-3">
                                <tr class="text-center">
                                    <th style="background-color: #338ded">
                                        Supplies Invoices
                                    </th>
                                    <th style="background-color: #338ded">
                                        Total Supplies Invoices Price: {{ $totalSupInvoicePrice }}
                                    </th>
                                </tr>
                            </table>
                        </div>
                            <div class="card-body table-responsive px-0 pb-2 m-2">
                                @foreach ($externalsupinvoices as $invoiceitem)
                                    <table class="table table-bordered table-striped p-3">
                                        <thead class="text-center">
                                        <tr>
                                            <th>Description</th>
                                            <th>Quantity</th>
                                            <th>Unit Price</th>
                                            <th>Total Price</th>
                                        </tr>
                                        </thead>
                                        <tbody class="text-center">
                                            @php
                                                $totalInvoicePrice = 0;
                                            @endphp
                                            @foreach ($invoiceitem->supexternalinvoices as $item)
                                            @php
                                                $totalPrice = $item->qty * $item->unitprice;
                                                $totalInvoicePrice += $totalPrice;
                                            @endphp
                                            <tr>
                                                <td>{{ $item->description }}</td>
                                                <td>{{ $item->qty }}</td>
                                                <td>{{ $item->unitprice }}</td>
                                                <td>{{ $totalPrice }}</td>
                                            </tr>
                                            @endforeach
                                            <tr>
                                                <td class="text-end text-Primary" colspan="5">
                                                    Total Invoice Price: {{ $totalInvoicePrice }}.EGP
                                                    @if ($invoiceitem->paid == $totalInvoicePrice)
                                                    /  <span class="text-success">all Paid</span>
                                                    @elseif($invoiceitem->paid > $totalInvoicePrice)
                                                        / Paid: {{ $invoiceitem->paid }}.EGP
                                                        / <span class="text-info"> GEC Debit: {{ $invoiceitem->paid - $totalInvoicePrice}}.EGP</span>
                                                    @elseif($invoiceitem->paid == 0)
                                                        /  <span class="text-danger">UnPaid</span>
                                                    @else
                                                    / Paid: {{ $invoiceitem->paid }}.EGP
                                                    / UnPaid: {{ $totalInvoicePrice - $invoiceitem->paid }}.EGP
                                                    @endif
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                    <hr>
                                @endforeach
                                <div class="pages text-center">
                                    {{ $externalsupinvoices->links() }}
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

@endsection

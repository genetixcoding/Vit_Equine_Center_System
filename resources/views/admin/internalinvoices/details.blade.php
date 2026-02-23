@extends('layouts.admin')
<title>{{ $supplier->name }} Details</title>
@section('content')

<div class="container-fluid py-4">
    <div class="row pt-4 p-2">
        <div class="col-12">
            <div class="card my-4 h-100">
                <div class="card-header p-0 position-relative mt-n4 mx-3 z-index-2">
                    <div class="bg-gradient-info shadow-primary border-radius-lg pt-4 pb-5">
                        <h4 class="text-white text-capitalize pe-3 ps-3">Details Supplier Table</h4>
                        <h6 class="text-white text-capitalize pe-3 ps-3">Name :- {{ $supplier->name }}</h6>
                        <h6 class="text-white text-capitalize pe-3 ps-3">Description :- {{ $supplier->description }}</h6>
                    </div>
                </div>
                <h4 class="text-white text-capitalize mt-2 pe-3 ps-3">
                    <a class="text-primary" href="{{ url('Suppliers') }}" >View Supplier Accounts ??!</a>
                </h4>
            </div>
        </div>
    </div>
</div>

@if ($allmedinternalinvoices->isNotEmpty() || $allsupinternalinvoices->isNotEmpty())
    <div class="accordion my-4" id="internalInvoicesAccordion">
        <div class="accordion-item">
            <h2 class="accordion-header" id="headingInternalInvoices">
                <button class="accordion-button collapsed bg-primary text-white" type="button"
                    data-bs-toggle="collapse" data-bs-target="#collapseInternalInvoices"
                    aria-expanded="false" aria-controls="collapseInternalInvoices">
                    📑 Internal Invoices (MED + SUP)
                </button>
            </h2>
            <div id="collapseInternalInvoices" class="accordion-collapse collapse"
                aria-labelledby="headingInternalInvoices" data-bs-parent="#internalInvoicesAccordion">
                <div class="accordion-body  px-2 py-1">
                    <div class="table-responsive">
                        <table class="table align-items-center mb-0 text-center" style="height: auto;">
                            <thead>
                                @php
                                    $totalMedinternalInvoicePrice = 0;
                                    $totalSupinternalInvoicePrice = 0;
                                    $totalMedinternalInvoice = 0;
                                    $totalSupinternalInvoice = 0;
                                    foreach ($supplier->internalinvoices as $internalinvoiceitem) {
                                        $totalMedinternalInvoice += $internalinvoiceitem->medinternalinvoices->count();
                                        $totalSupinternalInvoice += $internalinvoiceitem->supinternalinvoices->count();
                                        foreach ($internalinvoiceitem->medinternalinvoices as $item) {
                                            $totalMedinternalInvoicePrice += $item->totalprice;
                                        }
                                        foreach ($internalinvoiceitem->supinternalinvoices as $item) {
                                            $totalSupinternalInvoicePrice += $item->totalprice;
                                        }
                                    }
                                    $totalInvoicesCount = $totalMedinternalInvoice + $totalSupinternalInvoice;
                                @endphp
                                <tr>
                                    <th style="background-color: #338ded; color: #FFF;">
                                        Total Invoices: {{ $totalInvoicesCount }}
                                        <br>
                                        Med Invoices ({{ $totalMedinternalInvoice }})
                                        <br>Sup Invoices ({{ $totalSupinternalInvoice }})
                                    </th>
                                     <th style="background-color: #338ded; color: #FFF;">
                                        Total Prices:
                                         {{ $totalMedinternalInvoicePrice + $totalSupinternalInvoicePrice }}.EGP
                                        <br>
                                        Med Price: {{ $totalMedinternalInvoicePrice }}.EGP
                                        <br>
                                        Sup Price: {{ $totalSupinternalInvoicePrice }}.EGP
                                    </th>

                                </tr>
                                <tr>
                                   <th style="background-color: #338ded; color: #FFF;">
                                        Total Paid:
                                        {{ $supplier->internalinvoices->sum('paid') }}.EGP
                                    </th>
                                    <th style="background-color: #338ded; color: #FFF;">
                                        Total Invoices : {{ $supplier->internalinvoices->count() }}
                                        <br>
                                        @if ($supplier->internalinvoices->sum('paid') > $totalMedinternalInvoicePrice + $totalSupinternalInvoicePrice)
                                            <span class="text-info">Credit :
                                                {{ $supplier->internalinvoices->sum('paid') - ($totalMedinternalInvoicePrice + $totalSupinternalInvoicePrice) }}.EGP</span>
                                        @elseif ($supplier->internalinvoices->sum('paid') == 0)
                                            <span class="text-danger">Unpaid</span>
                                        @elseif ($supplier->internalinvoices->sum('paid') == $totalMedinternalInvoicePrice + $totalSupinternalInvoicePrice)
                                            <span class="text-success">All Paid</span>
                                        @else
                                            <span class="text-danger">Credit :
                                                {{ ($totalMedinternalInvoicePrice + $totalSupinternalInvoicePrice) - $supplier->internalinvoices->sum('paid') }}.EGP</span>
                                        @endif
                                    </th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endif


@if ($medinternalinvoicesByMonth->isNotEmpty())
<div class="container-fluid p-2 mt-2" style="background-color: #ffffff;">
    <div class="row">
        <div class="col-12">
            <div class="card m-auto">
                <div class="card-header">
                    <h6 class="text-primary">All Medical Internal Invoices</h6>
                </div>

                @php
                    // نجمع بالـ year
                    $groupedByYear = $medinternalinvoicesByMonth->groupBy('year');
                @endphp

                <div class="accordion" id="yearAccordion">
                    @foreach ($groupedByYear as $year => $months)
                    <div class="accordion-item">
                        <h2 class="accordion-header" id="heading-{{ $year }}">
                            <button class="accordion-button collapsed" type="button"
                                    data-bs-toggle="collapse"
                                    data-bs-target="#collapse-{{ $year }}"
                                    aria-expanded="false"
                                    aria-controls="collapse-{{ $year }}">
                                {{ $year }} (Total Invoices: {{ $months->sum('medinvoicecount') }})
                            </button>
                        </h2>

                        <div id="collapse-{{ $year }}" class="accordion-collapse collapse"
                             aria-labelledby="heading-{{ $year }}"
                             data-bs-parent="#yearAccordion">
                            <div class="accordion-body">

                                 @if(isset($medinternalinvoicesByMonth) && $medinternalinvoicesByMonth->count())
                                    <div class="mx-1 mb-0 mt-2">
                                        <h5 class="text-primary">Medical internalInvoices By Month</h5>

                                        @php
                                            // Group by year
                                            $groupedByYear = $medinternalinvoicesByMonth->groupBy('year');
                                        @endphp

                                        @foreach($groupedByYear as $year => $months)
                                            <table class="table table-bordered table-striped">
                                                <thead>
                                                    <tr>
                                                        <th>{{ $year }}
                                                            <br>
                                                            Medical invoices in year: {{ $months->sum('medinvoicecount') }}
                                                            <br>
                                                            / Total Price: {{ $months->sum('medtotalprice') + $months->sum('suptotalprice') }} EGP
                                                            <br>
                                                            Total Paid: {{ $months->sum('medpaid') + $months->sum('suppaid') }} EGP
                                                            /
                                                            @if (($months->sum('medpaid') + $months->sum('suppaid')) > ($months->sum('medtotalprice') + $months->sum('suptotalprice')))
                                                                <span class="text-success">Credit: {{ ($months->sum('medpaid') + $months->sum('suppaid')) - ($months->sum('medtotalprice') + $months->sum('suptotalprice')) }} EGP</span>
                                                            @elseif (($months->sum('medpaid') + $months->sum('suppaid')) < ($months->sum('medtotalprice') + $months->sum('suptotalprice')))
                                                                <span class="text-danger">Debit: {{ ($months->sum('medtotalprice') + $months->sum('suptotalprice')) - ($months->sum('medpaid') + $months->sum('suppaid')) }} EGP</span>
                                                            @elseif (($months->sum('medpaid') + $months->sum('suppaid')) == ($months->sum('medtotalprice') + $months->sum('suptotalprice')))
                                                                <span class="text-success">All paid</span>
                                                            @endif
                                                        </th>

                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach($months as $month)
                                                        <tr>
                                                            <td>{{ DateTime::createFromFormat('!m', $month->month)->format('F') }}
                                                                <br>
                                                                Count : {{ $month->medinvoicecount }}
                                                                / Total Price : {{ $month->medtotalprice }}.EGP
                                                                <br>
                                                                Total Paid : {{ $month->medpaid }}.EGP
                                                                /
                                                                @if ($month->medpaid > $month->medtotalprice)
                                                                    <span class="text-success">Credit : {{ $month->medpaid - $month->medtotalprice }}.EGP</span>
                                                                @elseif ($month->medpaid < $month->medtotalprice)
                                                                    <span class="text-danger">Debit : {{ $month->medtotalprice - $month->medpaid }}.EGP</span>
                                                                @elseif ($month->medpaid == $month->medtotalprice)
                                                                    <span class="text-success">All paid</span>
                                                                @else

                                                                @endif
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        @endforeach

                                        <div class="pages text-center">
                                            {{ $medinternalinvoicesByMonth->links() }}
                                        </div>
                                    </div>
                                @endif

                                {{-- جوا السنة نعرض الشهور --}}
                                @foreach ($months as $month)
                                    @php
                                        $medinternalinvoices = $allmedinternalinvoices->filter(function($item) use ($month) {
                                            return $item->created_at->year == $month->year && $item->created_at->month == $month->month;
                                        });
                                    @endphp


                                    @if($medinternalinvoices->count())
                                    <div class="mb-3">
                                        <table class="table table-bordered table-responsive table-striped">
                                            <tr class="text-center">
                                                <th colspan="2" style="background-color: #338ded; color: #FFF;">
                                                    {{ DateTime::createFromFormat('!m', $month->month)->format('F') }} {{ $month->year }}
                                                </th>
                                            </tr>
                                            <tr>
                                                    @php
                                                        $totalMedinternalInvoicePrice = 0;
                                                        foreach ($medinternalinvoices as $internalinvoiceitem) {
                                                            $internalinvoiceTotal = 0;
                                                            foreach ($internalinvoiceitem->medinternalinvoices as $item) {
                                                                $internalinvoiceTotal += $item->totalprice;
                                                            }
                                                            $totalMedinternalInvoicePrice += $internalinvoiceTotal;
                                                        }
                                                    @endphp
                                                <th style="background-color: #338ded; color: #FFF; text-align: center;">
                                                    Invoice Count : {{ $month->medinvoicecount ?? $month->count }}
                                                    <br>
                                                    Total Price: {{ $totalMedinternalInvoicePrice }} EGP
                                                </th>
                                                <th style="background-color: #338ded; color: #FFF;">
                                                    Paid: {{ $medinternalinvoices->sum('paid') }} EGP
                                                    <br>
                                                    @if ($medinternalinvoices->sum('paid') == $totalMedinternalInvoicePrice)
                                                        <span class="text-success">All Paid</span>
                                                    @elseif($medinternalinvoices->sum('paid') > $totalMedinternalInvoicePrice)
                                                    <span class="text-info">Credit: {{ $medinternalinvoices->sum('paid') - $totalMedinternalInvoicePrice }} EGP</span>
                                                    @elseif($medinternalinvoices->sum('paid') < $totalMedinternalInvoicePrice)
                                                    <span class="text-danger">Debit: {{ $totalMedinternalInvoicePrice - $medinternalinvoices->sum('paid') }} EGP</span>
                                                    @elseif($medinternalinvoices->sum('paid') == $totalMedinternalInvoicePrice)
                                                        <span class="text-success">All Paid</span>
                                                    @else

                                                    @endif
                                                </th>
                                            </tr>
                                        </table>

                                        {{-- تفاصيل كل فاتورة داخل الشهر --}}
                                        @foreach ($medinternalinvoices as $internalinvoiceitem)
                                            @php
                                                $totalinternalInvoicePrice = 0;
                                                foreach ($internalinvoiceitem->medinternalinvoices as $item) {
                                                    $totalinternalInvoicePrice += $item->totalprice;
                                                }
                                            @endphp
                                            <table class="table table-bordered table-striped">
                                                <tbody class="text-center">
                                                    @foreach ($internalinvoiceitem->medinternalinvoices as $item)
                                                    <tr>
                                                        <th style="background-color:#338ded">Qty x Price</th>
                                                        <th style="background-color:#338ded">Total Price</th>
                                                    </tr>
                                                    <tr>
                                                        <td>{{ $item->qty }} x {{ $item->price }}</td>
                                                        <td>{{ $item->totalprice }} EGP</td>
                                                    </tr>
                                                    <tr>
                                                        <td colspan="2">{{ $item->item }}</td>
                                                    </tr>
                                                    @endforeach
                                                    <tr>
                                                        <td class="text-end text-primary">
                                                            Total Invoice Price: {{ $totalinternalInvoicePrice }} EGP
                                                            <br>
                                                            Time: {{ $internalinvoiceitem->created_at->format('h:i A') }}
                                                        </td>
                                                        <td>
                                                            @if ($internalinvoiceitem->paid == $totalinternalInvoicePrice)
                                                                <span class="text-success">All Paid</span>
                                                            @elseif($internalinvoiceitem->paid > $totalinternalInvoicePrice)
                                                                Paid: {{ $internalinvoiceitem->paid }} EGP
                                                                <br>
                                                                <span class="text-info">Credit: {{ $internalinvoiceitem->paid - $totalinternalInvoicePrice }} EGP</span>
                                                            @elseif($internalinvoiceitem->paid == 0)
                                                                <span class="text-danger">Unpaid</span>
                                                            @else
                                                                Paid: {{ $internalinvoiceitem->paid }} EGP
                                                                <br>
                                                                <span class="text-danger">Debit: {{ $totalinternalInvoicePrice - $internalinvoiceitem->paid }} EGP</span>
                                                            @endif
                                                        </td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                            <hr>
                                        @endforeach
                                    </div>
                                    @endif
                                @endforeach
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>

            </div>
        </div>
    </div>
</div>
@endif


@if ($supinternalinvoicesByMonth->isNotEmpty())
    <div class="container-fluid p-2 mt-2 bg-white">
        <div class="card m-auto">
            <div class="card-header">
                <h6 class="text-primary">All Supplies Internal Invoices</h6>
            </div>

            @php $groupedByYear = $supinternalinvoicesByMonth->groupBy('year'); @endphp

            <div class="accordion" id="supYearAccordion">
                @foreach ($groupedByYear as $year => $months)
                <div class="accordion-item">
                    <h2 class="accordion-header" id="heading-sup-{{ $year }}">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse-sup-{{ $year }}" aria-expanded="false" aria-controls="collapse-sup-{{ $year }}">
                            {{ $year }} (Total Invoices: {{ $months->sum('supinvoicecount') }})
                        </button>
                    </h2>
                    <div id="collapse-sup-{{ $year }}" class="accordion-collapse collapse" aria-labelledby="heading-sup-{{ $year }}" data-bs-parent="#supYearAccordion">
                        <div class="accordion-body">

                            @if(isset($supinternalinvoicesByMonth) && $supinternalinvoicesByMonth->count())
                                <div class="my-4 mx-1">
                                    <h5 class="text-primary">Supplies internalInvoices By Month</h5>
                                    <table class="table table-bordered table-striped">

                                        <tbody>
                                        @php
                                            // Group by year
                                            $groupedByYear = $supinternalinvoicesByMonth->groupBy('year');
                                        @endphp

                                        @foreach($groupedByYear as $year => $months)
                                            <table class="table table-bordered table-striped">
                                                <thead>
                                                    <tr>
                                                        <th>{{ $year }}
                                                            <br>

                                                            Supplies invoices in year: {{ $months->sum('supinvoicecount') }}
                                                            <br>
                                                            Total Paid: {{ $months->sum('medpaid') + $months->sum('suppaid') }} EGP
                                                            /
                                                            @if (($months->sum('medpaid') + $months->sum('suppaid')) > ($months->sum('medtotalprice') + $months->sum('suptotalprice')))
                                                                <span class="text-success">Credit: {{ ($months->sum('medpaid') + $months->sum('suppaid')) - ($months->sum('medtotalprice') + $months->sum('suptotalprice')) }} EGP</span>
                                                            @elseif (($months->sum('medpaid') + $months->sum('suppaid')) < ($months->sum('medtotalprice') + $months->sum('suptotalprice')))
                                                                <span class="text-danger">Debit: {{ ($months->sum('medtotalprice') + $months->sum('suptotalprice')) - ($months->sum('medpaid') + $months->sum('suppaid')) }} EGP</span>
                                                            @elseif (($months->sum('medpaid') + $months->sum('suppaid')) == ($months->sum('medtotalprice') + $months->sum('suptotalprice')))
                                                                <span class="text-success">All paid</span>
                                                            @endif
                                                        </th>

                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach($months as $month)
                                                        <tr>
                                                            <td>{{ DateTime::createFromFormat('!m', $month->month)->format('F') }}
                                                                <br>
                                                                Count : {{ $month->supinvoicecount }}
                                                                / Total Price : {{ $month->suptotalprice }}.EGP
                                                                <br>
                                                                Total Paid : {{ $month->suppaid }}.EGP
                                                                /
                                                                @if ($month->suppaid > $month->suptotalprice)
                                                                    <span class="text-success">Credit : {{ $month->suppaid - $month->suptotalprice }}.EGP</span>
                                                                @elseif ($month->suppaid < $month->suptotalprice)
                                                                    <span class="text-danger">Debit : {{ $month->suptotalprice - $month->suppaid }}.EGP</span>
                                                                @elseif ($month->suppaid == $month->suptotalprice)
                                                                    <span class="text-success">All paid</span>
                                                                @else

                                                                @endif
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        @endforeach

                                        <div class="pages text-center">
                                            {{ $supinternalinvoicesByMonth->links() }}
                                        </div>
                                        </tbody>
                                    </table>
                                    <div class="pages text-center">
                                        {{ $supinternalinvoicesByMonth->links() }}
                                    </div>
                                </div>
                            @endif

                            @foreach ($months as $month)
                                @php
                                    $supinternalinvoices = $allsupinternalinvoices->filter(fn($item) => $item->created_at->year == $month->year && $item->created_at->month == $month->month);
                                @endphp
                                @if($supinternalinvoices->count())
                                <div class="mb-3">
                                    <table class="table table-bordered table-striped text-center">
                                        <tr>
                                            <th colspan="2" style="background-color:#338ded; color:#FFF;">
                                                {{ DateTime::createFromFormat('!m', $month->month)->format('F') }} {{ $month->year }}
                                            </th>
                                        </tr>
                                        <tr>
                                            @php $totalsupinternalInvoicePrice = $supinternalinvoices->sum(fn($i) => $i->supinternalinvoices->sum('totalprice')); @endphp
                                            <th style="background-color:#338ded; color:#FFF;">
                                                Invoice Count : {{ $month->supinvoicecount ?? $month->count }}
                                                <br>
                                                Total: {{ $totalsupinternalInvoicePrice }} EGP
                                            </th>
                                            <th style="background-color:#338ded; color:#FFF;">
                                                @php $paidSumMonth = $supinternalinvoices->sum('paid'); @endphp
                                                @if ($paidSumMonth == $totalsupinternalInvoicePrice)
                                                    <span class="text-success">All Paid</span>
                                                @elseif ($paidSumMonth < $totalsupinternalInvoicePrice)
                                                    Paid: {{ $paidSumMonth }} EGP<br>
                                                    <span class="text-info">Credit : {{ $paidSumMonth - $totalsupinternalInvoicePrice }} EGP</span>
                                                @elseif ($paidSumMonth == 0)
                                                    <span class="text-danger">Unpaid</span>
                                                @else
                                                    Paid: {{ $paidSumMonth }} EGP<br>
                                                    <span class="text-danger">Debit: {{ $totalsupinternalInvoicePrice - $paidSumMonth }} EGP</span>
                                                @endif
                                            </th>
                                        </tr>
                                    </table>

                                    @foreach ($supinternalinvoices as $internalinvoiceitem)
                                        @php $totalinternalInvoicePrice = $internalinvoiceitem->supinternalinvoices->sum('totalprice'); @endphp
                                        <table class="table table-bordered table-striped text-center mb-2">
                                            <thead>
                                                <tr>
                                                    <th style="background-color: #338ded;">Qty x Price</th>
                                                    <th style="background-color: #338ded;">Total Price</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($internalinvoiceitem->supinternalinvoices as $item)
                                                <tr>
                                                    <td>{{ $item->qty }} x {{ $item->price }}</td>
                                                    <td>{{ $item->totalprice }} EGP</td>
                                                </tr>
                                                <tr>
                                                    <td colspan="2">{{ $item->item }}</td>
                                                </tr>
                                                @endforeach
                                                <tr>
                                                    <td class="text-end text-primary">
                                                        Total Invoice Price: {{ $totalinternalInvoicePrice }} EGP<br>
                                                        Time: {{ $internalinvoiceitem->created_at->format('h:i A') }}
                                                    </td>
                                                    <td>
                                                        @if ($internalinvoiceitem->paid == $totalinternalInvoicePrice)
                                                            <span class="text-success">All Paid</span>
                                                        @elseif ($internalinvoiceitem->paid > $totalinternalInvoicePrice)
                                                            Paid: {{ $internalinvoiceitem->paid }} EGP<br>
                                                            <span class="text-info">Credit: {{ $internalinvoiceitem->paid - $totalinternalInvoicePrice }} EGP</span>
                                                        @elseif ($internalinvoiceitem->paid == 0)
                                                            <span class="text-danger">Unpaid</span>
                                                        @else
                                                            Paid: {{ $internalinvoiceitem->paid }} EGP<br>
                                                            <span class="text-danger">Debit: {{ $totalinternalInvoicePrice - $internalinvoiceitem->paid }} EGP</span>
                                                        @endif
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                        <hr>
                                    @endforeach
                                </div>
                                @endif
                            @endforeach
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
@endif

@if ($allfeeding->isNotEmpty() || $allbedding->isNotEmpty())
<div class="accordion my-4" id="feedBedAccordion">
    <div class="accordion-item">
        <h2 class="accordion-header" id="headingFeedBed">
            <button class="accordion-button collapsed bg-primary text-white" type="button"
                data-bs-toggle="collapse" data-bs-target="#collapseFeedBed"
                aria-expanded="false" aria-controls="collapseFeedBed">
                🐄 Feeding & Bedding
            </button>
        </h2>
        <div id="collapseFeedBed" class="accordion-collapse collapse"
            aria-labelledby="headingFeedBed" data-bs-parent="#feedBedAccordion">
            <div class="accordion-body">
                <div class="table-responsive mx-2 px-0 pb-2">
                    <table class="table align-items-center mb-0 text-center" style="height: auto;">
                        <thead>
                            <tr>
                                <th style="background-color: #338ded; color: #FFF;">
                                    Total Feed/Bedding : {{ $supplier->feedbed->count() }}
                                    <br> Feeding({{ $allfeeding->count() }}) / Bedding({{ $allbedding->count() }})
                                </th>
                                <th style="background-color: #338ded; color: #FFF;">
                                    @if ($supplier->feedbed->sum('paid') > $supplier->feedbed->sum('price'))
                                        <span class="text-info">Credit: {{ abs($supplier->feedbed->sum('paid') - $supplier->feedbed->sum('price')) }}.EGP</span>
                                    @elseif ($supplier->feedbed->sum('paid') == 0)
                                        <span class="text-danger">Unpaid</span>
                                    @elseif ($supplier->feedbed->sum('paid') == $supplier->feedbed->sum('price'))
                                        <span class="text-success">All Paid</span>
                                    @else
                                        <span class="text-danger">Total Debit</span>
                                        <br>
                                        <span class="text-danger">{{ abs($supplier->feedbed->sum('price') - $supplier->feedbed->sum('paid')) }}.EGP</span>
                                    @endif
                                </th>
                            </tr>
                            <tr>
                                <th style="background-color: #338ded; color: #FFF;">
                                    Total Price
                                    <br> {{ $supplier->feedbed->sum('price') }}.EGP
                                </th>
                                <th style="background-color: #338ded; color: #FFF;">
                                    Total Paid
                                    <br> {{ $supplier->feedbed->sum('paid') }}.EGP
                                </th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endif


@if ($feedingByMonth->isNotEmpty())
    <div class="container-fluid p-1 mt-2 bg-light">
        <div class="card m-auto">
            <div class="card-header">
                <h6 class="text-primary">All Feeding Records</h6>
            </div>

            @php $groupedByYear = $feedingByMonth->groupBy('year'); @endphp

            <div class="accordion" id="feedingYearAccordion">
                @foreach ($groupedByYear as $year => $months)
                <div class="accordion-item">
                    <h2 class="accordion-header" id="heading-feed-{{ $year }}">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse-feed-{{ $year }}" aria-expanded="false" aria-controls="collapse-feed-{{ $year }}">
                            {{ $year }} (Total Invoices: {{ $months->sum('feedingcount') }})
                        </button>
                    </h2>
                    <div id="collapse-feed-{{ $year }}" class="accordion-collapse collapse" aria-labelledby="heading-feed-{{ $year }}" data-bs-parent="#feedingYearAccordion">
                        <div class="accordion-body">

                            @if(isset($feedingByMonth) && $feedingByMonth->count())
                                <div class="my-4 mx-1">
                                    <h5 class="text-primary">Feeding By Month</h5>
                                    <table class="table table-bordered table-striped">

                                        <tbody>
                                        @php
                                            // Group by year
                                            $groupedByYear = $feedingByMonth->groupBy('year');
                                        @endphp

                                        @foreach($groupedByYear as $year => $months)
                                            <table class="table table-bordered table-striped">
                                                <thead>
                                                    <tr>
                                                        <th>{{ $year }}
                                                            <br>
                                                            Medical invoices in year: {{ $months->sum('medinvoicecount') }}
                                                            <br>
                                                            Supplies invoices in year: {{ $months->sum('supinvoicecount') }}
                                                            <br>                  / Total Price: {{ $months->sum('medtotalprice') + $months->sum('suptotalprice') }} EGP
                                                            <br>
                                                            Total Paid: {{ $months->sum('medpaid') + $months->sum('suppaid') }} EGP
                                                            /
                                                            @if (($months->sum('medpaid') + $months->sum('suppaid')) > ($months->sum('medtotalprice') + $months->sum('suptotalprice')))
                                                                <span class="text-success">Credit: {{ ($months->sum('medpaid') + $months->sum('suppaid')) - ($months->sum('medtotalprice') + $months->sum('suptotalprice')) }} EGP</span>
                                                            @elseif (($months->sum('medpaid') + $months->sum('suppaid')) < ($months->sum('medtotalprice') + $months->sum('suptotalprice')))
                                                                <span class="text-danger">Debit: {{ ($months->sum('medtotalprice') + $months->sum('suptotalprice')) - ($months->sum('medpaid') + $months->sum('suppaid')) }} EGP</span>
                                                            @elseif (($months->sum('medpaid') + $months->sum('suppaid')) == ($months->sum('medtotalprice') + $months->sum('suptotalprice')))
                                                                <span class="text-success">All paid</span>
                                                            @endif
                                                        </th>

                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach($months as $month)
                                                        <tr>
                                                            <td>{{ DateTime::createFromFormat('!m', $month->month)->format('F') }}
                                                                <br>
                                                                Count : {{ $month->feedingcount }}
                                                                / Total Price : {{ $month->feedingprice }}.EGP
                                                                <br>
                                                                Total Paid : {{ $month->feedingpaid }}.EGP
                                                                /
                                                                @if ($month->feedingpaid > $month->feedingprice)
                                                                    <span class="text-success">Credit : {{ $month->feedingpaid - $month->feedingprice }}.EGP</span>
                                                                @elseif ($month->feedingpaid < $month->feedingprice)
                                                                    <span class="text-danger">Debit : {{ $month->feedingprice - $month->feedingpaid }}.EGP</span>
                                                                @elseif ($month->feedingpaid == $month->feedingprice)
                                                                    <span class="text-success">All paid</span>
                                                                @else

                                                                @endif
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        @endforeach

                                        <div class="pages text-center">
                                            {{ $feedingByMonth->links() }}
                                        </div>
                                        </tbody>
                                    </table>
                                    <div class="pages text-center">
                                        {{ $feedingByMonth->links() }}
                                    </div>
                                </div>
                            @endif

                            @foreach ($months as $month)
                                @php
                                    $feedingRecords = $allfeeding->filter(fn($item) => $item->created_at->year == $month->year && $item->created_at->month == $month->month);
                                @endphp
                                @if($feedingRecords->count())
                                <div class="mb-3">
                                    <table class="table table-bordered table-striped text-center">
                                        <tr>
                                            <th colspan="2" style="background-color:#338ded; color:#FFF;">
                                                {{ DateTime::createFromFormat('!m', $month->month)->format('F') }} {{ $month->year }}
                                            </th>
                                        </tr>
                                        <tr>
                                            <th style="background-color:#338ded; color:#FFF;">
                                                Feeding Records: {{ $month->feedingcount ?? $month->count }}<br>
                                                Total Price: {{ $feedingRecords->sum('price') }} EGP
                                            </th>
                                            <th style="background-color:#338ded; color:#FFF;">
                                                @php $paidSumMonth = $feedingRecords->sum('paid'); $totalPrice = $feedingRecords->sum('price'); @endphp
                                                @if ($paidSumMonth == $totalPrice)
                                                    <span class="text-success">All Paid</span>
                                                @elseif ($paidSumMonth > $totalPrice)
                                                    Paid: {{ $paidSumMonth }} EGP<br>
                                                    <span class="text-info">Credit: {{ $paidSumMonth - $totalPrice }} EGP</span>
                                                @elseif ($paidSumMonth == 0)
                                                    <span class="text-danger">Unpaid</span>
                                                @else
                                                    Paid: {{ $paidSumMonth }} EGP<br>
                                                <span class="text-danger">Debit: {{ $totalPrice - $paidSumMonth }} EGP</span>
                                                @endif
                                            </th>
                                        </tr>
                                    </table>

                                    @foreach ($feedingRecords as $record)
                                    <table class="table table-bordered table-striped p-3">
                                        <thead>
                                            <tr>
                                                <th>Item / Qty</th>
                                                <th>Price / Paid</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td>{{ $record->item }} / {{ $record->qty }} Units</td>
                                                <td>{{ $record->price }} EGP / {{ $record->paid }} EGP</td>
                                            </tr>
                                            <tr>
                                                <td colspan="2" class="text-primary">
                                                    {{ $record->created_at->format('h:iA d/M/y') }}
                                                    @if ($record->price > $record->paid)
                                                    <span class="text-danger">Credit : {{ $record->price - $record->paid }} EGP</span>
                                                    @elseif ($record->price < $record->paid)
                                                    <span class="text-success">Debit : {{ $record->paid - $record->price }} EGP</span>
                                                    @elseif ($record->price == $record->paid)
                                                    <span class="text-info">All Paid</span>
                                                    @else

                                                    @endif
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                    <hr>
                                    @endforeach
                                </div>
                                @endif
                            @endforeach
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
@endif


@if ($beddingByMonth->isNotEmpty())
    <div class="container-fluid p-1 mt-2 bg-light">
        <div class="card m-auto">
            <div class="card-header">
                <h6 class="text-primary">All Bedding Records</h6>
            </div>

            @php $groupedByYear = $beddingByMonth->groupBy('year'); @endphp

            <div class="accordion" id="beddingYearAccordion">
                @foreach ($groupedByYear as $year => $months)
                <div class="accordion-item">
                    <h2 class="accordion-header" id="heading-feed-{{ $year }}">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse-feed-{{ $year }}" aria-expanded="false" aria-controls="collapse-feed-{{ $year }}">
                            {{ $year }} (Total Invoices: {{ $months->sum('beddingcount') }})
                        </button>
                    </h2>
                    <div id="collapse-feed-{{ $year }}" class="accordion-collapse collapse" aria-labelledby="heading-feed-{{ $year }}" data-bs-parent="#beddingYearAccordion">
                        <div class="accordion-body">

                            @if(isset($beddingByMonth) && $beddingByMonth->count())
                                <div class="my-4 mx-1">
                                    <h5 class="text-primary">Bedding By Month</h5>
                                    <table class="table table-bordered table-striped">

                                        <tbody>
                                        @php
                                            // Group by year
                                            $groupedByYear = $beddingByMonth->groupBy('year');
                                        @endphp

                                        @foreach($groupedByYear as $year => $months)
                                            <table class="table table-bordered table-striped">
                                                <thead>
                                                    <tr>
                                                        <th>{{ $year }}
                                                            <br>
                                                            Medical invoices in year: {{ $months->sum('medinvoicecount') }}
                                                            <br>
                                                            Supplies invoices in year: {{ $months->sum('supinvoicecount') }}
                                                            <br>                  / Total Price: {{ $months->sum('medtotalprice') + $months->sum('suptotalprice') }} EGP
                                                            <br>
                                                            Total Paid: {{ $months->sum('medpaid') + $months->sum('suppaid') }} EGP
                                                            /
                                                            @if (($months->sum('medpaid') + $months->sum('suppaid')) > ($months->sum('medtotalprice') + $months->sum('suptotalprice')))
                                                                <span class="text-success">Credit: {{ ($months->sum('medpaid') + $months->sum('suppaid')) - ($months->sum('medtotalprice') + $months->sum('suptotalprice')) }} EGP</span>
                                                            @elseif (($months->sum('medpaid') + $months->sum('suppaid')) < ($months->sum('medtotalprice') + $months->sum('suptotalprice')))
                                                                <span class="text-danger">Debit: {{ ($months->sum('medtotalprice') + $months->sum('suptotalprice')) - ($months->sum('medpaid') + $months->sum('suppaid')) }} EGP</span>
                                                            @elseif (($months->sum('medpaid') + $months->sum('suppaid')) == ($months->sum('medtotalprice') + $months->sum('suptotalprice')))
                                                                <span class="text-success">All paid</span>
                                                            @endif
                                                        </th>

                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach($months as $month)
                                                        <tr>
                                                            <td>{{ DateTime::createFromFormat('!m', $month->month)->format('F') }}
                                                                <br>
                                                                Count : {{ $month->beddingcount }}
                                                                / Total Price : {{ $month->beddingprice }}.EGP
                                                                <br>
                                                                Total Paid : {{ $month->beddingpaid }}.EGP
                                                                /
                                                                @if ($month->beddingpaid > $month->beddingprice)
                                                                    <span class="text-success">Credit : {{ $month->beddingpaid - $month->beddingprice }}.EGP</span>
                                                                @elseif ($month->beddingpaid < $month->beddingprice)
                                                                    <span class="text-danger">Debit : {{ $month->beddingprice - $month->beddingpaid }}.EGP</span>
                                                                @elseif ($month->beddingpaid == $month->beddingprice)
                                                                    <span class="text-success">All paid</span>
                                                                @else

                                                                @endif
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        @endforeach

                                        <div class="pages text-center">
                                            {{ $beddingByMonth->links() }}
                                        </div>
                                        </tbody>
                                    </table>
                                    <div class="pages text-center">
                                        {{ $beddingByMonth->links() }}
                                    </div>
                                </div>
                            @endif

                            @foreach ($months as $month)
                                @php
                                    $beddingRecords = $allbedding->filter(fn($item) => $item->created_at->year == $month->year && $item->created_at->month == $month->month);
                                @endphp
                                @if($beddingRecords->count())
                                <div class="mb-3">
                                    <table class="table table-bordered table-striped text-center">
                                        <tr>
                                            <th colspan="2" style="background-color:#338ded; color:#FFF;">
                                                {{ DateTime::createFromFormat('!m', $month->month)->format('F') }} {{ $month->year }}
                                            </th>
                                        </tr>
                                        <tr>
                                            <th style="background-color:#338ded; color:#FFF;">
                                                Bedding Records: {{ $month->beddingcount ?? $month->count }}<br>
                                                Total Price: {{ $beddingRecords->sum('price') }} EGP
                                            </th>
                                            <th style="background-color:#338ded; color:#FFF;">
                                                @php $paidSumMonth = $beddingRecords->sum('paid'); $totalPrice = $beddingRecords->sum('price'); @endphp
                                                @if ($paidSumMonth == $totalPrice)
                                                    <span class="text-success">All Paid</span>
                                                @elseif ($paidSumMonth > $totalPrice)
                                                    Paid: {{ $paidSumMonth }} EGP<br>
                                                    <span class="text-info">Credit: {{ $paidSumMonth - $totalPrice }} EGP</span>
                                                @elseif ($paidSumMonth == 0)
                                                    <span class="text-danger">Unpaid</span>
                                                @else
                                                    Paid: {{ $paidSumMonth }} EGP<br>
                                                <span class="text-danger">Debit: {{ $totalPrice - $paidSumMonth }} EGP</span>
                                                @endif
                                            </th>
                                        </tr>
                                    </table>

                                    @foreach ($beddingRecords as $record)
                                    <table class="table table-bordered table-striped p-3">
                                        <thead>
                                            <tr>
                                                <th>Item / Qty</th>
                                                <th>Price / Paid</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td>{{ $record->item }} / {{ $record->qty }} Units</td>
                                                <td>{{ $record->price }} EGP / {{ $record->paid }} EGP</td>
                                            </tr>
                                            <tr>
                                                <td colspan="2" class="text-primary">
                                                    {{ $record->created_at->format('h:iA d/M/y') }}
                                                    @if ($record->price > $record->paid)
                                                    <span class="text-danger">Credit : {{ $record->price - $record->paid }} EGP</span>
                                                    @elseif ($record->price < $record->paid)
                                                    <span class="text-success">Debit : {{ $record->paid - $record->price }} EGP</span>
                                                    @elseif ($record->price == $record->paid)
                                                    <span class="text-info">All Paid</span>
                                                    @else

                                                    @endif
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                    <hr>
                                    @endforeach
                                </div>
                                @endif
                            @endforeach
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
@endif


@endsection

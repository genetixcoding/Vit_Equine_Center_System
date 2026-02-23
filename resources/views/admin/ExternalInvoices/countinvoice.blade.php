@extends('layouts.admin')
<title>External Invoice Details</title>
@section('content')
<div class="container-fluid py-4">
    <div class="row pt-4 p-2">
        <div class="col-12">
            <div class="card my-4">
                <div class="card-header p-0 position-relative mt-n4 mx-3 z-index-2">
                    <div class="bg-gradient-info shadow-primary border-radius-lg pt-4 pb-3">
                        <h4 class="text-white text-capitalize ps-3">All External Invoice Details</h4>
                    </div>
                </div>




                @if ($allmedexternalinvoices->isNotEmpty() || $allsupexternalinvoices->isNotEmpty())
                    <div class="accordion my-4" id="externalInvoicesAccordion">
                        <div class="accordion-item">
                            <h2 class="accordion-header" id="headingexternalInvoices">
                                <button class="accordion-button collapsed bg-primary text-white" type="button"
                                    data-bs-toggle="collapse" data-bs-target="#collapseexternalInvoices"
                                    aria-expanded="false" aria-controls="collapseexternalInvoices">
                                    📑 External Invoices (MED + SUP)
                                </button>
                            </h2>
                            <div id="collapseexternalInvoices" class="accordion-collapse collapse"
                                aria-labelledby="headingexternalInvoices" data-bs-parent="#externalInvoicesAccordion">
                                <div class="accordion-body p-1">
                                    <div class="table-responsive">
                                        <table class="table align-items-center table-responsive text-center" style="height: auto;">
                                            <thead>
                                                @php
                                                    $totalMedexternalInvoicePrice = 0;
                                                    $totalSupexternalInvoicePrice = 0;
                                                    $totalMedexternalInvoice = 0;
                                                    $totalSupexternalInvoice = 0;
                                                    foreach ($externalinvoices as $externalinvoiceitem) {
                                                        $totalMedexternalInvoice += $externalinvoiceitem->medexternalinvoices->count();
                                                        $totalSupexternalInvoice += $externalinvoiceitem->supexternalinvoices->count();
                                                        foreach ($externalinvoiceitem->medexternalinvoices as $item) {
                                                            $totalMedexternalInvoicePrice += $item->totalprice;
                                                        }
                                                        foreach ($externalinvoiceitem->supexternalinvoices as $item) {
                                                            $totalSupexternalInvoicePrice += $item->totalprice;
                                                        }
                                                    }
                                                @endphp
                                                <tr>
                                                    <th colspan="2" style="background-color: #338ded; color: #FFF;">

                                                        Total Invoices : {{ $externalinvoices->count() }}
                                                        <br>
                                                        MedInvoices ({{ $totalMedexternalInvoice }})
                                                        /
                                                        MedInvoices ({{ $totalSupexternalInvoice }})
                                                        <br>Total Prices:
                                                        {{ $totalMedexternalInvoicePrice + $totalSupexternalInvoicePrice }}.EGP
                                                        <br>
                                                        MedPrice: {{ $totalMedexternalInvoicePrice }}.EGP
                                                        /
                                                        SupPrice: {{ $totalSupexternalInvoicePrice }}.EGP
                                                    </th>
                                                </tr>
                                                <tr>
                                                    <th style="background-color: #338ded; color: #FFF;">
                                                        Paid:
                                                         {{ $externalinvoices->sum('paid') }}.EGP
                                                    </th>
                                                    <th style="background-color: #338ded; color: #FFF;">
                                                        @if ($externalinvoices->sum('paid') > $totalMedexternalInvoicePrice + $totalSupexternalInvoicePrice)
                                                            <span class="text-info">Credit :
                                                                {{ $externalinvoices->sum('paid') - ($totalMedexternalInvoicePrice + $totalSupexternalInvoicePrice) }}.EGP</span>
                                                        @elseif ($externalinvoices->sum('paid') == 0)
                                                            <span class="text-danger">Unpaid</span>
                                                        @elseif ($externalinvoices->sum('paid') == $totalMedexternalInvoicePrice + $totalSupexternalInvoicePrice)
                                                            <span class="text-success">All Paid</span>
                                                        @else
                                                            <span class="text-danger">Credit :
                                                                {{ ($totalMedexternalInvoicePrice + $totalSupexternalInvoicePrice) - $externalinvoices->sum('paid') }}.EGP</span>
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


                @if ($medexternalinvoicesByMonth->isNotEmpty())
                <div class="container-fluid p-2 mt-2" style="background-color: #ffffff;">
                    <div class="row">
                        <div class="col-12">
                            <div class="card m-auto">
                                <div class="card-header">
                                    <h6 class="text-primary">All Medical external Invoices</h6>
                                </div>

                                @php
                                    // نجمع بالـ year
                                    $groupedByYear = $medexternalinvoicesByMonth->groupBy('year');
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

                                                @if(isset($medexternalinvoicesByMonth) && $medexternalinvoicesByMonth->count())
                                                    <div class="p-1">
                                                        <h5 class="text-primary">Medical externalInvoices By Month</h5>

                                                        @php
                                                            // Group by year
                                                            $groupedByYear = $medexternalinvoicesByMonth->groupBy('year');
                                                        @endphp

                                                        @foreach($groupedByYear as $year => $months)
                                                            <table class="table table-bordered table-striped">
                                                                <thead>
                                                                    <tr>
                                                                        <th>{{ $year }}
                                                                            <br>
                                                                            Total Count : {{ $months->sum('medinvoicecount') }}
                                                                            / Total Price : {{ $months->sum('medtotalprice') }}.EGP
                                                                            <br>
                                                                            Total Paid : {{ $months->sum('medpaid') }}.EGP
                                                                            /
                                                                            @if ($months->sum('medpaid') > $months->sum('medtotalprice'))
                                                                                <span class="text-success">Credit : {{ $months->sum('medpaid') - $months->sum('medtotalprice') }}.EGP</span>
                                                                            @elseif ($months->sum('medpaid') < $months->sum('medtotalprice'))
                                                                                <span class="text-danger">Debit : {{ $months->sum('medtotalprice') - $months->sum('medpaid') }}.EGP</span>
                                                                            @elseif ($months->sum('medpaid') == $months->sum('medtotalprice'))
                                                                                <span class="text-success">All paid</span>
                                                                            @else

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
                                                            {{ $medexternalinvoicesByMonth->links() }}
                                                        </div>
                                                    </div>
                                                @endif

                                                {{-- جوا السنة نعرض الشهور --}}
                                                @foreach ($months as $month)
                                                    @php
                                                        $medexternalinvoices = $allmedexternalinvoices->filter(function($item) use ($month) {
                                                            return $item->created_at->year == $month->year && $item->created_at->month == $month->month;
                                                        });
                                                    @endphp


                                                    @if($medexternalinvoices->count())
                                                    <div class="mb-3">
                                                        <table class="table table-bordered table-responsive table-striped">
                                                            <tr class="text-center">
                                                                <th colspan="2" style="background-color: #338ded; color: #FFF;">
                                                                    {{ DateTime::createFromFormat('!m', $month->month)->format('F') }} {{ $month->year }}
                                                                </th>
                                                            </tr>
                                                            <tr>
                                                                    @php
                                                                        $totalMedexternalInvoicePrice = 0;
                                                                        foreach ($medexternalinvoices as $externalinvoiceitem) {
                                                                            $externalinvoiceTotal = 0;
                                                                            foreach ($externalinvoiceitem->medexternalinvoices as $item) {
                                                                                $externalinvoiceTotal += $item->totalprice;
                                                                            }
                                                                            $totalMedexternalInvoicePrice += $externalinvoiceTotal;
                                                                        }
                                                                    @endphp
                                                                <th style="background-color: #338ded; color: #FFF; text-align: center;">
                                                                    Invoice Count : {{ $month->medinvoicecount ?? $month->count }}
                                                                    <br>
                                                                    Total Price: {{ $totalMedexternalInvoicePrice }} EGP
                                                                </th>
                                                                <th style="background-color: #338ded; color: #FFF;">
                                                                    Paid: {{ $medexternalinvoices->sum('paid') }} EGP
                                                                    <br>
                                                                    @if ($medexternalinvoices->sum('paid') == $totalMedexternalInvoicePrice)
                                                                        <span class="text-success">All Paid</span>
                                                                    @elseif($medexternalinvoices->sum('paid') > $totalMedexternalInvoicePrice)
                                                                    <span class="text-info">Credit: {{ $medexternalinvoices->sum('paid') - $totalMedexternalInvoicePrice }} EGP</span>
                                                                    @elseif($medexternalinvoices->sum('paid') < $totalMedexternalInvoicePrice)
                                                                    <span class="text-danger">Debit: {{ $totalMedexternalInvoicePrice - $medexternalinvoices->sum('paid') }} EGP</span>
                                                                    @elseif($medexternalinvoices->sum('paid') == $totalMedexternalInvoicePrice)
                                                                        <span class="text-success">All Paid</span>
                                                                    @else

                                                                    @endif
                                                                </th>
                                                            </tr>
                                                        </table>

                                                        {{-- تفاصيل كل فاتورة داخل الشهر --}}
                                                        @foreach ($medexternalinvoices as $externalinvoiceitem)
                                                            @php
                                                                $totalexternalInvoicePrice = 0;
                                                                foreach ($externalinvoiceitem->medexternalinvoices as $item) {
                                                                    $totalexternalInvoicePrice += $item->totalprice;
                                                                }
                                                            @endphp
                                                            <table class="table table-bordered table-striped">
                                                                <tbody class="text-center">
                                                                    @foreach ($externalinvoiceitem->medexternalinvoices as $item)
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
                                                                            Total Invoice Price: {{ $totalexternalInvoicePrice }} EGP
                                                                            <br>
                                                                            Time: {{ $externalinvoiceitem->created_at->format('h:i A') }}
                                                                        </td>
                                                                        <td>
                                                                            @if ($externalinvoiceitem->paid == $totalexternalInvoicePrice)
                                                                                <span class="text-success">All Paid</span>
                                                                            @elseif($externalinvoiceitem->paid > $totalexternalInvoicePrice)
                                                                                Paid: {{ $externalinvoiceitem->paid }} EGP
                                                                                <br>
                                                                                <span class="text-info">Credit: {{ $externalinvoiceitem->paid - $totalexternalInvoicePrice }} EGP</span>
                                                                            @elseif($externalinvoiceitem->paid == 0)
                                                                                <span class="text-danger">Unpaid</span>
                                                                            @else
                                                                                Paid: {{ $externalinvoiceitem->paid }} EGP
                                                                                <br>
                                                                                <span class="text-danger">Debit: {{ $totalexternalInvoicePrice - $externalinvoiceitem->paid }} EGP</span>
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


                @if ($supexternalinvoicesByMonth->isNotEmpty())
                    <div class="container-fluid p-2 mt-2 bg-white">
                        <div class="card m-auto">
                            <div class="card-header">
                                <h6 class="text-primary">All Supplies external Invoices</h6>
                            </div>

                            @php $groupedByYear = $supexternalinvoicesByMonth->groupBy('year'); @endphp

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

                                            @if(isset($supexternalinvoicesByMonth) && $supexternalinvoicesByMonth->count())
                                                <div class="my-4 mx-1">
                                                    <h5 class="text-primary">Supplies externalInvoices By Month</h5>
                                                    <table class="table table-bordered table-striped">

                                                        <tbody>
                                                        @php
                                                            // Group by year
                                                            $groupedByYear = $supexternalinvoicesByMonth->groupBy('year');
                                                        @endphp

                                                        @foreach($groupedByYear as $year => $months)
                                                            <table class="table table-bordered table-striped">
                                                                <thead>
                                                                    <tr>
                                                                        <th>{{ $year }}
                                                                            <br>
                                                                            Total Count : {{ $months->sum('supinvoicecount') }}
                                                                            / Total Price : {{ $months->sum('suptotalprice') }}.EGP
                                                                            <br>
                                                                            Total Paid : {{ $months->sum('suppaid') }}.EGP
                                                                            /
                                                                            @if ($months->sum('suppaid') > $months->sum('suptotalprice'))
                                                                                <span class="text-success">Credit : {{ $months->sum('suppaid') - $months->sum('suptotalprice') }}.EGP</span>
                                                                            @elseif ($months->sum('suppaid') < $months->sum('suptotalprice'))
                                                                                <span class="text-danger">Debit : {{ $months->sum('suptotalprice') - $months->sum('suppaid') }}.EGP</span>
                                                                            @elseif ($months->sum('suppaid') == $months->sum('suptotalprice'))
                                                                                <span class="text-success">All paid</span>
                                                                            @else

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
                                                            {{ $supexternalinvoicesByMonth->links() }}
                                                        </div>
                                                        </tbody>
                                                    </table>
                                                    <div class="pages text-center">
                                                        {{ $supexternalinvoicesByMonth->links() }}
                                                    </div>
                                                </div>
                                            @endif

                                            @foreach ($months as $month)
                                                @php
                                                    $supexternalinvoices = $allsupexternalinvoices->filter(fn($item) => $item->created_at->year == $month->year && $item->created_at->month == $month->month);
                                                @endphp
                                                @if($supexternalinvoices->count())
                                                <div class="mb-3">
                                                    <table class="table table-bordered table-striped text-center">
                                                        <tr>
                                                            <th colspan="2" style="background-color:#338ded; color:#FFF;">
                                                                {{ DateTime::createFromFormat('!m', $month->month)->format('F') }} {{ $month->year }}
                                                            </th>
                                                        </tr>
                                                        <tr>
                                                            @php $totalsupexternalInvoicePrice = $supexternalinvoices->sum(fn($i) => $i->supexternalinvoices->sum('totalprice')); @endphp
                                                            <th style="background-color:#338ded; color:#FFF;">
                                                                Invoice Count : {{ $month->supinvoicecount ?? $month->count }}
                                                                <br>
                                                                Total: {{ $totalsupexternalInvoicePrice }} EGP
                                                            </th>
                                                            <th style="background-color:#338ded; color:#FFF;">
                                                                @php $paidSumMonth = $supexternalinvoices->sum('paid'); @endphp
                                                                @if ($paidSumMonth == $totalsupexternalInvoicePrice)
                                                                    <span class="text-success">All Paid</span>
                                                                @elseif ($paidSumMonth < $totalsupexternalInvoicePrice)
                                                                    Paid: {{ $paidSumMonth }} EGP<br>
                                                                    <span class="text-info">Credit : {{ $paidSumMonth - $totalsupexternalInvoicePrice }} EGP</span>
                                                                @elseif ($paidSumMonth == 0)
                                                                    <span class="text-danger">Unpaid</span>
                                                                @else
                                                                    Paid: {{ $paidSumMonth }} EGP<br>
                                                                    <span class="text-danger">Debit: {{ $totalsupexternalInvoicePrice - $paidSumMonth }} EGP</span>
                                                                @endif
                                                            </th>
                                                        </tr>
                                                    </table>

                                                    @foreach ($supexternalinvoices as $externalinvoiceitem)
                                                        @php $totalexternalInvoicePrice = $externalinvoiceitem->supexternalinvoices->sum('totalprice'); @endphp
                                                        <table class="table table-bordered table-striped text-center mb-2">
                                                            <thead>
                                                                <tr>
                                                                    <th style="background-color: #338ded;">Qty x Price</th>
                                                                    <th style="background-color: #338ded;">Total Price</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                @foreach ($externalinvoiceitem->supexternalinvoices as $item)
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
                                                                        Total Invoice Price: {{ $totalexternalInvoicePrice }} EGP<br>
                                                                        Time: {{ $externalinvoiceitem->created_at->format('h:i A') }}
                                                                    </td>
                                                                    <td>
                                                                        @if ($externalinvoiceitem->paid == $totalexternalInvoicePrice)
                                                                            <span class="text-success">All Paid</span>
                                                                        @elseif ($externalinvoiceitem->paid > $totalexternalInvoicePrice)
                                                                            Paid: {{ $externalinvoiceitem->paid }} EGP<br>
                                                                            <span class="text-info">Credit: {{ $externalinvoiceitem->paid - $totalexternalInvoicePrice }} EGP</span>
                                                                        @elseif ($externalinvoiceitem->paid == 0)
                                                                            <span class="text-danger">Unpaid</span>
                                                                        @else
                                                                            Paid: {{ $externalinvoiceitem->paid }} EGP<br>
                                                                            <span class="text-danger">Debit: {{ $totalexternalInvoicePrice - $externalinvoiceitem->paid }} EGP</span>
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
            </div>
        </div>
    </div>
</div>
@endsection


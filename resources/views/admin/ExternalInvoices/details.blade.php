@extends('layouts.admin')
<title>{{ $stud->name }} Invoices Details</title>
@section('content')
<div class="container-fluid py-4">
    <div class="row pt-4 p-2">
        <div class="col-12">
            <div class="card my-4">
                <div class="card-header p-0 position-relative mt-n4 mx-3 z-index-2">
                    <div class="bg-gradient-info shadow-primary border-radius-lg pt-4 pb-3">
                        <h4 class="text-white text-capitalize ps-3">Stud Details Table</h4>
                        <h6 class="text-white text-capitalize ps-3">Stud Name:- {{ $stud->name }}</h6>
                        <h6 class="text-white text-capitalize ps-3">Description:- {{ $stud->description }}</h6>
                    </div>
                </div>
                <h4 class="text-white text-capitalize mt-2 pe-3 ps-3">
                    <a class="text-primary" href="{{ url('Medicalexternalinvoices') }}">View All External Invoices</a>
                </h4>
                {{-- Summary Table --}}
                @if ($stud->externalinvoices->count() > 0)
                    <div class="card-body mx-0 px-1">
                        <h4 class="text-primary text-center m-0 p-0">All External Invoices</h4>
                        <div class="table-responsive ">
                        <table class="table align-items-center mb-0 text-center" style="height: auto;">
                            <thead>
                                @php
                                    $totalMedInvoicePrice = 0;
                                    $totalSupInvoicePrice = 0;

                                    foreach ($stud->externalinvoices as $invoiceitem) {

                                        foreach ($invoiceitem->medexternalinvoices as $item) {
                                            $totalMedInvoicePrice += $item->totalprice;
                                        }
                                        foreach ($invoiceitem->supexternalinvoices as $item) {
                                            $totalSupInvoicePrice += $item->totalprice;
                                        }
                                    }
                                @endphp

                                <tr>
                                    <th style="background-color: #338ded; color: #FFF;">
                                        Invoices : {{ $stud->externalinvoices->count()}}
                                        <br>
                                        Price:
                                        {{ $totalMedInvoicePrice + $totalSupInvoicePrice }}.EGP
                                    </th>
                                    <th style="background-color: #338ded; color: #FFF;">
                                        Paid:
                                        {{ $stud->externalinvoices->sum('paid') }}.EGP
                                        <br>
                                        @if ($stud->externalinvoices->sum('paid') > $totalMedInvoicePrice + $totalSupInvoicePrice)
                                            <span class="text-info">Credit : {{ $stud->externalinvoices->sum('paid') - ($totalMedInvoicePrice + $totalSupInvoicePrice) }}.EGP</span>
                                        @elseif ($stud->externalinvoices->sum('paid') < $totalMedInvoicePrice + $totalSupInvoicePrice)
                                            <span class="text-danger">Debit : {{ ($totalMedInvoicePrice + $totalSupInvoicePrice) - $stud->externalinvoices->sum('paid') }}.EGP</span>

                                        @elseif ($stud->externalinvoices->sum('paid') == $totalMedInvoicePrice + $totalSupInvoicePrice)
                                            <span class="text-success">All Paid</span>
                                        @else
                                            <span class="text-warning">Unpaid : {{ ($totalMedInvoicePrice + $totalSupInvoicePrice) - $stud->externalinvoices->sum('paid') }}.EGP</span>
                                        @endif
                                    </th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                    <div class="table-responsive">
                        <table class="table align-items-center mb-0 text-center" style="height: auto;">
                            <thead>
                                <tr>

                                    <th>Year</th>
                                    <th>Count</th>
                                    <th>Total Price</th>
                                    <th>Total Paid</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    // جمع كل الشهور من الجدولين
                                    $allMonths = collect();
                                    foreach ($medexternalinvoicesByMonth as $m) {
                                        $allMonths->push(['year' => $m->year, 'month' => $m->month, 'count' => $m->count]);
                                    }
                                    foreach ($supexternalinvoicesByMonth as $m) {
                                        $allMonths->push(['year' => $m->year, 'month' => $m->month, 'count' => $m->count]);
                                    }
                                    // دمج وتجميع الشهور حسب السنة والشهر
                                    $allMonths = $allMonths->groupBy(function($item) {
                                        return $item['year'].'-'.$item['month'];
                                    })->map(function($items) {
                                        $first = $items->first();
                                        return [
                                            'year' => $first['year'],
                                            'month' => $first['month'],
                                            'count' => $items->sum('count')
                                        ];
                                    })->sortByDesc(fn($m) => $m['year']*100 + $m['month']);

                                    // تجميع حسب السنة
                                    $years = $allMonths->groupBy('year');
                                @endphp
                                @foreach($years as $year => $months)
                                    @php
                                        $yearTotalPrice = 0;
                                        $yearTotalPaid = 0;
                                        $yearCount = 0;
                                        foreach($months as $month) {
                                            // Medical
                                            $medinvoices = $allmedexternalinvoices->filter(function($item) use ($month) {
                                                return $item->created_at->year == $month['year'] && $item->created_at->month == $month['month'];
                                            });
                                            foreach ($medinvoices as $invoiceitem) {
                                                foreach ($invoiceitem->medexternalinvoices as $item) {
                                                    $yearTotalPrice += $item->totalprice;
                                                }
                                                $yearTotalPaid += $invoiceitem->paid;
                                            }
                                            // Supplies
                                            $supinvoices = $allsupexternalinvoices->filter(function($item) use ($month) {
                                                return $item->created_at->year == $month['year'] && $item->created_at->month == $month['month'];
                                            });
                                            foreach ($supinvoices as $invoiceitem) {
                                                foreach ($invoiceitem->supexternalinvoices as $item) {
                                                    $yearTotalPrice += $item->totalprice;
                                                }
                                                $yearTotalPaid += $invoiceitem->paid;
                                            }
                                            $yearCount += $month['count'];
                                        }
                                    @endphp
                                    <tr style="background:#e3eafc;font-weight:bold;">
                                        <td>{{ $year }}</td>
                                        <td>{{ $yearCount }} Invoices</td>
                                        <td>{{ $yearTotalPrice }}.EGP</td>
                                        <td>{{ $yearTotalPaid }}.EGP
                                            / @if ($yearTotalPrice > $yearTotalPaid)
                                                <span class="text-danger">Debit: {{ $yearTotalPrice - $yearTotalPaid }}.EGP</span>
                                            @elseif ($yearTotalPrice < $yearTotalPaid)
                                            <span class="text-info">Credit: {{ $yearTotalPaid - $yearTotalPrice }}.EGP</span>
                                            @elseif ($yearTotalPrice == $yearTotalPaid)
                                            <span class="text-success">All Paid</span>
                                            @else
                                                <span class="text-warning">Unpaid</span>
                                            @endif
                                        </td>
                                    </tr>
                                    @foreach($months as $month)
                                        @php
                                            $totalPrice = 0;
                                            $totalPaid = 0;
                                            // Medical
                                            $medinvoices = $allmedexternalinvoices->filter(function($item) use ($month) {
                                                return $item->created_at->year == $month['year'] && $item->created_at->month == $month['month'];
                                            });
                                            foreach ($medinvoices as $invoiceitem) {
                                                foreach ($invoiceitem->medexternalinvoices as $item) {
                                                    $totalPrice += $item->totalprice;
                                                }
                                                $totalPaid += $invoiceitem->paid;
                                            }
                                            // Supplies
                                            $supinvoices = $allsupexternalinvoices->filter(function($item) use ($month) {
                                                return $item->created_at->year == $month['year'] && $item->created_at->month == $month['month'];
                                            });
                                            foreach ($supinvoices as $invoiceitem) {
                                                foreach ($invoiceitem->supexternalinvoices as $item) {
                                                    $totalPrice += $item->totalprice;
                                                }
                                                $totalPaid += $invoiceitem->paid;
                                            }
                                        @endphp
                                        <tr>
                                            <td>{{ DateTime::createFromFormat('!m', $month['month'])->format('F') }}</td>
                                            <td>{{ $month['count'] }} Invoices</td>
                                            <td>{{ $totalPrice }}.EGP</td>
                                            <td>{{ $totalPaid }}.EGP
                                            / @if ($totalPrice > $totalPaid)
                                                <span class="text-danger">Debit: {{ $totalPrice - $totalPaid }}.EGP</span>
                                            @elseif ($totalPrice < $totalPaid)
                                            <span class="text-info">Credit: {{ $totalPaid - $totalPrice }}.EGP</span>
                                            @elseif ($totalPrice == $totalPaid)
                                            <span class="text-success">All Paid</span>
                                            @else
                                                <span class="text-warning">Unpaid</span>
                                            @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
                </div>
            </div>
        </div>
    </div>
</div>
<h1>Medical External InvoicesMedical External InvoicesMedical External InvoicesMedical External InvoicesMedical External Invoices</h1>
{{-- Medical Invoices By Year & Month (Accordion) --}}
@if ($medexternalinvoicesByMonth->isNotEmpty())
    <div class="container-fluid p-3" style="background-color: #ffffff;">
        <div class="row">
            <div class="col-12">
                <div class="card m-auto">
                    <div class="card-header">
                        <h6 class="text-primary">All Medical External Invoices</h6>
                    </div>

                    @php
                        $groupedByYear = $medexternalinvoicesByMonth->groupBy('year');
                    @endphp

                    <div class="accordion" id="yearAccordion">
                        @foreach($groupedByYear as $year => $months)
                            @php
                                $yearTotalPrice = 0;
                                $yearTotalPaid = 0;
                                $yearCount = 0;
                                foreach($months as $month) {
                                    $medinvoices = $allmedexternalinvoices->filter(function($item) use ($month) {
                                        return $item->created_at->year == $month->year && $item->created_at->month == $month->month;
                                    });
                                    foreach ($medinvoices as $invoiceitem) {
                                        foreach ($invoiceitem->medexternalinvoices as $item) {
                                            $yearTotalPrice += $item->totalprice;
                                        }
                                        $yearTotalPaid += $invoiceitem->paid;
                                    }
                                    $yearCount += $month->count;
                                }
                            @endphp

                            {{-- Accordion للسنة --}}
                            <div class="accordion-item">
                                <h2 class="accordion-header" id="heading-{{ $year }}">
                                    <button class="accordion-button collapsed" type="button"
                                            data-bs-toggle="collapse"
                                            data-bs-target="#collapse-{{ $year }}"
                                            aria-expanded="false"
                                            aria-controls="collapse-{{ $year }}">
                                        {{ $year }} ({{ $yearCount }} Invoices - {{ $yearTotalPrice }} EGP / Paid: {{ $yearTotalPaid }} EGP)
                                    </button>
                                </h2>

                                <div id="collapse-{{ $year }}" class="accordion-collapse collapse"
                                     aria-labelledby="heading-{{ $year }}"
                                     data-bs-parent="#yearAccordion">
                                    <div class="accordion-body">

                                        {{-- Accordion للشهور داخل السنة --}}
                                        <div class="accordion" id="monthAccordion-{{ $year }}">
                                            @foreach($months as $month)
                                                @php
                                                    $medinvoices = $allmedexternalinvoices->filter(function($item) use ($month) {
                                                        return $item->created_at->year == $month->year && $item->created_at->month == $month->month;
                                                    });
                                                    $totalPrice = 0;
                                                    $totalPaid = 0;
                                                    foreach ($medinvoices as $invoiceitem) {
                                                        foreach ($invoiceitem->medexternalinvoices as $item) {
                                                            $totalPrice += $item->totalprice;
                                                        }
                                                        $totalPaid += $invoiceitem->paid;
                                                    }
                                                @endphp

                                                <div class="accordion-item">
                                                    <h2 class="accordion-header" id="heading-{{ $year }}-{{ $month->month }}">
                                                        <button class="accordion-button collapsed" type="button"
                                                                data-bs-toggle="collapse"
                                                                data-bs-target="#collapse-{{ $year }}-{{ $month->month }}"
                                                                aria-expanded="false"
                                                                aria-controls="collapse-{{ $year }}-{{ $month->month }}">
                                                            {{ DateTime::createFromFormat('!m', $month->month)->format('F') }}
                                                            ({{ $month->count }} Invoices - {{ $totalPrice }} EGP / Paid: {{ $totalPaid }} EGP)
                                                        </button>
                                                    </h2>

                                                    <div id="collapse-{{ $year }}-{{ $month->month }}" class="accordion-collapse collapse"
                                                         aria-labelledby="heading-{{ $year }}-{{ $month->month }}"
                                                         data-bs-parent="#monthAccordion-{{ $year }}">
                                                        <div class="accordion-body">

                                                            {{-- تفاصيل الفواتير داخل الشهر --}}
                                                            @foreach ($medinvoices as $invoiceitem)
                                                                @php
                                                                    $totalInvoicePrice = 0;
                                                                    foreach ($invoiceitem->medexternalinvoices as $item) {
                                                                        $totalInvoicePrice += $item->totalprice;
                                                                    }
                                                                @endphp
                                                                <table class="table table-bordered table-striped">
                                                                    <tr class="text-center">
                                                                        <th colspan="2" style="background-color:#338ded; color:#FFF;">
                                                                            Day: {{ $invoiceitem->created_at->format('d h:i A') }}
                                                                        </th>
                                                                    </tr>
                                                                    <tr>
                                                                        <th>Description</th>
                                                                        <th>Qty X Price = Total</th>
                                                                    </tr>
                                                                    @foreach ($invoiceitem->medexternalinvoices as $item)
                                                                        <tr>
                                                                            <td>{{ $item->item }}</td>
                                                                            <td>{{ $item->qty }} x {{ $item->price }} = {{ $item->totalprice }} EGP</td>
                                                                        </tr>
                                                                    @endforeach
                                                                    <tr>
                                                                        <td colspan="2" class="text-primary">
                                                                            Invoice Price: {{ $totalInvoicePrice }} EGP
                                                                            <br> Paid: {{ $invoiceitem->paid }} EGP
                                                                            /
                                                                            @if ($invoiceitem->paid == $totalInvoicePrice)
                                                                                <span class="text-success">All Paid</span>
                                                                            @elseif($invoiceitem->paid > $totalInvoicePrice)
                                                                                <span class="text-info">Credit: {{ $invoiceitem->paid - $totalInvoicePrice }} EGP</span>
                                                                            @elseif($invoiceitem->paid == 0)
                                                                                <span class="text-danger">Unpaid</span>
                                                                            @else
                                                                                <span class="text-danger">Debit: {{ $totalInvoicePrice - $invoiceitem->paid }} EGP</span>
                                                                            @endif
                                                                        </td>
                                                                    </tr>
                                                                </table>
                                                            @endforeach

                                                        </div>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>

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

{{-- Supplies Invoices By Month --}}
@if ($supexternalinvoicesByMonth->isNotEmpty())
    @if(isset($supexternalinvoicesByMonth) && count($supexternalinvoicesByMonth))
    <div class="container-fluid p-3 mt-2" style="background-color: #ffffff;">
            <div class="row">
                <div class="col-12">
                    <div class="card m-auto">
                        <div class="card-header">
                            <div class="row">
                                <div class="">
                                    <h6 class="text-primary">All Supplies External Invoices</h6>
                                </div>
                            </div>
                        </div>
                        <h5 class="text-primary m-2">Supplies Invoices By Month</h5>
                        <div class="mx-1 mb-0 mt-2 table-responsive">
                            <table class="table table-bordered table-responsive table-striped">
                                <thead>
                                    <tr>

                                        <th>Year</th>
                                        <th>Count</th>
                                        <th>Total Price</th>
                                        <th>Total Paid</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php
                                        $grouped = $supexternalinvoicesByMonth->groupBy('year');
                                    @endphp
                                    @foreach($grouped as $year => $months)
                                        @php
                                            $yearTotalPrice = 0;
                                            $yearTotalPaid = 0;
                                            $yearCount = 0;
                                            foreach($months as $month) {
                                                $supinvoices = $allsupexternalinvoices->filter(function($item) use ($month) {
                                                    return $item->created_at->year == $month->year && $item->created_at->month == $month->month;
                                                });
                                                foreach ($supinvoices as $invoiceitem) {
                                                    foreach ($invoiceitem->supexternalinvoices as $item) {
                                                        $yearTotalPrice += $item->totalprice;
                                                    }
                                                    $yearTotalPaid += $invoiceitem->paid;
                                                }
                                                $yearCount += $month->count;
                                            }
                                        @endphp
                                        <tr style="background:#e3eafc;font-weight:bold;">
                                            <td>{{ $year }}</td>
                                            <td>{{ $yearCount }} Invoices</td>
                                            <td>{{ $yearTotalPrice }}.EGP</td>
                                            <td>{{ $yearTotalPaid }}.EGP
                                                / @if ($yearTotalPrice > $yearTotalPaid)
                                                    <span class="text-danger">Debit: {{ $yearTotalPrice - $yearTotalPaid }}.EGP</span>
                                                @elseif ($yearTotalPrice < $yearTotalPaid)
                                                <span class="text-info">Credit: {{ $yearTotalPaid - $yearTotalPrice }}.EGP</span>
                                                @elseif ($yearTotalPrice == $yearTotalPaid)
                                                <span class="text-success">All Paid</span>
                                                @else
                                                    <span class="text-warning">Unpaid</span>
                                                @endif
                                            </td>
                                        </tr>
                                        @foreach($months as $i => $month)
                                            @php
                                                $supinvoices = $allsupexternalinvoices->filter(function($item) use ($month) {
                                                    return $item->created_at->year == $month->year && $item->created_at->month == $month->month;
                                                });
                                                $totalPrice = 0;
                                                $totalPaid = 0;
                                                foreach ($supinvoices as $invoiceitem) {
                                                    foreach ($invoiceitem->supexternalinvoices as $item) {
                                                        $totalPrice += $item->totalprice;
                                                    }
                                                    $totalPaid += $invoiceitem->paid;
                                                }
                                            @endphp
                                            <tr>
                                                <td>{{ DateTime::createFromFormat('!m', $month->month)->format('F') }}</td>
                                                <td>{{ $month->count }} Invoices</td>
                                                <td>{{ $totalPrice }}.EGP</td>
                                                <td>{{ $totalPaid }}.EGP
                                                / @if ($totalPrice > $totalPaid)
                                                    <span class="text-danger">Debit: {{ $totalPrice - $totalPaid }}.EGP</span>
                                                @elseif ($totalPrice < $totalPaid)
                                                <span class="text-info">Credit: {{ $totalPaid - $totalPrice }}.EGP</span>
                                                @elseif ($totalPrice == $totalPaid)
                                                <span class="text-success">All Paid</span>
                                                @else
                                                    <span class="text-warning">Unpaid</span>
                                                @endif
                                                </td>
                                            </tr>
                                        @endforeach
                                    @endforeach
                                </tbody>
                            </table>
                            <div class="pages text-center">
                                @if(method_exists($supexternalinvoicesByMonth, 'links'))
                                    {{ $supexternalinvoicesByMonth->links() }}
                                @endif
                            </div>
                        </div>
                        <div class="card-body table-responsive mx-2 px-0 pb-2">
                            <table class="table  text-center  table-striped mb-0">
                                <tbody class="table  text-center mb-0">
                                    @foreach ($supexternalinvoicesByMonth as $month)
                                        @php
                                            $supinvoices = $allsupexternalinvoices->filter(function($item) use ($month) {
                                                return $item->created_at->year == $month->year && $item->created_at->month == $month->month;
                                            });
                                        @endphp
                                        @if($supinvoices->count())
                                            <div class="table-responsive ">
                                                <table class="table table-bordered table-striped p-3">
                                                    <tr class="text-center">
                                                        <th colspan="2" style="background-color: #338ded; color: #FFF;">
                                                            {{ DateTime::createFromFormat('!m', $month->month)->format('F') }} {{ $month->year }}
                                                        </th>
                                                    </tr>
                                                    <tr>
                                                        <th style="background-color: #338ded; color: #FFF;text-align: center;">
                                                            Supplies Invoices
                                                            <br>
                                                            @php
                                                                $totalsupInvoicePrice = 0;
                                                                foreach ($supinvoices as $invoiceitem) {
                                                                    $invoiceTotal = 0;
                                                                    foreach ($invoiceitem->supexternalinvoices as $item) {
                                                                        $invoiceTotal += $item->qty * $item->unitprice;
                                                                    }
                                                                    $totalsupInvoicePrice += $invoiceTotal;
                                                                }
                                                            @endphp
                                                            Total: {{ $totalsupInvoicePrice }}.EGP
                                                        </th>
                                                        <th style="background-color: #338ded; color: #FFF;">
                                                            @if ($supinvoices->sum('paid') == $totalsupInvoicePrice)
                                                                <br>  <span class="text-success">All Paid</span>
                                                            @elseif($supinvoices->sum('paid') > $totalsupInvoicePrice)
                                                                Total Paid: {{ $supinvoices->sum('paid') }}.EGP
                                                                <br>
                                                                <span class="text-info">Total Credit: {{ $supinvoices->sum('paid') - $totalsupInvoicePrice}}.EGP</span>
                                                            @elseif($supinvoices->sum('paid') == 0)
                                                                <span class="text-danger">Total Debit: {{ $totalsupInvoicePrice }}.EGP</span>
                                                            @else
                                                                Total Paid: {{ $supinvoices->sum('paid') }}.EGP
                                                                <br>
                                                                <span class="text-danger">Total Debit: {{ $totalsupInvoicePrice - $supinvoices->sum('paid') }}.EGP</span>
                                                            @endif
                                                        </th>
                                                    </tr>

                                                @foreach ($supinvoices as $invoiceitem)
                                                    @php
                                                        $totalInvoicePrice = 0;
                                                        foreach ($invoiceitem->supexternalinvoices as $item) {
                                                            $totalInvoicePrice += $item->qty * $item->unitprice;
                                                        }
                                                    @endphp
                                                    <tbody class="text-center">
                                                            <tr>
                                                                <th colspan="2" style="background-color: #338ded; color: #FFF;">
                                                                    Day: {{ $invoiceitem->created_at->format('d h:iA') }}
                                                                </th>
                                                            </tr>
                                                            @foreach ($invoiceitem->supexternalinvoices as $item)
                                                            <tr>
                                                                <td>Description</td>
                                                                <td>Qty X Price</td>
                                                            </tr>
                                                            <tr>
                                                                <td>
                                                                    {{ $item->item }}
                                                                </td>
                                                                <td>
                                                                    {{ $item->qty }} X {{ $item->price }}
                                                                    / {{ $item->totalprice }}.EGP
                                                                </td>
                                                            </tr>

                                                            @endforeach

                                                            <tr>
                                                                <td colspan="2" class=" text-Primary">
                                                                    Invoice Price: {{ $totalInvoicePrice }}.EGP
                                                                    <br> Paid: {{ $invoiceitem->paid }}.EGP
                                                                    / @if ($invoiceitem->paid == $totalInvoicePrice)
                                                                        <span class="text-success">All Paid</span>
                                                                    @elseif($invoiceitem->paid > $totalInvoicePrice)
                                                                        <span class="text-info">Credit: {{ $invoiceitem->paid - $totalInvoicePrice}}.EGP</span>
                                                                    @elseif($invoiceitem->paid == 0)
                                                                        <span class="text-danger">Unpaid</span>
                                                                    @else
                                                                        <span class="text-danger">Debit: {{ $totalInvoicePrice - $invoiceitem->paid }}.EGP</span>
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
                                    <div class="pages text-center">
                                        {{ $supexternalinvoicesByMonth->links() }}
                                    </div>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    @endif
@endif
@endsection

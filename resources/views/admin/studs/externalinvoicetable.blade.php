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

                        <div class="table-responsive">
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
                                            Invoices : {{ $stud->externalinvoices->count() }}
                                            <br>
                                            Price: {{ $totalMedInvoicePrice + $totalSupInvoicePrice }}.EGP
                                        </th>
                                        <th style="background-color: #338ded; color: #FFF;">
                                            Paid: {{ $stud->externalinvoices->sum('paid') }}.EGP
                                            <br>
                                            @if ($stud->externalinvoices->sum('paid') > $totalMedInvoicePrice + $totalSupInvoicePrice)
                                                <span class="text-info">Credit : {{ $stud->externalinvoices->sum('paid') - ($totalMedInvoicePrice + $totalSupInvoicePrice) }}.EGP</span>
                                            @elseif ($stud->externalinvoices->sum('paid') < $totalMedInvoicePrice + $totalSupInvoicePrice)
                                                <span class="text-danger">Debit : {{ ($totalMedInvoicePrice + $totalSupInvoicePrice) - $stud->externalinvoices->sum('paid') }}.EGP</span>
                                            @elseif ($stud->externalinvoices->sum('paid') == $totalMedInvoicePrice + $totalSupInvoicePrice)
                                                <span class="text-success">All Paid</span>
                                            @else
                                                <span class="text-warning">Unpaid</span>
                                            @endif
                                        </th>
                                    </tr>
                                </thead>
                            </table>
                        </div>

                        @php
                            // تجهيز البيانات: دمج الشهور MED + SUP
                            $allMonths = collect();
                            foreach ($medexternalinvoicesByMonth as $m) {
                                $allMonths->push(['year' => $m->year, 'month' => $m->month, 'count' => $m->count]);
                            }
                            foreach ($supexternalinvoicesByMonth as $m) {
                                $allMonths->push(['year' => $m->year, 'month' => $m->month, 'count' => $m->count]);
                            }
                            $allMonths = $allMonths->groupBy(fn($item) => $item['year'].'-'.$item['month'])
                                ->map(function($items) {
                                    $first = $items->first();
                                    return [
                                        'year' => $first['year'],
                                        'month' => $first['month'],
                                        'count' => $items->sum('count')
                                    ];
                                })
                                ->sortByDesc(fn($m) => $m['year']*100 + $m['month']);
                            $years = $allMonths->groupBy('year');
                        @endphp

                        <div class="accordion mt-3" id="externalInvoicesAccordion">
                            @foreach($years as $year => $months)
                                @php
                                    $yearTotalPrice = 0;
                                    $yearTotalPaid = 0;
                                    $yearCount = 0;
                                    foreach($months as $month) {
                                        $medinvoices = $allmedexternalinvoices->filter(fn($item) => $item->created_at->year == $month['year'] && $item->created_at->month == $month['month']);
                                        foreach ($medinvoices as $invoiceitem) {
                                            foreach ($invoiceitem->medexternalinvoices as $item) {
                                                $yearTotalPrice += $item->totalprice;
                                            }
                                            $yearTotalPaid += $invoiceitem->paid;
                                        }
                                        $supinvoices = $allsupexternalinvoices->filter(fn($item) => $item->created_at->year == $month['year'] && $item->created_at->month == $month['month']);
                                        foreach ($supinvoices as $invoiceitem) {
                                            foreach ($invoiceitem->supexternalinvoices as $item) {
                                                $yearTotalPrice += $item->totalprice;
                                            }
                                            $yearTotalPaid += $invoiceitem->paid;
                                        }
                                        $yearCount += $month['count'];
                                    }
                                @endphp

                                <div class="accordion-item">
                                    <h2 class="accordion-header" id="heading{{ $year }}">
                                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse{{ $year }}" aria-expanded="false" aria-controls="collapse{{ $year }}">
                                            📅 Year: {{ $year }} | Total Invoices: {{ $yearCount }}  |Total: {{ $yearTotalPrice }} EGP | Paid: {{ $yearTotalPaid }} EGP
                                        </button>
                                    </h2>
                                    <div id="collapse{{ $year }}" class="accordion-collapse collapse" aria-labelledby="heading{{ $year }}" data-bs-parent="#externalInvoicesAccordion">
                                        <div class="accordion-body p- table-responsive table-responsive">
                                            <table class="table table-bordered table-responsive">

                                                <tbody>
                                                    @foreach($months as $month)
                                                        @php
                                                            $totalPrice = 0;
                                                            $totalPaid = 0;
                                                            $medinvoices = $allmedexternalinvoices->filter(fn($item) => $item->created_at->year == $month['year'] && $item->created_at->month == $month['month']);
                                                            foreach ($medinvoices as $invoiceitem) {
                                                                foreach ($invoiceitem->medexternalinvoices as $item) {
                                                                    $totalPrice += $item->totalprice;
                                                                }
                                                                $totalPaid += $invoiceitem->paid;
                                                            }
                                                            $supinvoices = $allsupexternalinvoices->filter(fn($item) => $item->created_at->year == $month['year'] && $item->created_at->month == $month['month']);
                                                            foreach ($supinvoices as $invoiceitem) {
                                                                foreach ($invoiceitem->supexternalinvoices as $item) {
                                                                    $totalPrice += $item->totalprice;
                                                                }
                                                                $totalPaid += $invoiceitem->paid;
                                                            }
                                                        @endphp
                                                        <tr>
                                                            <td>{{ DateTime::createFromFormat('!m', $month['month'])->format('F') }}
                                                                <br>
                                                            Invoices Count : {{ $month['count'] }}/
                                                            Total Price : {{ $totalPrice }}.EGP
                                                            <br>
                                                            TotalPaid : {{ $totalPaid }}.EGP /
                                                            @if ($totalPrice > $totalPaid)
                                                                <span class="text-danger">Debit: {{ $totalPrice - $totalPaid }}.EGP</span>
                                                                @elseif ($totalPrice < $totalPaid)
                                                                    <span class="text-info">Credit: {{ $totalPaid - $totalPrice }}.EGP</span>
                                                                @else
                                                                    <span class="text-success">All Paid</span>
                                                                @endif
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif

            </div>
        </div>
    </div>
</div>
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
                                            <table class="table-responsive">
                                                <tr>
                                                    <th>{{ $year }}
                                                        <br>
                                                         ({{ $yearCount }} Invoices / Price: {{ $yearTotalPrice }} EGP
                                                    <br>
                                                    Paid: {{ $yearTotalPaid }} EGP
                                                    @if ($yearTotalPrice > $yearTotalPaid)
                                                            / <span class="text-danger">Debit: {{ $yearTotalPrice - $yearTotalPaid }} EGP</span>
                                                    @elseif ($yearTotalPrice < $yearTotalPaid)
                                                    / <span class="text-info">Credit: {{ $yearTotalPaid - $yearTotalPrice }} EGP</span>
                                                    @elseif ($yearTotalPrice == $yearTotalPaid)
                                                    / <span class="text-success">All Paid</span>
                                                    @else
                                                    @endif
                                                    </td>
                                                </tr>
                                            </table>

                                        )
                                    </button>
                                </h2>

                                <div id="collapse-{{ $year }}" class="accordion-collapse collapse"
                                     aria-labelledby="heading-{{ $year }}"
                                     data-bs-parent="#yearAccordion">
                                    <div class="accordion-body p-1 table-responsive">

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
                                                                <table class="table-responsive">
                                                                    <tr>
                                                                        <td>
                                                                            {{ DateTime::createFromFormat('!m', $month->month)->format('F') }}
                                                                            <br>
                                                                            ({{ $month->count }} Invoices / Price : {{ $totalPrice }} EGP
                                                                            <br>
                                                                             Paid : {{ $totalPaid }} EGP
                                                                            @if ($totalPrice > $totalPaid)
                                                                                / <span class="text-danger">Debit: {{ $totalPrice - $totalPaid }} EGP</span>
                                                                            @elseif ($totalPrice < $totalPaid)
                                                                                / <span class="text-info">Credit: {{ $totalPaid - $totalPrice }} EGP</span>
                                                                            @elseif ($totalPrice == $totalPaid)
                                                                                / <span class="text-success">All Paid</span>
                                                                            @else
                                                                            @endif
                                                                            )
                                                                        </td>
                                                                    </tr>
                                                                </table>

                                                        </button>
                                                    </h2>

                                                    <div id="collapse-{{ $year }}-{{ $month->month }}" class="accordion-collapse collapse"
                                                         aria-labelledby="heading-{{ $year }}-{{ $month->month }}"
                                                         data-bs-parent="#monthAccordion-{{ $year }}">
                                                        <div class="accordion-body p-1 table-responsive">

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

{{-- Supplies Invoices By Year & Month (Accordion) --}}
@if ($supexternalinvoicesByMonth->isNotEmpty())
    <div class="container-fluid p-3 mt-2" style="background-color: #ffffff;">
        <div class="row">
            <div class="col-12">
                <div class="card m-auto">
                    <div class="card-header">
                        <h6 class="text-primary">All Supplies External Invoices</h6>
                    </div>

                    @php
                        $groupedByYear = $supexternalinvoicesByMonth->groupBy('year');
                    @endphp

                    <div class="accordion" id="supYearAccordion">
                        @foreach($groupedByYear as $year => $months)
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

                            {{-- Accordion للسنة --}}
                            <div class="accordion-item">
                                <h2 class="accordion-header" id="sup-heading-{{ $year }}">
                                    <button class="accordion-button collapsed" type="button"
                                            data-bs-toggle="collapse"
                                            data-bs-target="#sup-collapse-{{ $year }}"
                                            aria-expanded="false"
                                            aria-controls="sup-collapse-{{ $year }}">
                                        <table class="table-responsive">
                                                <tr>
                                                    <td>{{ $year }}
                                                        <br>
                                                         ({{ $yearCount }} Invoices / Price: {{ $yearTotalPrice }} EGP
                                                    <br>
                                                    Paid: {{ $yearTotalPaid }} EGP
                                                    @if ($yearTotalPrice > $yearTotalPaid)
                                                            / <span class="text-danger">Debit: {{ $yearTotalPrice - $yearTotalPaid }} EGP</span>
                                                    @elseif ($yearTotalPrice < $yearTotalPaid)
                                                    / <span class="text-info">Credit: {{ $yearTotalPaid - $yearTotalPrice }} EGP</span>
                                                    @elseif ($yearTotalPrice == $yearTotalPaid)
                                                    / <span class="text-success">All Paid</span>
                                                    @else
                                                    @endif
                                                    </td>
                                                </tr>
                                            </table>
                                            )
                                    </button>
                                </h2>

                                <div id="sup-collapse-{{ $year }}" class="accordion-collapse collapse"
                                     aria-labelledby="sup-heading-{{ $year }}"
                                     data-bs-parent="#supYearAccordion">
                                    <div class="accordion-body p-1 table-responsive">

                                        {{-- Accordion للشهور داخل السنة --}}
                                        <div class="accordion" id="supMonthAccordion-{{ $year }}">
                                            @foreach($months as $month)
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

                                                <div class="accordion-item">
                                                    <h2 class="accordion-header" id="sup-heading-{{ $year }}-{{ $month->month }}">
                                                        <button class="accordion-button collapsed" type="button"
                                                                data-bs-toggle="collapse"
                                                                data-bs-target="#sup-collapse-{{ $year }}-{{ $month->month }}"
                                                                aria-expanded="false"
                                                                aria-controls="sup-collapse-{{ $year }}-{{ $month->month }}">
                                                            <table class="table-responsive">
                                                                    <tr>
                                                                        <td>
                                                                            {{ DateTime::createFromFormat('!m', $month->month)->format('F') }}
                                                                            <br>
                                                                            ({{ $month->count }} Invoices / Price : {{ $totalPrice }} EGP
                                                                            <br>
                                                                             Paid : {{ $totalPaid }} EGP
                                                                            @if ($totalPrice > $totalPaid)
                                                                                / <span class="text-danger">Debit: {{ $totalPrice - $totalPaid }} EGP</span>
                                                                            @elseif ($totalPrice < $totalPaid)
                                                                                / <span class="text-info">Credit: {{ $totalPaid - $totalPrice }} EGP</span>
                                                                            @elseif ($totalPrice == $totalPaid)
                                                                                / <span class="text-success">All Paid</span>
                                                                            @else
                                                                            @endif
                                                                            )
                                                                        </td>
                                                                    </tr>
                                                                </table>
                                                            </button>
                                                    </h2>

                                                    <div id="sup-collapse-{{ $year }}-{{ $month->month }}" class="accordion-collapse collapse"
                                                         aria-labelledby="sup-heading-{{ $year }}-{{ $month->month }}"
                                                         data-bs-parent="#supMonthAccordion-{{ $year }}">
                                                        <div class="accordion-body p-1 table-responsive">

                                                            {{-- تفاصيل الفواتير داخل الشهر --}}
                                                            @foreach ($supinvoices as $invoiceitem)
                                                                @php
                                                                    $totalInvoicePrice = 0;
                                                                    foreach ($invoiceitem->supexternalinvoices as $item) {
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
                                                                    @foreach ($invoiceitem->supexternalinvoices as $item)
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
@endsection

@extends('layouts.admin')
<title>Invoices Details</title>
@section('content')
<div class="container-fluid py-4">
    <div class="row pt-4 p-2">
        <div class="col-12">
            <div class="card my-4">
                <div class="card-header p-0 position-relative mt-n4 mx-3 z-index-2">
                    <div class="bg-gradient-info shadow-primary border-radius-lg pt-4 pb-5">
                        <h4 class="text-white text-capitalize pe-3 ps-3">All Invoices Details</h4>
                    </div>
                </div>

                @if(isset($invoicesByMonth) && $invoicesByMonth->count())

                <div class="mt-4">
                    <h5 class="text-primary">Invoices By Year & Month</h5>

                    @php
                        $groupedByYear = $invoicesByMonth->groupBy('year');
                        $invoiceYears = $groupedByYear->keys();
                        $selectedInvoiceYear = request('invoice_year', $invoiceYears->first());
                    @endphp

                    {{-- Dropdown اختيار السنة --}}
                    <div class="mb-3">
                        <form method="GET" action="">
                            <select name="invoice_year" id="invoice_year" class="form-select w-auto d-inline-block" onchange="this.form.submit()">
                                @foreach($invoiceYears as $year)
                                    <option value="{{ $year }}" {{ $selectedInvoiceYear == $year ? 'selected' : '' }}>
                                        {{ $year }}
                                    </option>
                                @endforeach
                            </select>
                        </form>
                    </div>

                    {{-- Accordion --}}
                    @if($groupedByYear->has($selectedInvoiceYear))
                        <div class="accordion" id="accordion-{{ $selectedInvoiceYear }}">
                            <div class="accordion-item">
                                <h2 class="accordion-header" id="heading-year-{{ $selectedInvoiceYear }}">
                                    <button class="accordion-button" type="button" data-bs-toggle="collapse"
                                            data-bs-target="#collapse-year-{{ $selectedInvoiceYear }}" aria-expanded="true"
                                            aria-controls="collapse-year-{{ $selectedInvoiceYear }}">
                                            <div class="">

                                                Year: {{ $selectedInvoiceYear }} <br>

                                                All Invoices: {{ $groupedByYear[$selectedInvoiceYear]->sum('count') }}
                                                Total Price: {{$groupedByYear[$selectedInvoiceYear]->sum('totalPrice') }} EGP <br>
                                                Paid: {{$groupedByYear[$selectedInvoiceYear]->sum('totalPaid') }} EGP
                                                <hr>
                                                @if ($groupedByYear[$selectedInvoiceYear]->sum('totalPaid') < $groupedByYear[$selectedInvoiceYear]->sum('totalPrice'))
                                                    <span class="text-danger">Debit: {{$groupedByYear[$selectedInvoiceYear]->sum('totalPrice') - $groupedByYear[$selectedInvoiceYear]->sum('totalPaid') }} EGP</span>
                                                @elseif ($groupedByYear[$selectedInvoiceYear]->sum('totalPaid') > $groupedByYear[$selectedInvoiceYear]->sum('totalPrice'))
                                                    <span class="text-success">Credit: {{$groupedByYear[$selectedInvoiceYear]->sum('totalPaid') - $groupedByYear[$selectedInvoiceYear]->sum('totalPrice') }} EGP</span>
                                                @else
                                                    <span class="text-primary">All Paid</span>
                                                @endif
                                            </div>
                                    </button>
                                </h2>
                                <div id="collapse-year-{{ $selectedInvoiceYear }}" class="accordion-collapse collapse show"
                                    aria-labelledby="heading-year-{{ $selectedInvoiceYear }}" data-bs-parent="#accordion-{{ $selectedInvoiceYear }}">
                                    <div class="accordion-body">

                                        {{-- شهور السنة --}}
                                        <div class="accordion" id="accordion-months-{{ $selectedInvoiceYear }}">
                                            @foreach($groupedByYear[$selectedInvoiceYear] as $month)
                                                <div class="accordion-item">
                                                    <h2 class="accordion-header" id="heading-month-{{ $month->month }}">
                                                        <button class="accordion-button collapsed" type="button"
                                                                data-bs-toggle="collapse"
                                                                data-bs-target="#collapse-month-{{ $selectedInvoiceYear }}-{{ $month->month }}"
                                                                aria-expanded="false"
                                                                aria-controls="collapse-month-{{ $selectedInvoiceYear }}-{{ $month->month }}">
                                                            {{ DateTime::createFromFormat('!m', $month->month)->format('F') }}
                                                            / Invoices: {{ $month->count }}
                                                        </button>
                                                    </h2>
                                                    <div id="collapse-month-{{ $selectedInvoiceYear }}-{{ $month->month }}"
                                                        class="accordion-collapse collapse"
                                                        aria-labelledby="heading-month-{{ $month->month }}"
                                                        data-bs-parent="#accordion-months-{{ $selectedInvoiceYear }}">
                                                        <div class="accordion-body">
                                                            Total Price: {{$month->totalPrice }} EGP /
                                                            Paid: {{$month->totalPaid }} EGP <br>
                                                            @if ($month->totalPaid < $month->totalPrice)
                                                                <span class="text-danger">Debit: {{$month->totalPrice - $month->totalPaid }} EGP</span>
                                                            @elseif ($month->totalPaid > $month->totalPrice)
                                                                <span class="text-success">Credit: {{$month->totalPaid - $month->totalPrice }} EGP</span>
                                                            @else
                                                                <span class="text-primary">All Paid</span>
                                                            @endif
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

                </div>
                @endif








                @if ($invoicesByMonth->isNotEmpty())
                    <div class="card-body m-2 p-0 table-responsive">
                        <table class="table table-bordered text-center table-responsive m-2 px-0 pb-2 table-striped">
                            @foreach ($invoicesByMonth as $month)
                                @php
                                    $invoiceMonth = $allinvoices->filter(function($item) use ($month) {
                                        return $item->created_at->year == $month->year && $item->created_at->month == $month->month;
                                    });

                                    // FIX: use correct relationship names and default to empty collection
                                    $monthTotalPrice = $invoiceMonth->sum(function($invoice) {
                                        $meds = $invoice->medinternalinvoices ?? collect();
                                        $sups = $invoice->supinternalinvoices ?? collect();
                                        return ($meds->sum('totalprice')) + ($sups->sum('totalprice'));
                                    });
                                @endphp

                                @if($invoiceMonth->count())

                                    <thead>
                                        <tr>
                                            <th style="background-color: #4a91ee; color: white;">
                                                <span style="color: black">
                                                    {{ DateTime::createFromFormat('!m', $month->month)->format('F') }} {{ $month->year }}
                                                </span>
                                                <br>
                                            </th>
                                            <th style="background-color: #4a91ee; color: white;">
                                                <span style="color: black">
                                                    ({{ $invoiceMonth->count() }} Invoices)
                                                </span>
                                                <br>
                                            </th>
                                        </tr>

                                        <tr>
                                            <th colspan="2" style="background-color: #4a91ee; color: white;">
                                                Total Price: {{$monthTotalPrice }} EGP
                                            <br>
                                                Paid: {{$invoiceMonth->sum('paid') }} EGP
                                                @if ($monthTotalPrice > $invoiceMonth->sum('paid'))
                                                    <span class="text-danger">Debit: {{$monthTotalPrice - $invoiceMonth->sum('paid') }} EGP</span>
                                                @elseif ($monthTotalPrice < $invoiceMonth->sum('paid'))
                                                    <span class="text-success">Credit: {{$invoiceMonth->sum('paid') - $monthTotalPrice }} EGP</span>
                                                @else
                                                    <span class="text-primary">All Paid</span>
                                                @endif
                                            </th>
                                        </tr>
                                    </thead>
                                    @foreach ($invoiceMonth as $invoice)
                                        <tbody>
                                            <tr>
                                                <th>
                                                    <a class="text-primary" href="{{ url('Supplier/Accounts/'.$invoice->supplier->name) }}" >{{ $invoice->supplier->name}} </a>
                                                </th>
                                                <th>
                                                        Day : {{ date('d h:iA', strtotime($invoice->created_at)) }}
                                                </th>
                                            </tr>
                                            {{-- Medical Invoices for this invoice --}}
                                            @php
                                                $meds = $invoice->medinternalinvoices ?? collect();
                                            @endphp
                                            @if ($meds->count())
                                                <tr>
                                                    <td>
                                                        <strong>Medical Invoice</strong>
                                                        <br>
                                                        Price: {{ $meds->sum('totalprice') }}
                                                    </td>

                                                    <td>
                                                        Paid: {{ $invoice->paid ?? '' }} <br>
                                                        @if ($invoice->paid > $meds->sum('totalprice'))
                                                            <span class="text-success">
                                                                Credit: {{ $invoice->paid - $meds->sum('totalprice') }}</span>
                                                        @elseif ($invoice->paid < $meds->sum('totalprice'))
                                                            <span class="text-danger">Debit: {{ $meds->sum('totalprice') - $invoice->paid }}</span>
                                                        @else
                                                            <span class="text-primary">All Paid</span>
                                                        @endif
                                                    </td>
                                                </tr>
                                            @endif
                                            {{-- Supplies Invoices for this invoice --}}
                                            @php
                                                $sups = $invoice->supinternalinvoices ?? collect();
                                            @endphp
                                            @if ($sups->count())
                                                <tr>
                                                    <td>
                                                        <strong>Supplies Invoice</strong>
                                                        <br>
                                                        Price: {{ $sups->sum('totalprice') }}
                                                    </td>
                                                    <td>

                                                        Paid: {{ $invoice->paid ?? '' }} <br>
                                                        @if ($invoice->paid > $sups->sum('totalprice'))
                                                            <span class="text-success">
                                                                Credit: {{ $invoice->paid - $sups->sum('totalprice') }}</span>
                                                        @elseif ($invoice->paid < $sups->sum('totalprice'))
                                                            <span class="text-danger">Debit: {{ $sups->sum('totalprice') - $invoice->paid }}</span>
                                                        @else
                                                            <span class="text-primary">All Paid</span>
                                                        @endif
                                                    </td>
                                                </tr>
                                            @endif
                                        </tbody>
                                    @endforeach
                                @endif
                            @endforeach
                        </table>
                        <div class="pages text-center">
                            {{ $invoicesByMonth->links() }}
                        </div>
                    </div>
                @else
                    <div class="card-body text-center">
                        <h5 class="text-danger">No Invoices Found</h5>
                    </div>
                @endif

            </div>
        </div>
    </div>
</div>


@endsection


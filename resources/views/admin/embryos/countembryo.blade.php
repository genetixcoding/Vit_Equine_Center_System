@extends('layouts.admin')
<title>Embryo Page</title>
@section('content')
<div class="container-fluid py-4">
    <div class="row pt-4 p-2">
        <div class="col-12">
            <div class="card my-4">
                <div class="card-header p-0 position-relative mt-n4 mx-3 z-index-2">
                    <div class="bg-gradient-info shadow-primary border-radius-lg pt-4 pb-5">
                        <h4 class="text-white text-capitalize ps-3">Embryo Table</h4>
                    </div>
                </div>

                {{-- Embryos By Year & Month --}}
                @if(isset($embryosByMonth) && $embryosByMonth->count())
                    @php
                        $groupedByYear = $embryosByMonth->groupBy('year');
                    @endphp
                    <div class="p-1">
                        <h5 class="text-primary">Embryos By Year & Month</h5>
                        <div class="accordion" id="yearEmbryoAccordion">
                            @foreach($groupedByYear as $year => $months)
                                <div class="accordion-item">
                                    <h2 class="accordion-header" id="headingYearEmbryo{{ $year }}">
                                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseYearEmbryo{{ $year }}" aria-expanded="false" aria-controls="collapseYearEmbryo{{ $year }}">
                                            Year: {{ $year }} ({{ $groupedByYear[$year]->sum('count') }} Embryos)
                                        </button>
                                    </h2>
                                    <div id="collapseYearEmbryo{{ $year }}" class="accordion-collapse collapse" aria-labelledby="headingYearEmbryo{{ $year }}" data-bs-parent="#yearEmbryoAccordion">
                                        <div class="accordion-body">

                                            <strong>
                                                Total Cost: {{ $groupedByYear[$year]->sum('cost') }}.EGP /
                                                Paid: {{ $groupedByYear[$year]->sum('paid') }}.EGP /
                                                @if ($groupedByYear[$year]->sum('cost') > $groupedByYear[$year]->sum('paid'))
                                                    <span class="text-danger">Unpaid: {{ $groupedByYear[$year]->sum('cost') - $groupedByYear[$year]->sum('paid') }}.EGP</span>
                                                @elseif ($groupedByYear[$year]->sum('cost') < $groupedByYear[$year]->sum('paid'))
                                                    <span class="text-success">Credit: {{ $groupedByYear[$year]->sum('paid') - $groupedByYear[$year]->sum('cost') }}.EGP</span>
                                                @else
                                                    <span class="text-success">All Paid</span>
                                                @endif
                                            </strong>

                                            {{-- Months inside year --}}
                                            <div class="accordion mt-2" id="monthEmbryoAccordion{{ $year }}">
                                                @foreach($months as $month)
                                                    <div class="accordion-item">
                                                        <h2 class="accordion-header" id="headingMonthEmbryo{{ $year.$month->month }}">
                                                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseMonthEmbryo{{ $year.$month->month }}" aria-expanded="false" aria-controls="collapseMonthEmbryo{{ $year.$month->month }}">
                                                                {{ DateTime::createFromFormat('!m', $month->month)->format('F') }} ({{ $month->count }}) Embryos
                                                            </button>
                                                        </h2>
                                                        <div id="collapseMonthEmbryo{{ $year.$month->month }}" class="accordion-collapse collapse" aria-labelledby="headingMonthEmbryo{{ $year.$month->month }}" data-bs-parent="#monthEmbryoAccordion{{ $year }}">
                                                            <div class="accordion-body p-0 table-responsive">
                                                                <table class="table table-bordered text-center table-striped mb-0">
                                                                    <thead>
                                                                        <tr>
                                                                            <th style="background-color: #4a91ee; color: white;">
                                                                                Total Cost: {{ $month->cost }}.EGP / Paid: {{ $month->paid }}.EGP
                                                                                <br>
                                                                                @if ($month->cost == 0)
                                                                                    Free
                                                                                @elseif ($month->paid == null)
                                                                                    <span class="text-danger">Not Paid</span>
                                                                                @elseif ($month->cost > $month->paid)
                                                                                    <span class="text-danger">Debit: {{ abs($month->cost - $month->paid) }}.EGP</span>
                                                                                @elseif ($month->cost < $month->paid)
                                                                                    <span class="text-success">Credit: {{ abs($month->paid - $month->cost) }}.EGP</span>
                                                                                @else
                                                                                    <span class="text-success">All Paid</span>
                                                                                @endif
                                                                            </th>
                                                                        </tr>
                                                                    </thead>
                                                                    <tbody>
                                                                        @php
                                                                            $embryos = $allembryos->filter(function($item) use ($month) {
                                                                                return $item->created_at->year == $month->year && $item->created_at->month == $month->month;
                                                                            });
                                                                        @endphp
                                                                        @foreach($embryos as $item)
                                                                            <tr>
                                                                                <td>
                                                                                    <a href="{{ url('Details/Embryo/'.$item->id) }}">{{ $item->localhorsename }}</a> /
                                                                                    Day: {{ date('d h:iA', strtotime($item->created_at)) }}
                                                                                </td>
                                                                            </tr>
                                                                            <tr>
                                                                                <td>
                                                                                    @if ($item->cost)
                                                                                        Cost: {{ $item->cost }}.EGP
                                                                                    @else
                                                                                        <span class="text-success">Totally Free</span>
                                                                                    @endif
                                                                                    @if ($item->paid)
                                                                                        / Paid: {{ $item->paid }}.EGP
                                                                                    @else
                                                                                        / <span class="text-danger">Not Paid</span> /
                                                                                    @endif
                                                                                    @if ($item->cost > $item->paid)
                                                                                        <span class="text-danger">Debit: {{ $item->cost - $item->paid }}.EGP</span>
                                                                                    @elseif ($item->cost < $item->paid)
                                                                                        <span class="text-success">Credit: {{ $item->paid - $item->cost }}.EGP</span>
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
@endsection

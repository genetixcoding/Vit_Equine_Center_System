@extends('layouts.admin')
<title>Breedings Page</title>
@section('content')
<div class="container-fluid py-4">
    <div class="row pt-4 p-2">
        <div class="col-12">
            <div class="card my-4">
                <div class="card-header p-0 position-relative mt-n4 mx-3 z-index-2">
                    <div class="bg-gradient-info shadow-primary border-radius-lg pt-4 pb-5">
                        <h4 class="text-white text-capitalize ps-3">Breedings Count</h4>
                    </div>
                </div>

                {{-- Breedings By Year & Month --}}
                @if(isset($breedingsByMonth) && $breedingsByMonth->count())
                    @php
                        $groupedByYear = $breedingsByMonth->groupBy('year');
                    @endphp
                    <div class="p-1">
                        <h5 class="text-primary">Breedings By Year & Month</h5>
                        <div class="accordion" id="yearBreedingAccordion">
                            @foreach($groupedByYear as $year => $months)
                                <div class="accordion-item">
                                    <h2 class="accordion-header" id="headingYearBreeding{{ $year }}">
                                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseYearBreeding{{ $year }}" aria-expanded="false" aria-controls="collapseYearBreeding{{ $year }}">
                                            Year: {{ $year }} ({{ $groupedByYear[$year]->sum('count') }} Breedings)
                                        </button>
                                    </h2>
                                    <div id="collapseYearBreeding{{ $year }}" class="accordion-collapse collapse" aria-labelledby="headingYearBreeding{{ $year }}" data-bs-parent="#yearBreedingAccordion">
                                        <div class="accordion-body">

                                            <strong>
                                                Total Cost: {{ $groupedByYear[$year]->sum('totalCost') }}.EGP /
                                                Paid: {{ $groupedByYear[$year]->sum('totalPaid') }}.EGP /
                                                @if ($groupedByYear[$year]->sum('totalCost') > $groupedByYear[$year]->sum('totalPaid'))
                                                    <span class="text-danger">Unpaid: {{ $groupedByYear[$year]->sum('totalCost') - $groupedByYear[$year]->sum('totalPaid') }}.EGP</span>
                                                @elseif ($groupedByYear[$year]->sum('totalCost') < $groupedByYear[$year]->sum('totalPaid'))
                                                    <span class="text-success">Credit: {{ $groupedByYear[$year]->sum('totalPaid') - $groupedByYear[$year]->sum('totalCost') }}.EGP</span>
                                                @else
                                                    <span class="text-success">All Paid</span>
                                                @endif
                                            </strong>

                                            {{-- Months inside year --}}
                                            <div class="accordion mt-2" id="monthBreedingAccordion{{ $year }}">
                                                @foreach($months as $month)
                                                    <div class="accordion-item">
                                                        <h2 class="accordion-header" id="headingMonthBreeding{{ $year.$month->month }}">
                                                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseMonthBreeding{{ $year.$month->month }}" aria-expanded="false" aria-controls="collapseMonthBreeding{{ $year.$month->month }}">
                                                                {{ DateTime::createFromFormat('!m', $month->month)->format('F') }} ({{ $month->count }}) Breedings
                                                            </button>
                                                        </h2>
                                                        <div id="collapseMonthBreeding{{ $year.$month->month }}" class="accordion-collapse collapse" aria-labelledby="headingMonthBreeding{{ $year.$month->month }}" data-bs-parent="#monthBreedingAccordion{{ $year }}">
                                                            <div class="accordion-body p-0 table-responsive">
                                                                <table class="table table-bordered text-center table-striped mb-0">
                                                                    <thead>
                                                                        <tr>
                                                                            <th style="background-color: #4a91ee; color: white;">
                                                                                Total Cost: {{ $month->totalCost }}.EGP / Paid: {{ $month->totalPaid }}.EGP
                                                                                <br>
                                                                                @if ($month->totalCost == 0)
                                                                                    Free
                                                                                @elseif ($month->totalPaid == null)
                                                                                    <span class="text-danger">Not Paid</span>
                                                                                @elseif ($month->totalCost > $month->totalPaid)
                                                                                    <span class="text-danger">Debit: {{ abs($month->totalCost - $month->totalPaid) }}.EGP</span>
                                                                                @elseif ($month->totalCost < $month->totalPaid)
                                                                                    <span class="text-success">Credit: {{ abs($month->totalPaid - $month->totalCost) }}.EGP</span>
                                                                                @else
                                                                                    <span class="text-success">All Paid</span>
                                                                                @endif
                                                                            </th>
                                                                        </tr>
                                                                    </thead>
                                                                    <tbody>
                                                                        @php
                                                                            $breedings = $allbreedings->filter(function($item) use ($month) {
                                                                                return $item->created_at->year == $month->year && $item->created_at->month == $month->month;
                                                                            });
                                                                        @endphp
                                                                        @foreach($breedings as $item)
                                                                            <tr>
                                                                                <td>
                                                                                    @if ($item->user)
                                                                                        {{ is_object($item->user) ? $item->user->name : $item->user }}
                                                                                    @else
                                                                                        <span class="text-success">Natural Breeding</span>
                                                                                    @endif
                                                                                    /
                                                                                    @if ($item->femaleHorse)
                                                                                        {{ is_object($item->femaleHorse) ? $item->femaleHorse->name : $item->femaleHorse }}
                                                                                    @endif
                                                                                    @if ($item->maleHorse)
                                                                                        {{ is_object($item->maleHorse) ? $item->maleHorse->name : $item->maleHorse }}
                                                                                    @endif
                                                                                    / Day: {{ date('d h:iA', strtotime($item->created_at)) }}
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
                                                                                        / Paid: {{ $item->paid }}.EGP /
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

                <br>
            </div>
        </div>
    </div>
</div>
@endsection

@extends('layouts.admin')
<title>Expenses Page</title>
@section('content')
<div class="container-fluid py-4">
    <div class="row pt-4 p-2">
        <div class="col-12">
            <div class="card my-4">
                <div class="card-header p-0 position-relative mt-n4 mx-3 z-index-2">
                    <div class="bg-gradient-info shadow-primary border-radius-lg pt-4 pb-5">
                        <h4 class="text-white text-capitalize ps-3">Expenses Table</h4>
                    </div>
                </div>

                {{-- Expenses By Year & Month --}}
                @if(isset($expensesByMonth) && $expensesByMonth->count())
                    <div class="p-1">
                        <h5 class="text-primary">Expenses By Year & Month</h5>
                        <div class="accordion" id="yearAccordion">
                            @php
                                $groupedByYear = $expensesByMonth->groupBy('year');
                            @endphp
                            @foreach($groupedByYear as $year => $months)
                                <div class="accordion-item">
                                    <h2 class="accordion-header" id="headingYear{{ $year }}">
                                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseYear{{ $year }}" aria-expanded="false" aria-controls="collapseYear{{ $year }}">
                                            Year: {{ $year }} ({{ $groupedByYear[$year]->sum('count') }} Expenses)
                                        </button>
                                    </h2>
                                    <div id="collapseYear{{ $year }}" class="accordion-collapse collapse" aria-labelledby="headingYear{{ $year }}" data-bs-parent="#yearAccordion">
                                        <div class="accordion-body p-0 table-responsive">
                                            Total Expenses: {{ $groupedByYear[$year]->sum('totalCost') }}.EGP

                                            {{-- Months inside year --}}
                                            <div class="accordion mt-2" id="monthAccordion{{ $year }}">
                                                @foreach($months as $month)
                                                    <div class="accordion-item">
                                                        <h2 class="accordion-header" id="headingMonth{{ $year.$month->month }}">
                                                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseMonth{{ $year.$month->month }}" aria-expanded="false" aria-controls="collapseMonth{{ $year.$month->month }}">
                                                                {{ DateTime::createFromFormat('!m', $month->month)->format('F') }} ({{ $month->count }} Expenses)
                                                            </button>
                                                        </h2>
                                                        <div id="collapseMonth{{ $year.$month->month }}" class="accordion-collapse collapse" aria-labelledby="headingMonth{{ $year.$month->month }}" data-bs-parent="#monthAccordion{{ $year }}">
                                                            <div class="accordion-body p-0 table-responsive">
                                                                @php
                                                                    $expenses = $allexpenses->filter(function($item) use ($month) {
                                                                        return $item->created_at->year == $month->year && $item->created_at->month == $month->month;
                                                                    });
                                                                    $totalCost = $expenses->sum('cost');
                                                                @endphp
                                                                <table class="table table-bordered text-center table-striped mb-0">
                                                                    <thead>
                                                                        <tr>
                                                                            <th style="background-color: #4a91ee; color: white;">
                                                                                Total Expenses: {{ $totalCost }}.EGP
                                                                            </th>
                                                                        </tr>
                                                                    </thead>
                                                                    <tbody>
                                                                        @foreach ($expenses as $item)
                                                                            <tr>
                                                                                <td>
                                                                                    {{ $item->item ?? 'Expense' }} <br>
                                                                                    Cost: {{ $item->cost }}.EGP <br>
                                                                                    Day: {{ date('d h:iA', strtotime($item->created_at)) }}
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
                        <div class="pages text-center mt-2">
                            {{ $expensesByMonth->links() }}
                        </div>
                    </div>
                @else
                    <div class="card-body text-center">
                        <h5 class="text-danger">No Expenses Found</h5>
                    </div>
                @endif

            </div>
        </div>
    </div>
</div>
@endsection

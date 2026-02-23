@extends('layouts.admin')
<title>Financial Page</title>
@section('content')
<div class="container-fluid py-4">
    <div class="row pt-4 p-2">
        <div class="col-12">
            <div class="card my-4">
                <div class="card-header p-0 position-relative mt-n4 mx-3 z-index-2">
                    <div class="bg-gradient-info shadow-primary border-radius-lg pt-4 pb-5">
                        <h4 class="text-white text-capitalize ps-3">Financial Table</h4>
                    </div>
                </div>

                {{-- Finances By Year & Month --}}
                @if(isset($financesByMonth) && $financesByMonth->count())
                    <div class="p-1">
                        <h5 class="text-primary">Finances By Year & Month</h5>
                        <div class="accordion" id="yearAccordion">
                            @php
                                $groupedByYear = $financesByMonth->groupBy('year');
                            @endphp
                            @foreach($groupedByYear as $year => $months)
                                <div class="accordion-item">
                                    <h2 class="accordion-header" id="headingYear{{ $year }}">
                                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseYear{{ $year }}" aria-expanded="false" aria-controls="collapseYear{{ $year }}">
                                            Year: {{ $year }} ({{ $groupedByYear[$year]->sum('count') }} Finances)
                                        </button>
                                    </h2>
                                    <div id="collapseYear{{ $year }}" class="accordion-collapse collapse" aria-labelledby="headingYear{{ $year }}" data-bs-parent="#yearAccordion">
                                        <div class="accordion-body">
                                            Total Amount: {{ $groupedByYear[$year]->sum('totalAmount') }}.EGP /
                                            DecAmount: {{ $groupedByYear[$year]->sum('totalDecAmount') }}.EGP

                                            {{-- Months inside year --}}
                                            <div class="accordion mt-2" id="monthAccordion{{ $year }}">
                                                @foreach($months as $month)
                                                    <div class="accordion-item">
                                                        <h2 class="accordion-header" id="headingMonth{{ $year.$month->month }}">
                                                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseMonth{{ $year.$month->month }}" aria-expanded="false" aria-controls="collapseMonth{{ $year.$month->month }}">
                                                                {{ DateTime::createFromFormat('!m', $month->month)->format('F') }} ({{ $month->count }} Finances)
                                                            </button>
                                                        </h2>
                                                        <div id="collapseMonth{{ $year.$month->month }}" class="accordion-collapse collapse" aria-labelledby="headingMonth{{ $year.$month->month }}" data-bs-parent="#monthAccordion{{ $year }}">
                                                            <div class="accordion-body p-0 table-responsive">
                                                                @php
                                                                    $finances = $allfinances->filter(function($item) use ($month) {
                                                                        return $item->created_at->year == $month->year && $item->created_at->month == $month->month;
                                                                    });
                                                                    $totalAmount = $finances->sum('amount');
                                                                    $totalDecAmount = $finances->sum('decamount');
                                                                @endphp
                                                                <table class="table table-bordered text-center table-striped mb-0">
                                                                    <thead>
                                                                        <tr>
                                                                            <th style="background-color: #4a91ee; color: white;">
                                                                                Total Amount: {{ $totalAmount }}.EGP <br>
                                                                                Exp: {{ $totalAmount - $totalDecAmount }}.EGP / Dec: {{ $totalDecAmount }}.EGP
                                                                            </th>
                                                                        </tr>
                                                                    </thead>
                                                                    <tbody>
                                                                        @foreach ($finances as $item)
                                                                            <tr>
                                                                                <td>
                                                                                    <a href="{{url('Details/Finance/'.$item->id)}}">
                                                                                        {{ $item->description ?? 'Finance' }} /
                                                                                        Day: {{ date('d h:iA', strtotime($item->created_at)) }} (View)
                                                                                    </a>
                                                                                </td>
                                                                            </tr>
                                                                            <tr>
                                                                                <td>
                                                                                    Amount: {{ $item->amount }} .EGP <br>
                                                                                    Exp: {{ $item->amount - $item->decamount }} .EGP / Dec: {{ $item->decamount }} .EGP
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
                            {{ $financesByMonth->links() }}
                        </div>
                    </div>
                @else
                    <div class="card-body text-center">
                        <h5 class="text-primary text-center">No Financial Data Found</h5>
                    </div>
                @endif

            </div>
        </div>
    </div>
</div>
@endsection

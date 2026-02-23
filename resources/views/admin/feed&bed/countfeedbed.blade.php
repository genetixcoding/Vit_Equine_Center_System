@extends('layouts.admin')
<title>Feeding & Bedding Page</title>
@section('content')
<div class="container-fluid py-4">
    <div class="row pt-4 p-2">
        <div class="col-12">
            <div class="card my-4">
                <div class="card-header p-0 position-relative mt-n4 mx-3 z-index-2">
                    <div class="bg-gradient-info shadow-primary border-radius-lg pt-4 pb-5">
                        <h4 class="text-white text-capitalize ps-3">Feeding & Bedding Table</h4>
                    </div>
                </div>

                {{-- Feeding & Bedding By Year & Month --}}
                @if(isset($feedbedByMonth) && $feedbedByMonth->count())
                    <div class="p-1 mx-4 table-responsive">
                        <h5 class="text-primary">Feeding & Bedding By Year & Month</h5>
                        <div class="accordion" id="yearAccordionFeedBed">
                            @php $groupedByYear = $feedbedByMonth->groupBy('year'); @endphp
                            @foreach($groupedByYear as $year => $months)
                                @php
                                    $totalPriceYear = $groupedByYear[$year]->sum('price');
                                @endphp
                                <div class="accordion-item">
                                    <h2 class="accordion-header" id="headingYearFeedBed{{ $year }}">
                                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseYearFeedBed{{ $year }}" aria-expanded="false" aria-controls="collapseYearFeedBed{{ $year }}">
                                            Year: {{ $year }} ({{ $groupedByYear[$year]->sum('count') }} items) / Total Price: {{ $totalPriceYear }}.EGP
                                        </button>
                                    </h2>
                                    <div id="collapseYearFeedBed{{ $year }}" class="accordion-collapse collapse" aria-labelledby="headingYearFeedBed{{ $year }}" data-bs-parent="#yearAccordionFeedBed">
                                        <div class="accordion-body p-0 table-responsive">
                                            <div class="accordion" id="monthAccordionFeedBed{{ $year }}">
                                                @foreach($months as $month)
                                                    @php
                                                        $feedbedItems = $allfeedbed->filter(function($item) use ($month) {
                                                            return $item->created_at->year == $month->year && $item->created_at->month == $month->month;
                                                        });
                                                        $totalPriceMonth = $feedbedItems->sum('price');
                                                    @endphp
                                                    <div class="accordion-item">
                                                        <h2 class="accordion-header" id="headingMonthFeedBed{{ $year.$month->month }}">
                                                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseMonthFeedBed{{ $year.$month->month }}" aria-expanded="false" aria-controls="collapseMonthFeedBed{{ $year.$month->month }}">
                                                                {{ DateTime::createFromFormat('!m', $month->month)->format('F') }} ({{ $feedbedItems->count() }} items) / Total Price: {{ $totalPriceMonth }}.EGP
                                                            </button>
                                                        </h2>
                                                        <div id="collapseMonthFeedBed{{ $year.$month->month }}" class="accordion-collapse collapse" aria-labelledby="headingMonthFeedBed{{ $year.$month->month }}" data-bs-parent="#monthAccordionFeedBed{{ $year }}">
                                                            <div class="accordion-body p-0 table-responsive">
                                                                <table class="table table-bordered table-striped text-center mb-0">
                                                                    <thead>
                                                                        <tr>
                                                                            <th style="background-color: #4a91ee; color:white;">Item</th>
                                                                            <th style="background-color: #4a91ee; color:white;">Price / Date</th>
                                                                        </tr>
                                                                    </thead>
                                                                    <tbody>
                                                                        @foreach($feedbedItems as $item)
                                                                            <tr>
                                                                                <td>{{ $item->item }}</td>
                                                                                <td>{{ $item->price }}.EGP / {{ date('d h:iA', strtotime($item->created_at)) }}</td>
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
                            {{ $feedbedByMonth->links() }}
                        </div>
                    </div>
                @else
                    <div class="card-body text-center">
                        <h5 class="text-danger">No Feeding Or Bedding Found</h5>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection

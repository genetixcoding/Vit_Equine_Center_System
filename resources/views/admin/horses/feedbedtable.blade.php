@extends('layouts.admin')
<title>{{ $horse->name }} Feeding & Beeding Page</title>
@section('content')
<div class="container-fluid py-4">
    <div class="row pt-4 p-2">
        <div class="col-12">
            <div class="card my-4">
                <div class="card-header p-0 position-relative mt-n4 mx-3 z-index-2">
                    <div class="bg-gradient-info shadow-primary border-radius-lg pt-4 pb-5">
                        <h4 class="text-white text-capitalize ps-3">{{ $horse->name }} <br> Feeding & Beeding Table</h4>
                    </div>
                </div>
                <div class="card-body m-2 table-responsive m-2 px-0 pb-2p-2">

                </div>

            @if ($feedingByMonth->isNotEmpty())
                <div class="container-fluid p-2 mt-5">
                    <div class="row">
                        <div class="col-12">
                            <div class="card m-auto">
                                <div class="card-header">
                                    <div class="row">
                                        <div class="">
                                            <h6 class="text-primary">All Feedings {{ $allfeeding->count() }} Feeding Items</h6>
                                        </div>
                                    </div>
                                </div>

                                {{-- اكوردينج للسنة ثم الشهور --}}
                                @php
                                    $feedingByYear = $feedingByMonth->groupBy('year');
                                @endphp

                                <div class="accordion" id="feedingYearAccordion">
                                    @foreach($feedingByYear as $year => $months)
                                        <div class="accordion-item">
                                            <h2 class="accordion-header" id="heading-feeding-{{ $year }}">
                                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse-feeding-{{ $year }}" aria-expanded="false" aria-controls="collapse-feeding-{{ $year }}">
                                                    📅 Year: {{ $year }} (Total: {{ $months->sum('count') }} Feedings)
                                                </button>
                                            </h2>
                                            <div id="collapse-feeding-{{ $year }}" class="accordion-collapse collapse" aria-labelledby="heading-feeding-{{ $year }}" data-bs-parent="#feedingYearAccordion">
                                                <div class="accordion-body p-0">

                                                    {{-- اكوردينج الشهور --}}
                                                    <div class="accordion" id="feedingMonthAccordion-{{ $year }}">
                                                        @foreach($months as $month)
                                                            @php
                                                                $monthFeedings = $allfeeding->filter(function($item) use ($month) {
                                                                    return $item->created_at->year == $month->year && $item->created_at->month == $month->month;
                                                                });
                                                                $groupedmonthFeedings = $monthFeedings->groupBy('feedbed_id');
                                                            @endphp

                                                            <div class="accordion-item">
                                                                <h2 class="accordion-header" id="heading-feeding-{{ $year }}-{{ $month->month }}">
                                                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse-feeding-{{ $year }}-{{ $month->month }}" aria-expanded="false" aria-controls="collapse-feeding-{{ $year }}-{{ $month->month }}">
                                                                        📆 {{ DateTime::createFromFormat('!m', $month->month)->format('F') }} ({{ $monthFeedings->count() }} Feedings)
                                                                    </button>
                                                                </h2>
                                                                <div id="collapse-feeding-{{ $year }}-{{ $month->month }}" class="accordion-collapse collapse" aria-labelledby="heading-feeding-{{ $year }}-{{ $month->month }}" data-bs-parent="#feedingMonthAccordion-{{ $year }}">
                                                                    <div class="accordion-body p-0">

                                                                        {{-- الداتا نفسها بدون أي تعديل --}}
                                                                        @if($monthFeedings->count())
                                                                            <table class="table table-bordered table-striped mb-3 text-center">
                                                                                <tbody>
                                                                                    @foreach ($groupedmonthFeedings as $feedbedId => $feedingsGroup)
                                                                                        @php
                                                                                            $feedbed = $feedingbedings->firstWhere('id', $feedbedId);
                                                                                        @endphp
                                                                                        @if($feedbed)
                                                                                            <tr>
                                                                                                <th style="background-color: #2f78cc; color: #FFF;">
                                                                                                    {{ $feedbed->item }} ({{ $feedingsGroup->count() }} Feedings)
                                                                                                </th>
                                                                                                <th style="background-color: #2f78cc; color: #FFF;">
                                                                                                    {{ date('h:iA d-M-y', strtotime($feedbed->created_at)) }}
                                                                                                </th>
                                                                                            </tr>
                                                                                            @foreach ($feedingsGroup as $itemfeed)
                                                                                                <tr>
                                                                                                    <td>{{ $itemfeed->item }}</td>
                                                                                                    <td>{{ $itemfeed->horse->name ?? '' }}</td>
                                                                                                </tr>
                                                                                                <tr>
                                                                                                    <td>{{ date('h:iA d/M/y', strtotime($itemfeed->created_at)) }}</td>
                                                                                                    <td>{{ $itemfeed->qty }} Unit</td>
                                                                                                </tr>
                                                                                            @endforeach
                                                                                        @endif
                                                                                    @endforeach
                                                                                </tbody>
                                                                            </table>
                                                                        @endif

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
                                    {{ $feedingByMonth->links() }}
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
            @else
            <div class="text-primary text-center">
                <h6>No Feedings Found</h6>
            </div>
            @endif





                @if ($beddingByMonth->isNotEmpty())
                    <div class="container-fluid p-2 mt-5">
                        <div class="row">
                            <div class="col-12">
                                <div class="card m-auto">
                                    <div class="card-header">
                                        <div class="row">
                                            <div class="">
                                                <h6>All Bedding Descriptions</h6>
                                                <h6 class="text-primary">Total Bedding: {{ $allbedding->count() }}</h6>
                                            </div>
                                        </div>
                                    </div>

                                    @php
                                        $beddingsByYear = $beddingByMonth->groupBy('year');
                                    @endphp

                                    <div class="accordion" id="beddingYearAccordion">
                                        @foreach($beddingsByYear as $year => $months)
                                            <div class="accordion-item">
                                                <h2 class="accordion-header" id="heading-bedding-{{ $year }}">
                                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse-bedding-{{ $year }}" aria-expanded="false" aria-controls="collapse-bedding-{{ $year }}">
                                                        📅 Year: {{ $year }} (Total: {{ $months->sum('count') }} Beddings)
                                                    </button>
                                                </h2>
                                                <div id="collapse-bedding-{{ $year }}" class="accordion-collapse collapse" aria-labelledby="heading-bedding-{{ $year }}" data-bs-parent="#beddingYearAccordion">
                                                    <div class="accordion-body table-responsive">
                                                        @foreach($months as $month)
                                                            @php
                                                                $monthBeddings = $allbedding->filter(fn($item) => $item->created_at->year == $month->year && $item->created_at->month == $month->month);
                                                                $groupedMonthBeddings = $monthBeddings->groupBy('feedbed_id');
                                                            @endphp

                                                            @if($monthBeddings->count())
                                                                <table class="table table-bordered table-striped text-center mb-3">
                                                                    <thead>
                                                                        <tr style="background-color: #4a91ee; color: #fff;">
                                                                            <th colspan="5">{{ DateTime::createFromFormat('!m', $month->month)->format('F') }} {{ $month->year }} ({{ $monthBeddings->count() }} Beddings)</th>
                                                                        </tr>
                                                                    </thead>
                                                                    <tbody>
                                                                        @foreach ($groupedMonthBeddings as $feedbedId => $beddingsGroup)
                                                                            @php
                                                                                $feedbed = $feedingbedings->firstWhere('id', $feedbedId);
                                                                            @endphp
                                                                            @if($feedbed)
                                                                                <tr>
                                                                                    <th style="background-color: #2f78cc; color: #FFF;">{{ $feedbed->item }} ({{ $beddingsGroup->count() }} Beddings)</th>
                                                                                    <th style="background-color: #2f78cc; color: #FFF;">{{ date('h:iA d-M-y', strtotime($feedbed->created_at)) }}</th>
                                                                                </tr>
                                                                                @foreach ($beddingsGroup as $itembed)
                                                                                    <tr>
                                                                                        <td>{{ $itembed->item }}</td>
                                                                                        <td>{{ $itembed->horse->name ?? '' }}</td>
                                                                                    </tr>
                                                                                    <tr>
                                                                                        <td>{{ date('h:iA d/M/y', strtotime($itembed->created_at)) }}</td>
                                                                                        <td>{{ $itembed->qty }} Unit</td>
                                                                                    </tr>
                                                                                @endforeach
                                                                            @endif
                                                                        @endforeach
                                                                    </tbody>
                                                                </table>
                                                            @endif
                                                        @endforeach
                                                        <div class="pages text-center">
                                                            {{ $beddingByMonth->links() }}
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
                @else
                    <div class="text-primary text-center">
                        <h6>No Beddings Found</h6>
                    </div>
                @endif

            </div>
        </div>
    </div>
</div>
@endsection

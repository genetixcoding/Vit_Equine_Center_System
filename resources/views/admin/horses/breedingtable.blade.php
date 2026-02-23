@extends('layouts.admin')
<title>{{ $horse->name }} Breedings Page</title>
@section('content')
<div class="container-fluid py-4">
    <div class="row pt-4 p-2">
        <div class="col-12">
            <div class="card my-4">
                <div class="card-header p-0 position-relative mt-n4 mx-3 z-index-2">
                    <div class="bg-gradient-info shadow-primary border-radius-lg pt-4 pb-5">
                        <h4 class="text-white text-capitalize ps-3">{{ $horse->name }} <br> Breedings Table</h4>
                    </div>
                </div>
                <div class="card-body m-2 table-responsive m-2 px-0 pb-2p-2">

                </div>
                @if ($breedingsByMonth->isNotEmpty())
                    <div class="container-fluid p-2 mt-2">
                        <div class="row">
                            <div class="col-12">
                                <div class="card m-auto">
                                    <div class="card-header">
                                        <div class="row">
                                            <div class="">
                                                <h6 class="text-primary">All Breedings
                                                </h6>
                                            </div>
                                        </div>
                                    </div>

                                    {{-- Breedings By Month --}}
                                   @if(isset($breedingsByMonth) && $breedingsByMonth->count())
                                @php
                                    // نجمع البيانات حسب السنة
                                    $breedingsByYear = $breedingsByMonth->groupBy('year');
                                @endphp

                                <div class="accordion" id="breedingYearAccordion">
                                    @foreach($breedingsByYear as $year => $months)
                                        <div class="accordion-item">
                                            <h2 class="accordion-header" id="heading-breeding-{{ $year }}">
                                                <button class="accordion-button collapsed" type="button"
                                                    data-bs-toggle="collapse"
                                                    data-bs-target="#collapse-breeding-{{ $year }}"
                                                    aria-expanded="false"
                                                    aria-controls="collapse-breeding-{{ $year }}">
                                                    📅 Year: {{ $year }}
                                                    (Total: {{ $months->sum('count') }} Breedings)
                                                </button>
                                            </h2>
                                            <div id="collapse-breeding-{{ $year }}"
                                                class="accordion-collapse collapse"
                                                aria-labelledby="heading-breeding-{{ $year }}"
                                                data-bs-parent="#breedingYearAccordion">
                                                <div class="accordion-body">
                                                    <table class="table table-bordered table-striped text-center">
                                                        <thead>
                                                            <tr>
                                                                <th>Month</th>
                                                                <th>Breedings Count</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            @foreach($months as $month)
                                                                <tr>
                                                                    <td>{{ DateTime::createFromFormat('!m', $month->month)->format('F') }}</td>
                                                                    <td>{{ $month->count }}</td>
                                                                </tr>
                                                            @endforeach
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @endif


                                    <div class="card-body table-responsive m-2 px-0 pb-2px-0 pb-2">
                                        <table class="table  text-center  table-striped mb-0">
                                            <tbody class="table  text-center mb-0">
                                                @foreach ($breedingsByMonth as $month)
                                                        @php
                                                            $breedings = $allbreedings->filter(function($item) use ($month) {
                                                                return $item->created_at->year == $month->year && $item->created_at->month == $month->month;
                                                            });

                                                        @endphp

                                                        @if($breedings->count())
                                                            <tr>
                                                                <th style="background-color: #4a91ee;">
                                                                    {{ DateTime::createFromFormat('!m', $month->month)->format('F') }} {{ $month->year }}

                                                                    ({{ $breedings->count() }} breedings)
                                                                </th>
                                                            </tr>
                                                            @foreach ($breedings as $breedingitem)
                                                                <tr>
                                                                    <td style="background-color: #4a91ee; color: #fff;">
                                                                        {{ ($breedingitem->user) ? $breedingitem->user->name : 'Natural Breeding' }}
                                                                        <br>
                                                                        Day: {{ date('d h:iA', strtotime($breedingitem->created_at)) }}
                                                                    </td>
                                                                </tr>
                                                                <tbody class="text-center">
                                                                    <tr>
                                                                        <td>
                                                                            {{-- Ensure femalehorse is an object before accessing name --}}

                                                                            @if(is_object($breedingitem->femaleHorse) && isset($breedingitem->femaleHorse->name))
                                                                                {{ $breedingitem->femaleHorse->name }}
                                                                            @else

                                                                            @endif

                                                                            {{-- Ensure maleHorse is an object before accessing name --}}
                                                                            @if(is_object($breedingitem->maleHorse) && isset($breedingitem->maleHorse->name))
                                                                                {{ $breedingitem->maleHorse->name }}
                                                                            @else

                                                                            @endif

                                                                            {{ $breedingitem->horsename }}
                                                                            {{ $breedingitem->stud }}
                                                                        </td>
                                                                    </tr>

                                                                    <tr>
                                                                        <td>
                                                                          Cost: {{ $breedingitem->cost ?? 'Unknown' }}

                                                                            Paid: {{ $breedingitem->paid ?? 'Unknown' }}

                                                                            @if ($breedingitem->paid > $breedingitem->cost)
                                                                                <span class="text-success">Credit: {{ $breedingitem->paid - $breedingitem->cost }}</span>
                                                                            @elseif ($breedingitem->paid < $breedingitem->cost)
                                                                                <span class="text-danger">Debit: {{ $breedingitem->cost - $breedingitem->paid }}</span>
                                                                            @else

                                                                            @endif
                                                                        </td>


                                                                    </tr>
                                                                </tbody>
                                                            @endforeach
                                                        @endif
                                                @endforeach
                                                <div class="pages text-center">
                                                    {{ $breedingsByMonth->links() }}
                                                </div>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection

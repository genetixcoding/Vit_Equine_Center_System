@extends('layouts.admin')
<title>Breeding @if ($breeding->embryo->isNotEmpty()) && Embryo @endif Details Page</title>
@section('content')
<div class="container-fluid py-4">
    <div class="row pt-4 p-2">
        <div class="col-12">
          <div class="card my-4">
            <div class="card-header p-0 position-relative mt-n4 mx-3 z-index-2">
              <div class="bg-gradient-info shadow-primary border-radius-lg pt-4 pb-5">
                <h4 class="text-white text-capitalize ps-3">
                    Details Table
                    Breeding :
                    @if ($breeding->femaleHorse)
                        {{ is_object($breeding->femaleHorse) ? $breeding->femaleHorse->name : $breeding->femaleHorse }}
                    @endif
                    @if ($breeding->maleHorse)
                            {{ is_object($breeding->maleHorse) ? $breeding->maleHorse->name : $breeding->maleHorse }}
                    @endif
                    @if ($breeding->horsename)
                        / {{$breeding->horsename}} belongs to
                        {{ $breeding->stud ?? 'Unknown'}}
                    @endif
                    <br>
                    @if ($breeding->embryo->isNotEmpty())
                        Embryo :
                        @foreach ($breeding->embryo as $item)
                        {{ $item->localhorsename }}
                        @endforeach
                    @endif
                </h4>
              </div>
            </div>
            <div>

            </div>
            <div class="container-fluid p-2 mt-5">
                <div class="row">
                    <div class="col-12">
                        <div class="card m-auto">
                            <div class="card-header">
                                <div class="row">

                                    <div class="col-12">
                                        @php
                                            $embryoCost = $breeding->embryo ? $breeding->embryo->sum('cost') : 0;
                                            $embryoPaid = $breeding->embryo ? $breeding->embryo->sum('paid') : 0;
                                            $totalCost = ($breeding->cost ?? 0) + $embryoCost;
                                            $totalPaid = ($breeding->paid ?? 0) + $embryoPaid;
                                            $total = $totalCost - $totalPaid;
                                        @endphp

                                        <span class="text-primary">
                                            @if ($totalCost > 0 )
                                                All Cost: {{ $totalCost }} .EGP
                                            @endif
                                        </span>
                                        <br>
                                        @if ($totalCost > $totalPaid)
                                        <span class="text-danger">All Debit: {{ $total }} .EGP</span>
                                        @elseif ($totalCost < $totalPaid)
                                        <span class="text-success">All  Credit : {{ abs($total) }} .EGP</span>
                                        @elseif ($totalPaid == 0 )

                                        @elseif ($totalCost == $totalPaid)
                                        <span class="text-info">All Paid</span>
                                        @endif

                                    </div>
                                </div>
                            </div>
                            <div class="card-body table-responsive m-2 px-0 pb-2px-0 pb-2">
                                <table class="table  text-center  table-striped mb-0">
                                    <tbody class="table  text-center mb-0">
                                        <tr>
                                            <th colspan="2" style="background-color: #4a91ee; color: #fff;">
                                                Breeding Details: {{ date('h:iA d/M/y', strtotime($breeding->created_at)) }} <br>
                                                @if ( $breeding->status == 1)
                                                    <span class="text-white badge bg-success">Breeding Successed</span>
                                                @elseif ($breeding->status == 2)
                                                    <span class="text-white badge bg-danger">Breeding UnSuccessed</span>
                                                @else
                                                    <span class="text-white badge bg-info">Breeding Still in Progress</span>
                                                @endif
                                            </th>
                                        </tr>
                                        <tbody>
                                            <tr>
                                                <td>
                                                    {{ $breeding->user->name ?? 'Natural Breeding'}}
                                                </td>
                                                <td>
                                                    @if ($breeding->cost)
                                                    Cost: {{ $breeding->cost }} .EGP
                                                    @else
                                                      <span class="text-succsess">Free Cost</span>
                                                    @endif
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    @if ($breeding->paid == 0)
                                                        Paid Not Recorded
                                                    @else
                                                        Paid: {{ $breeding->paid}} .EGP /
                                                    @endif
                                                </td>

                                                <td>
                                                    @if ($breeding->cost > $breeding->paid)
                                                        <span class="text-danger">Debit: {{ $breeding->cost - $breeding->paid }}.EGP</span>
                                                    @elseif ($breeding->cost < $breeding->paid)
                                                        <span class="text-success"> Credit: {{ $breeding->paid - $breeding->cost }}.EGP</span>
                                                    @else
                                                        <span class="text-success">Paid Breeding</span>
                                                    @endif
                                                </td>
                                            </tr>
                                            <tr>
                                                <td colspan="2">
                                                    @if ($breeding->femaleHorse)
                                                        {{ is_object($breeding->femaleHorse) ? $breeding->femaleHorse->name : $breeding->femaleHorse }}
                                                    @endif
                                                    @if ($breeding->maleHorse)
                                                            {{ is_object($breeding->maleHorse) ? $breeding->maleHorse->name : $breeding->maleHorse }}
                                                    @endif
                                                    @if ($breeding->horsename)
                                                        / {{$breeding->horsename}} to
                                                        {{ $breeding->stud ?? 'Unknown'}}
                                                    @endif
                                                    @if ($breeding->femaleHorse && $breeding->maleHorse)
                                                        /  Stud's Breeding
                                                    @endif
                                                </td>

                                            </tr>

                                             @if ($breeding->finance)
                                            <tr>
                                                <td colspan="2">
                                                Finance : {{ $breeding->finance->amount}} .EGP / {{ $breeding->finance->description}} <br>
                                                    {{ $breeding->finance->created_at ? $breeding->finance->created_at->format('h:iA d-M-y') : 'Unknown'}}
                                                </td>
                                            </tr>
                                            @endif

                                            @if ($breeding->description)
                                            <tr>
                                                <td colspan="2">{{ $breeding->description}}</td>
                                            </tr>
                                            @endif

                                            @if ($breeding->embryo && !$breeding->embryo->isEmpty())
                                            @foreach ($breeding->embryo as $embryo)
                                                    <tr>
                                                        <th colspan="2" style="background-color: #4a91ee; color: #fff;">
                                                            Embryo Details: {{ date('h:iA d-M-y', strtotime($embryo->created_at)) ?? 'Unknown'}} <br>
                                                             @if ($embryo->status == 1)
                                                                <span class="bage bg-success">Embryo Successed</span>
                                                            @elseif ($embryo->status == 2)
                                                                <span class="bage bg-danger">Embryo UnSuccessed</span>
                                                            @else
                                                                <span class="bage bg-info">Embryo Still in Progress</span>

                                                            @endif
                                                        </th>

                                                    </tr>
                                                    <tr>
                                                        <td>
                                                            {{ $embryo->localhorsename }}
                                                        </td>
                                                        <td>
                                                            {{ $embryo->user->name }}
                                                        </td>

                                                    </tr>
                                                    <tr>
                                                        <td colspan="2">
                                                            Cost: {{ $embryo->cost}} .EGP

                                                            @if ($embryo->paid)
                                                                Paid: {{ $embryo->paid}} .EGP
                                                            @endif
                                                            @if ($embryo->cost > $embryo->paid)
                                                                <span class="text-danger">Debit: {{ $embryo->cost - $embryo->paid }}.EGP</span>
                                                            @elseif ($embryo->cost < $embryo->paid)
                                                                <span class="text-success">Credit : {{ $embryo->paid - $embryo->cost }}.EGP</span>
                                                            @else
                                                                <span class="text-success">Embryo Paid</span>
                                                            @endif
                                                        </td>
                                                    </tr>
                                                    @if ($embryo->finance)
                                                    <tr>
                                                        <td colspan="2">
                                                           Finance : {{ $embryo->finance->amount}} .EGP / {{ $embryo->finance->description}} <br>
                                                            {{ $embryo->finance->created_at ? $embryo->finance->created_at->format('h:iA d-M-y') : 'Unknown'}}
                                                        </td>
                                                    </tr>
                                                    @endif

                                                    @if ($embryo->description)
                                                    <tr>
                                                        <td colspan="2">{{ $embryo->description}}</td>
                                                    </tr>
                                                    @endif
                                                @endforeach
                                            @endif
                                        </tbody>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

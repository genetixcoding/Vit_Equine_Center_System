@extends('layouts.admin')
<title>Details</title>
@section('content')
<div class="container-fluid py-4">
    <div class="row pt-4 p-2">
        <div class="col-12">
            <div class="card my-4">
                <div class="card-header p-0 position-relative mt-n4 mx-3 z-index-2">
                    <div class="bg-gradient-info shadow-primary border-radius-lg pt-4 pb-5">
                        <h4 class="text-white text-capitalize ps-3">Finance Details Table</h4>
                    </div>
                </div>
                <div class="row mx-2 my-5">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header pb-0">
                                <div class="row">
                                    <div class="">
                                        <h5 class="text-primary text-center">
                                           Basic : {{$finance->amount}} .EGP
                                            Balance : {{$finance->decamount}} .EGP
                                            <br>

                                            {{ date('h:iA d/M/y', strtotime($finance->created_at)) }}</h5>
                                    </div>

                                </div>
                            </div>

                            <div class="card-body px-0 pb-2 m-2">
                                <div class="table-responsive m-2 px-0 pb-2p-0">
                                  <table class="table align-finances-center mb-0 text-center">
                                    <tbody>
                                        <tr>
                                            <th style="background-color : #2f78cc; color: #FFF;">
                                                {{ $finance->description  ?? 'Finance'}} /
                                                Day: {{ date('d h:iA', strtotime($finance->created_at)) }}
                                            <br>
                                                Basic :
                                                {{$finance->amount}} .EGP
                                                Credit : {{$finance->decamount}} .EGP
                                            </th>
                                        </tr>

                                        @if ($finance->expenses && $finance->expenses->count() != 0)
                                        <tr>
                                            <td>
                                                {{ $finance->expenses->count('cost') }} Expenses / {{ $finance->expenses->sum('cost') }} .EGP
                                            </td>
                                        </tr>
                                        @endif
                                        @if ($finance->invoices && $finance->invoices->count() != 0)
                                        <tr>
                                            <td>
                                                {{ $finance->invoices->count('id') }} Invoices / {{ $finance->invoices->sum('paid') }} .EGP
                                            </td>
                                        </tr>
                                        @endif
                                        @if ($finance->feedingbedding && $finance->feedingbedding->count() != 0)
                                        <tr>
                                            <td>
                                                {{ $finance->feedingbedding->count() }} Feeding & Bedding / {{ $finance->feedingbedding->sum('price') }} .EGP
                                            </td>
                                        </tr>
                                        @endif
                                        @if ($finance->salary && $finance->salary->count() != 0)
                                        <tr>
                                            <td>
                                                {{ $finance->salary->count('salaryamount') }} Employee Salary / {{ $finance->salary->sum('salaryamount') }} .EGP
                                            </td>
                                        </tr>
                                        @endif
                                        @if ($finance->breeding && $finance->breeding->count() != 0)
                                        <tr>
                                            <td>
                                                {{ $finance->breeding->count('cost') }} Breeding / {{ $finance->breeding->sum('cost') }} .EGP
                                            </td>
                                        </tr>
                                        @endif
                                        @if ($finance->embryo && $finance->embryo->count() != 0)
                                        <tr>
                                            <td>
                                                {{ $finance->embryo->count('cost') }} Embryo / {{ $finance->embryo->sum('cost') }} .EGP
                                            </td>
                                        </tr>
                                        @endif

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
</div>

@endsection
